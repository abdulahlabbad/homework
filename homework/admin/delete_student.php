<?php
include "../config.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    $q = mysqli_query($conn, "SELECT * FROM students WHERE id=$id");
    $student = ($q && mysqli_num_rows($q) > 0) ? mysqli_fetch_assoc($q) : null;

    if ($student) {
        if ($student['photo'] != "" && file_exists("../uploads/".$student['photo'])) {
            unlink("../uploads/".$student['photo']);
        }
        mysqli_query($conn, "DELETE FROM students WHERE id=$id");
        header("Location: students.php?msg=" . urlencode("Student deleted successfully"));
        exit();
    }
}

header("Location: students.php?msg=" . urlencode("Student not found"));
exit();
?>
