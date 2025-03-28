<?php
session_start();
$conn = new mysqli("localhost", "root", "", "aquarium");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$action = $_POST['action'] ?? '';
$user_id = intval($_POST['user_id'] ?? 0);

if (!$user_id || !$action) {
    die("ข้อมูลไม่ถูกต้อง");
}

// เริ่มการจัดการตาม action ที่ได้รับ
if ($action === 'update') {
    $new_role = $_POST['new_role'] ?? 'm'; // Default เป็น member
    $sql = "UPDATE users SET position = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }
    $stmt->bind_param("si", $new_role, $user_id);

    // Execute statement
    if ($stmt->execute()) {
        // Successfully updated role
        $_SESSION['message'] = 'Role updated successfully';
    } else {
        die("Error executing statement: " . $stmt->error);
    }
} elseif ($action === 'ban') {
    $banned = 'b';
    $sql = "UPDATE users SET position = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }
    $stmt->bind_param("si", $banned, $user_id);

    // Execute statement
    if ($stmt->execute()) {
        // Successfully banned user
        $_SESSION['message'] = 'User banned successfully';
    } else {
        die("Error executing statement: " . $stmt->error);
    }
} elseif ($action === 'unban') {
    // กลับสถานะผู้ใช้เป็น member
    $default = 'm';
    $sql = "UPDATE users SET position = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }
    $stmt->bind_param("si", $default, $user_id);

    // Execute statement
    if ($stmt->execute()) {
        // Successfully unbanned user
        $_SESSION['message'] = 'User unbanned successfully';
    } else {
        die("Error executing statement: " . $stmt->error);
    }
}

$stmt->close();
$conn->close();

header("Location: admin_mng.php");
exit();
?>
