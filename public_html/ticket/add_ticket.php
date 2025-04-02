<?php
session_start();

// เชื่อมต่อฐานข้อมูล
$conn = new mysqli('mysql', 'user', 'password', 'aquarium');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// รับข้อมูลจากฟอร์ม
$event_id = $_POST['event_id'];
$event_name = $_POST['event_name'];
$event_location = $_POST['event_location'];
$event_province = $_POST['event_province'];  // รับข้อมูลจังหวัดจากฟอร์ม
$event_price = $_POST['event_price'];
$event_ticket_quantity = $_POST['event_ticket_quantity'];
$event_description = $_POST['event_description'];
$event_activity = $_POST['event_activity'];  // หมวดหมู่กิจกรรม

// ตรวจสอบว่า activity มีค่าหรือไม่
if (empty($event_activity)) {
    die("Activity cannot be empty.");
}

if (is_array($event_activity)) {
    // ใช้ implode เพื่อแปลงเป็น string ที่คั่นด้วย comma
    $event_activity = implode(",", $event_activity);
}

// ตรวจสอบว่า event_ticket_quantity ติดลบหรือไม่
if ($event_ticket_quantity < 0) {
    // ถ้าจำนวนตั๋วติดลบให้รีไดเรกต์ไปยังหน้าแก้ไขและแสดงข้อความเตือน
    header("Location: ../admin/edit_event.php?id=" . $event_id . "&error=negative_quantity");
    exit(); // หยุดการทำงานของสคริปต์
}

// ทำการทำความสะอาดข้อมูลที่ได้รับจากฟอร์ม
$event_name = htmlspecialchars($event_name, ENT_QUOTES, 'UTF-8');
$event_location = htmlspecialchars($event_location, ENT_QUOTES, 'UTF-8');
$event_description = htmlspecialchars($event_description, ENT_QUOTES, 'UTF-8');

// การอัปโหลดรูปภาพ
if (isset($_FILES['event_image']) && $_FILES['event_image']['error'] == 0) {
    // ระบุที่เก็บไฟล์
    $image_tmp = $_FILES['event_image']['tmp_name'];
    $image_name = basename($_FILES['event_image']['name']);
    $image_path = '../uploads/' . $image_name;

    // อัปโหลดไฟล์
    if (move_uploaded_file($image_tmp, $image_path)) {
        // อัปเดต URL ของรูปภาพในฐานข้อมูล
        $sql = "UPDATE events SET image = ? WHERE event_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $image_path, $event_id);
        $stmt->execute();
        $stmt->close();
    } else {
        echo "Error uploading image.";
        exit();
    }
}

// อัปเดตข้อมูลกิจกรรมในฐานข้อมูล
$sql = "UPDATE events SET name = ?, location = ?, province = ?, price = ?, ticket_quantity = ?, description = ?, activity = ? WHERE event_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssdsss", $event_name, $event_location, $event_province, $event_price, $event_ticket_quantity, $event_description, $event_activity, $event_id);

if ($stmt->execute()) {
    header("Location: ../admin/edit_event.php?id=" . $event_id);  // Redirect to event edit page
} else {
    // กรณีที่มีข้อผิดพลาด
    echo "Error updating event: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
