<?php
// database connection
$host = "localhost";
$user = "root";
$pass = "";
$db   = "student_registration_system";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8mb4");

// start session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

/** Escape output for HTML (XSS prevention) */
function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

/** Sanitize string for SQL (mysqli) */
function clean($conn, $value) {
    return mysqli_real_escape_string($conn, trim((string)$value));
}

/** Sanitize uploaded image filename; returns safe name or empty string */
function safe_upload_name($original) {
    $original = basename((string)$original);
    $ext = strtolower(pathinfo($original, PATHINFO_EXTENSION));
    $allowed = array('jpg', 'jpeg', 'png', 'gif', 'webp');
    if (!in_array($ext, $allowed, true)) {
        return "";
    }
    return time() . "_" . bin2hex(random_bytes(4)) . "." . $ext;
}
?>
