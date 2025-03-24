<?php
session_start();

// ตั้งค่าการเชื่อมต่อฐานข้อมูล
$servername  = "localhost";
$dbUsername  = "root";
$dbPassword  = "";
$dbname      = "aquarium"; // ปรับตามชื่อฐานข้อมูลที่คุณใช้

// สร้างการเชื่อมต่อ
$conn = new mysqli($servername, $dbUsername, $dbPassword, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // รับข้อมูลจากฟอร์ม
    $user      = trim($_POST['user']);
    $email     = trim($_POST['email']);
    $pwd       = $_POST['password'];
    $firstname = trim($_POST['firstname']);
    $lastname  = trim($_POST['lastname']);
    $gender    = trim($_POST['gender']);
    $birthdate = trim($_POST['birthdate']);

    // ตรวจสอบให้แน่ใจว่าฟิลด์ทั้งหมดไม่ว่างเปล่า
    if (empty($user) || empty($email) || empty($pwd) || empty($firstname) || empty($lastname) || empty($gender) || empty($birthdate)) {
        $error = "Please fill in all fields.";
    } else {
        // ตรวจสอบว่ามี email ซ้ำกันหรือไม่
        $check_sql = "SELECT * FROM users WHERE email = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("s", $email);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            $error = "This email is already registered. Please use a different email.";
        } else {
            // เข้ารหัสรหัสผ่าน
            $hashed_password = password_hash($pwd, PASSWORD_DEFAULT);
            // ทุกคนที่สมัครเป็นสมาชิกจะได้ตำแหน่ง "m" (member)
            $position = "m";

            // เตรียมคำสั่ง SQL เพื่อ insert ข้อมูลลงในตาราง users
            $sql = "INSERT INTO users (username, email, password, position, firstname, lastname, gender, birthdate)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            if ($stmt) {
                // กำหนด type เป็น string ทั้งหมด (8 ตัวแปร)
                $stmt->bind_param("ssssssss", $user, $email, $hashed_password, $position, $firstname, $lastname, $gender, $birthdate);
                if ($stmt->execute()) {
                    // หากสมัครสำเร็จ ให้เปลี่ยนเส้นทางไปที่ login.php
                    $_SESSION['user']  = $user;
                    $_SESSION['email'] = $email;
                    $_SESSION['role']  = $position;
                    header("Location: login.php");
                    exit();
                } else {
                    $error = "Error: " . $stmt->error;
                }
                $stmt->close();
            } else {
                $error = "Error preparing statement: " . $conn->error;
            }
        }
        $check_stmt->close();
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registration Result</title>
  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Google Fonts: Poppins -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            poppins: ['Poppins', 'sans-serif'],
          },
          colors: {
            mainBlue: '#040F53',
            hoverBlue: '#003080'
          }
        }
      }
    };
  </script>
</head>
<body class="flex items-center justify-center min-h-screen bg-fixed bg-center bg-cover bg-no-repeat" style="background-image: url('image/8929102.jpg'); font-family: 'Poppins', sans-serif;">
  <div class="bg-white text-gray-800 rounded-xl p-6 max-w-sm shadow-lg text-center">
    <?php 
      if ($error != "") {
          echo "<p class='mb-4 text-red-500'>$error</p>";
          echo "<p><a href='register.php' class='text-mainBlue font-semibold hover:underline'>Back to Register</a></p>";
      }
    ?>
  </div>
</body>
</html>
