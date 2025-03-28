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

<header
    class="relative h-32 bg-center bg-cover bg-no-repeat"
    style="background-image: url('image/8929102.jpg');">
  
    <div class="absolute top-5 left-5 z-20 flex items-center">
      <a href="aquarium.php" class="text-white text-xl font-bold">Equarium</a>
    </div>
  </header>
  
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
    <main class="max-w-6xl mx-auto px-4 py-8">
    
    <!-- แถบ Category Pills (ตัวอย่าง) -->
    <div class="flex flex-wrap gap-2 mb-6">
      <!-- ตัวอย่าง Pills (ปรับตาม Category จริง) -->
      <span class="bg-white text-black px-3 py-1 rounded-full cursor-pointer">ทั้งหมด</span>
      <span class="bg-white text-black px-3 py-1 rounded-full cursor-pointer">5 ดาว</span>
      <span class="bg-white text-black px-3 py-1 rounded-full cursor-pointer">4 ดาว</span>
      <span class="bg-white text-black px-3 py-1 rounded-full cursor-pointer">3 ดาว</span>
      <span class="bg-white text-black px-3 py-1 rounded-full cursor-pointer">อื่น ๆ</span>
      <!-- เพิ่มตามต้องการ -->
    </div>

    <!-- พื้นที่แสดงรีวิว (รองรับ scroll ถ้ามีจำนวนมาก) -->
    <div class="bg-white text-black rounded-lg p-4 h-[400px] overflow-y-auto">
      <h2 class="text-xl font-semibold mb-4">รีวิวทั้งหมด</h2>

      <!-- ตัวอย่าง Placeholder รีวิว (กรณีไม่มี DB) -->
      <div class="space-y-4">
        <!-- รีวิว 1 -->
        <div class="p-4 bg-gray-100 rounded">
          <h3 class="font-bold text-gray-800">ผู้รีวิว: John Doe</h3>
          <p class="text-gray-700 mt-2">“Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent finibus…”</p>
        </div>
        <!-- รีวิว 2 -->
        <div class="p-4 bg-gray-100 rounded">
          <h3 class="font-bold text-gray-800">ผู้รีวิว: Jane Smith</h3>
          <p class="text-gray-700 mt-2">“Phasellus scelerisque nulla ac odio vulputate, in ullamcorper nisi ultricies…”</p>
        </div>
        <!-- รีวิว 3 -->
        <div class="p-4 bg-gray-100 rounded">
          <h3 class="font-bold text-gray-800">ผู้รีวิว: Alice</h3>
          <p class="text-gray-700 mt-2">“Aliquam erat volutpat. Vivamus suscipit semper nibh, nec posuere turpis…”</p>
        </div>
        <!-- รีวิวเพิ่มเติมตามต้องการ -->
      </div>
    </div>
  </main>

  <!-- Footer (โลโก้หรือไอคอนอยู่ตรงกลางล่าง) -->
  <footer class="mt-8">
    <div class="flex justify-center items-center">
      <!-- ตัวอย่างไอคอนหรือโลโก้ด้านล่าง -->
    </div>
    <p class="text-center text-white mt-2">&copy; 2025 Equarium. All rights reserved.</p>
  </footer>
</body>
</html>
