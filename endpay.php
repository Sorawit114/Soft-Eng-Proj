<?php
session_start();
include 'navbar.php';

// ตรวจสอบการล็อกอิน
if (!isset($_SESSION['session_id'])) {
    header("Location: aquarium.php");
    die();
}

// รับ ticket id จาก GET (ticket id ที่ได้จาก ticket_save.php)
$ticket_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($ticket_id === 0) {
    die("Invalid ticket ID.");
}

$conn = new mysqli("localhost", "root", "", "aquarium");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ดึงข้อมูล ticket จากตาราง ticket
$sql = "SELECT * FROM ticket WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $ticket_id);
$stmt->execute();
$result = $stmt->get_result();
$ticket = $result->fetch_assoc();
$stmt->close();
$conn->close();

if (!$ticket) {
    die("Ticket not found.");
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
  <header class="relative h-32 bg-center bg-cover bg-no-repeat" style="background-image: url('8929102.jpg');">
    <div class="absolute top-5 left-5 z-50 flex items-center">
      <a href="aquarium.php" class="text-white text-xl font-bold">Equarium</a>
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
        <img src="ticket1.png" alt="Ticket" class="w-92" />
        
        <!-- ส่วนแสดง Barcode และ Ticket Code อยู่ด้านขวาของรูป -->
        <div class="absolute inset-y-0 right-0 flex items-center">
            <!-- Barcode -->
            <img src="barcode.png" alt="Barcode" class="h-96" />

            <!-- Ticket Code หมุน 90 องศา -->
            <span class="text-black transform -rotate-90 text-md">
            <?php echo htmlspecialchars($ticket['ticket_code']); ?>
            </span>
        </div>
    </div>

    <div class="flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-6 justify-end">
        <a href="aquarium.php" 
           class="bg-white text-black px-6 py-2 rounded-md hover:bg-hoverBlue hover:text-white transition-colors">
          กลับสู่หน้าหลัก
        </a>
        <a href="myorder.php" 
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
