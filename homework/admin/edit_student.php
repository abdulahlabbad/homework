<?php
include "../config.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: students.php");
    exit();
}

$id = (int)$_GET['id'];
$error = "";
$success = "";

$q = mysqli_query($conn, "SELECT * FROM students WHERE id=$id");
if (!$q || mysqli_num_rows($q) == 0) {
    header("Location: students.php");
    exit();
}
$student = mysqli_fetch_assoc($q);

if (isset($_POST['update'])) {
    $name = clean($conn, $_POST['name']);
    $email = clean($conn, $_POST['email']);
    $phone = clean($conn, $_POST['phone']);
    $address = clean($conn, $_POST['address']);
    $photo = $student['photo'];

    if ($name === "" || $email === "" || $phone === "" || $address === "") {
        $error = "Please fill in all required fields!";
    } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address!";
    } else {
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK && $_FILES['photo']['name'] != "") {
            $folder = "../uploads/";
            if (!is_dir($folder)) {
                mkdir($folder, 0755, true);
            }
            $photo_name = safe_upload_name($_FILES['photo']['name']);
            if ($photo_name === "") {
                $error = "Invalid photo type. Allowed: jpg, jpeg, png, gif, webp.";
            } elseif (move_uploaded_file($_FILES['photo']['tmp_name'], $folder . $photo_name)) {
                if ($student['photo'] != "" && file_exists($folder . $student['photo'])) {
                    unlink($folder . $student['photo']);
                }
                $photo = $photo_name;
            } else {
                $error = "Failed to upload photo.";
            }
        }

        if ($error === "") {
            $photo_sql = clean($conn, $photo);
            $sql = "UPDATE students SET name='$name', email='$email', phone='$phone', address='$address', photo='$photo_sql' WHERE id=$id";
            if (mysqli_query($conn, $sql)) {
                $success = "Student updated!";
                $q = mysqli_query($conn, "SELECT * FROM students WHERE id=$id");
                $student = mysqli_fetch_assoc($q);
            } else {
                $error = "Error updating student. Please try again.";
            }
        }
    }
}

$page_title = "Edit Student";
include "header.php";
?>

            <div class="content-box">
                <h5 class="mb-3">تعديل الطالب</h5>

                <?php if($error != "") { ?>
                    <div class="alert alert-danger"><?php echo e($error); ?></div>
                <?php } ?>
                <?php if($success != "") { ?>
                    <div class="alert alert-success"><?php echo e($success); ?></div>
                <?php } ?>

                <form method="post" enctype="multipart/form-data" class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-bold">الاسم</label>
                        <input type="text" name="name" class="form-control" value="<?php echo e($student['name']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">البريد الإلكتروني</label>
                        <input type="email" name="email" class="form-control" value="<?php echo e($student['email']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">الهاتف</label>
                        <input type="text" name="phone" class="form-control" value="<?php echo e($student['phone']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">العنوان</label>
                        <input type="text" name="address" class="form-control" value="<?php echo e($student['address']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">الصورة الشخصية</label>
                        <?php if($student['photo'] != "" && file_exists("../uploads/".$student['photo'])) { ?>
                            <div class="mb-2">
                                <img src="../uploads/<?php echo e($student['photo']); ?>" width="60" height="60" style="border-radius:50%; object-fit:cover;" alt="photo">
                            </div>
                        <?php } ?>
                        <input type="file" name="photo" class="form-control" accept="image/*">
                    </div>
                    <button type="submit" name="update" class="btn btn-primary">تعديل</button>
                    <a href="students.php" class="btn btn-secondary">عودة</a>
                </form>
            </div>

<?php include "footer.php"; ?>
