<?php
session_start();
include 'navbar.php';

// หากยังไม่ล็อกอิน ให้ redirect ไปหน้า aquarium.php
if (!isset($_SESSION['session_id'])) {
  header("Location: aquarium.php");
  die();
}

$conn = new mysqli("localhost", "root", "", "aquarium");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// รับค่า id จาก GET (event id)
$event_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($event_id === 0) {
  die("Invalid event ID.");
}

// ดึงข้อมูล event ตาม event_id
$sql = "SELECT * FROM events WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();
$event = $result->fetch_assoc();
$stmt->close();

if (!$event) {
  die("Event not found.");
}

// ดึงข้อมูล ticket สำหรับ event นี้ (ล่าสุด)
$sqlTicket = "SELECT * FROM ticket WHERE event_id = ? ORDER BY created_at DESC LIMIT 1";
$stmtTicket = $conn->prepare($sqlTicket);
$stmtTicket->bind_param("i", $event_id);
$stmtTicket->execute();
$resultTicket = $stmtTicket->get_result();
$ticketRecord = $resultTicket->fetch_assoc();
$stmtTicket->close();

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Payment Summary</title>
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
  <!-- Header ที่เป็นรูปภาพ -->
  <header class="relative h-32 bg-center bg-cover bg-no-repeat" style="background-image: url('image/8929102.jpg');">
    <!-- ลิงก์ Aquarium มุมบนซ้าย -->
    <div class="absolute top-5 left-5 z-50 flex items-center">
      <a href="aquarium.php" class="text-white text-xl font-bold">Equarium</a>
    </div>
  </header>

  <!-- หัวข้อหลัก -->
  <h1 class="text-3xl md:text-5xl font-bold drop-shadow-lg text-white px-8 mt-4">
    สรุปการชำระเงิน
  </h1>

  <!-- ส่วนเนื้อหาหลัก -->
  <main class="max-w-5xl mx-auto px-4 py-8">
    <!-- รูปตั๋วด้านบน -->
    <div class="relative inline-block mb-8">
      <img src="image/ticket1.png" alt="Ticket" class="w-92" />
      <!-- ข้อความหรือข้อมูลบนรูปตั๋ว -->
      <div class="absolute inset-0 flex flex-col items-center justify-center">
        <h2 class="text-2xl font-bold text-black drop-shadow-lg">
          <?php echo htmlspecialchars($event['name']); ?>
        </h2>
      </div>
    </div>

    <!-- 3 การ์ด: ข้อตกลง / สรุปราคารวม / ปุ่มยืนยัน -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <!-- การ์ด 1: ข้อตกลง -->
      <div class="bg-white text-black rounded p-4">
        <h3 class="text-lg font-semibold text-mainBlue mb-2">ข้อตกลง</h3>
        <p class="text-sm leading-relaxed">
          1. เมื่อซื้อบัตรแล้ว ไม่สามารถขอคืนเงินได้<br/>
          2. กรุณาแสดงบัตรเข้างานหรือหลักฐานการซื้อในวันเข้าชม<br/>
          3. โปรดรักษาความเรียบร้อยและไม่ทำลายสัตว์น้ำหรือสิ่งแวดล้อม<br/>
          4. ข้อกำหนดและเงื่อนไขอื่น ๆ ตามที่ผู้ให้บริการกำหนด<br/>
        </p>
      </div>

      <!-- การ์ด 2: สรุปราคารวม จำนวนตั๋ว -->
      <div class="bg-white text-black rounded p-4">
        <h3 class="text-lg font-semibold text-mainBlue mb-2">สรุปราคารวม</h3>
        <?php if ($ticketRecord): ?>
          <p>จำนวนตั๋ว: <?php echo htmlspecialchars($ticketRecord['ticket_quantity']); ?></p>
          <p>ราคาต่อใบ: ฿<?php echo htmlspecialchars($event['price']); ?></p>
          <p>ราคารวม: ฿<?php echo htmlspecialchars($ticketRecord['total_price']); ?></p>
          <p>รหัสตั๋ว: <?php echo htmlspecialchars($ticketRecord['ticket_code']); ?></p>
        <?php else: ?>
          <p>No ticket data available.</p>
        <?php endif; ?>
      </div>

      <!-- การ์ด 3: ปุ่มยืนยัน -->
      <div class="bg-white text-black rounded p-4 flex flex-col justify-center items-center">
        <h3 class="text-lg font-semibold text-mainBlue mb-2">ชำระเงิน</h3>
        <p class="mb-4 text-sm text-gray-600">
          กดปุ่มยืนยันเพื่อดำเนินการชำระเงิน
        </p>
        <!-- ส่ง ticket id (หรือ event id หากไม่มี ticketRecord) ไปที่ payconfirm.php -->
        <a href="payconfirm.php?id=<?php echo $ticketRecord ? $ticketRecord['id'] : $event_id; ?>"
           class="bg-mainBlue text-white px-6 py-2 rounded hover:bg-hoverBlue transition-colors">
          ยืนยัน
        </a>
      </div>
    </div>
  </main>

  <!-- Footer -->
  <footer class="bg-mainBlue text-white py-4 text-center mt-8">
    <p>&copy; 2025 Equarium. All rights reserved.</p>
  </footer>
</body>
</html>
