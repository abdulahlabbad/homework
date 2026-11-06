<?php
include "config.php";

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$error = "";
$success = "";

if (isset($_POST['register'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    if ($username === "" || $email === "" || $password === "") {
        $error = "Please fill in all required fields!";
    } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address!";
    } elseif (strlen($password) < 4) {
        $error = "Password must be at least 4 characters!";
    } elseif ($password != $confirm) {
        $error = "Passwords do not match!";
    } else {
        $check = mysqli_query($conn, "SELECT id FROM users WHERE username='$username' OR email='$email' LIMIT 1");
        if ($check && mysqli_num_rows($check) > 0) {
            $error = "Username or email already exists!";
        } else {
            // تم تحديث هذه الجزئية لاستخدام password_hash بدلاً من md5
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (username, email, password, role) VALUES ('$username', '$email', '$hashed_password', 'user')";
            if (mysqli_query($conn, $sql)) {
                $success = "Account created successfully! You can login now.";
            } else {
                $error = "Error creating account. Please try again.";
            }
        }
    }
}

$page_title = "Register - Student Registration System";
include "include/header.php";
?>

<!-- نفس كود الـ HTML القديم الخاص بك من هنا وحتى النهاية -->
            <div class="row justify-content-center">
                <div class="col-md-5 col-8 box-login bg-white shadow-lg p-4">
                    <h3 class="text-center">إنشاء حساب جديد</h3>

                    <?php if($error != "") { ?>
                        <div class="alert alert-danger alert-msg"><?php echo e($error); ?></div>
                    <?php } ?>
                    <?php if($success != "") { ?>
                        <div class="alert alert-success alert-msg"><?php echo e($success); ?></div>
                    <?php } ?>

                    <form action="register.php" method="post">
                        <label for="username" class="form-control bg-transparent border-0 fw-bold">اسم المستخدم</label>
                        <input type="text" class="form-control mb-3" id="username" name="username" placeholder="أدخل اسمك" required>
                        
                        <label for="email" class="form-control bg-transparent border-0 fw-bold">الريد الإلكتروني</label>
                        <input type="email" class="form-control mb-3" id="email" name="email" placeholder="أدخل ايميلك" required>

                        <label for="password" class="form-control bg-transparent border-0 fw-bold">كلمة المرور</label>
                        <input type="password" class="form-control mb-3" id="password" name="password" placeholder="أدخل كلمة المرور" required minlength="4">

                        <label for="confirm_password" class="form-control bg-transparent border-0 fw-bold">تأكيد كلمة المرور</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="تأكيد كلمة المرور" required minlength="4">

                        <button type="submit" name="register" class="btn btn-primary rounded-pill my-4 w-100 text-center">إنشاء حساب</button>

                        <p class="text-center">لديك حساب بالفعل؟ <a href="login.php">سجّل الدخول من هنا</a></p>
                    </form>
                </div>
            </div>

<?php include "include/footer.php"; ?>