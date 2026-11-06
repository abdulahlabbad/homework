<?php
include "config.php";

if (isset($_SESSION['user_id'])) {
    if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {
        header("Location: view_student.php");
    } else {
        header("Location: view_student.php");
    }
    exit();
}

$error = "";

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    // جلب المستخدم بناءً على اسم المستخدم أو الإيميل فقط
    $sql = "SELECT * FROM users WHERE username='$username' OR email='$username' LIMIT 1";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        
        // التحقق من كلمة المرور باستخدام password_verify بدلاً من المقارنة المباشرة
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = $row['role'];

            if ($row['role'] == 'admin') {
                header("Location: admin/index.php");
            } else {
                header("Location: view_student.php");
            }
            exit();
        } else {
            $error = "Wrong username or password!";
        }
    } else {
        $error = "Wrong username or password!";
    }
}

$page_title = "Login - Student Registration System";
include "include/header.php";
?>

<!-- نفس كود الـ HTML القديم الخاص بك من هنا وحتى النهاية -->
            <div class="row justify-content-center">
                <div class="col-md-5 col-8 box-login bg-white shadow-lg p-4">
                    <h3 class="text-center">تسجيل الدخول إالى حسابك</h3>

                    <?php if($error != "") { ?>
                        <div class="alert alert-danger alert-msg"><?php echo e($error); ?></div>
                    <?php } ?>

                    <form action="login.php" method="post">
                        <label for="username" class="form-control bg-transparent border-0 fw-bold">اسم المستخدم أو البريد الإلكتروني</label>
                        <input type="text" class="form-control mb-3" id="username" name="username" placeholder="Enter your name or email" required>

                        <label for="password" class="form-control bg-transparent border-0 fw-bold">كلمة المرور</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>

                        <button type="submit" name="login" class="btn btn-primary rounded-pill my-4 w-100 text-center">تسجيل الدخول</button>

                        <p class="text-center">ليس لديك حساب؟ <a href="register.php">أنشئ حساباََ جديداََ</a></p>
                    </form>
                </div>
            </div>

<?php include "include/footer.php"; ?>