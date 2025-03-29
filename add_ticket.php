<?php
session_start();

// เชื่อมต่อฐานข้อมูล
$conn = new mysqli("localhost", "root", "", "aquarium");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// รับข้อมูลจาก URL
$event_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$count = isset($_GET['count']) ? intval($_GET['count']) : 0;
$action = isset($_GET['action']) ? $_GET['action'] : 'add'; // 'add' หรือ 'subtract'

if ($event_id === 0 || $count === 0) {
    die("Invalid event ID or count.");
}

// ดึงข้อมูล event
$sql = "SELECT * FROM events WHERE event_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $event_id);
$stmt->execute();
$event = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$event) {
    die("Event not found.");
}

// ตรวจสอบการเพิ่มหรือลดจำนวนตั๋ว
if ($action === 'add') {
    // เพิ่มจำนวนตั๋ว
    $new_quantity = $event['ticket_quantity'] + $count;
} elseif ($action === 'subtract') {
    // ลดจำนวนตั๋ว
    $new_quantity = $event['ticket_quantity'] - $count;
    if ($new_quantity < 0) {
        die("Cannot subtract more tickets than available.");
    }
} else {
    die("Invalid action.");
}

// อัปเดตจำนวนตั๋ว
$sqlUpdate = "UPDATE events SET ticket_quantity = ? WHERE event_id = ?";
$stmtUpdate = $conn->prepare($sqlUpdate);
$stmtUpdate->bind_param("ii", $new_quantity, $event_id);
$stmtUpdate->execute();

if ($stmtUpdate->affected_rows > 0) {
    // Redirect back to event page after update
    header("Location: edit_event.php?id=" . $event_id);
    exit();
} else {
    echo "Error updating ticket quantity.";
}

$stmtUpdate->close();
$conn->close();
?>
