<?php
// auth_check.php — حماية الصفحات: لازم المستخدم يكون عامل تسجيل دخول
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit;
}
