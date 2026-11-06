<?php
include "../config.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

$error = "";
$success = "";

if (isset($_POST['add'])) {
    $name = clean($conn, $_POST['name']);
    $email = clean($conn, $_POST['email']);
    $phone = clean($conn, $_POST['phone']);
    $address = clean($conn, $_POST['address']);
    $photo = "";

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
                $photo = $photo_name;
            } else {
                $error = "Failed to upload photo.";
            }
        }

        if ($error === "") {
            $sql = "INSERT INTO students (name, email, phone, address, photo) VALUES ('$name', '$email', '$phone', '$address', '$photo')";
            if (mysqli_query($conn, $sql)) {
                $success = "Student added successfully!";
            } else {
                $error = "Error saving student. Please try again.";
            }
        }
    }
}

$page_title = "Add Student";
include "header.php";
?>

            <div class="content-box">
                <h5 class="mb-3">إضافة طالب جديد</h5>

                <?php if($error != "") { ?>
                    <div class="alert alert-danger"><?php echo e($error); ?></div>
                <?php } ?>
                <?php if($success != "") { ?>
                    <div class="alert alert-success"><?php echo e($success); ?></div>
                <?php } ?>

                <form method="post" enctype="multipart/form-data" class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-bold">اسم الطالب</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">البريد الإلكتروني</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">رقم الهاتف</label>
                        <input type="text" name="phone" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">العنوان</label>
                        <input type="text" name="address" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">الصورة الشخصية</label>
                        <input type="file" name="photo" class="form-control" accept="image/*">
                    </div>
                    <button type="submit" name="add" class="btn btn-primary">إضافة طالب</button>
                    <a href="students.php" class="btn btn-secondary">عودة</a>
                </form>
            </div>

<?php include "footer.php"; ?>
