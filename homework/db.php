<?php
// db.php — الاتصال بقاعدة البيانات باستخدام PDO
$host = 'localhost';
$db   = 'student_registration_system';
$user = 'root';
$pass = '';            // كلمة سر XAMPP الافتراضية فاضية

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$db;charset=utf8mb4",
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false, // Prepared statements حقيقية ضد SQL Injection
        ]
    );
} catch (PDOException $e) {
    die('Database connection failed.');
}
