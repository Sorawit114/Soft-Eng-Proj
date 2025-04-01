<?php 
session_start();
include '../includes/navbar.php';

// หากยังไม่ล็อกอิน ให้ redirect ไปหน้า aquarium.php
if (!isset($_SESSION['session_id'])) {
  header("Location:  ../homepage/aquarium.php");
  die();
}

// ดึงค่า id จาก URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id === 0) {
    die("Invalid event ID.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>User Profile - Agreement</title>
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
<body class="font-poppins min-h-screen bg-mainBlue">
  <!-- Header ที่เป็นรูปภาพ -->
  <header class="relative h-32 bg-center bg-cover bg-no-repeat" style="background-image: url('../image/8929102.jpg');">
    <!-- ลิงก์ Aquarium มุมบนซ้าย -->
    <div class="absolute top-5 left-5 z-50 flex items-center">
      <a href="../homepage/aquarium.php" class="text-white text-3xl font-bold">Equarium</a>
    </div>
  </header>
  <br>
  <h1 class="text-3xl md:text-5xl font-bold drop-shadow-lg text-white px-8">
    ข้อตกลงในการซื้อบัตร
  </h1>

  <!-- ส่วนเนื้อหาหลัก -->
  <main class="max-w-4xl mx-auto px-4 py-8">
    <!-- กล่องข้อตกลง -->
    <div class="bg-gray-200 text-black p-6 rounded-lg mb-6">
      <h2 class="text-xl font-semibold mb-4 text-center text-[#001a4d]">
        โปรดอ่านข้อตกลงก่อนทำการซื้อบัตร
      </h2>
      <p class="leading-relaxed">
        1. เมื่อซื้อบัตรแล้ว ไม่สามารถขอคืนเงินได้<br/>
        2. กรุณาแสดงบัตรเข้างานหรือหลักฐานการซื้อในวันเข้าชม<br/>
        3. โปรดรักษาความเรียบร้อยและไม่ทำลายสัตว์น้ำหรือสิ่งแวดล้อม<br/>
        4. ข้อกำหนดและเงื่อนไขอื่น ๆ ตามที่ผู้ให้บริการกำหนด<br/>
      </p>
    </div>

    <!-- ปุ่มยืนยัน (ส่งค่า id ไปยัง ticket.php) -->
    <div class="flex justify-end">
      <a href="../ticket/ticket.php?id=<?php echo $id; ?>" 
         class="bg-white text-black px-6 py-2 rounded-md hover:bg-hoverBlue transition-colors">
        ยืนยัน
      </a>
    </div>
  </main>

  <!-- Footer -->
  <footer class="bg-mainBlue text-white py-4 text-center mt-8">
    <p>&copy; 2025 Equarium. All rights reserved.</p>
  </footer>
</body>
</html>
