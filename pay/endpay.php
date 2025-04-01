<?php
session_start();
include '../includes/navbar.php';

if (!isset($_SESSION['session_id'])) {
    die("กรุณาเข้าสู่ระบบก่อน");
}

// รับ ticket_id จาก GET หรือ POST
$ticket_id = $_GET['id'] ?? ($_POST['ticket_id'] ?? 0);
$ticket_id = intval($ticket_id);
if ($ticket_id === 0) {
    die("ไม่พบรหัสตั๋ว");
}

// ดึงข้อมูลตั๋วจากฐานข้อมูล
$conn = new mysqli("localhost", "root", "", "aquarium");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$stmt = $conn->prepare("SELECT * FROM ticket WHERE id = ?");
$stmt->bind_param("i", $ticket_id);
$stmt->execute();
$result = $stmt->get_result();
$ticket = $result->fetch_assoc();
$stmt->close();

if (!$ticket) {
    die("ไม่พบข้อมูลตั๋ว");
}

// หากมีการส่งฟอร์มอัปโหลด
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['slip'])) {
    $slip_tmp = $_FILES['slip']['tmp_name'];
    $slip_name = 'slip_' . uniqid() . '.' . pathinfo($_FILES['slip']['name'], PATHINFO_EXTENSION);

    // ย้ายไฟล์ไปยังโฟลเดอร์ uploads/
    if (!move_uploaded_file($slip_tmp, "../uploads/$slip_name")) {
        $uploadMessage = "เกิดข้อผิดพลาดในการอัปโหลดสลิป";
    } else {
        // บันทึกชื่อไฟล์ลงฐานข้อมูล
        $conn = new mysqli("localhost", "root", "", "aquarium");
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $update = $conn->prepare("UPDATE ticket SET slip_image = ? WHERE id = ?");
        $update->bind_param("si", $slip_name, $ticket_id);
        $update->execute();
        $update->close();
        $conn->close();

        $uploadMessage = "อัปโหลดสลิปเรียบร้อยแล้ว ✅";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>End Payment</title>
  <!-- Tailwind CSS via CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Google Fonts: Poppins -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            poppins: ["Poppins", "sans-serif"],
          },
          colors: {
            mainBlue: "#040F53",
            hoverBlue: "#003080",
          },
        },
      },
    }
  </script>
</head>
<body class="font-poppins min-h-screen bg-mainBlue text-white">
  <!-- Header (รูปพื้นหลัง) -->
  <header class="relative h-32 bg-center bg-cover bg-no-repeat" style="background-image: url('../image/8929102.jpg');">
    <div class="absolute top-5 left-5 z-50 flex items-center">
      <a href="../homepage/aquarium.php" class="text-white text-3xl font-bold">Equarium</a>
    </div>
  </header>

  <!-- หัวข้อหลัก -->
  <h1 class="text-3xl md:text-5xl font-bold drop-shadow-lg text-white px-8 mt-4 text-center">
    จองตั๋วสำเร็จ
  </h1>

  <!-- ส่วนเนื้อหาหลัก -->
  <main class="max-w-5xl mx-auto px-4 py-8">
    
    <!-- การ์ดแสดงตั๋ว -->
    <div class="relative inline-block">
        <!-- รูปตั๋ว -->
        <img src="../image/ticket1.png" alt="Ticket" class="w-92" />
        

    </div>

    <div class="flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-6 justify-end">
        <a href=" ../homepage/aquarium.php" 
           class="bg-white text-black px-6 py-2 rounded-md hover:bg-hoverBlue hover:text-white transition-colors">
          กลับสู่หน้าหลัก
        </a>
        <a href="../user/myorder.php" 
           class="bg-white text-black px-6 py-2 rounded-md hover:bg-hoverBlue hover:text-white transition-colors">
          ดูตั๋ว
        </a>
      </div>
  </main>

  <!-- Footer -->
  <footer class="bg-mainBlue text-white py-4 text-center mt-8">
    <p>&copy; 2025 Equarium. All rights reserved.</p>
  </footer>
</body>
</html>
