<?php

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

/*
|--------------------------------------------------------------------------
| BIG BOSS BUNDLES — SECURE PRODUCT CATALOG
|--------------------------------------------------------------------------
| Prices are stored SERVER-SIDE.
| Customer/browser prices are NEVER trusted.
|
| Amounts are in cents:
| $12.99 = 1299
|--------------------------------------------------------------------------
*/

$PRODUCTS = [

    'edge-control' => [
        'name' => 'Big Boss Bundles Edge Control',
        'price' => 1299
    ]

    /*
    More products will be added here as we connect:
    Wig Glue
    Wax Stick
    Lace Melting Spray
    Lace Tint Mousse
    Hair Accelerator Oil
    Pressing Comb
    Wig Bands
    Bundles
    Closures
    Frontals
    Wigs
    Bulk Braiding Hair
    */
];


/*
|--------------------------------------------------------------------------
| GET STRIPE SECRET KEY
|--------------------------------------------------------------------------
| The actual key will NOT be stored in this file.
|--------------------------------------------------------------------------
*/

$stripeSecretKey = getenv('STRIPE_SECRET_KEY');

if (!$stripeSecretKey) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Stripe is not configured on the server yet.'
    ]);
    exit;
}


/*
|--------------------------------------------------------------------------
| READ CART
|--------------------------------------------------------------------------
*/

$rawInput = file_get_contents('php://input');
$data = json_decode($rawInput, true);

if (
    !$data ||
    !isset($data['items']) ||
    !is_array($data['items']) ||
    count($data['items']) === 0
) {
    http_response_code(400);
    echo json_encode([
        'error' => 'Your shopping bag is empty.'
    ]);
    exit;
}


/*
|--------------------------------------------------------------------------
| BUILD VERIFIED STRIPE LINE ITEMS
|--------------------------------------------------------------------------
*/

$stripeFields = [];

$itemNumber = 0;

foreach ($data['items'] as $item) {

    $productId = $item['productId'] ?? '';

    if (!isset($PRODUCTS[$productId])) {
        http_response_code(400);
        echo json_encode([
            'error' => 'An item in your bag is not available for checkout.'
        ]);
        exit;
    }

    $product = $PRODUCTS[$productId];

    $quantity = isset($item['quantity'])
        ? (int)$item['quantity']
        : 1;

    if ($quantity < 1) {
        $quantity = 1;
    }

    if ($quantity > 20) {
        $quantity = 20;
    }


    /*
    |--------------------------------------------------------------------------
    | PRODUCT DESCRIPTION / VARIATIONS
    |--------------------------------------------------------------------------
    */

    $details = [];

    if (!empty($item['texture'])) {
        $details[] = 'Texture: ' . substr($item['texture'], 0, 50);
    }

    if (!empty($item['length'])) {
        $details[] = 'Length: ' . substr($item['length'], 0, 30);
    }

    if (!empty($item['density'])) {
        $details[] = 'Density: ' . substr($item['density'], 0, 30);
    }

    if (!empty($item['laceSize'])) {
        $details[] = 'Lace: ' . substr($item['laceSize'], 0, 30);
    }

    if (!empty($item['color'])) {
        $details[] = 'Color: ' . substr($item['color'], 0, 50);
    }

    $description = implode(' • ', $details);


    /*
    |--------------------------------------------------------------------------
    | STRIPE LINE ITEM
    |--------------------------------------------------------------------------
    */

    $prefix = 'line_items[' . $itemNumber . ']';

    $stripeFields[$prefix . '[price_data][currency]'] = 'usd';

    $stripeFields[
        $prefix . '[price_data][product_data][name]'
    ] = $product['name'];

    if ($description !== '') {
        $stripeFields[
            $prefix . '[price_data][product_data][description]'
        ] = $description;
    }

    $stripeFields[
        $prefix . '[price_data][unit_amount]'
    ] = $product['price'];

    $stripeFields[$prefix . '[quantity]'] = $quantity;

    $itemNumber++;
}


/*
|--------------------------------------------------------------------------
| STRIPE CHECKOUT SETTINGS
|--------------------------------------------------------------------------
*/

$stripeFields['mode'] = 'payment';

$stripeFields['success_url'] =
    'https://bigbossbundles.com/success.html?session_id={CHECKOUT_SESSION_ID}';

$stripeFields['cancel_url'] =
    'https://bigbossbundles.com/cart.html';

$stripeFields['billing_address_collection'] = 'auto';

$stripeFields['shipping_address_collection[allowed_countries][0]'] = 'US';

$stripeFields['phone_number_collection[enabled]'] = 'true';

$stripeFields['allow_promotion_codes'] = 'true';


/*
|--------------------------------------------------------------------------
| CREATE STRIPE CHECKOUT SESSION
|--------------------------------------------------------------------------
*/

$ch = curl_init('https://api.stripe.com/v1/checkout/sessions');

curl_setopt_array($ch, [

    CURLOPT_RETURNTRANSFER => true,

    CURLOPT_POST => true,

    CURLOPT_POSTFIELDS =>
        http_build_query($stripeFields),

    CURLOPT_HTTPHEADER => [
        'Authorization: Bearer ' . $stripeSecretKey,
        'Content-Type: application/x-www-form-urlencoded'
    ],

    CURLOPT_TIMEOUT => 30
]);


$response = curl_exec($ch);

$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

$curlError = curl_error($ch);

curl_close($ch);


if ($response === false || $curlError) {

    http_response_code(500);

    echo json_encode([
        'error' => 'Unable to connect to secure checkout.'
    ]);

    exit;
}


$stripeResponse = json_decode($response, true);


if (
    $httpCode < 200 ||
    $httpCode >= 300 ||
    empty($stripeResponse['url'])
) {

    http_response_code(500);

    echo json_encode([
        'error' => 'Stripe could not create the checkout session.'
    ]);

    exit;
}


/*
|--------------------------------------------------------------------------
| RETURN SECURE STRIPE CHECKOUT URL
|--------------------------------------------------------------------------
*/

echo json_encode([
    'url' => $stripeResponse['url']
]);

exit;
