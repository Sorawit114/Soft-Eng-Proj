<?php
session_start();

// ถ้ายังไม่มี session['id'] แสดงว่ายังไม่ได้ล็อกอิน ให้กลับหน้า aquarium
if(!isset($_SESSION['session_id'])){
  header("Location: ../homepage/aquarium.php");
  die();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>User Profile</title>
  <!-- Tailwind CSS via CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  
  <!-- Google Fonts: Poppins (ตัวอย่าง) -->
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
  <!-- Header -->
  <header
    class="relative h-32 bg-center bg-cover bg-no-repeat"
    style="background-image: url('image/8929102.jpg');">


    <div class="absolute top-5 left-5 z-20 flex items-center">
      <a href="../homepage/aquarium.php" class="text-white text-xl font-bold">Equarium</a>
    </div>
  </header>
  
  <!-- Main Container -->
  <main class="container mx-auto py-10 px-4 overflow-x-auto">
    <?php include 'user_page.php'?>
      
      <!-- ขวา: ฟอร์มแก้ไขโปรไฟล์ -->
      <div class="w-full max-w-xl bg-white p-8 rounded-xl shadow-lg min-w-[300px]">
      <div class="bg-white text-gray-800 p-6 rounded-xl shadow-lg max-w-md w-full">
    <!-- หัวข้อ -->
    <h3 class="text-l font-semibold text-mainBlue mb-3">
      พบปัญหาหรือมีความต้องการความช่วยเหลือ ?
    </h3>
    <h3 class="text-lg font-semibold mb-4">
      Having trouble or have something to suggest ?
    </h3>
    
    <!-- ข้อความอธิบาย -->
    <p class="mb-4 leading-relaxed">
      ทางทีมงานอยู่ระหว่างการพัฒนาให้แอปพลิเคชันของเราใช้งานได้อย่างมีประสิทธิภาพ
      และมอบประสบการณ์ที่ดีที่สุดแก่ผู้ใช้งานของเรา
      ความเห็นของคุณจะช่วยให้เราพัฒนาแพลตฟอร์มการจองตั๋วนี้
      ให้ดียิ่งขึ้นในทุกแง่มุม
    </p>
    <p class="mb-4 leading-relaxed">
      We're continuously improving to provide you the best ticketing experience possible.
      Your feedback is invaluable in shaping our platform.
    </p>
    <p class="mb-4 leading-relaxed">
      Feel free to share your thoughts with us at
      <span class="font-semibold text-mainBlue">Equarium@hotmail.com</span>.
    </p>
    
    <!-- อีเมลติดต่อ -->
    <p class="text-center text-sm text-gray-600">
      หรือส่งข้อเสนอแนะและคำแนะนำของคุณมาได้ที่ <br>
      <span class="font-semibold text-mainBlue">Equarium@hotmail.com</span>
    </p>
  </div>
</div>
    </div>
  </main>
</body>
</html>
