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

// รับค่า id จาก GET
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id === 0) {
  die("Invalid event ID.");
}

// ดึงข้อมูล event ตาม id
$sql = "SELECT * FROM events WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$event = $result->fetch_assoc();
$stmt->close();
$conn->close();

if (!$event) {
  die("Event not found.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Buy Tickets</title>
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
    ซื้อบัตรเข้าชม
  </h1>

  <!-- ส่วนเนื้อหาหลัก (Form) -->
  <main class="max-w-4xl mx-auto px-4 py-8">
    <form id="ticketForm" method="POST" action="ticket_save.php">
      <!-- ส่งค่า event id (ใช้ hidden input) -->
      <input type="hidden" name="event_id" value="<?php echo $id; ?>" />
      <input type="hidden" name="user_id" value="<?php echo isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 0; ?>" />

      <!-- รูปตั๋ว -->
      <div class="relative inline-block mb-8">
        <img src="image/ticket1.png" alt="Ticket" class="w-92 mb-3" />
        <!-- ข้อความทับบนรูป (ตัวอย่าง) -->
        <div class="absolute inset-0 flex flex-col items-center justify-center">
          <h2 class="text-2xl font-bold text-black drop-shadow-lg">Demo</h2>
        </div>
      </div>

      <!-- ส่วนเลือกวันที่และจำนวนตั๋ว อยู่ในแถวเดียวกัน (สอง div คนละส่วน) -->
      <div class="flex items-center justify-between mb-4 w-full">
        <!-- Div แรก: ช่องเลือกวันที่ -->
        <div>
          <label for="ticketDate" class="block mb-1 font-semibold text-white">
            วันที่เข้าชม
          </label>
          <input 
            type="date" 
            id="ticketDate" 
            name="ticket_date"
            class="border border-gray-300 rounded px-3 py-2 text-black focus:outline-none focus:ring-2 focus:ring-mainBlue"
            required
          />
        </div>
        <!-- Div ที่สอง: ปุ่มเพิ่ม/ลด จำนวนตั๋ว -->
        <div class="flex items-center space-x-2">
          <button 
            type="button"
            id="decrementBtn" 
            class="bg-gray-300 px-3 py-1 rounded hover:bg-gray-400 text-black transition">
            -
          </button>
          <input 
            type="text" 
            id="ticketQuantity" 
            name="ticket_quantity"
            value="1" 
            readonly 
            class="w-12 text-center border border-gray-300 rounded py-1 text-black"
          />
          <button 
            type="button"
            id="incrementBtn" 
            class="bg-gray-300 px-3 py-1 rounded hover:bg-gray-400 text-black transition">
            +
          </button>
        </div>
      </div>

      <!-- แสดงราคาและราคารวม -->
      <p class="mb-1">
        ราคา/ใบ: <span id="pricePerTicket" class="font-semibold"><?php echo htmlspecialchars($event['price']); ?></span> บาท
      </p>
      <p class="mb-6">
        ราคารวม: <span id="totalPrice" class="font-semibold"><?php echo htmlspecialchars($event['price']); ?></span> บาท
      </p>

      <!-- ปุ่มยืนยัน (Submit Form) -->
      <div class="flex justify-end">
        <button 
          type="submit"
          id="confirmBtn"
          class="bg-white text-black px-6 py-2 rounded-md hover:bg-hoverBlue hover:text-white transition-colors">
          ยืนยัน
        </button>
      </div>
    </form>
  </main>

  <!-- Footer -->
  <footer class="bg-mainBlue text-white py-4 text-center mt-8">
    <p>&copy; 2025 Equarium. All rights reserved.</p>
  </footer>

  <!-- JavaScript สำหรับเพิ่ม/ลดจำนวนตั๋ว และคำนวณราคารวม -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const decrementBtn = document.getElementById('decrementBtn');
      const incrementBtn = document.getElementById('incrementBtn');
      const quantityInput = document.getElementById('ticketQuantity');
      const pricePerTicketSpan = document.getElementById('pricePerTicket');
      const totalPriceSpan = document.getElementById('totalPrice');

      // ดึงราคาต่อใบจากฐานข้อมูล
      const pricePerTicket = parseInt(pricePerTicketSpan.textContent) || 300;

      function updateTotalPrice() {
        const qty = parseInt(quantityInput.value) || 1;
        const total = qty * pricePerTicket;
        totalPriceSpan.textContent = total;
      }

      decrementBtn.addEventListener('click', () => {
        let qty = parseInt(quantityInput.value);
        if (qty > 1) {
          qty--;
          quantityInput.value = qty;
          updateTotalPrice();
        }
      });

      incrementBtn.addEventListener('click', () => {
        let qty = parseInt(quantityInput.value);
        qty++;
        quantityInput.value = qty;
        updateTotalPrice();
      });

      updateTotalPrice();
    });
  </script>
</body>
</html>
