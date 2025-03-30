<?php
session_start();
$user = $_POST['user'];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // รับค่าอีเมลและรหัสผ่านจาก POST
    $email = trim($_POST['email']);
    $pwd = $_POST['password'];

    // ตรวจสอบว่าผู้ใช้กรอกข้อมูลครบหรือไม่
    if (empty($email) || empty($pwd)) {
        $_SESSION['error'] = "Please fill in all fields.";
        header("Location: login.php");
        exit();
    }

    // ตั้งค่าการเชื่อมต่อฐานข้อมูล
    $servername = "localhost";
    $dbUsername = "root";
    $dbPassword = "";
    $dbname = "aquarium"; // เปลี่ยนเป็นชื่อฐานข้อมูลที่คุณใช้

    // สร้างการเชื่อมต่อ
    $conn = new mysqli($servername, $dbUsername, $dbPassword, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // ค้นหาผู้ใช้ที่มีอีเมลตรงกัน
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        $_SESSION['error'] = "Database error.";
        header("Location: login.php");
        exit();
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // ถ้าพบผู้ใช้
    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();

        // ตรวจสอบรหัสผ่าน (สมมุติว่าในฐานข้อมูลรหัสผ่านถูกเข้ารหัสไว้แล้ว)
        if (password_verify($pwd, $row['password'])) {
            // ตรวจสอบ role หากเป็น 'b' (บัญชีถูกระงับ)
            if ($row['position'] === 'b') {
                $_SESSION['error'] = "บัญชีของคุณถูกระงับ";
                header("Location: login.php");
                exit();
            }

            // บันทึกข้อมูลลงใน session สำหรับบัญชีที่ไม่ได้ถูกระงับ
            $_SESSION['session_id'] = session_id();
            $_SESSION['id'] = $row['id'];
            $_SESSION['user'] = $row['username'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['role'] = $row['position'];

            // เปลี่ยนเส้นทางไปยัง aquarium.php
            header("Location: ../home/aquarium.php");
            exit();
        } else {
            $_SESSION['error'] = "Invalid email or password.";
            header("Location: login.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "No user";
        header("Location: login.php");
        exit();
    }
    $stmt->close();
    $conn->close();
} else {
    header("Location: login.php");
    exit();
}
?>
