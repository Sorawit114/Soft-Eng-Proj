<?php
session_start();

// เชื่อมต่อฐานข้อมูล
$conn = new mysqli("localhost", "root", "", "aquarium");
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

// อัปเดตสถานะในฐานข้อมูล
if ($used_value) {
  // ถ้าเป็น "อนุมัติ" → เพิ่ม used = 'ยังไม่ได้ใช้งาน'
  $sql = "UPDATE ticket SET status = ?, used = ? WHERE id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ssi", $new_status, $used_value, $ticket_id);
} else {
  // ถ้าไม่ใช่อนุมัติ → อัปเดตแค่ status
  $sql = "UPDATE ticket SET status = ? WHERE id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("si", $new_status, $ticket_id);
}

// Execute statement and redirect based on success
$success = $stmt->execute();

if ($success) {
  header("Location: check_payment.php?msg=updated");
  exit;
} else {
  echo "เกิดข้อผิดพลาดในการอัปเดต: " . $conn->error;
}

$stmt->close();
$conn->close();
?>
