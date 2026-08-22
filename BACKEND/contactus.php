<?php

// =========================================================
// CONTACT US
// =========================================================

header('Content-Type: application/json; charset=utf-8');


// =========================================================
// ONLY POST
// =========================================================

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    http_response_code(405);

    echo json_encode([
        'success' => false,
        'message' => 'Method not allowed.'
    ]);

    exit;
}


// =========================================================
// INITIALIZE ERRORS
// =========================================================

$errors = [];


// =========================================================
// GET FORM DATA
// =========================================================

$name = isset($_POST['name'])
    ? trim(strip_tags($_POST['name']))
    : '';

$email = isset($_POST['email'])
    ? trim($_POST['email'])
    : '';

$subject = isset($_POST['subject'])
    ? trim(strip_tags($_POST['subject']))
    : '';

$message = isset($_POST['message'])
    ? trim(strip_tags($_POST['message']))
    : '';


// =========================================================
// VALIDATE NAME
// =========================================================

if (mb_strlen($name) < 3) {

    $errors['name'] = 'Minimum length is 3 characters.';
} elseif (mb_strlen($name) > 100) {

    $errors['name'] = 'Name is too long.';
}


// =========================================================
// VALIDATE EMAIL
// =========================================================

if (empty($email)) {

    $errors['email'] = 'Email is required.';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

    $errors['email'] = 'Invalid email address.';
}


// =========================================================
// VALIDATE SUBJECT
// =========================================================

$allowedSubjects = [
    'Order Issue',
    'Refund Request',
    'Bug Report',
    'Other'
];

if (
    empty($subject) ||
    $subject === 'Subject' ||
    !in_array($subject, $allowedSubjects, true)
) {

    $errors['subject'] = 'Please choose a valid subject.';
}


// =========================================================
// VALIDATE MESSAGE
// =========================================================

if (mb_strlen($message) < 10) {

    $errors['message'] =
        'Minimum length is 10 characters.';
} elseif (mb_strlen($message) > 2000) {

    $errors['message'] =
        'Maximum length is 2000 characters.';
}


// =========================================================
// RETURN VALIDATION ERRORS
// =========================================================

if (!empty($errors)) {

    http_response_code(422);

    echo json_encode([
        'success' => false,
        'message' => 'Please correct the invalid values.',
        'errors' => $errors
    ]);

    exit;
}


// =========================================================
// START SESSION
// =========================================================

session_start();


// =========================================================
// RATE LIMIT
// =========================================================

$now = time();

if (
    isset($_SESSION['last_submit']) &&
    ($now - $_SESSION['last_submit']) < 30
) {

    http_response_code(429);

    echo json_encode([
        'success' => false,
        'message' => 'Please wait before sending another message.'
    ]);

    exit;
}


// =========================================================
// DATA FILE
// =========================================================

$dataFile = __DIR__ . '/contact_messages.json';


// =========================================================
// CREATE NEW MESSAGE
// =========================================================

$newMessage = [

    'name' => $name,

    'email' => $email,

    'subject' => $subject,

    'message' => $message,

    'date' => date('Y-m-d H:i:s'),

    'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
];


// =========================================================
// READ EXISTING MESSAGES
// =========================================================

$messages = [];

if (file_exists($dataFile)) {

    $content = file_get_contents($dataFile);

    $messages = json_decode(
        $content,
        true
    ) ?: [];
}


// =========================================================
// ADD NEW MESSAGE
// =========================================================

$messages[] = $newMessage;


// =========================================================
// SAVE MESSAGES
// =========================================================

file_put_contents(
    $dataFile,
    json_encode(
        $messages,
        JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
    )
);


// =========================================================
// UPDATE LAST SUBMIT TIME
// =========================================================

$_SESSION['last_submit'] = $now;


// =========================================================
// SUCCESS RESPONSE
// =========================================================

echo json_encode([
    'success' => true,
    'message' => 'Your message has been received. We will answer you soon.'
]);