/*
|--------------------------------------------------------------------------
| GET STRIPE CHECKOUT SESSION
|--------------------------------------------------------------------------
*/

$session =
    $event['data']['object'] ?? [];

if (!is_array($session)) {

    http_response_code(400);

    echo json_encode([
        'success' => false,
        'error' => 'Invalid Stripe Checkout Session.'
    ]);

    exit;
}


/*
|--------------------------------------------------------------------------
| COMPLETED CHECKOUT MUST BE PAID
|--------------------------------------------------------------------------
*/

if (
    $event['type'] === 'checkout.session.completed' &&
    ($session['payment_status'] ?? '') !== 'paid'
) {

    http_response_code(200);

    echo json_encode([
        'success' => true,
        'ignored' => true,
        'reason' => 'Completed Checkout Session is not paid.'
    ]);

    exit;
}
