<?php
session_start();
include '../includes/navbar.php';

// ตรวจสอบว่าผู้ใช้ล็อกอินหรือไม่
if (!isset($_SESSION['session_id']) || $_SESSION['role'] !== 'a') {
    header("Location:  ../homepage/aquarium.php");
    exit;
}

// เชื่อมต่อฐานข้อมูล
$conn = new mysqli("localhost", "root", "", "aquarium");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// รับ review_id ที่ส่งมาจากฟอร์ม
$review_id = isset($_POST['review_id']) ? intval($_POST['review_id']) : 0;

if ($review_id > 0) {
    // สร้างคำสั่ง SQL เพื่อลบรีวิวที่มี review_id ตรงกับที่ส่งมา
    $sql = "DELETE FROM review WHERE id = ?";
    
    // เตรียมคำสั่ง SQL
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $review_id);
    
    // ลบรีวิวจากฐานข้อมูล
    if ($stmt->execute()) {
        // ถ้าลบสำเร็จ, ให้กลับไปที่หน้าแอดมินจัดการรีวิว
        header("Location: admin_mng.php");
        exit;
    } else {
        // ถ้ามีข้อผิดพลาด
        die("Error deleting review: " . $stmt->error);
    }
    
    $stmt->close();
} else {
    // ถ้าไม่ได้รับ review_id ที่ถูกต้อง
    die("Invalid review ID.");
}

// ปิดการเชื่อมต่อฐานข้อมูล
$conn->close();
?>
