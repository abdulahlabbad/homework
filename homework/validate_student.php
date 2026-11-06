<?php
// validate_student.php — نقطة AJAX: بتستقبل بيانات الفورم وبترجع JSON بالأخطاء
require 'auth_check.php';
require 'db.php';
require 'functions.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['valid' => false, 'errors' => ['form' => 'Method not allowed']]);
    exit;
}

$ignoreId = isset($_POST['id']) && $_POST['id'] !== '' ? (int) $_POST['id'] : null;
$errors   = validateStudent($_POST, $pdo, $ignoreId);

echo json_encode([
    'valid'  => empty($errors),
    'errors' => $errors,
]);
