<?php
session_start();

// เชื่อมต่อฐานข้อมูล
$conn = new mysqli('mysql', 'user', 'password', 'aquarium');
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// รับค่าจากฟอร์ม
$ticket_id = $_POST['ticket_id'] ?? null;
$action = $_POST['action'] ?? null;

// ตรวจสอบข้อมูล
if (!$ticket_id || !$action) {
  die("Missing ticket ID or action");
}

// กำหนดสถานะใหม่ตาม action
$new_status = "";
$used_value = null; // สำหรับการใช้กับ 'used'

if ($action === "approve") {
  $new_status = "อนุมัติ";
  $used_value = "ยังไม่ได้ใช้งาน"; // ใช้ในกรณีที่อนุมัติ
} elseif ($action === "reject") {
  $new_status = "ไม่อนุมัติ";
} else {
  die("Invalid action");
}

// เริ่มทำการอัปเดตสถานะในฐานข้อมูล
if ($used_value) {
  // ถ้าเป็น "อนุมัติ" → เพิ่ม used = 'ยังไม่ได้ใช้งาน'
  $sql = "UPDATE ticket SET status = ?, used = ? WHERE id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ssi", $new_status, $used_value, $ticket_id);
} else {
  // ถ้าเป็น "ไม่อนุมัติ" → อัปเดตแค่ status และคืนจำนวนตั๋ว
  // ขั้นตอนนี้จะเพิ่มตั๋วกลับไปที่ event
  $sql_get_event = "SELECT event_id, ticket_quantity FROM ticket WHERE id = ?";
  $stmt_get_event = $conn->prepare($sql_get_event);
  $stmt_get_event->bind_param("i", $ticket_id);
  $stmt_get_event->execute();
  $stmt_get_event->bind_result($event_id, $ticket_quantity);
  $stmt_get_event->fetch();
  $stmt_get_event->close();

  if ($event_id && $ticket_quantity !== null) {
    // อัปเดต ticket_quantity ใน event ให้เท่ากับ ticket_quantity ใน ticket
    $sql_update_event = "UPDATE events SET ticket_quantity = ticket_quantity + ? WHERE event_id = ?";
    $stmt_update_event = $conn->prepare($sql_update_event);
    $stmt_update_event->bind_param("ii", $ticket_quantity, $event_id);
    $stmt_update_event->execute();
    $stmt_update_event->close();
  }

  // อัปเดตสถานะของตั๋ว
  $sql = "UPDATE ticket SET status = ? WHERE id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("si", $new_status, $ticket_id);
}

$success = $stmt->execute();

if ($success) {
  header("Location: ../admin/check_payment.php?msg=updated");
  exit;
} else {
  echo "เกิดข้อผิดพลาดในการอัปเดต: " . $conn->error;
}

$stmt->close();
$conn->close();
?>
