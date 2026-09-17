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
| HAIR CARE PRODUCT CATALOG
|--------------------------------------------------------------------------
| Prices are in cents.
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
| BURMESE DOUBLE DRAWN BUNDLE PRICING
|--------------------------------------------------------------------------
| Retail prices in cents.
|--------------------------------------------------------------------------
*/

$BURMESE_BASE = [

    10 => 5100,
    12 => 5700,
    14 => 6300,
    16 => 7800,
    18 => 9600,
    20 => 11400,
    22 => 13200,
    24 => 15000,
    26 => 17400,
    28 => 20100,
    30 => 22800

];

$BURMESE_SPECIALTY = [

    10 => 5700,
    12 => 6300,
    14 => 6900,
    16 => 8400,
    18 => 10200,
    20 => 12000,
    22 => 13800,
    24 => 15600,
    26 => 18000,
    28 => 20700,
    30 => 23400

];

$BURMESE_SPECIALTY_TEXTURES = [

    'Deep Wave',
    'Curly',
    'Kinky Straight',
    'Kinky Curly'

];

/*
|--------------------------------------------------------------------------
| BIG BOSS BEAUTY ACADEMY
|--------------------------------------------------------------------------
| Authoritative server-side class pricing.
| Prices are in cents.
|--------------------------------------------------------------------------
*/

$ACADEMY_CLASSES = [

    '2026-09-26-stitch-braids' => [
        'course' => 'Stitch Braids',
        'date' => 'September 26, 2026',
        'time' => '12:00 PM – 4:30 PM',
        'youthPrice' => 29900,
        'adultPrice' => 34900
    ],

    '2026-10-03-quick-weave' => [
        'course' => 'Quick Weave',
        'date' => 'October 3, 2026',
        'time' => '12:00 PM – 4:30 PM',
        'youthPrice' => 29900,
        'adultPrice' => 34900
    ],

    '2026-10-10-wig-installation' => [
        'course' => 'Wig Installation',
        'date' => 'October 10, 2026',
        'time' => '12:00 PM – 4:00 PM',
        'youthPrice' => 40000,
        'adultPrice' => 45000
    ],

    '2026-10-17-sew-in-installation' => [
        'course' => 'Sew In Installation',
        'date' => 'October 17, 2026',
        'time' => '12:00 PM – 4:30 PM',
        'youthPrice' => 32500,
        'adultPrice' => 37500
    ],

    '2026-10-24-feed-in-braids' => [
        'course' => 'Feed In Braids',
        'date' => 'October 24, 2026',
        'time' => '12:00 PM – 4:30 PM',
        'youthPrice' => 27500,
        'adultPrice' => 32500
    ],

    '2026-10-31-box-braids' => [
        'course' => 'Box Braids',
        'date' => 'October 31, 2026',
        'time' => '12:00 PM – 4:30 PM',
        'youthPrice' => 27500,
        'adultPrice' => 32500
    ],

    '2026-11-07-cornrows-fundamentals' => [
        'course' => 'Cornrows Fundamentals',
        'date' => 'November 7, 2026',
        'time' => '12:00 PM – 4:30 PM',
        'youthPrice' => 24900,
        'adultPrice' => 29900
    ],

    '2026-11-14-stitch-braids' => [
        'course' => 'Stitch Braids',
        'date' => 'November 14, 2026',
        'time' => '12:00 PM – 4:30 PM',
        'youthPrice' => 29900,
        'adultPrice' => 34900
    ],

    '2026-11-21-quick-weave' => [
        'course' => 'Quick Weave',
        'date' => 'November 21, 2026',
        'time' => '12:00 PM – 4:30 PM',
        'youthPrice' => 29900,
        'adultPrice' => 34900
    ],

    '2026-11-28-wig-installation' => [
        'course' => 'Wig Installation',
        'date' => 'November 28, 2026',
        'time' => '12:00 PM – 4:00 PM',
        'youthPrice' => 40000,
        'adultPrice' => 45000
    ],

    '2026-12-05-sew-in-installation' => [
        'course' => 'Sew In Installation',
        'date' => 'December 5, 2026',
        'time' => '12:00 PM – 4:30 PM',
        'youthPrice' => 32500,
        'adultPrice' => 37500
    ],

    '2026-12-12-feed-in-braids' => [
        'course' => 'Feed In Braids',
        'date' => 'December 12, 2026',
        'time' => '12:00 PM – 4:30 PM',
        'youthPrice' => 27500,
        'adultPrice' => 32500
    ],

    '2026-12-19-box-braids' => [
        'course' => 'Box Braids',
        'date' => 'December 19, 2026',
        'time' => '12:00 PM – 4:30 PM',
        'youthPrice' => 27500,
        'adultPrice' => 32500
    ],

    /* December 26, 2026 — No Class */
    /* January 2, 2027 — No Class */

    '2027-01-09-cornrows-fundamentals' => [
        'course' => 'Cornrows Fundamentals',
        'date' => 'January 9, 2027',
        'time' => '12:00 PM – 4:30 PM',
        'youthPrice' => 24900,
        'adultPrice' => 29900
    ],

    '2027-01-16-stitch-braids' => [
        'course' => 'Stitch Braids',
        'date' => 'January 16, 2027',
        'time' => '12:00 PM – 4:30 PM',
        'youthPrice' => 29900,
        'adultPrice' => 34900
    ],

    '2027-01-23-quick-weave' => [
        'course' => 'Quick Weave',
        'date' => 'January 23, 2027',
        'time' => '12:00 PM – 4:30 PM',
        'youthPrice' => 29900,
        'adultPrice' => 34900
    ],

    '2027-01-30-wig-installation' => [
        'course' => 'Wig Installation',
        'date' => 'January 30, 2027',
        'time' => '12:00 PM – 4:00 PM',
        'youthPrice' => 40000,
        'adultPrice' => 45000
    ],

    '2027-02-06-sew-in-installation' => [
        'course' => 'Sew In Installation',
        'date' => 'February 6, 2027',
        'time' => '12:00 PM – 4:30 PM',
        'youthPrice' => 32500,
        'adultPrice' => 37500
    ],

    '2027-02-13-feed-in-braids' => [
        'course' => 'Feed In Braids',
        'date' => 'February 13, 2027',
        'time' => '12:00 PM – 4:30 PM',
        'youthPrice' => 27500,
        'adultPrice' => 32500
    ],

    '2027-02-20-box-braids' => [
        'course' => 'Box Braids',
        'date' => 'February 20, 2027',
        'time' => '12:00 PM – 4:30 PM',
        'youthPrice' => 27500,
        'adultPrice' => 32500
    ],

    '2027-02-27-cornrows-fundamentals' => [
        'course' => 'Cornrows Fundamentals',
        'date' => 'February 27, 2027',
        'time' => '12:00 PM – 4:30 PM',
        'youthPrice' => 24900,
        'adultPrice' => 29900
    ],

    '2027-03-06-stitch-braids' => [
        'course' => 'Stitch Braids',
        'date' => 'March 6, 2027',
        'time' => '12:00 PM – 4:30 PM',
        'youthPrice' => 29900,
        'adultPrice' => 34900
    ],

    '2027-03-13-quick-weave' => [
        'course' => 'Quick Weave',
        'date' => 'March 13, 2027',
        'time' => '12:00 PM – 4:30 PM',
        'youthPrice' => 29900,
        'adultPrice' => 34900
    ],

    '2027-03-20-wig-installation' => [
        'course' => 'Wig Installation',
        'date' => 'March 20, 2027',
        'time' => '12:00 PM – 4:00 PM',
        'youthPrice' => 40000,
        'adultPrice' => 45000
    ],

    '2027-03-27-sew-in-installation' => [
        'course' => 'Sew In Installation',
        'date' => 'March 27, 2027',
        'time' => '12:00 PM – 4:30 PM',
        'youthPrice' => 32500,
        'adultPrice' => 37500
    ]

];
/*
|--------------------------------------------------------------------------
| Read cart
|--------------------------------------------------------------------------
*/

$input = json_decode(
    file_get_contents('php://input'),
    true
);

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

/*
|--------------------------------------------------------------------------
| Prepare Stripe line items
|--------------------------------------------------------------------------
*/

$stripeFields = [];

$lineIndex = 0;

$merchandiseSubtotal = 0;

$academySessionId = '';
$academyReservationToken = '';

/*
|--------------------------------------------------------------------------
| Build secure Stripe line items
|--------------------------------------------------------------------------
*/

foreach ($input['items'] as $item) {

    $productId =
        $item['productId'] ?? '';

    $quantity =
        intval(
            $item['quantity'] ?? 1
        );

    if ($quantity < 1) {
        $quantity = 1;
    }

    if ($quantity > 20) {
        $quantity = 20;
    }

    /*
    |--------------------------------------------------------------------------
    | BURMESE BUNDLES
    |--------------------------------------------------------------------------
    */

    if ($productId === 'burmese-bundle') {

        $texture =
            trim(
                $item['texture'] ?? ''
            );

        $lengthRaw =
            $item['length'] ?? '';

        /*
        | Handles:
        | 12"
        | 12 inches
        | 12
        */

        $length =
            intval(
                preg_replace(
                    '/[^0-9]/',
                    '',
                    (string)$lengthRaw
                )
            );

        $allowedTextures = [

            'Straight',
            'Body Wave',
            'Deep Wave',
            'Curly',
            'Kinky Straight',
            'Kinky Curly'

        ];

        if (
            !in_array(
                $texture,
                $allowedTextures,
                true
            )
        ) {

            http_response_code(400);

            echo json_encode([
                'error' =>
                    'Invalid Burmese bundle texture.'
            ]);

            exit;
        }

        /*
        |--------------------------------------------------------------------------
        | Select secure price table
        |--------------------------------------------------------------------------
        */

        if (
            in_array(
                $texture,
                $BURMESE_SPECIALTY_TEXTURES,
                true
            )
        ) {

            $priceTable =
                $BURMESE_SPECIALTY;

        } else {

            $priceTable =
                $BURMESE_BASE;
        }

        /*
        |--------------------------------------------------------------------------
        | Verify length exists
        |--------------------------------------------------------------------------
        */

        if (
            !isset(
                $priceTable[$length]
            )
        ) {

            http_response_code(400);

            echo json_encode([
                'error' =>
                    'That Burmese bundle length is not available.'
            ]);

            exit;
        }

        /*
        |--------------------------------------------------------------------------
        | Authoritative server price
        |--------------------------------------------------------------------------
        */

        $unitAmount =
            $priceTable[$length];

        $productName =
            'Burmese Double Drawn Bundle';

        $descriptionParts = [

            $texture,
            $length . '"'

        ];

        if (
            !empty(
                $item['color']
            )
        ) {

            $descriptionParts[] =
                $item['color'];
        }

        $descriptionParts[] =
            'Double Drawn';

        $descriptionParts[] =
            'Extra Full';

        $description =
            implode(
                ' • ',
                $descriptionParts
            );

    }

    /*
    |--------------------------------------------------------------------------
    | HAIR CARE PRODUCTS
    |--------------------------------------------------------------------------
    */

    elseif (
        isset(
            $PRODUCTS[$productId]
        )
    ) {

        $product =
            $PRODUCTS[$productId];

        $unitAmount =
            intval(
                $product['price']
            );

        $productName =
            $product['name'];

        $variationParts = [];

        if (
            !empty(
                $item['texture']
            )
        ) {

            $variationParts[] =
                $item['texture'];
        }

        if (
            !empty(
                $item['length']
            )
        ) {

            $variationParts[] =
                $item['length'];
        }

        if (
            !empty(
                $item['density']
            )
        ) {

            $variationParts[] =
                $item['density'];
        }

        if (
            !empty(
                $item['laceSize']
            )
        ) {

            $variationParts[] =
                $item['laceSize'];
        }

        if (
            !empty(
                $item['color']
            )
        ) {

            $variationParts[] =
                $item['color'];
        }

        $description =
            implode(
                ' • ',
                $variationParts
            );

    }
       

   /*
|--------------------------------------------------------------------------
| BIG BOSS BEAUTY ACADEMY REGISTRATION
|--------------------------------------------------------------------------
*/

elseif (
    strpos($productId, 'academy-') === 0
) {

    $classSessionId =
        trim(
            $item['classSessionId'] ?? ''
        );

    $studentType =
        trim(
            $item['studentType'] ?? ''
        );

    $studentAge =
        intval(
            $item['studentAge'] ?? 0
        );

    $studentName =
        trim(
            $item['studentName'] ?? ''
        );

    $studentEmail =
        trim(
            $item['studentEmail'] ?? ''
        );

    $studentPhone =
        trim(
            $item['studentPhone'] ?? ''
        );

    $parentName =
        trim(
            $item['parentName'] ?? ''
        );

    $parentPhone =
        trim(
            $item['parentPhone'] ?? ''
        );

    $parentEmail =
        trim(
            $item['parentEmail'] ?? ''
        );


    /*
    |--------------------------------------------------------------------------
    | VERIFY CLASS SESSION
    |--------------------------------------------------------------------------
    */

    if (
        !isset(
            $ACADEMY_CLASSES[$classSessionId]
        )
    ) {

        http_response_code(400);

        echo json_encode([
            'error' =>
                'That Beauty Academy class is not available.'
        ]);

        exit;
    }


    $class =
        $ACADEMY_CLASSES[$classSessionId];
        $academySessionId =
    $classSessionId;
    /*
|--------------------------------------------------------------------------
| VERIFY CLASS HAS AVAILABLE SEATS
|--------------------------------------------------------------------------
*/

$academySeatFile =
    dirname(__DIR__, 2) .
    '/academy-private/academy-seats.json';


if (!is_file($academySeatFile)) {

    http_response_code(500);

    echo json_encode([
        'error' =>
            'Academy seat availability is temporarily unavailable.'
    ]);

    exit;
}


$academySeatContents =
    file_get_contents(
        $academySeatFile
    );


$academySeats =
    json_decode(
        $academySeatContents,
        true
    );


if (
    !is_array($academySeats) ||
    !array_key_exists(
        $classSessionId,
        $academySeats
    )
) {

    http_response_code(500);

    echo json_encode([
        'error' =>
            'Academy seat availability could not be verified.'
    ]);

    exit;
}


$seatsRemaining =
    intval(
        $academySeats[$classSessionId]
    );


if ($seatsRemaining <= 0) {

    http_response_code(409);

    echo json_encode([
        'error' =>
            'This Beauty Academy class is sold out. Please join the waiting list.'
    ]);

    exit;
}


    /*
    |--------------------------------------------------------------------------
    | VERIFY PRODUCT ID MATCHES CLASS SESSION
    |--------------------------------------------------------------------------
    */

    if (
        $productId !==
        'academy-' . $classSessionId
    ) {

        http_response_code(400);

        echo json_encode([
            'error' =>
                'Invalid Beauty Academy registration.'
        ]);

        exit;
    }


    /*
    |--------------------------------------------------------------------------
    | VERIFY STUDENT AGE AND REGISTRATION TYPE
    |--------------------------------------------------------------------------
    */

    if (
        $studentAge >= 10 &&
        $studentAge <= 16
    ) {

        if (
            $studentType !== 'youth'
        ) {

            http_response_code(400);

            echo json_encode([
                'error' =>
                    'Students ages 10 through 16 must use Youth registration.'
            ]);

            exit;
        }


        $unitAmount =
            intval(
                $class['youthPrice']
            );

    } elseif (
        $studentAge >= 17
    ) {

        if (
            $studentType !== 'adult'
        ) {

            http_response_code(400);

            echo json_encode([
                'error' =>
                    'Students age 17 and older must use Adult registration.'
            ]);

            exit;
        }


        $unitAmount =
            intval(
                $class['adultPrice']
            );

    } else {

        http_response_code(400);

        echo json_encode([
            'error' =>
                'Beauty Academy students must be at least 10 years old.'
        ]);

        exit;
    }


    /*
    |--------------------------------------------------------------------------
    | REQUIRE STUDENT INFORMATION
    |--------------------------------------------------------------------------
    */

    if (
        $studentName === '' ||
        $studentEmail === '' ||
        $studentPhone === ''
    ) {

        http_response_code(400);

        echo json_encode([
            'error' =>
                'Student registration information is incomplete.'
        ]);

        exit;
    }


    /*
    |--------------------------------------------------------------------------
    | REQUIRE PARENT/GUARDIAN INFORMATION FOR YOUTH
    |--------------------------------------------------------------------------
    */

    if (
        $studentType === 'youth' &&
        (
            $parentName === '' ||
            $parentPhone === '' ||
            $parentEmail === ''
        )
    ) {

        http_response_code(400);

        echo json_encode([
            'error' =>
                'Parent or guardian information is required for Youth registration.'
        ]);

        exit;
    }


    /*
    |--------------------------------------------------------------------------
    | ONE REGISTRATION = ONE CLASS SEAT
    |--------------------------------------------------------------------------
    */

    $quantity = 1;


    /*
    |--------------------------------------------------------------------------
    | STRIPE PRODUCT INFORMATION
    |--------------------------------------------------------------------------
    */

    $productName =
        $class['course'] .
        ' Masterclass';


    $descriptionParts = [

        'Class Date: ' .
        $class['date'],

        'Time: ' .
        $class['time'],

        'Student: ' .
        $studentName,

        'Age: ' .
        $studentAge,

        $studentType === 'youth'
            ? 'Youth Ages 10–16'
            : 'Adult Ages 17+'

    ];


    $description =
        implode(
            ' • ',
            $descriptionParts
        );

}
    else {

        http_response_code(400);

        echo json_encode([
            'error' =>
                'One of the products in your bag is not available for checkout yet: ' .
                $productId
        ]);

        exit;
    }


  /*
|--------------------------------------------------------------------------
| Merchandise subtotal
|--------------------------------------------------------------------------
| Academy classes do not count toward merchandise shipping.
*/

if (strpos($productId, 'academy-') !== 0) {

    $merchandiseSubtotal +=
        $unitAmount * $quantity;

}

    /*
    |--------------------------------------------------------------------------
    | Stripe line item
    |--------------------------------------------------------------------------
    */

    $stripeFields[
        "line_items[$lineIndex][price_data][currency]"
    ] = 'usd';

    $stripeFields[
        "line_items[$lineIndex][price_data][product_data][name]"
    ] = $productName;

    if (
        $description !== ''
    ) {

        $stripeFields[
            "line_items[$lineIndex][price_data][product_data][description]"
        ] = $description;
    }

    $stripeFields[
        "line_items[$lineIndex][price_data][unit_amount]"
    ] = $unitAmount;

    $stripeFields[
        "line_items[$lineIndex][quantity]"
    ] = $quantity;

    /*

|--------------------------------------------------------------------------
| Stripe tax code
|--------------------------------------------------------------------------
| Academy registrations are services, not tangible merchandise.
*/

if (strpos($productId, 'academy-') !== 0) {

    $stripeFields[
        "line_items[$lineIndex][price_data][product_data][tax_code]"
    ] = 'txcd_99999999';

}

    $lineIndex++;
}

/*
|--------------------------------------------------------------------------
| Stripe Checkout settings
|--------------------------------------------------------------------------
*/

$stripeFields['mode'] =
    'payment';
    /*
|--------------------------------------------------------------------------
| CHECKOUT SESSION EXPIRATION
|--------------------------------------------------------------------------
| Academy seats are held for 30 minutes while the customer pays.
*/

if ($academySessionId !== '') {

    $stripeFields['expires_at'] =
        time() + (30 * 60);

}
    
    if ($academySessionId !== '') {

    $stripeFields[
        'metadata[academy_session_id]'
    ] = $academySessionId;

}

$stripeFields['success_url'] =
    'https://bigbossbundles.com/success.html?session_id={CHECKOUT_SESSION_ID}';

$stripeFields['cancel_url'] =
    'https://bigbossbundles.com/cart.html';

$stripeFields['billing_address_collection'] =
    'auto';

if ($merchandiseSubtotal > 0) {

    $stripeFields[
        'shipping_address_collection[allowed_countries][0]'
    ] = 'US';

}

$stripeFields[
    'phone_number_collection[enabled]'
] = 'true';

$stripeFields[
    'allow_promotion_codes'
] = 'true';

/*
|--------------------------------------------------------------------------
| Automatic Stripe Tax
|--------------------------------------------------------------------------
*/

$stripeFields[
    'automatic_tax[enabled]'
] = 'true';

/*
|--------------------------------------------------------------------------
| Shipping
|--------------------------------------------------------------------------
|
| Under $150:
| Standard $7.95
| Expedited $14.95
|
| $150+:
| Free Standard
| Expedited $14.95
|--------------------------------------------------------------------------
*/
if ($merchandiseSubtotal > 0) {

if (
    $merchandiseSubtotal >= 15000
) {

    /*
    |--------------------------------------------------------------------------
    | FREE STANDARD SHIPPING
    |--------------------------------------------------------------------------
    */

    $stripeFields[
        'shipping_options[0][shipping_rate_data][type]'
    ] = 'fixed_amount';

    $stripeFields[
        'shipping_options[0][shipping_rate_data][fixed_amount][amount]'
    ] = 0;

    $stripeFields[
        'shipping_options[0][shipping_rate_data][fixed_amount][currency]'
    ] = 'usd';

    $stripeFields[
        'shipping_options[0][shipping_rate_data][display_name]'
    ] = 'Free Standard Shipping';

    $stripeFields[
        'shipping_options[0][shipping_rate_data][delivery_estimate][minimum][unit]'
    ] = 'business_day';

    $stripeFields[
        'shipping_options[0][shipping_rate_data][delivery_estimate][minimum][value]'
    ] = 3;

    $stripeFields[
        'shipping_options[0][shipping_rate_data][delivery_estimate][maximum][unit]'
    ] = 'business_day';

    $stripeFields[
        'shipping_options[0][shipping_rate_data][delivery_estimate][maximum][value]'
    ] = 7;

} else {

    /*
    |--------------------------------------------------------------------------
    | STANDARD SHIPPING — $7.95
    |--------------------------------------------------------------------------
    */

    $stripeFields[
        'shipping_options[0][shipping_rate_data][type]'
    ] = 'fixed_amount';

    $stripeFields[
        'shipping_options[0][shipping_rate_data][fixed_amount][amount]'
    ] = 795;

    $stripeFields[
        'shipping_options[0][shipping_rate_data][fixed_amount][currency]'
    ] = 'usd';

    $stripeFields[
        'shipping_options[0][shipping_rate_data][display_name]'
    ] = 'Standard Shipping';

    $stripeFields[
        'shipping_options[0][shipping_rate_data][delivery_estimate][minimum][unit]'
    ] = 'business_day';

    $stripeFields[
        'shipping_options[0][shipping_rate_data][delivery_estimate][minimum][value]'
    ] = 3;

    $stripeFields[
        'shipping_options[0][shipping_rate_data][delivery_estimate][maximum][unit]'
    ] = 'business_day';

    $stripeFields[
        'shipping_options[0][shipping_rate_data][delivery_estimate][maximum][value]'
    ] = 7;
}

/*
|--------------------------------------------------------------------------
| EXPEDITED SHIPPING — $14.95
|--------------------------------------------------------------------------
*/

$stripeFields[
    'shipping_options[1][shipping_rate_data][type]'
] = 'fixed_amount';

$stripeFields[
    'shipping_options[1][shipping_rate_data][fixed_amount][amount]'
] = 1495;

$stripeFields[
    'shipping_options[1][shipping_rate_data][fixed_amount][currency]'
] = 'usd';

$stripeFields[
    'shipping_options[1][shipping_rate_data][display_name]'
] = 'Expedited Shipping';

$stripeFields[
    'shipping_options[1][shipping_rate_data][delivery_estimate][minimum][unit]'
] = 'business_day';

$stripeFields[
    'shipping_options[1][shipping_rate_data][delivery_estimate][minimum][value]'
] = 1;

$stripeFields[
    'shipping_options[1][shipping_rate_data][delivery_estimate][maximum][unit]'
] = 'business_day';

$stripeFields[
    'shipping_options[1][shipping_rate_data][delivery_estimate][maximum][value]'
] = 3;
}

/*
|--------------------------------------------------------------------------
| RESERVE BEAUTY ACADEMY SEAT BEFORE STRIPE CHECKOUT
|--------------------------------------------------------------------------
*/

if ($academySessionId !== '') {

    $academySeatFile =
        dirname(__DIR__, 2) .
        '/academy-private/academy-seats.json';

    $academyReservationFile =
        dirname(__DIR__, 2) .
        '/academy-private/academy-reservations.json';

    $academyLockFile =
        dirname(__DIR__, 2) .
        '/academy-private/academy-seat-inventory.lock';

    $academyLockHandle =
        fopen(
            $academyLockFile,
            'c'
        );

    if ($academyLockHandle === false) {
        http_response_code(500);
        echo json_encode([
            'error' =>
                'Unable to access Academy seat inventory.'
        ]);
        exit;
    }

    if (!flock($academyLockHandle, LOCK_EX)) {
        fclose($academyLockHandle);

        http_response_code(500);
        echo json_encode([
            'error' =>
                'Unable to lock Academy seat inventory.'
        ]);
        exit;
    }

    $academySeatContents =
        file_get_contents(
            $academySeatFile
        );

    $academyReservationContents =
        file_get_contents(
            $academyReservationFile
        );

    $academySeats =
        json_decode(
            $academySeatContents,
            true
        );

    $academyReservations =
        json_decode(
            $academyReservationContents,
            true
        );

    if (
        !is_array($academySeats) ||
        !is_array($academyReservations) ||
        !array_key_exists(
            $academySessionId,
            $academySeats
        )
    ) {
        flock(
            $academyLockHandle,
            LOCK_UN
        );

        fclose(
            $academyLockHandle
        );

        http_response_code(500);

        echo json_encode([
            'error' =>
                'Academy seat inventory could not be verified.'
        ]);

        exit;
    }

    $currentAcademySeats =
        intval(
            $academySeats[
                $academySessionId
            ]
        );

    if ($currentAcademySeats <= 0) {

        flock(
            $academyLockHandle,
            LOCK_UN
        );

        fclose(
            $academyLockHandle
        );

        http_response_code(409);

        echo json_encode([
            'error' =>
                'This Beauty Academy class is sold out. Please join the waiting list.'
        ]);

        exit;
    }

    try {

        $academyReservationToken =
            bin2hex(
                random_bytes(16)
            );

    } catch (Throwable $e) {

        flock(
            $academyLockHandle,
            LOCK_UN
        );

        fclose(
            $academyLockHandle
        );

        http_response_code(500);

        echo json_encode([
            'error' =>
                'Unable to create Academy reservation.'
        ]);

        exit;
    }

    $academySeats[
        $academySessionId
    ] =
        $currentAcademySeats - 1;

    $academyReservations[
        $academyReservationToken
    ] = [
        'academy_session_id' =>
            $academySessionId,

        'status' =>
            'reserved',

        'created_at' =>
            gmdate('c'),

        'stripe_session_id' =>
            ''
    ];

   $academyReservationsSaved =
    file_put_contents(
        $academyReservationFile,
        json_encode(
            $academyReservations,
            JSON_PRETTY_PRINT |
            JSON_UNESCAPED_SLASHES
        ),
        LOCK_EX
    );

if ($academyReservationsSaved === false) {

    flock(
        $academyLockHandle,
        LOCK_UN
    );

    fclose(
        $academyLockHandle
    );

    http_response_code(500);

    echo json_encode([
        'error' =>
            'Academy reservation could not be saved.'
    ]);

    exit;
}

$academySeatsSaved =
    file_put_contents(
        $academySeatFile,
        json_encode(
            $academySeats,
            JSON_PRETTY_PRINT |
            JSON_UNESCAPED_SLASHES
        ),
        LOCK_EX
    );

if ($academySeatsSaved === false) {

    unset(
        $academyReservations[
            $academyReservationToken
        ]
    );

    file_put_contents(
        $academyReservationFile,
        json_encode(
            $academyReservations,
            JSON_PRETTY_PRINT |
            JSON_UNESCAPED_SLASHES
        ),
        LOCK_EX
    );

    flock(
        $academyLockHandle,
        LOCK_UN
    );

    fclose(
        $academyLockHandle
    );

    http_response_code(500);

    echo json_encode([
        'error' =>
            'Academy seat reservation could not be completed.'
    ]);

    exit;
}


    flock(
        $academyLockHandle,
        LOCK_UN
    );

    fclose(
        $academyLockHandle
    );

    $stripeFields[
        'metadata[academy_reservation_id]'
    ] =
        $academyReservationToken;
}
/*
|--------------------------------------------------------------------------
| Create Stripe Checkout Session
|--------------------------------------------------------------------------
*/

$ch =
    curl_init(
        'https://api.stripe.com/v1/checkout/sessions'
    );

curl_setopt_array(
    $ch,
    [

        CURLOPT_RETURNTRANSFER => true,

        CURLOPT_POST => true,

        CURLOPT_POSTFIELDS =>
            http_build_query(
                $stripeFields
            ),

        CURLOPT_HTTPHEADER => [

            'Authorization: Bearer ' .
            $stripeSecretKey,

            'Content-Type: application/x-www-form-urlencoded'

        ]

    ]
);

$response =
    curl_exec(
        $ch
    );

$httpCode =
    curl_getinfo(
        $ch,
        CURLINFO_HTTP_CODE
    );

$curlError =
    curl_error(
        $ch
    );

curl_close(
    $ch
);

/*
|--------------------------------------------------------------------------
| Connection error
|--------------------------------------------------------------------------
*/

if (
    $response === false ||
    $curlError
) {

    /*
    |--------------------------------------------------------------------------
    | RETURN RESERVED ACADEMY SEAT
    |--------------------------------------------------------------------------
    */

    if ($academyReservationToken !== '') {

        $academyLockHandle =
            fopen(
                $academyLockFile,
                'c'
            );

        if (
            $academyLockHandle !== false &&
            flock(
                $academyLockHandle,
                LOCK_EX
            )
        ) {

            $academySeats =
                json_decode(
                    file_get_contents(
                        $academySeatFile
                    ),
                    true
                );

            $academyReservations =
                json_decode(
                    file_get_contents(
                        $academyReservationFile
                    ),
                    true
                );

            if (
                is_array($academySeats) &&
                is_array($academyReservations) &&
                isset(
                    $academyReservations[
                        $academyReservationToken
                    ]
                ) &&
                $academyReservations[
                    $academyReservationToken
                ]['status'] === 'reserved' &&
                array_key_exists(
                    $academySessionId,
                    $academySeats
                )
            ) {

                $academySeats[
                    $academySessionId
                ] =
                    min(
                        7,
                        intval(
                            $academySeats[
                                $academySessionId
                            ]
                        ) + 1
                    );

                $academyReservations[
                    $academyReservationToken
                ]['status'] =
                    'released';

                $academyReservations[
                    $academyReservationToken
                ]['released_at'] =
                    gmdate('c');

                file_put_contents(
                    $academySeatFile,
                    json_encode(
                        $academySeats,
                        JSON_PRETTY_PRINT |
                        JSON_UNESCAPED_SLASHES
                    ),
                    LOCK_EX
                );

                file_put_contents(
                    $academyReservationFile,
                    json_encode(
                        $academyReservations,
                        JSON_PRETTY_PRINT |
                        JSON_UNESCAPED_SLASHES
                    ),
                    LOCK_EX
                );
            }

            flock(
                $academyLockHandle,
                LOCK_UN
            );

            fclose(
                $academyLockHandle
            );
        }
    }

    http_response_code(500);

    echo json_encode([
        'error' =>
            'Stripe connection error: ' .
            $curlError
    ]);

    exit;
}

$stripeResponse =
    json_decode(
        $response,
        true
    );

/*
|--------------------------------------------------------------------------
| Stripe error
|--------------------------------------------------------------------------
*/

if (
    $httpCode < 200 ||
    $httpCode >= 300 ||
    empty($stripeResponse['url']) ||
    empty($stripeResponse['id'])
) {

    /*
    |--------------------------------------------------------------------------
    | RETURN RESERVED ACADEMY SEAT
    |--------------------------------------------------------------------------
    */

    if ($academyReservationToken !== '') {

        $academyLockHandle =
            fopen(
                $academyLockFile,
                'c'
            );

        if (
            $academyLockHandle !== false &&
            flock(
                $academyLockHandle,
                LOCK_EX
            )
        ) {

            $academySeats =
                json_decode(
                    file_get_contents(
                        $academySeatFile
                    ),
                    true
                );

            $academyReservations =
                json_decode(
                    file_get_contents(
                        $academyReservationFile
                    ),
                    true
                );

            if (
                is_array($academySeats) &&
                is_array($academyReservations) &&
                isset(
                    $academyReservations[
                        $academyReservationToken
                    ]
                ) &&
                $academyReservations[
                    $academyReservationToken
                ]['status'] === 'reserved' &&
                array_key_exists(
                    $academySessionId,
                    $academySeats
                )
            ) {

                $academySeats[
                    $academySessionId
                ] =
                    min(
                        7,
                        intval(
                            $academySeats[
                                $academySessionId
                            ]
                        ) + 1
                    );

                $academyReservations[
                    $academyReservationToken
                ]['status'] =
                    'released';

                $academyReservations[
                    $academyReservationToken
                ]['released_at'] =
                    gmdate('c');

                file_put_contents(
                    $academySeatFile,
                    json_encode(
                        $academySeats,
                        JSON_PRETTY_PRINT |
                        JSON_UNESCAPED_SLASHES
                    ),
                    LOCK_EX
                );

                file_put_contents(
                    $academyReservationFile,
                    json_encode(
                        $academyReservations,
                        JSON_PRETTY_PRINT |
                        JSON_UNESCAPED_SLASHES
                    ),
                    LOCK_EX
                );
            }

            flock(
                $academyLockHandle,
                LOCK_UN
            );

            fclose(
                $academyLockHandle
            );
        }
    }

    $stripeMessage =
        'Stripe could not create the checkout session.';

    if (
        !empty(
            $stripeResponse[
                'error'
            ][
                'message'
            ]
        )
    ) {

        $stripeMessage .=
            ' ' .
            $stripeResponse[
                'error'
            ][
                'message'
            ];
    }

    http_response_code(500);

    echo json_encode([
        'error' =>
            $stripeMessage
    ]);

    exit;
}

/*
|--------------------------------------------------------------------------
| SAVE STRIPE SESSION ID TO ACADEMY RESERVATION
|--------------------------------------------------------------------------
*/

if (
    $academyReservationToken !== '' &&
    !empty($stripeResponse['id'])
) {

    $academyLockHandle =
        fopen(
            $academyLockFile,
            'c'
        );

    if (
        $academyLockHandle !== false &&
        flock(
            $academyLockHandle,
            LOCK_EX
        )
    ) {

        $academyReservations =
            json_decode(
                file_get_contents(
                    $academyReservationFile
                ),
                true
            );

        if (
            is_array($academyReservations) &&
            isset(
                $academyReservations[
                    $academyReservationToken
                ]
            ) &&
            $academyReservations[
                $academyReservationToken
            ]['status'] === 'reserved'
        ) {

            $academyReservations[
                $academyReservationToken
            ]['stripe_session_id'] =
                $stripeResponse['id'];

            $academyReservations[
                $academyReservationToken
            ]['stripe_session_created_at'] =
                gmdate('c');

            file_put_contents(
                $academyReservationFile,
                json_encode(
                    $academyReservations,
                    JSON_PRETTY_PRINT |
                    JSON_UNESCAPED_SLASHES
                ),
                LOCK_EX
            );
        }

        flock(
            $academyLockHandle,
            LOCK_UN
        );

        fclose(
            $academyLockHandle
        );
    }
}

/*
|--------------------------------------------------------------------------
| Return Stripe Checkout URL
|--------------------------------------------------------------------------
*/

echo json_encode([
    'url' =>
        $stripeResponse['url']
]);
