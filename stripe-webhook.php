<?php

/*
|--------------------------------------------------------------------------
| BIG BOSS BUNDLES — STRIPE WEBHOOK
|--------------------------------------------------------------------------
*/

header('Content-Type: application/json');


/*
|--------------------------------------------------------------------------
| PRIVATE FILES
|--------------------------------------------------------------------------
*/

$configFile =
    dirname(__DIR__, 2) .
    '/academy-private/stripe-webhook-config.php';

$seatFile =
    dirname(__DIR__, 2) .
    '/academy-private/academy-seats.json';

$processedFile =
    dirname(__DIR__, 2) .
    '/academy-private/stripe-processed-events.json';

$reservationFile =
    dirname(__DIR__, 2) .
    '/academy-private/academy-reservations.json';


/*
|--------------------------------------------------------------------------
| LOAD WEBHOOK SECRET
|--------------------------------------------------------------------------
*/

if (!is_file($configFile)) {

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'error' => 'Webhook configuration unavailable.'
    ]);

    exit;
}

require $configFile;

if (
    !isset($STRIPE_WEBHOOK_SECRET) ||
    !is_string($STRIPE_WEBHOOK_SECRET) ||
    $STRIPE_WEBHOOK_SECRET === ''
) {

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'error' => 'Webhook configuration invalid.'
    ]);

    exit;
}


/*
|--------------------------------------------------------------------------
| READ STRIPE REQUEST
|--------------------------------------------------------------------------
*/

$payload =
    file_get_contents('php://input');

$signatureHeader =
    $_SERVER['HTTP_STRIPE_SIGNATURE'] ?? '';

if (
    $payload === false ||
    $payload === '' ||
    $signatureHeader === ''
) {

    http_response_code(400);

    echo json_encode([
        'success' => false,
        'error' => 'Invalid webhook request.'
    ]);

    exit;
}


/*
|--------------------------------------------------------------------------
| VERIFY STRIPE SIGNATURE
|--------------------------------------------------------------------------
*/

$timestamp = null;
$signatures = [];

foreach (
    explode(',', $signatureHeader)
    as $part
) {

    $pieces =
        explode(
            '=',
            trim($part),
            2
        );

    if (count($pieces) !== 2) {
        continue;
    }

    if ($pieces[0] === 't') {

        $timestamp =
            $pieces[1];

    } elseif ($pieces[0] === 'v1') {

        $signatures[] =
            $pieces[1];
    }
}

if (
    $timestamp === null ||
    empty($signatures) ||
    !ctype_digit($timestamp)
) {

    http_response_code(400);

    echo json_encode([
        'success' => false,
        'error' => 'Invalid Stripe signature.'
    ]);

    exit;
}


/*
|--------------------------------------------------------------------------
| REJECT OLD SIGNATURES
|--------------------------------------------------------------------------
*/

if (
    abs(time() - intval($timestamp)) > 300
) {

    http_response_code(400);

    echo json_encode([
        'success' => false,
        'error' => 'Expired Stripe signature.'
    ]);

    exit;
}

$signedPayload =
    $timestamp .
    '.' .
    $payload;

$expectedSignature =
    hash_hmac(
        'sha256',
        $signedPayload,
        $STRIPE_WEBHOOK_SECRET
    );

$signatureValid = false;

foreach ($signatures as $signature) {

    if (
        hash_equals(
            $expectedSignature,
            $signature
        )
    ) {

        $signatureValid = true;
        break;
    }
}

if (!$signatureValid) {

    http_response_code(400);

    echo json_encode([
        'success' => false,
        'error' => 'Stripe signature verification failed.'
    ]);

    exit;
}


/*
|--------------------------------------------------------------------------
| DECODE EVENT
|--------------------------------------------------------------------------
*/

$event =
    json_decode(
        $payload,
        true
    );

if (
    !is_array($event) ||
    empty($event['id']) ||
    empty($event['type'])
) {

    http_response_code(400);

    echo json_encode([
        'success' => false,
        'error' => 'Invalid Stripe event.'
    ]);

    exit;
}


/*
|--------------------------------------------------------------------------
| ONLY HANDLE CHECKOUT COMPLETION AND EXPIRATION
|--------------------------------------------------------------------------
*/

if (
    $event['type'] !== 'checkout.session.completed' &&
    $event['type'] !== 'checkout.session.expired'
) {

    http_response_code(200);

    echo json_encode([
        'success' => true,
        'ignored' => true
    ]);

    exit;
}


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


/*
|--------------------------------------------------------------------------
| GET ACADEMY METADATA
|--------------------------------------------------------------------------
*/

$academySessionId =
    trim(
        $session['metadata']['academy_session_id'] ?? ''
    );

$academyReservationId =
    trim(
        $session['metadata']['academy_reservation_id'] ?? ''
    );


/*
|--------------------------------------------------------------------------
| IGNORE NON-ACADEMY CHECKOUTS
|--------------------------------------------------------------------------
*/

if (
    $academySessionId === '' &&
    $academyReservationId === ''
) {

    http_response_code(200);

    echo json_encode([
        'success' => true,
        'ignored' => true,
        'reason' => 'Not an Academy registration.'
    ]);

    exit;
}


/*
|--------------------------------------------------------------------------
| REQUIRE COMPLETE ACADEMY METADATA
|--------------------------------------------------------------------------
*/

if (
    $academySessionId === '' ||
    $academyReservationId === ''
) {

    http_response_code(400);

    echo json_encode([
        'success' => false,
        'error' => 'Incomplete Academy checkout metadata.'
    ]);

    exit;
}


/*
|--------------------------------------------------------------------------
| VERIFY PRIVATE INVENTORY FILES
|--------------------------------------------------------------------------
*/

if (
    !is_file($seatFile) ||
    !is_file($processedFile) ||
    !is_file($reservationFile)
) {

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'error' => 'Academy inventory files unavailable.'
    ]);

    exit;
}


/*
|--------------------------------------------------------------------------
| CREATE INVENTORY LOCK
|--------------------------------------------------------------------------
*/

$lockFile =
    dirname($seatFile) .
    '/academy-seat-inventory.lock';

$lockHandle =
    fopen(
        $lockFile,
        'c'
    );

if ($lockHandle === false) {

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'error' => 'Unable to open Academy inventory lock.'
    ]);

    exit;
}

if (!flock($lockHandle, LOCK_EX)) {

    fclose($lockHandle);

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'error' => 'Unable to lock Academy inventory.'
    ]);

    exit;
}


/*
|--------------------------------------------------------------------------
| READ CURRENT INVENTORY
|--------------------------------------------------------------------------
*/

$seatContents =
    file_get_contents($seatFile);

$processedContents =
    file_get_contents($processedFile);

$reservationContents =
    file_get_contents($reservationFile);

$seats =
    json_decode(
        $seatContents,
        true
    );

$processedEvents =
    json_decode(
        $processedContents,
        true
    );

$reservations =
    json_decode(
        $reservationContents,
        true
    );

if (
    !is_array($seats) ||
    !is_array($processedEvents) ||
    !is_array($reservations)
) {

    flock($lockHandle, LOCK_UN);
    fclose($lockHandle);

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'error' => 'Academy inventory data is invalid.'
    ]);

    exit;
}


/*
|--------------------------------------------------------------------------
| PREVENT DUPLICATE STRIPE EVENT PROCESSING
|--------------------------------------------------------------------------
*/

$eventId =
    $event['id'];

if (
    in_array(
        $eventId,
        $processedEvents,
        true
    )
) {

    flock($lockHandle, LOCK_UN);
    fclose($lockHandle);

    http_response_code(200);

    echo json_encode([
        'success' => true,
        'duplicate' => true
    ]);

    exit;
}


/*
|--------------------------------------------------------------------------
| VERIFY CLASS EXISTS
|--------------------------------------------------------------------------
*/

if (
    !array_key_exists(
        $academySessionId,
        $seats
    )
) {

    flock($lockHandle, LOCK_UN);
    fclose($lockHandle);

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'error' => 'Academy class inventory not found.'
    ]);

    exit;
}


/*
|--------------------------------------------------------------------------
| VERIFY RESERVATION EXISTS
|--------------------------------------------------------------------------
*/

if (
    !array_key_exists(
        $academyReservationId,
        $reservations
    ) ||
    !is_array(
        $reservations[$academyReservationId]
    )
) {

    flock($lockHandle, LOCK_UN);
    fclose($lockHandle);

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'error' => 'Academy reservation not found.'
    ]);

    exit;
}

$reservation =
    $reservations[$academyReservationId];


/*
|--------------------------------------------------------------------------
| VERIFY RESERVATION BELONGS TO CLASS
|--------------------------------------------------------------------------
*/

if (
    ($reservation['academy_session_id'] ?? '') !==
    $academySessionId
) {

    flock($lockHandle, LOCK_UN);
    fclose($lockHandle);

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'error' => 'Academy reservation does not match class.'
    ]);

    exit;
}


/*
|--------------------------------------------------------------------------
| VERIFY STRIPE SESSION MATCHES RESERVATION
|--------------------------------------------------------------------------
*/

$storedStripeSessionId =
    trim(
        $reservation['stripe_session_id'] ?? ''
    );

$currentStripeSessionId =
    trim(
        $session['id'] ?? ''
    );

if (
    $storedStripeSessionId !== '' &&
    $currentStripeSessionId !== '' &&
    $storedStripeSessionId !== $currentStripeSessionId
) {

    flock($lockHandle, LOCK_UN);
    fclose($lockHandle);

    http_response_code(400);

    echo json_encode([
        'success' => false,
        'error' => 'Stripe Checkout Session does not match reservation.'
    ]);

    exit;
}


/*
|--------------------------------------------------------------------------
| PROCESS COMPLETED PAYMENT
|--------------------------------------------------------------------------
*/

if (
    $event['type'] === 'checkout.session.completed'
) {

    $reservationStatus =
        $reservation['status'] ?? '';

    if ($reservationStatus === 'reserved') {

        $reservations[$academyReservationId]['status'] =
            'paid';

        $reservations[$academyReservationId]['paid_at'] =
            gmdate('c');

        $reservations[$academyReservationId]['stripe_event_id'] =
            $eventId;

        if ($currentStripeSessionId !== '') {

            $reservations[$academyReservationId]['stripe_session_id'] =
                $currentStripeSessionId;
        }
    }
}


/*
|--------------------------------------------------------------------------
| PROCESS EXPIRED CHECKOUT
|--------------------------------------------------------------------------
*/

if (
    $event['type'] === 'checkout.session.expired'
) {

    $reservationStatus =
        $reservation['status'] ?? '';

    if ($reservationStatus === 'reserved') {

        $currentSeats =
            intval(
                $seats[$academySessionId]
            );

        $seats[$academySessionId] =
            min(
                7,
                $currentSeats + 1
            );

        $reservations[$academyReservationId]['status'] =
            'expired';

        $reservations[$academyReservationId]['expired_at'] =
            gmdate('c');

        $reservations[$academyReservationId]['stripe_event_id'] =
            $eventId;

        if ($currentStripeSessionId !== '') {

            $reservations[$academyReservationId]['stripe_session_id'] =
                $currentStripeSessionId;
        }
    }
}


/*
|--------------------------------------------------------------------------
| RECORD STRIPE EVENT
|--------------------------------------------------------------------------
*/

$processedEvents[] =
    $eventId;


/*
|--------------------------------------------------------------------------
| ENCODE UPDATED DATA
|--------------------------------------------------------------------------
*/

$seatJson =
    json_encode(
        $seats,
        JSON_PRETTY_PRINT |
        JSON_UNESCAPED_SLASHES
    );

$reservationJson =
    json_encode(
        $reservations,
        JSON_PRETTY_PRINT |
        JSON_UNESCAPED_SLASHES
    );

$processedJson =
    json_encode(
        $processedEvents,
        JSON_PRETTY_PRINT |
        JSON_UNESCAPED_SLASHES
    );

if (
    $seatJson === false ||
    $reservationJson === false ||
    $processedJson === false
) {

    flock($lockHandle, LOCK_UN);
    fclose($lockHandle);

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'error' => 'Academy inventory could not be encoded.'
    ]);

    exit;
}


/*
|--------------------------------------------------------------------------
| SAVE RESERVATION
|--------------------------------------------------------------------------
*/

$reservationSaved =
    file_put_contents(
        $reservationFile,
        $reservationJson,
        LOCK_EX
    );

if ($reservationSaved === false) {

    flock($lockHandle, LOCK_UN);
    fclose($lockHandle);

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'error' => 'Academy reservation could not be updated.'
    ]);

    exit;
}


/*
|--------------------------------------------------------------------------
| SAVE SEAT INVENTORY
|--------------------------------------------------------------------------
*/

$seatSaved =
    file_put_contents(
        $seatFile,
        $seatJson,
        LOCK_EX
    );

if ($seatSaved === false) {

    flock($lockHandle, LOCK_UN);
    fclose($lockHandle);

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'error' => 'Academy seat inventory could not be updated.'
    ]);

    exit;
}


/*
|--------------------------------------------------------------------------
| SAVE PROCESSED STRIPE EVENTS
|--------------------------------------------------------------------------
*/

$processedSaved =
    file_put_contents(
        $processedFile,
        $processedJson,
        LOCK_EX
    );

if ($processedSaved === false) {

    flock($lockHandle, LOCK_UN);
    fclose($lockHandle);

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'error' => 'Stripe event record could not be updated.'
    ]);

    exit;
}


/*
|--------------------------------------------------------------------------
| RELEASE INVENTORY LOCK
|--------------------------------------------------------------------------
*/

flock(
    $lockHandle,
    LOCK_UN
);

fclose(
    $lockHandle
);


/*
|--------------------------------------------------------------------------
| SUCCESS
|--------------------------------------------------------------------------
*/

http_response_code(200);

echo json_encode([
    'success' => true,
    'event_type' =>
        $event['type'],
    'academy_session_id' =>
        $academySessionId,
    'academy_reservation_id' =>
        $academyReservationId,
    'reservation_status' =>
        $reservations[$academyReservationId]['status'] ?? '',
    'seats_remaining' =>
        $seats[$academySessionId]
]);
