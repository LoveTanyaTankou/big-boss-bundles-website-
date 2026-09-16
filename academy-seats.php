<?php

header('Content-Type: application/json');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');


/*
|--------------------------------------------------------------------------
| BIG BOSS BEAUTY ACADEMY — PUBLIC SEAT AVAILABILITY
|--------------------------------------------------------------------------
| Reads the private seat inventory.
| This endpoint is READ ONLY.
|--------------------------------------------------------------------------
*/

$seatFile =
    dirname(__DIR__, 2) .
    '/academy-private/academy-seats.json';


/*
|--------------------------------------------------------------------------
| VERIFY PRIVATE INVENTORY EXISTS
|--------------------------------------------------------------------------
*/

if (!is_file($seatFile)) {

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'error' => 'Academy seat inventory is unavailable.'
    ]);

    exit;
}


/*
|--------------------------------------------------------------------------
| READ INVENTORY
|--------------------------------------------------------------------------
*/

$contents =
    file_get_contents($seatFile);

if ($contents === false) {

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'error' => 'Academy seat inventory could not be read.'
    ]);

    exit;
}


/*
|--------------------------------------------------------------------------
| DECODE INVENTORY
|--------------------------------------------------------------------------
*/

$seats =
    json_decode(
        $contents,
        true
    );

if (
    !is_array($seats) ||
    json_last_error() !== JSON_ERROR_NONE
) {

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'error' => 'Academy seat inventory is invalid.'
    ]);

    exit;
}


/*
|--------------------------------------------------------------------------
| SANITIZE PUBLIC RESPONSE
|--------------------------------------------------------------------------
| Only session IDs and remaining seat counts are returned.
|--------------------------------------------------------------------------
*/

$publicSeats = [];

foreach ($seats as $sessionId => $remaining) {

    if (!is_string($sessionId)) {
        continue;
    }

    $remaining = intval($remaining);

    if ($remaining < 0) {
        $remaining = 0;
    }

    if ($remaining > 7) {
        $remaining = 7;
    }

    $publicSeats[$sessionId] =
        $remaining;
}


/*
|--------------------------------------------------------------------------
| RETURN AVAILABILITY
|--------------------------------------------------------------------------
*/

echo json_encode([
    'success' => true,
    'capacity' => 7,
    'seats' => $publicSeats
]);
