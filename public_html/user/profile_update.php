<?php
session_start();

// ตรวจสอบว่าผู้ใช้ล็อกอินอยู่หรือไม่
if (!isset($_SESSION['session_id'])) {
    $_SESSION['error'] = "User not logged in.";
    header("Location: ../login/login.php");
    exit();
}

// ตรวจสอบว่ามีการส่งข้อมูลผ่าน POST หรือไม่
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // รับข้อมูลจากฟอร์ม
    $first_name = trim($_POST['first_name']);
    $last_name  = trim($_POST['last_name']);
    $gender     = $_POST['gender']; // ค่าที่ส่งมาอาจเป็น "not_specified", "male", หรือ "female"
    $dob        = $_POST['dob'];    // รูปแบบ YYYY-MM-DD

    // ตรวจสอบข้อมูลขั้นต่ำ (ตัวอย่าง: ต้องไม่ว่างสำหรับ first_name และ last_name)
    if (empty($first_name) || empty($last_name)) {
        $_SESSION['error'] = "Please fill in all required fields.";
        header("Location: user.php");
        exit();
    }

    // ตั้งค่าการเชื่อมต่อฐานข้อมูล
    $servername = "localhost";
    $dbUsername = "root";
    $dbPassword = "";
    $dbname     = "aquarium"; // ชื่อฐานข้อมูล

    $conn = new mysqli($servername, $dbUsername, $dbPassword, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // เตรียมคำสั่ง UPDATE สำหรับอัปเดตข้อมูลผู้ใช้ที่ล็อกอินอยู่
    $sql = "UPDATE users
            SET first_name = ?,
                last_name  = ?,
                gender     = ?,
                dob        = ?
            WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        $_SESSION['error'] = "Database error: " . $conn->error;
        header("Location: user.php");
        exit();
    }

    // ใช้ $_SESSION['id'] เพื่อระบุผู้ใช้ที่ล็อกอินอยู่
    $stmt->bind_param("ssssi", $first_name, $last_name, $gender, $dob, $_SESSION['id']);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Profile updated successfully!";
    } else {
        $_SESSION['error'] = "Failed to update profile. Please try again.";
    }

    $stmt->close();
    $conn->close();

    // เปลี่ยนเส้นทางกลับไปที่หน้า user.php เพื่อแสดงผล
    header("Location: mngpro.php");
    exit();
} else {
    header("Location: mngpro.php");
    exit();
}
?>
