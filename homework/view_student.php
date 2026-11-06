<?php
include "config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// إعدادات البحث والفرز والصفحات
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : "";
$sort = isset($_GET['sort']) ? $_GET['sort'] : "id";
$order = isset($_GET['order']) && $_GET['order'] == 'desc' ? 'DESC' : 'ASC';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 5; // عدد الطلاب في كل صفحة (يمكنك تغييره إلى 10)
$offset = ($page - 1) * $limit;

// بناء شرط البحث
$where_clause = "";
if ($search != "") {
    $where_clause = "WHERE name LIKE '%$search%' OR email LIKE '%$search%' OR phone LIKE '%$search%'";
}

// جلب العدد الإجمالي للطلاب لحساب الصفحات
$total_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM students $where_clause");
$total_rows = mysqli_fetch_assoc($total_result)['total'];
$total_pages = ceil($total_rows / $limit);

// جلب البيانات المطلوبة مع الفرز والتقسيم
$sql = "SELECT * FROM students $where_clause ORDER BY $sort $order LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $sql);

$page_title = "View Students - Student Registration System";
include "include/header.php";
?>

<!-- واجهة المستخدم -->
<div class="row justify-content-center">
    <div class="col-md-10 col-12 overflow-hidden box-login bg-white shadow-lg p-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
            <h3>قائمة الطلاب</h3>
            <a href="add_student.php" class="btn btn-primary rounded-pill my-2 px-4">إضافة طالب جديد</a>
        </div>

        <?php if(isset($_GET['msg'])) { ?>
            <div class="alert alert-success alert-msg"><?php echo e($_GET['msg']); ?></div>
        <?php } ?>

        <!-- شريط البحث والفرز -->
        <form method="GET" action="view_student.php" class="row mb-4 g-2">
            <div class="col-md-5">
                <input type="text" name="search" class="form-control" placeholder="ابحث بالاسم أو البريد الإلكتروني أو الهاتف..." value="<?php echo e($search); ?>">
            </div>
            <div class="col-md-4">
                <select name="sort" class="form-select">
                    <option value="id" <?php if($sort=='id') echo 'selected'; ?>>ترتيب بحسب المعرف</option>
                    <option value="name" <?php if($sort=='name') echo 'selected'; ?>>ترتيب بحسب الاسم</option>
                    <option value="email" <?php if($sort=='email') echo 'selected'; ?>>ترتيب ضبحسب البريد الإلكتروني</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100">بحث / ترتيب</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-secondary">
                    <tr>
                        <th>المعرف</th>
                        <th>الصورة الشخصي</th>
                        <th>الاسم</th>
                        <th>البريد الإلكتروني</th>
                        <th class="d-none d-md-table-cell">الهاتف</th>
                        <th class="d-none d-md-table-cell">العنوان</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result && mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                    <tr>
                        <td><?php echo (int)$row['id']; ?></td>
                        <td>
                            <?php if($row['photo'] != "" && file_exists("uploads/".$row['photo'])) { ?>
                                <img src="uploads/<?php echo e($row['photo']); ?>" class="student-photo" alt="photo">
                            <?php } else { ?>
                                <span class="badge bg-secondary">لا يوجد صورة</span>
                            <?php } ?>
                        </td>
                        <td><?php echo e($row['name']); ?></td>
                        <td><?php echo e($row['email']); ?></td>
                        <td class="d-none d-md-table-cell"><?php echo e($row['phone']); ?></td>
                        <td class="d-none d-md-table-cell"><?php echo e($row['address']); ?></td>
                        <td>
                            <a href="update_student.php?id=<?php echo (int)$row['id']; ?>" class="btn btn-sm btn-warning text-white fw-bold mb-2 mb-sm-0">تعديل</a>
                            <a href="delete_student.php?id=<?php echo (int)$row['id']; ?>" class="btn btn-sm btn-danger fw-bold">حذف</a>
                        </td>
                    </tr>
                    <?php
                        }
                    } else {
                    ?>
                    <tr><td colspan="7" class="text-center">لا يوجد طلاب</td></tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <!-- أزرار التنقل بين الصفحات (Pagination) -->
        <?php if ($total_pages > 1): ?>
        <nav>
            <ul class="pagination justify-content-center">
                <li class="page-item <?php if($page <= 1) echo 'disabled'; ?>">
                    <a class="page-link" href="?page=<?php echo $page-1; ?>&search=<?php echo e($search); ?>&sort=<?php echo e($sort); ?>">السابق</a>
                </li>
                <?php for($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?php if($page == $i) echo 'active'; ?>">
                        <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo e($search); ?>&sort=<?php echo e($sort); ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>
                <li class="page-item <?php if($page >= $total_pages) echo 'disabled'; ?>">
                    <a class="page-link" href="?page=<?php echo $page+1; ?>&search=<?php echo e($search); ?>&sort=<?php echo e($sort); ?>">التالي</a>
                </li>
            </ul>
        </nav>
        <?php endif; ?>

    </div>
</div>

<?php include "include/footer.php"; ?>