<?php
// view_students.php — عرض الطلاب مع: البحث (LIKE) + الترتيب + التقسيم لصفحات (Pagination)
require 'auth_check.php';
require 'db.php';
require 'functions.php';

// ===== 1) إعدادات التقسيم لصفحات =====
$perPage = 10;                                            // 10 طلاب بكل صفحة
$page    = max(1, (int) ($_GET['page'] ?? 1));            // رقم الصفحة الحالية (أقل شي 1)

// ===== 2) البحث =====
$search = trim($_GET['search'] ?? '');

// ===== 3) الترتيب: قائمة بيضاء (Whitelist) لأن أسماء الأعمدة ما بتنحط كـ parameters =====
$sortable = ['id', 'name', 'email', 'phone', 'created_at'];
$sort  = in_array($_GET['sort'] ?? '', $sortable, true) ? $_GET['sort'] : 'created_at';
$order = strtolower($_GET['order'] ?? '') === 'asc' ? 'ASC' : 'DESC';

// شرط WHERE للبحث بالاسم أو الإيميل أو الهاتف
$where  = '';
$params = [];
if ($search !== '') {
    $where = 'WHERE name LIKE :s1 OR email LIKE :s2 OR phone LIKE :s3';
    $like  = '%' . $search . '%';
    $params = [':s1' => $like, ':s2' => $like, ':s3' => $like];
}

// ===== 4) عدد النتائج الكلي (لحساب عدد الصفحات) =====
$countStmt = $pdo->prepare("SELECT COUNT(*) FROM students $where");
$countStmt->execute($params);
$total      = (int) $countStmt->fetchColumn();
$totalPages = max(1, (int) ceil($total / $perPage));
$page       = min($page, $totalPages);                    // إذا كتب رقم صفحة أكبر من الموجود
$offset     = ($page - 1) * $perPage;

// ===== 5) جلب طلاب الصفحة الحالية فقط باستخدام LIMIT و OFFSET =====
$sql  = "SELECT id, name, email, phone, address, profile_picture, created_at
         FROM students $where
         ORDER BY $sort $order
         LIMIT :limit OFFSET :offset";
$stmt = $pdo->prepare($sql);
foreach ($params as $k => $v) {
    $stmt->bindValue($k, $v, PDO::PARAM_STR);
}
$stmt->bindValue(':limit',  $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset,  PDO::PARAM_INT);
$stmt->execute();
$students = $stmt->fetchAll();

// دالة مساعدة: بتبني رابط بيحافظ على البحث والترتيب وبيغيّر بس اللي بدنا ياه
function pageUrl(array $changes): string
{
    $query = array_merge($_GET, $changes);
    return 'view_students.php?' . http_build_query($query);
}

// رابط ترتيب العمود: إذا كان العمود نفسه مرتّب، منقلب الاتجاه
function sortLink(string $col, string $label, string $sort, string $order): string
{
    $newOrder = ($sort === $col && $order === 'ASC') ? 'desc' : 'asc';
    $arrow    = $sort === $col ? ($order === 'ASC' ? ' ▲' : ' ▼') : '';
    return '<a class="text-white text-decoration-none" href="' .
        e(pageUrl(['sort' => $col, 'order' => $newOrder, 'page' => 1])) . '">' .
        e($label) . $arrow . '</a>';
}

$messages = [
    'added'   => 'Student added successfully.',
    'updated' => 'Student updated successfully.',
    'deleted' => 'Student deleted successfully.',
];
$msg = $messages[$_GET['msg'] ?? ''] ?? null;
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>عرض الطلاب</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .avatar { width: 44px; height: 44px; object-fit: cover; border-radius: 50%; }
        .avatar-empty { width: 44px; height: 44px; border-radius: 50%; background: #dee2e6;
                        display: inline-flex; align-items: center; justify-content: center; font-weight: 600; }
    </style>
</head>
<body class="bg-light">
<nav class="navbar navbar-dark bg-primary mb-4">
    <div class="container">
        <a class="navbar-brand" href="index.html">تسجيل الطلاب</a>
        <div>
            <a href="add_student.php" class="btn btn-outline-light btn-sm">إضافة طالب</a>
            <a href="logout.php" class="btn btn-light btn-sm">تسجيل الخروج</a>
        </div>
    </div>
</nav>

<div class="container">
    <?php if ($msg): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= e($msg) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- شريط البحث: GET حتى البحث يضل موجود بالرابط وبين الصفحات -->
    <form class="row g-2 mb-3" method="get" action="view_students.php">
        <div class="col-12 col-md-8">
            <input type="search" name="search" class="form-control"
                   placeholder="ابحث بالاسم أو البريد الإلكتروني أو الهاتف..." value="<?= e($search) ?>">
        </div>
        <input type="hidden" name="sort" value="<?= e($sort) ?>">
        <input type="hidden" name="order" value="<?= e(strtolower($order)) ?>">
        <div class="col-6 col-md-2 d-grid">
            <button class="btn btn-primary">بحث</button>
        </div>
        <div class="col-6 col-md-2 d-grid">
            <a href="view_students.php" class="btn btn-outline-secondary">Reset</a>
        </div>
    </form>

    <p class="text-muted small mb-2">
        <?= $total ?> student(s) found<?= $search !== '' ? ' for “' . e($search) . '”' : '' ?>
        — page <?= $page ?> of <?= $totalPages ?>
    </p>

    <!-- table-responsive: الجدول بيصير قابل للتمرير الأفقي عالموبايل -->
    <div class="table-responsive shadow-sm bg-white rounded">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-primary">
            <tr class="bg-primary">
                <th class="bg-primary">الصورة الشخصية</th>
                <th class="bg-primary"><?= sortLink('name', 'Name', $sort, $order) ?></th>
                <th class="bg-primary"><?= sortLink('email', 'Email', $sort, $order) ?></th>
                <th class="bg-primary"><?= sortLink('phone', 'Phone', $sort, $order) ?></th>
                <th class="bg-primary text-white d-none d-lg-table-cell">العنوان</th>
                <th class="bg-primary"><?= sortLink('created_at', 'Added', $sort, $order) ?></th>
                <th class="bg-primary text-white text-end">الإجراءات</th>
            </tr>
            </thead>
            <tbody>
            <?php if (!$students): ?>
                <tr><td colspan="7" class="text-center text-muted py-4">لا يوجد طلاب</td></tr>
            <?php endif; ?>
            <?php foreach ($students as $s): ?>
                <tr>
                    <td>
                        <?php if ($s['profile_picture']): ?>
                            <img src="<?= e($s['profile_picture']) ?>" class="avatar" alt="">
                        <?php else: ?>
                            <span class="avatar-empty"><?= e(mb_strtoupper(mb_substr($s['name'], 0, 1))) ?></span>
                        <?php endif; ?>
                    </td>
                    <td><?= e($s['name']) ?></td>
                    <td><?= e($s['email']) ?></td>
                    <td><?= e($s['phone']) ?></td>
                    <td class="d-none d-lg-table-cell"><?= e($s['address']) ?></td>
                    <td class="small text-muted"><?= e(date('Y-m-d', strtotime($s['created_at']))) ?></td>
                    <td class="text-end text-nowrap">
                        <a href="update_student.php?id=<?= (int) $s['id'] ?>" class="btn btn-sm btn-warning">تعديل</a>
                        <!-- الحذف بـ POST مع رسالة تأكيد قبل التنفيذ -->
                        <form action="delete_student.php" method="post" class="d-inline"
                              onsubmit="return confirm('Are you sure you want to delete this student?');">
                            <input type="hidden" name="id" value="<?= (int) $s['id'] ?>">
                            <button class="btn btn-sm btn-danger">حذف</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- أزرار التنقل: Previous / أرقام الصفحات / Next -->
    <?php if ($totalPages > 1): ?>
        <nav class="mt-3" aria-label="Students pages">
            <ul class="pagination justify-content-center flex-wrap">
                <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                    <a class="page-link" href="<?= e(pageUrl(['page' => $page - 1])) ?>">السابق</a>
                </li>
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                        <a class="page-link" href="<?= e(pageUrl(['page' => $i])) ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
                <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                    <a class="page-link" href="<?= e(pageUrl(['page' => $page + 1])) ?>">التالي</a>
                </li>
            </ul>
        </nav>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
