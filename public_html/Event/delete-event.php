<?php
// เชื่อมต่อกับฐานข้อมูล
$conn = new mysqli('mysql', 'user', 'password', 'aquarium');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// ตรวจสอบว่า event_id ถูกส่งมาหรือไม่
if (isset($_POST['event_id'])) {
    $event_id = $_POST['event_id'];
    
    // สร้างคำสั่ง SQL สำหรับการลบ event
    $sql = "DELETE FROM events WHERE event_id = ?";
    
    // เตรียมคำสั่ง SQL
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('i', $event_id); // 'i' หมายถึง integer
        
        // Execute คำสั่ง SQL
        if ($stmt->execute()) {
            // ลบสำเร็จแล้ว redirect กลับไปยังหน้าหลัก
            header("Location:../admin/editinfo_ticket.php"); // เปลี่ยนไปหน้าแสดงรายการอีเว้นต์
            exit();
        } else {
            echo "เกิดข้อผิดพลาดในการลบอีเว้นต์";
        }
        
        $stmt->close();
    }
}

$conn->close();
?>