<?php
// functions.php — دوال مشتركة: التحقق من البيانات + رفع الصورة

/**
 * التحقق من حقول الطالب.
 * نفس الدالة بتستعملها صفحة AJAX وصفحة الحفظ (التحقق بالسيرفر ما بينلغى أبداً).
 * ترجع مصفوفة أخطاء: [اسم الحقل => رسالة الخطأ]
 */
function validateStudent(array $data, PDO $pdo, ?int $ignoreId = null): array
{
    $errors = [];

    $name    = trim($data['name']    ?? '');
    $email   = trim($data['email']   ?? '');
    $phone   = trim($data['phone']   ?? '');
    $address = trim($data['address'] ?? '');

    // 1) الاسم
    if ($name === '') {
        $errors['name'] = 'Name is required.';
    } elseif (mb_strlen($name) > 100) {
        $errors['name'] = 'Name must be at most 100 characters.';
    }

    // 2) الإيميل: فاضي؟ صيغته صح؟ مكرر؟
    if ($email === '') {
        $errors['email'] = 'Email is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Invalid email format.';
    } else {
        $sql = 'SELECT id FROM students WHERE email = ?';
        $params = [$email];
        if ($ignoreId !== null) {          // وقت التعديل منتجاهل الطالب نفسه
            $sql .= ' AND id <> ?';
            $params[] = $ignoreId;
        }
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        if ($stmt->fetch()) {
            $errors['email'] = 'This email is already registered.';
        }
    }

    // 3) الهاتف: أرقام فقط مع + اختيارية، بين 7 و 15 خانة
    if ($phone === '') {
        $errors['phone'] = 'Phone number is required.';
    } elseif (!preg_match('/^\+?[0-9]{7,15}$/', $phone)) {
        $errors['phone'] = 'Phone must be 7–15 digits (optional + at start).';
    }

    // 4) العنوان
    if ($address === '') {
        $errors['address'] = 'Address is required.';
    }

    return $errors;
}

/**
 * رفع صورة البروفايل باستخدام move_uploaded_file()
 * ترجع: ['path' => المسار أو null, 'error' => رسالة أو null]
 */
function uploadProfilePicture(array $file): array
{
    // الصورة اختيارية: إذا ما انرفع شي منرجع null بدون خطأ
    if (!isset($file['error']) || $file['error'] === UPLOAD_ERR_NO_FILE) {
        return ['path' => null, 'error' => null];
    }
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['path' => null, 'error' => 'Upload failed. Please try again.'];
    }

    // الحجم الأقصى 2MB
    $maxSize = 2 * 1024 * 1024;
    if ($file['size'] > $maxSize) {
        return ['path' => null, 'error' => 'Image must be smaller than 2MB.'];
    }

    // نتحقق من النوع الحقيقي للملف (مو من الامتداد اللي بيكتبه المستخدم)
    $allowed = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/gif'  => 'gif',
        'image/webp' => 'webp',
    ];
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime  = $finfo->file($file['tmp_name']);
    if (!isset($allowed[$mime])) {
        return ['path' => null, 'error' => 'Only JPG, PNG, GIF or WEBP images are allowed.'];
    }

    // اسم فريد للملف لمنع التكرار والكتابة فوق ملفات تانية
    $uploadDir = __DIR__ . '/uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    $newName = bin2hex(random_bytes(8)) . '.' . $allowed[$mime];

    if (!move_uploaded_file($file['tmp_name'], $uploadDir . $newName)) {
        return ['path' => null, 'error' => 'Could not save the uploaded image.'];
    }

    // منخزّن بقاعدة البيانات المسار النسبي فقط
    return ['path' => 'uploads/' . $newName, 'error' => null];
}

// اختصار لحماية المخرجات من XSS
function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}
