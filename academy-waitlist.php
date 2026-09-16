<?php

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    http_response_code(405);

    echo json_encode([
        'success' => false,
        'error' => 'Method not allowed.'
    ]);

    exit;
}


/*
|--------------------------------------------------------------------------
| READ WAITING LIST SUBMISSION
|--------------------------------------------------------------------------
*/

$input = json_decode(
    file_get_contents('php://input'),
    true
);

if (!$input || !is_array($input)) {

    http_response_code(400);

    echo json_encode([
        'success' => false,
        'error' => 'Invalid waiting list submission.'
    ]);

    exit;
}


/*
|--------------------------------------------------------------------------
| CLEAN FORM VALUES
|--------------------------------------------------------------------------
*/

$courseName = trim(
    $input['courseName'] ?? ''
);

$classSessionId = trim(
    $input['classSessionId'] ?? ''
);

$classDate = trim(
    $input['classDate'] ?? ''
);

$classTime = trim(
    $input['classTime'] ?? ''
);

$studentFirstName = trim(
    $input['studentFirstName'] ?? ''
);

$studentLastName = trim(
    $input['studentLastName'] ?? ''
);

$studentAge = intval(
    $input['studentAge'] ?? 0
);

$studentType = trim(
    $input['studentType'] ?? ''
);

$studentEmail = trim(
    $input['studentEmail'] ?? ''
);

$studentPhone = trim(
    $input['studentPhone'] ?? ''
);

$parentName = trim(
    $input['parentName'] ?? ''
);

$parentPhone = trim(
    $input['parentPhone'] ?? ''
);

$parentEmail = trim(
    $input['parentEmail'] ?? ''
);

$notifySeat = !empty(
    $input['notifySeat']
);

$notifyNext = !empty(
    $input['notifyNext']
);


/*
|--------------------------------------------------------------------------
| REQUIRED INFORMATION
|--------------------------------------------------------------------------
*/

if (
    $courseName === '' ||
    $classSessionId === '' ||
    $classDate === '' ||
    $studentFirstName === '' ||
    $studentLastName === '' ||
    $studentAge < 10 ||
    $studentEmail === '' ||
    $studentPhone === ''
) {

    http_response_code(400);

    echo json_encode([
        'success' => false,
        'error' => 'Please complete all required waiting list information.'
    ]);

    exit;
}


/*
|--------------------------------------------------------------------------
| VALIDATE EMAIL
|--------------------------------------------------------------------------
*/

if (!filter_var($studentEmail, FILTER_VALIDATE_EMAIL)) {

    http_response_code(400);

    echo json_encode([
        'success' => false,
        'error' => 'Please enter a valid student email address.'
    ]);

    exit;
}


/*
|--------------------------------------------------------------------------
| VALIDATE AGE GROUP
|--------------------------------------------------------------------------
*/

if ($studentAge >= 10 && $studentAge <= 16) {

    if ($studentType !== 'youth') {

        http_response_code(400);

        echo json_encode([
            'success' => false,
            'error' => 'Students ages 10 through 16 must use Youth registration.'
        ]);

        exit;
    }

    if (
        $parentName === '' ||
        $parentPhone === '' ||
        $parentEmail === ''
    ) {

        http_response_code(400);

        echo json_encode([
            'success' => false,
            'error' => 'Parent or guardian information is required for Youth students.'
        ]);

        exit;
    }

    if (!filter_var($parentEmail, FILTER_VALIDATE_EMAIL)) {

        http_response_code(400);

        echo json_encode([
            'success' => false,
            'error' => 'Please enter a valid parent or guardian email address.'
        ]);

        exit;
    }

} elseif ($studentAge >= 17) {

    if ($studentType !== 'adult') {

        http_response_code(400);

        echo json_encode([
            'success' => false,
            'error' => 'Students age 17 and older must use Adult registration.'
        ]);

        exit;
    }

} else {

    http_response_code(400);

    echo json_encode([
        'success' => false,
        'error' => 'Students must be at least 10 years old.'
    ]);

    exit;
}


/*
|--------------------------------------------------------------------------
| NOTIFICATION PREFERENCES
|--------------------------------------------------------------------------
*/

$seatPreference =
    $notifySeat ? 'YES' : 'NO';

$nextPreference =
    $notifyNext ? 'YES' : 'NO';


/*
|--------------------------------------------------------------------------
| EMAIL WAITING LIST NOTICE
|--------------------------------------------------------------------------
*/

$to = '5129375119@txt.att.net';

$subject =
    'Big Boss Beauty Academy Waiting List';


$message =
    "NEW BEAUTY ACADEMY WAITING LIST REQUEST\n\n" .

    "COURSE INFORMATION\n" .
    "Course: " . $courseName . "\n" .
    "Class Date: " . $classDate . "\n" .
    "Class Time: " . $classTime . "\n" .
    "Session ID: " . $classSessionId . "\n\n" .

    "STUDENT INFORMATION\n" .
    "Student: " .
        $studentFirstName . " " .
        $studentLastName . "\n" .

    "Age: " . $studentAge . "\n" .
    "Registration Type: " .
        ucfirst($studentType) . "\n" .

    "Email: " . $studentEmail . "\n" .
    "Phone: " . $studentPhone . "\n\n" .

    "WAITING LIST PREFERENCES\n" .
    "Notify if a seat opens: " .
        $seatPreference . "\n" .

    "Notify about next class: " .
        $nextPreference . "\n";


if ($studentType === 'youth') {

    $message .=

        "\nPARENT / GUARDIAN INFORMATION\n" .
        "Name: " . $parentName . "\n" .
        "Phone: " . $parentPhone . "\n" .
        "Email: " . $parentEmail . "\n";
}


$headers =
    "From: Big Boss Beauty Academy <noreply@bigbossbundles.com>\r\n" .
    "Reply-To: " . $studentEmail . "\r\n";


$mailSent = mail(
    $to,
    $subject,
    $message,
    $headers
);


/*
|--------------------------------------------------------------------------
| RETURN RESULT
|--------------------------------------------------------------------------
*/

if (!$mailSent) {

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'error' => 'Your waiting list request could not be sent. Please try again.'
    ]);

    exit;
}


echo json_encode([
    'success' => true,
    'message' => 'You have been added to the Big Boss Beauty Academy waiting list.'
]);
