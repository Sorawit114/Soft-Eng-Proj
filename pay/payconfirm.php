<?php
session_start();

// ตรวจสอบการล็อกอิน
if (!isset($_SESSION['session_id'])) {
    header("Location:  ../homepage/aquarium.php");
    die();
}

// รับค่า ticket id จาก GET
$ticket_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($ticket_id === 0) {
    die("Invalid ticket ID.");
}

// เชื่อมต่อฐานข้อมูล
$conn = new mysqli("localhost", "root", "", "aquarium");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ดึงข้อมูลตั๋วจากฐานข้อมูล
$sql = "SELECT * FROM ticket WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $ticket_id);
$stmt->execute();
$result = $stmt->get_result();
$ticket = $result->fetch_assoc();
$stmt->close();

if (!$ticket) {
    die("Ticket not found.");
}

// ตรวจสอบว่ามีการอัปโหลดสลิปหรือไม่
$slip_image = $ticket['slip_image'] ? "<img src='../uploads/" . htmlspecialchars($ticket['slip_image']) . "' alt='Slip Image' class='max-w-full max-h-80'>" : null;

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Payment Confirmation</title>
  <!-- Tailwind CSS via CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: { poppins: ['Poppins', 'sans-serif'] },
          colors: { mainBlue: '#040F53', hoverBlue: '#003080' },
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
  <h1 class="text-3xl md:text-5xl font-bold drop-shadow-lg text-white px-8 mt-4">
    ยืนยันการชำระเงิน
  </h1>

  <!-- ส่วนเนื้อหาหลัก -->
  <main class="max-w-5xl mx-auto px-4 py-8">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
      <!-- การ์ด 1: QR Code และข้อมูลการโอน -->
      <div class="bg-white text-black rounded p-6 flex flex-col items-center">
        <h2 class="text-xl font-semibold mb-4 text-mainBlue">ชำระผ่านธนาคาร</h2>
        <img src="image/qr.png" alt="QR Code" class="w-48 mb-4" />
        <div class="text-center">
          <p class="font-semibold">KBank</p>
          <p>เลขบัญชี: 123-4567890</p>
          <p>ชื่อบัญชี: Equarium Co., Ltd.</p>
        </div>
      </div>

      <!-- การ์ด 2: แบบฟอร์มอัพโหลดสลิปการโอน -->
      <div class="bg-white text-black rounded p-6">
        <h2 class="text-xl font-semibold mb-4 text-mainBlue">อัพโหลดสลิปการโอนเงิน</h2>
        <form action="endpay.php?id=<?php echo $ticket_id ?>" method="POST" enctype="multipart/form-data" class="space-y-4">
          <input type="hidden" name="ticket_id" value="<?php echo htmlspecialchars($ticket_id); ?>">

          <div>
            <label for="paymentSlip" class="block mb-1 font-semibold">สลิปการโอนเงิน</label>
            <input type="file" id="paymentSlip" name="slip" accept="image/*" class="w-full border border-gray-300 rounded px-3 py-2" <?php echo $slip_image ? 'disabled' : 'required'; ?> />
          </div>

          <!-- ข้อความเตือน -->
          <p class="text-sm text-gray-600">กรุณาชำระเงินภายใน 15 นาที</p>

          <!-- แสดงสลิปการโอนหากมี -->
          <?php if ($slip_image): ?>
            <div class="mb-4">
              <p class="text-sm text-gray-600">สลิปการโอนที่อัปโหลดแล้ว:</p>
              <?php echo $slip_image; ?>
            </div>
          <?php endif; ?>

          <!-- ปุ่ม submit -->
          <button type="submit" class="w-full bg-mainBlue text-white px-6 py-2 rounded-md hover:bg-hoverBlue transition-colors" <?php echo $slip_image ? 'disabled' : ''; ?>>
            ยืนยันการชำระเงิน
          </button>
        </form>
      </div>
    </div>
  </main>

  <!-- Footer -->
  <footer class="bg-mainBlue text-white py-4 text-center mt-8">
    <p>&copy; 2025 Equarium. All rights reserved.</p>
  </footer>
</body>
</html>
