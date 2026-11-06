<?php
include "../config.php";
$page_title = "Manage Students";
include "header.php";

$result = mysqli_query($conn, "SELECT * FROM students ORDER BY id ASC");
?>

            <div class="content-box">
                <div class="d-flex justify-content-between mb-3 flex-wrap gap-2">
                    <h5>قائمة الطلاب</h5>
                    <a href="add_student.php" class="btn btn-primary btn-sm"><i class="bi bi-plus"></i> إضافة طالب</a>
                </div>

                <?php if(isset($_GET['msg'])) { ?>
                    <div class="alert alert-success"><?php echo e($_GET['msg']); ?></div>
                <?php } ?>

                <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>المعرف</th>
                            <th>صورة</th>
                            <th>الاسم</th>
                            <th>الايميل</th>
                            <th>الهاتف</th>
                            <th>العنوان</th>
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
                                <?php if($row['photo'] != "" && file_exists("../uploads/".$row['photo'])) { ?>
                                    <img src="../uploads/<?php echo e($row['photo']); ?>" width="40" height="40" style="border-radius:50%; object-fit:cover;" alt="photo">
                                <?php } else { ?>
                                    <span class="badge bg-secondary">لا يوجد صورة</span>
                                <?php } ?>
                            </td>
                            <td><?php echo e($row['name']); ?></td>
                            <td><?php echo e($row['email']); ?></td>
                            <td><?php echo e($row['phone']); ?></td>
                            <td><?php echo e($row['address']); ?></td>
                            <td>
                                <a href="edit_student.php?id=<?php echo (int)$row['id']; ?>" class="btn btn-warning btn-sm text-white">تعديل</a>
                                <a href="delete_student.php?id=<?php echo (int)$row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this student?');">حذف</a>
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
            </div>

<?php include "footer.php"; ?>
