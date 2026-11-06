<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php if(isset($page_title)){ echo $page_title; } else { echo "Admin Dashboard"; } ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body {
            background: #f1f5f9;
            font-family: Arial, sans-serif;
        }
        .sidebar {
            min-height: 100vh;
            background: #0f172a;
            color: white;
            padding-top: 20px;
        }
        @media (max-width: 767.98px) {
            .sidebar {
                min-height: auto;
                margin-bottom: 15px;
            }
        }
        .sidebar a {
            color: #cbd5e1;
            text-decoration: none;
            display: block;
            padding: 12px 20px;
            margin-bottom: 5px;
        }
        .sidebar a:hover, .sidebar a.active {
            background: #1e293b;
            color: white;
        }
        .sidebar h4 {
            padding: 0 20px 20px;
            border-bottom: 1px solid #334155;
        }
        .stat-box {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            margin-bottom: 20px;
        }
        .stat-box h2 {
            margin: 0;
            color: #0ea5e9;
        }
        .content-box {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        .topbar {
            background: white;
            padding: 15px 25px;
            margin-bottom: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 sidebar">
            <h4><i class="bi bi-speedometer2"></i> لوحة التحكم</h4>
            <a href="index.php" class="<?php if(basename($_SERVER['PHP_SELF'])=='index.php') echo 'active'; ?>"><i class="bi bi-house"></i> لوحة التحكم</a>
            <a href="students.php" class="<?php if(basename($_SERVER['PHP_SELF'])=='students.php') echo 'active'; ?>"><i class="bi bi-people"></i> الطلاب</a>
            <a href="users.php" class="<?php if(basename($_SERVER['PHP_SELF'])=='users.php') echo 'active'; ?>"><i class="bi bi-person-gear"></i> المستخدمين</a>
            <a href="add_student.php" class="<?php if(basename($_SERVER['PHP_SELF'])=='add_student.php') echo 'active'; ?>"><i class="bi bi-person-plus"></i> إضافة طالب</a>
            <hr style="border-color:#334155;">
            <a href="../index.php"><i class="bi bi-box-arrow-left"></i> العودة إلى الموقع</a>
            <a href="../logout.php"><i class="bi bi-box-arrow-right"></i> تسجيل خروج</a>
        </div>
        <div class="col-md-10 p-4">
            <div class="topbar d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><?php if(isset($page_title)){ echo $page_title; } else { echo "Dashboard"; } ?></h5>
                <span>Welcome, <strong><?php echo $_SESSION['username']; ?></strong></span>
            </div>
