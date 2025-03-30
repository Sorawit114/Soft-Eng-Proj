<?php
session_start();

// ตรวจสอบ request method
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location:  ../home/aquarium.php");
    exit();
}

// ตรวจสอบการล็อกอิน
if (!isset($_SESSION['session_id'])) {
    header("Location:  ../home/aquarium.php");
    exit();
}

// สมมติว่าคุณเก็บ user id ใน session['user_id'] 
if (!isset($_SESSION['id'])) {
    die("User ID not found in session.");
}
$user_id = intval($_SESSION['id']);

// รับข้อมูลจากฟอร์ม
$event_id = isset($_POST['event_id']) ? intval($_POST['event_id']) : 0;
$ticket_date = isset($_POST['ticket_date']) ? trim($_POST['ticket_date']) : "";
$ticket_quantity = isset($_POST['ticket_quantity']) ? intval($_POST['ticket_quantity']) : 0;

// ตรวจสอบข้อมูลพื้นฐาน
if ($event_id === 0 || empty($ticket_date) || $ticket_quantity <= 0) {
    die("Invalid input data.");
}

// เชื่อมต่อฐานข้อมูล
$conn = new mysqli("localhost", "root", "", "aquarium");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ดึงราคาตั๋วจากตาราง events สำหรับ event_id ที่ระบุ
$sql = "SELECT price FROM events WHERE event_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();
$eventData = $result->fetch_assoc();
if (!$eventData) {
    die("Event not found.");
}
$price_per_ticket = floatval($eventData['price']);
$total_price = $price_per_ticket * $ticket_quantity;
$stmt->close();

// สร้างรหัสตั๋วโดยอิงจาก event_id (2 หลัก) และวันที่จอง (ticket_date: YYYYMMDD)
$ticket_code = sprintf("%02d", $event_id) . str_replace("-", "", $ticket_date);

// เตรียมคำสั่ง SQL เพื่อ insert ข้อมูลลงในตาราง ticket (เพิ่ม user_id)
$sqlInsert = "INSERT INTO ticket (user_id, event_id, ticket_code, ticket_date, ticket_quantity, total_price, status, used)
              VALUES (?, ?, ?, ?, ?, ?, 'รอตรวจสอบ')";
$stmtInsert = $conn->prepare($sqlInsert);
$stmtInsert->bind_param("iissid", $user_id, $event_id, $ticket_code, $ticket_date, $ticket_quantity, $total_price);

if ($stmtInsert->execute()) {
    // ดึง ticket id ที่เพิ่ง insert ได้
    $ticket_id = $stmtInsert->insert_id;
    // ส่ง event_id ไปยัง payment.php
    header("Location: ../pay/payment.php?event_id=" . $event_id);
    exit();
} else {
    die("Error inserting ticket: " . $stmtInsert->error);
}

$stmtInsert->close();
$conn->close();
?>