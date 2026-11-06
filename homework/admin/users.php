<?php
include "../config.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

$error = "";
$success = "";

if (isset($_GET['make_admin'])) {
    $uid = (int)$_GET['make_admin'];
    if ($uid > 0) {
        mysqli_query($conn, "UPDATE users SET role='admin' WHERE id=$uid");
        $success = "User is now admin!";
    }
}

if (isset($_GET['make_user'])) {
    $uid = (int)$_GET['make_user'];
    if ($uid > 0) {
        if ($uid != (int)$_SESSION['user_id']) {
            mysqli_query($conn, "UPDATE users SET role='user' WHERE id=$uid");
            $success = "User role changed to user!";
        } else {
            $error = "You cannot remove your own admin role!";
        }
    }
}

if (isset($_GET['delete'])) {
    $uid = (int)$_GET['delete'];
    if ($uid > 0) {
        if ($uid != (int)$_SESSION['user_id']) {
            mysqli_query($conn, "DELETE FROM users WHERE id=$uid");
            $success = "User deleted!";
        } else {
            $error = "You cannot delete your own account!";
        }
    }
}

$result = mysqli_query($conn, "SELECT * FROM users ORDER BY id ASC");

$page_title = "Manage Users";
include "header.php";
?>

            <div class="content-box">
                <h5 class="mb-3">جميع المستخدمين</h5>

                <?php if($error != "") { ?>
                    <div class="alert alert-danger"><?php echo e($error); ?></div>
                <?php } ?>
                <?php if($success != "") { ?>
                    <div class="alert alert-success"><?php echo e($success); ?></div>
                <?php } ?>

                <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>المعرف</th>
                            <th>الاسم</th>
                            <th>البريد الإلكتروني</th>
                            <th>القاعدة</th>
                            <th>تاريخ الإنشاء</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = mysqli_fetch_assoc($result)) { ?>
                        <tr>
                            <td><?php echo (int)$row['id']; ?></td>
                            <td><?php echo e($row['username']); ?></td>
                            <td><?php echo e($row['email']); ?></td>
                            <td>
                                <?php if($row['role'] == 'admin') { ?>
                                    <span class="badge bg-danger">مشرف</span>
                                <?php } else { ?>
                                    <span class="badge bg-primary">المستخدم</span>
                                <?php } ?>
                            </td>
                            <td><?php echo e($row['created_at']); ?></td>
                            <td>
                                <?php if($row['role'] == 'user') { ?>
                                    <a href="users.php?make_admin=<?php echo (int)$row['id']; ?>" class="btn btn-success btn-sm">تعيين كمسؤول</a>
                                <?php } else { ?>
                                    <a href="users.php?make_user=<?php echo (int)$row['id']; ?>" class="btn btn-secondary btn-sm">تعيين كمستخدم</a>
                                <?php } ?>
                                <a href="users.php?delete=<?php echo (int)$row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this user?');">Delete</a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
                </div>
            </div>

<?php include "footer.php"; ?>
