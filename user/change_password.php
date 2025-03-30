<?php
session_start();

// เชื่อมต่อกับฐานข้อมูล
$servername = "localhost";
$username = "root";  // ใช้ชื่อผู้ใช้ฐานข้อมูลของคุณ
$password = "";      // ใช้รหัสผ่านฐานข้อมูลของคุณ
$dbname = "your_database";  // ใช้ชื่อฐานข้อมูลของคุณ

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ตรวจสอบการส่งข้อมูลจากฟอร์ม
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    $user_id = $_SESSION['session_id']; // รับ user_id จาก session

    // ตรวจสอบว่า new password กับ confirm password ตรงกัน
    if ($new_password !== $confirm_password) {
        $_SESSION['error'] = "New password and confirmation do not match.";
        header("Location: change_password.php");
        exit();
    }

    // ตรวจสอบว่า old password ถูกต้องหรือไม่
    $sql = "SELECT password FROM users WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($hashed_password);
            $stmt->fetch();
            
            // ตรวจสอบว่า old password ถูกต้องหรือไม่
            if (password_verify($old_password, $hashed_password)) {
                // แปลงรหัสผ่านใหม่ให้เป็นรูปแบบที่ปลอดภัย
                $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

                // อัพเดตฐานข้อมูลด้วยรหัสผ่านใหม่
                $update_sql = "UPDATE users SET password = ? WHERE id = ?";
                if ($update_stmt = $conn->prepare($update_sql)) {
                    $update_stmt->bind_param("si", $new_hashed_password, $user_id);
                    $update_stmt->execute();

                    if ($update_stmt->affected_rows > 0) {
                        $_SESSION['success'] = "Password updated successfully!";
                    } else {
                        $_SESSION['error'] = "Error: Could not update password.";
                    }
                    $update_stmt->close();
                }
            } else {
                $_SESSION['error'] = "Old password is incorrect.";
            }
        } else {
            $_SESSION['error'] = "User not found.";
        }
        $stmt->close();
    }

    // รีไดเร็กต์กลับไปที่หน้า change_password
    header("Location: ../user/pwd.php");
    exit();
}

$conn->close();
?>
