<?php
session_start();
if(isset($_SESSION['session_id'])){header("Location:aquarium.php");die();}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Register</title>
  <!-- Tailwind CSS -->
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
            mainBlue: "#001a4d",
            hoverBlue: "#003080",
          },
        },
      },
    };
  </script>
</head>
<body class="relative bg-fixed bg-center bg-cover bg-no-repeat" style="background-image: url('8929102.jpg'); font-family: 'Poppins', sans-serif;">
  <!-- ลิงก์ Aquarium ที่มุมบนซ้าย -->
  <div class="absolute top-5 left-5">
    <a href="aquarium.php" class="text-white text-xl font-bold">Aquarium</a>
  </div>
  
  <!-- Container จัดกลางทั้งแนวตั้งและแนวนอน -->
  <div class="flex items-center justify-center min-h-screen">
    <div class="bg-white text-gray-800 rounded-xl p-6 max-w-sm shadow-lg">
      <h2 class="text-xl font-semibold text-mainBlue mb-4">Register</h2>
      <!-- ฟอร์มส่งข้อมูลไปที่ reg_save.php -->
      <form action="reg_save.php" method="POST">
        <div class="mb-4">
          <label for="user" class="block text-sm font-medium mb-1">Username</label>
          <input type="text" id="user" name="user" placeholder="Username" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-mainBlue" required>
        </div>
        <div class="mb-4">
          <label for="email" class="block text-sm font-medium mb-1">Email</label>
          <input type="email" id="email" name="email" placeholder="Email" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-mainBlue" required>
        </div>
        <!-- Password Field -->
        <div class="mb-4 relative">
          <label for="password" class="block text-sm font-medium mb-1">Password</label>
          <input type="password" id="password" name="password" placeholder="••••••••" class="w-full border border-gray-300 rounded px-3 py-2 pr-10 focus:outline-none focus:ring-2 focus:ring-mainBlue" required>
          <!-- หากต้องการเพิ่มปุ่ม toggle show/hide password สามารถเพิ่มโค้ดได้ที่นี่ -->
        </div>
        <button type="submit" class="w-full bg-mainBlue text-white py-2 rounded hover:bg-hoverBlue transition-colors">
          Confirm
        </button>
      </form>
      <p class="mt-4 text-sm text-center">
        Already have an account?
        <a href="login.php" class="text-mainBlue font-semibold hover:underline">Sign in.</a>
      </p>
    </div>
  </div>
</body>
</html>
