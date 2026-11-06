<?php
include "../config.php";
$page_title = "Dashboard";
include "header.php";

$total_students = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM students"))['total'];
$total_users = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM users"))['total'];
$total_admins = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role='admin'"))['total'];

$latest = mysqli_query($conn, "SELECT * FROM students ORDER BY id ASC LIMIT 5");
?>

            <div class="row">
                <div class="col-md-4">
                    <div class="stat-box text-center">
                        <i class="bi bi-people fs-1 text-primary"></i>
                        <h2><?php echo (int)$total_students; ?></h2>
                        <p class="mb-0 text-muted">إجمالي الطلاب</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-box text-center">
                        <i class="bi bi-person fs-1 text-success"></i>
                        <h2><?php echo (int)$total_users; ?></h2>
                        <p class="mb-0 text-muted">إجمالي المستخدمين</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-box text-center">
                        <i class="bi bi-shield-lock fs-1 text-danger"></i>
                        <h2><?php echo (int)$total_admins; ?></h2>
                        <p class="mb-0 text-muted">المشرفون</p>
                    </div>
                </div>
            </div>

            <div class="content-box mt-3">
                <h5 class="mb-3">أحدث الطلاب المضافين</h5>
                <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>المعرف</th>
                            <th>الاسم</th>
                            <th>الايميل</th>
                            <th>الهاتف</th>
                            <th>العنوان</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($latest && mysqli_num_rows($latest) > 0) {
                            while($row = mysqli_fetch_assoc($latest)) { ?>
                        <tr>
                            <td><?php echo (int)$row['id']; ?></td>
                            <td><?php echo e($row['name']); ?></td>
                            <td><?php echo e($row['email']); ?></td>
                            <td><?php echo e($row['phone']); ?></td>
                            <td><?php echo e($row['address']); ?></td>
                        </tr>
                        <?php }
                        } else { ?>
                        <tr><td colspan="5" class="text-center">لا يوجد طلاب</td></tr>
                        <?php } ?>
                    </tbody>
                </table>
                </div>
                <a href="students.php" class="btn btn-primary btn-sm">إضافة كل الطلاب</a>
            </div>

<?php include "footer.php"; ?>
