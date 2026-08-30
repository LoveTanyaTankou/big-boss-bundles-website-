<?php

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'error' => 'Method not allowed.'
    ]);
    exit;
}

/*
|--------------------------------------------------------------------------
| Load Stripe secret key from private file
|--------------------------------------------------------------------------
*/

require_once dirname(__DIR__, 2) . '/stripe-config.php';

if (empty($stripeSecretKey)) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Stripe is not configured on the server yet.'
    ]);
    exit;
}

/*
|--------------------------------------------------------------------------
| Secure product catalog
|--------------------------------------------------------------------------
| Prices are in cents.
| Customers cannot change these prices from their browser.
|--------------------------------------------------------------------------
*/

$PRODUCTS = [

    'edge-control' => [
        'name' => 'Big Boss Bundles Edge Control',
        'price' => 1299
    ],

    'lace-tint-mousse' => [
        'name' => 'Big Boss Bundles Lace Tint Mousse',
        'price' => 1199
    ],

    'wig-glue' => [
        'name' => 'Big Boss Bundles Wig Glue',
        'price' => 1499
    ],

    'wax-stick' => [
        'name' => 'Big Boss Bundles Wax Stick',
        'price' => 999
    ],

    'lace-melting-spray' => [
        'name' => 'Big Boss Bundles Lace Melting Spray',
        'price' => 1299
    ],

    'hair-accelerator-oil' => [
        'name' => 'Big Boss Bundles Hair Accelerator Oil',
        'price' => 1500
    ],

    'pressing-comb' => [
        'name' => 'Big Boss Bundles Pressing Comb',
        'price' => 4999
    ],

    'wig-bands' => [
        'name' => 'Big Boss Bundles Wig Bands',
        'price' => 199
    ],

    'protective-shield' => [
        'name' => 'Protective Shield',
        'price' => 1499
    ]

];

/*
|--------------------------------------------------------------------------
| Read cart
|--------------------------------------------------------------------------
*/

$input = json_decode(file_get_contents('php://input'), true);

if (
    !$input ||
    empty($input['items']) ||
    !is_array($input['items'])
) {
    http_response_code(400);
    echo json_encode([
        'error' => 'Your bag is empty.'
    ]);
    exit;
}

$stripeFields = [];
$lineIndex = 0;
$merchandiseSubtotal = 0;

/*
|--------------------------------------------------------------------------
| Build secure Stripe line items
|--------------------------------------------------------------------------
*/

foreach ($input['items'] as $item) {

    $productId = $item['productId'] ?? '';

    if (!isset($PRODUCTS[$productId])) {
        http_response_code(400);
        echo json_encode([
            'error' => 'One of the products in your bag is not available for checkout yet: ' . $productId
        ]);
        exit;
    }

    $product = $PRODUCTS[$productId];

    $quantity = intval($item['quantity'] ?? 1);

    if ($quantity < 1) {
        $quantity = 1;
    }

    if ($quantity > 20) {
        $quantity = 20;
    }

    $unitAmount = intval($product['price']);

    $merchandiseSubtotal += $unitAmount * $quantity;

    /*
    |--------------------------------------------------------------------------
    | Product variation description
    |--------------------------------------------------------------------------
    */

    $variationParts = [];

    if (!empty($item['texture'])) {
        $variationParts[] = $item['texture'];
    }

    if (!empty($item['length'])) {
        $variationParts[] = $item['length'];
    }

    if (!empty($item['density'])) {
        $variationParts[] = $item['density'];
    }

    if (!empty($item['laceSize'])) {
        $variationParts[] = $item['laceSize'];
    }

    if (!empty($item['color'])) {
        $variationParts[] = $item['color'];
    }

    $description = implode(' • ', $variationParts);

    /*
    |--------------------------------------------------------------------------
    | Stripe line item
    |--------------------------------------------------------------------------
    */

    $stripeFields["line_items[$lineIndex][price_data][currency]"] = 'usd';

    $stripeFields["line_items[$lineIndex][price_data][product_data][name]"] =
        $product['name'];

    if ($description !== '') {
        $stripeFields["line_items[$lineIndex][price_data][product_data][description]"] =
            $description;
    }

    $stripeFields["line_items[$lineIndex][price_data][unit_amount]"] =
        $unitAmount;

    $stripeFields["line_items[$lineIndex][quantity]"] =
        $quantity;

    /*
    |--------------------------------------------------------------------------
    | Tell Stripe Tax these are taxable physical products
    |--------------------------------------------------------------------------
    */

    $stripeFields["line_items[$lineIndex][price_data][product_data][tax_code]"] =
        'txcd_99999999';

    $lineIndex++;
}

/*
|--------------------------------------------------------------------------
| Stripe Checkout settings
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
| Automatic sales tax
|--------------------------------------------------------------------------
*/

$stripeFields['automatic_tax[enabled]'] = 'true';

/*
|--------------------------------------------------------------------------
| Shipping
|--------------------------------------------------------------------------
|
| Under $150:
|   Standard $7.95
|   Expedited $14.95
|
| $150 and above:
|   FREE Standard Shipping
|   Expedited $14.95
|--------------------------------------------------------------------------
*/

if ($merchandiseSubtotal >= 15000) {

    /*
    | FREE STANDARD SHIPPING
    */

    $stripeFields['shipping_options[0][shipping_rate_data][type]'] =
        'fixed_amount';

    $stripeFields['shipping_options[0][shipping_rate_data][fixed_amount][amount]'] =
        0;

    $stripeFields['shipping_options[0][shipping_rate_data][fixed_amount][currency]'] =
        'usd';

    $stripeFields['shipping_options[0][shipping_rate_data][display_name]'] =
        'Free Standard Shipping';

    $stripeFields['shipping_options[0][shipping_rate_data][delivery_estimate][minimum][unit]'] =
        'business_day';

    $stripeFields['shipping_options[0][shipping_rate_data][delivery_estimate][minimum][value]'] =
        3;

    $stripeFields['shipping_options[0][shipping_rate_data][delivery_estimate][maximum][unit]'] =
        'business_day';

    $stripeFields['shipping_options[0][shipping_rate_data][delivery_estimate][maximum][value]'] =
        7;

} else {

    /*
    | STANDARD SHIPPING — $7.95
    */

    $stripeFields['shipping_options[0][shipping_rate_data][type]'] =
        'fixed_amount';

    $stripeFields['shipping_options[0][shipping_rate_data][fixed_amount][amount]'] =
        795;

    $stripeFields['shipping_options[0][shipping_rate_data][fixed_amount][currency]'] =
        'usd';

    $stripeFields['shipping_options[0][shipping_rate_data][display_name]'] =
        'Standard Shipping';

    $stripeFields['shipping_options[0][shipping_rate_data][delivery_estimate][minimum][unit]'] =
        'business_day';

    $stripeFields['shipping_options[0][shipping_rate_data][delivery_estimate][minimum][value]'] =
        3;

    $stripeFields['shipping_options[0][shipping_rate_data][delivery_estimate][maximum][unit]'] =
        'business_day';

    $stripeFields['shipping_options[0][shipping_rate_data][delivery_estimate][maximum][value]'] =
        7;
}

/*
|--------------------------------------------------------------------------
| EXPEDITED SHIPPING — $14.95
|--------------------------------------------------------------------------
*/

$stripeFields['shipping_options[1][shipping_rate_data][type]'] =
    'fixed_amount';

$stripeFields['shipping_options[1][shipping_rate_data][fixed_amount][amount]'] =
    1495;

$stripeFields['shipping_options[1][shipping_rate_data][fixed_amount][currency]'] =
    'usd';

$stripeFields['shipping_options[1][shipping_rate_data][display_name]'] =
    'Expedited Shipping';

$stripeFields['shipping_options[1][shipping_rate_data][delivery_estimate][minimum][unit]'] =
    'business_day';

$stripeFields['shipping_options[1][shipping_rate_data][delivery_estimate][minimum][value]'] =
    1;

$stripeFields['shipping_options[1][shipping_rate_data][delivery_estimate][maximum][unit]'] =
    'business_day';

$stripeFields['shipping_options[1][shipping_rate_data][delivery_estimate][maximum][value]'] =
    3;

/*
|--------------------------------------------------------------------------
| Create Stripe Checkout Session
|--------------------------------------------------------------------------
*/

$ch = curl_init('https://api.stripe.com/v1/checkout/sessions');

curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => http_build_query($stripeFields),
    CURLOPT_HTTPHEADER => [
        'Authorization: Bearer ' . $stripeSecretKey,
        'Content-Type: application/x-www-form-urlencoded'
    ]
]);

$response = curl_exec($ch);

$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

$curlError = curl_error($ch);

curl_close($ch);

if ($response === false || $curlError) {
    http_response_code(500);

    echo json_encode([
        'error' => 'Stripe connection error: ' . $curlError
    ]);

    exit;
}

$stripeResponse = json_decode($response, true);

/*
|--------------------------------------------------------------------------
| Show useful Stripe errors while testing
|--------------------------------------------------------------------------
*/

if (
    $httpCode < 200 ||
    $httpCode >= 300 ||
    empty($stripeResponse['url'])
) {

    $stripeMessage =
        'Stripe could not create the checkout session.';

    if (!empty($stripeResponse['error']['message'])) {
        $stripeMessage .= ' ' . $stripeResponse['error']['message'];
    }

    http_response_code(500);

    echo json_encode([
        'error' => $stripeMessage
    ]);

    exit;
}

/*
|--------------------------------------------------------------------------
| Send Stripe Checkout URL back to cart
|--------------------------------------------------------------------------
*/

echo json_encode([
    'url' => $stripeResponse['url']
]);
