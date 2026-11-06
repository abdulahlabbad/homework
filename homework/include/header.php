<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title><?php if(isset($page_title)){ echo $page_title; } else { echo "Student Registration System"; } ?></title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap');
        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }
        body {
            background-color: #f8fafc;
            font-family: 'DM Sans';
        }
        .nav-link:hover ,
        .btn-primary:hover {
            background-color: #0f172a;
            color: white;
            transition-duration: 0.3s;
        }
        p {
            color: #334155;
        }
        .box-login {
            border-radius: 30px;
        }
        p a {
            text-decoration: none;
        }
        p a:hover {
            text-decoration: underline;
        }
        .alert-msg {
            border-radius: 15px;
        }
        .student-photo {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 50%;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row mb-5">
            <div class="col-12">
                <div class="navbar bg-primary navbar-expand-lg p-3">
                    <a href="index.php" class="navbar-brand fw-bold fs-4 me-sm-5 pe-5 ps-sm-2 text-white">
                        نظام تسجيل الطلاب
                    </a>

                    <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbar">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse justify-content-end pt-sm-0 pt-3 mt-5 mt-sm-0" id="navbar">
                        <ul class="navbar-nav">
                            <li class="nav-item"><a href="index.php" class="nav-link text-white rounded-pill fw-bold me-sm-3">الرئيسية</a></li>
                            <?php if(isset($_SESSION['user_id'])) { ?>
                                <li class="nav-item"><a href="add_student.php" class="nav-link text-white rounded-pill fw-bold me-sm-3">إضافة طالب</a></li>
                                <li class="nav-item"><a href="view_student.php" class="nav-link text-white rounded-pill fw-bold me-sm-3">عرض الطلاب</a></li>
                                <?php if(isset($_SESSION['role']) && $_SESSION['role'] == 'admin') { ?>
                                    <li class="nav-item"><a href="admin/index.php" class="nav-link text-white rounded-pill fw-bold me-sm-3">لوحة التحكم</a></li>
                                <?php } ?>
                                <li class="nav-item"><a href="logout.php" class="nav-link text-white rounded-pill fw-bold me-sm-3">تسجيل الخروج (<?php echo e($_SESSION['username']); ?>)</a></li>
                            <?php } else { ?>
                                <li class="nav-item"><a href="login.php" class="nav-link text-white rounded-pill fw-bold me-sm-3">تسجيل الدخول</a></li>
                                <li class="nav-item"><a href="register.php" class="nav-link text-white rounded-pill fw-bold me-sm-3">التسجيل</a></li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
