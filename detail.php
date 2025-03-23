<?php
session_start();

// เชื่อมต่อฐานข้อมูล
$conn = new mysqli("localhost", "root", "", "aquarium");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// รับค่า id จาก GET
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// ดึงข้อมูล event ตาม id
$sql = "SELECT * FROM events WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

$event = $result->fetch_assoc();
include 'navbar.php';

$stmt->close();
$conn->close();

// ถ้าไม่พบข้อมูล event ที่มี id ตรงกัน
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
  <!-- Google Fonts: Poppins -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
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
<body class="bg-mainBlue min-h-screen text-white font-poppins">

  <!-- ส่วนบน (Hero Section) 
       ใช้ background-image + gradient overlay เพื่อให้ภาพเฟดกับสี -->
       <header class="relative h-[300px] md:h-[400px] bg-no-repeat bg-cover bg-center"
        style="background-image: url('<?php echo htmlspecialchars($event['image']); ?>');">
  <!-- Gradient Overlay: เฟดจาก mainBlue ไปเป็นโปร่งใส -->
  <div class="absolute inset-0 bg-gradient-to-b from-transparent to-[#040F53]"></div>
  <div class="absolute top-5 left-5 z-50 flex items-center">
      <a href="aquarium.php" class="text-white text-xl font-bold">Equarium</a>
    </div>

  <!-- ปุ่ม Back อยู่ด้านล่างซ้าย หลังจากเฟดเสร็จ -->
  <div class="absolute bottom-5 left-5 z-50">
    <a href="javascript:history.back()" class="inline-flex items-center space-x-2 text-white hover:underline">
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

  <!-- เนื้อหาหลัก -->
  <main class="max-w-6xl mx-auto px-4 py-8">
    <!-- บล็อกแสดงภาพ, ราคา, ปุ่มซื้อตั๋ว (เล็กลง: max-w-lg) -->
    <section class="bg-white text-black rounded-2xl p-4 mb-8 max-w-3xl mx-auto flex flex-col md:flex-row shadow-md">
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
      <div>
        <h2 class="text-xl font-bold mb-2 text-mainBlue">
          <?php echo htmlspecialchars($event['name']); ?>
        </h2>
        <!-- แสดง Price แทน Description -->
        <p class="text-gray-700 mb-4">
          Price: ฿<?php echo htmlspecialchars($event['price']); ?>
        </p>
      </div>
      <!-- ปุ่ม Buy Ticket -->
      <?php if (isset($_SESSION['session_id'])): ?>
      <div class="flex justify-end">
        <a href="prepay.php?id=<?php echo $event['id']; ?>" 
           class="inline-block bg-mainBlue text-white px-4 py-2 rounded-2xl hover:bg-hoverBlue transition">
          Buy Ticket
          </a>
          </div>
        <?php else: ?>
          <!-- ถ้ายังไม่ล็อกอิน แสดงปุ่มกด/ลิงก์ หรือแสดง Pop-up -->
          <div class="flex justify-end">
            <button 
              id="openModal" 
              class="inline-block bg-mainBlue text-white px-4 py-2 rounded-2xl hover:bg-hoverBlue transition">
              Buy Ticket
            </button>
          </div>
        <?php endif; ?>
      </div>
    </section>

    <!-- ส่วน Reviews (ตัวอย่างเป็นช่องๆ) -->
    <section class="mb-8">
      <h3 class="text-xl font-semibold mb-4">Reviews</h3>
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <!-- ตัวอย่างรีวิวแบบ placeholder -->
        <div class="bg-white text-black p-4 rounded">
          <p>Review 1</p>
        </div>
        <div class="bg-white text-black p-4 rounded">
          <p>Review 2</p>
        </div>
        <div class="bg-white text-black p-4 rounded">
          <p>Review 3</p>
        </div>
        <div class="bg-white text-black p-4 rounded">
          <p>Review 4</p>
        </div>
      </div>
      <!-- ลิงก์ "อ่านเพิ่มเติม" -->
      <div class="mt-4">
        <a href="all_reviews.php?id=<?php echo $event['id']; ?>" 
           class="text-white underline">
          อ่านเพิ่มเติม
        </a>
      </div>
    </section>

    <!-- บล็อกข้อมูลเพิ่มเติมขนาดใหญ่ (ตามภาพตัวอย่าง) -->
    <section class="bg-[#ffffff] text-black rounded-lg p-6">
      <h3 class="text-2xl font-semibold mb-4">ข้อมูล</h3>
      <p class="leading-relaxed">
        <!-- ตัวอย่างข้อความ -->
        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque 
        habitant morbi tristique senectus et netus et malesuada fames ac turpis 
        egestas. Vestibulum ante ipsum primis in faucibus orci luctus et 
        ultrices posuere cubilia curae; Donec sollicitudin sem sed tortor 
        consequat, et posuere arcu hendrerit. Phasellus ultrices lacus sed 
        efficitur fermentum. Vivamus at neque massa.
      </p>
    </section>
  </main>

  <!-- Footer -->
  <footer class="bg-[#001a4d] text-white py-4 text-center">
    <p>&copy; 2025 Equarium. All rights reserved.</p>
  </footer>

    <!-- ส่วน Pop-up (Modal) ที่จะบอกให้ล็อกอินก่อน -->
  <?php if (!isset($_SESSION['session_id'])): ?>
    <div 
      id="loginModal" 
      class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden"
    >
      <div class="bg-gray-200 text-gray-800 p-6 rounded-md shadow-md w-full max-w-sm text-center">
        <p class="mb-4 text-xl">Please log in to purchase tickets</p>
        <a href="login.php" class="bg-mainBlue text-white px-4 py-2 rounded hover:bg-hoverBlue">
          Go to Login
        </a>
      </div>
    </div>
  <?php endif; ?>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const openModalBtn = document.getElementById('openModal');
      const loginModal = document.getElementById('loginModal');

      // ถ้ามีปุ่ม openModal แสดงว่า user ยังไม่ล็อกอิน
      if (openModalBtn && loginModal) {
        openModalBtn.addEventListener('click', () => {
          // แสดง modal
          loginModal.classList.remove('hidden');
        });

        // ปิด modal เมื่อคลิกที่พื้นหลัง
        loginModal.addEventListener('click', (e) => {
          if (e.target === loginModal) {
            loginModal.classList.add('hidden');
          }
        });
      }
    });
  </script>

</body>
</html>
