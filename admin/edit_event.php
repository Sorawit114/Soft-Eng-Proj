<?php
session_start();
include '../includes/navbar.php';

if (!isset($_SESSION['session_id']) || $_SESSION['role'] !== 'a') {
    // ถ้าไม่มี session_id หรือ role ไม่ใช่ 'a' ให้ redirect ไปหน้า home
    header("Location:  ../homepage/aquarium.php");
    exit();
}
// เชื่อมต่อฐานข้อมูล
$conn = new mysqli("localhost", "root", "", "aquarium");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// รับข้อมูลจาก URL
$event_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($event_id === 0) {
    die("Invalid event ID.");
}

// ดึงข้อมูล event
$sql = "SELECT * FROM events WHERE event_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $event_id);
$stmt->execute();
$event = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$event) {
    die("Event not found.");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Event Detail</title>
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            poppins: ['Poppins', 'sans-serif'],
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
<body class="bg-mainBlue text-white font-poppins">

  <!-- Header -->
  <header class="relative h-32 bg-center bg-cover bg-no-repeat" style="background-image: url('../image/jellyfish-aquarium-black-background-glowing-white-amoled-3840x2160-2094.jpg');">
        <div class="absolute top-5 left-5 z-50 flex items-center">
            <a href="../homepage/aquarium.php" class="text-white text-xl font-bold">Equarium</a>
        </div>
        <div class="absolute bottom-5 left-5 z-50">
            <a href="editinfo_ticket.php" class="inline-flex items-center space-x-2 text-white hover:underline">
            <!-- ไอคอนลูกศรซ้าย -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" 
                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" 
                    d="M15 19l-7-7 7-7" />
            </svg>
            <span>Back</span>
            </a>
        </div>
  </header>

  

  <!-- Main Content -->
  <main class="max-w-6xl mx-auto px-4 py-8">
    
    <!-- บล็อกแสดงภาพ, ราคา, ปุ่มซื้อตั๋ว (เล็กลง: max-w-lg) -->
    <section class="bg-white text-black rounded-2xl p-6 mb-8 max-w-3xl mx-auto flex flex-col md:flex-row shadow-md">
      <!-- ส่วนรูปภาพ (ซ้าย) -->
      <div class="md:w-1/2">
        <img 
          src="<?php echo htmlspecialchars($event['image']); ?>" 
          alt="<?php echo htmlspecialchars($event['name']); ?>"
          class="w-full h-full object-cover rounded-2xl md:rounded-r-none"
        >
      </div>

      <!-- ส่วนรายละเอียด (ขวา) -->
      <div class="md:w-1/2 p-4 flex flex-col justify-between">
        <h2 class="text-xl font-bold mb-2 text-mainBlue"><?php echo htmlspecialchars($event['name']); ?></h2>
        <p class="text-gray-700 mb-4">Location: <?php echo htmlspecialchars($event['location']); ?></p>
        <p class="text-lg mb-4">Price: ฿<?php echo htmlspecialchars($event['price']); ?></p>
        <p class="text-lg mb-4">จำนวนตั๋วที่มี: <?php echo htmlspecialchars($event['ticket_quantity']); ?> ใบ</p>
        
        

        <!-- ปุ่มเพิ่มลดจำนวนตั๋ว และยืนยันซ่อน -->
        <div id="ticketOptions" class="hidden mt-4">
          <div class="flex items-center space-x-2">
            <span>เพิ่มจำนวนตั๋ว:</span>
            <button id="decrease" class="bg-gray-300 px-2 py-1 rounded">-</button>
            <input type="number" id="ticketCount" class="w-16 text-center" value="1" min="1" />
            <button id="increase" class="bg-gray-300 px-2 py-1 rounded">+</button>
          </div>
        </div>
      </div>
    </section>

    <section class="bg-[#ffffff] text-black rounded-lg p-6">
      <h3 class="text-2xl font-semibold mb-4">ข้อมูลเพิ่มเติม</h3>
      <p class="leading-relaxed">
        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque 
        habitant morbi tristique senectus et netus et malesuada fames ac turpis 
        egestas.
      </p>
    </section>

    <!-- แสดงปุ่มแก้ไข (ซ่อนปุ่มเพิ่ม/ลดจำนวนตั๋ว) -->
        <div class="flex justify-between items-center mt-5">
          <button id="editButton" 
            class="bg-white text-mainBlue px-4 py-2 rounded-2xl hover:bg-hoverBlue transition w-full">
            แก้ไข
          </button>
        </div>

        <!-- ปุ่มเพิ่มลดจำนวนตั๋ว และยืนยันซ่อน -->
        <div class="mt-4 flex justify-between">
            <button id="confirmButton" class="bg-white text-mainBlue px-4 py-2 rounded-2xl hover:bg-hoverBlue transition w-full">
              ยืนยัน
            </button>
        </div>
    </div>
  </main>

  <footer class="bg-[#001a4d] text-white py-4 text-center">
    <p>&copy; 2025 Equarium. All rights reserved.</p>
  </footer>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
    const editButton = document.getElementById('editButton');
    const ticketOptions = document.getElementById('ticketOptions');
    const decreaseButton = document.getElementById('decrease');
    const increaseButton = document.getElementById('increase');
    const ticketCountInput = document.getElementById('ticketCount');
    const confirmButton = document.getElementById('confirmButton');

    // แสดง/ซ่อนตัวเลือกเพิ่มจำนวนตั๋ว
    editButton.addEventListener('click', function() {
        editButton.classList.add('hidden'); // ซ่อนปุ่มแก้ไข
        ticketOptions.classList.remove('hidden'); // แสดงปุ่มเพิ่ม/ลดจำนวนตั๋วและยืนยัน
    });

    // เพิ่มจำนวนตั๋ว (ไม่ต้องเช็ค maximum)
    increaseButton.addEventListener('click', function() {
        let count = parseInt(ticketCountInput.value); // อ่านค่าจาก input
        ticketCountInput.value = count + 1; // เพิ่มจำนวนตั๋ว
    });

    // ลดจำนวนตั๋ว (ไม่ให้ลดต่ำกว่า 1)
    decreaseButton.addEventListener('click', function() {
        let count = parseInt(ticketCountInput.value); // อ่านค่าจาก input
        ticketCountInput.value = count - 1;
    });

    // ยืนยันการเลือก
    confirmButton.addEventListener('click', function() {
        const selectedCount = ticketCountInput.value;
        // ส่งไปที่ add_ticket.php พร้อมข้อมูลที่เลือก
        window.location.href = 'ticket/add_ticket.php?id=<?php echo $event['event_id']; ?>&count=' + selectedCount;
  });
});
  </script>

</body>
</html>