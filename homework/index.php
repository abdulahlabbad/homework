<?php
include "config.php";

$page_title = "Welcome - Student Registration System";
include "include/header.php";
?>

<div class="row justify-content-center">
    <div class="container mt-4">
        <h3 class="text-center mb-4">أسماء مجموعة المشروع</h3>
        <div class="col-10 mx-auto mb-5">
            <div class="table-responsive shadow-sm bg-white rounded">
                <table class="table table-hover align-middle mb-0 text-center">
                    <thead class="table-primary">
                        <tr>
                            <th>الرقم</th>
                            <th>اسم الطالب (English)</th>
                            <th>الاسم بالعربي</th>
                            <th>الصف</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>raneem_288986</td>
                            <td>رنيم عويدات</td>
                            <td>4</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>lara_276447</td>
                            <td>لارا القطامي</td>
                            <td>4</td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>sedraalmontaha_316284</td>
                            <td>سدره المنتهى شاهين</td>
                            <td>4</td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>shahed_306206</td>
                            <td>شهد بيان</td>
                            <td>4</td>
                        </tr>
                        <tr>
                            <td>5</td>
                            <td>abdulah_294258</td>
                            <td>عبدالله لباد</td>
                            <td>4</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    

</div>
    <div class="col-md-6 col-9 box-login bg-white shadow-lg p-5 text-center">
        <h3 class="fw-bold mb-3">مرحباََ بك في نظام إدارة الطلاب</h3>
        <p class="text-muted mb-4">إدارة سجلات الطلاب بكل سهولة وفعالية</p>

        <?php if (isset($_SESSION['user_id'])): ?>
            <div class="d-grid gap-2 col-8 mx-auto">
                <a href="view_student.php" class="btn btn-primary rounded-pill py-2">الانتقال لجدول الطلاب</a>
                <a href="logout.php" class="btn btn-outline-danger rounded-pill py-2">تسجيل الخروج</a>
            </div>
        <?php else: ?>
            <div class="d-flex justify-content-center gap-3">
                <a href="login.php" class="btn btn-primary rounded-pill px-4 py-2 w-50">تسجيل الدخول</a>
                <a href="register.php" class="btn btn-primary rounded-pill px-4 py-2 w-50">إنشاء حساب</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include "include/footer.php"; ?>