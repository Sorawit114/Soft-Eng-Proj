<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: checkin.php");
    exit;
}

if (!isset($_SESSION['session_id']) || $_SESSION['role'] !== 'a') {
    die("Unauthorized");
}

$ticket_id = $_POST['ticket_id'] ?? null;
if (!$ticket_id) {
    die("Missing ticket ID");
}

$conn = new mysqli("localhost", "root", "", "aquarium");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// อัปเดตสถานะ
$sql = "UPDATE ticket SET used = 'ใช้งานแล้ว' WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $ticket_id);
$success = $stmt->execute();

if ($success) {
    header("Location: checkin.php?checked=1");
    exit;
} else {
    echo "Error updating ticket: " . $conn->error;
}

$stmt->close();
$conn->close();
?>
