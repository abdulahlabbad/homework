<?php
// add_student.php — فورم إضافة طالب + حفظه مع رفع الصورة
require 'auth_check.php';
require 'db.php';
require 'functions.php';

$errors = [];
$old    = ['name' => '', 'email' => '', 'phone' => '', 'address' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old = array_map('trim', array_intersect_key($_POST, $old)) + $old;

    // تحقق من السيرفر (حتى لو الـ JavaScript معطّل)
    $errors = validateStudent($old, $pdo);

    // رفع الصورة
    $upload = uploadProfilePicture($_FILES['profile_picture'] ?? []);
    if ($upload['error']) {
        $errors['profile_picture'] = $upload['error'];
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare(
            'INSERT INTO students (name, email, phone, address, profile_picture)
             VALUES (:name, :email, :phone, :address, :pic)'
        );
        $stmt->execute([
            ':name'    => $old['name'],
            ':email'   => $old['email'],
            ':phone'   => $old['phone'],
            ':address' => $old['address'],
            ':pic'     => $upload['path'],
        ]);
        header('Location: view_students.php?msg=added');
        exit;
    }

    // إذا فشل الإدخال والصورة انرفعت، منحذفها حتى ما تضل ملفات يتيمة
    if (!empty($upload['path']) && is_file(__DIR__ . '/' . $upload['path'])) {
        unlink(__DIR__ . '/' . $upload['path']);
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>إضافة طالب</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-dark bg-primary mb-4">
    <div class="container">
        <a class="navbar-brand" href="index.html">تسجيل الطلاب</a>
        <div>
            <a href="view_students.php" class="btn btn-outline-light btn-sm">عرض الطلاب</a>
            <a href="logout.php" class="btn btn-light btn-sm">تسجيل الخروج</a>
        </div>
    </div>
</nav>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h1 class="h4 mb-3">إضافة طالب</h1>

                    <form id="studentForm" action="add_student.php" method="post"
                          enctype="multipart/form-data" novalidate>

                        <div class="mb-3">
                            <label for="name" class="form-label">الاسم</label>
                            <input type="text" id="name" name="name" maxlength="100"
                                   class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>"
                                   value="<?= e($old['name']) ?>">
                            <div class="invalid-feedback" data-for="name"><?= e($errors['name'] ?? '') ?></div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">البريد الإلكتروني</label>
                            <input type="email" id="email" name="email" maxlength="100"
                                   class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>"
                                   value="<?= e($old['email']) ?>">
                            <div class="invalid-feedback" data-for="email"><?= e($errors['email'] ?? '') ?></div>
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">الهاتف</label>
                            <input type="text" id="phone" name="phone" maxlength="15"
                                   class="form-control <?= isset($errors['phone']) ? 'is-invalid' : '' ?>"
                                   value="<?= e($old['phone']) ?>">
                            <div class="invalid-feedback" data-for="phone"><?= e($errors['phone'] ?? '') ?></div>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">العنوان</label>
                            <textarea id="address" name="address" rows="2"
                                      class="form-control <?= isset($errors['address']) ? 'is-invalid' : '' ?>"><?= e($old['address']) ?></textarea>
                            <div class="invalid-feedback" data-for="address"><?= e($errors['address'] ?? '') ?></div>
                        </div>

                        <div class="mb-3">
                            <label for="profile_picture" class="form-label">صورة الملف الشخصي (اختياري)</label>
                            <input type="file" id="profile_picture" name="profile_picture" accept="image/*"
                                   class="form-control <?= isset($errors['profile_picture']) ? 'is-invalid' : '' ?>">
                            <div class="invalid-feedback" data-for="profile_picture"><?= e($errors['profile_picture'] ?? '') ?></div>
                            <img id="preview" class="img-thumbnail mt-2 d-none" style="max-height:120px" alt="Preview">
                        </div>

                        <button type="submit" class="btn btn-primary w-100" id="submitBtn">حفظ الطالب</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/validate.js"></script>
</body>
</html>
