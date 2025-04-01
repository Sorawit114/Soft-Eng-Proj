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
            mainBlue: "#040F53",
            hoverBlue: "#003080",
          },
        },
      },
    };
  </script>
</head>
<body class="relative bg-fixed bg-center bg-cover bg-no-repeat" style="background-image: url('../image/8929102.jpg'); font-family: 'Poppins', sans-serif;">
  <!-- ลิงก์ Aquarium ที่มุมบนซ้าย -->
  <div class="absolute top-5 left-5">
    <a href="../homepage/aquarium.php" class="text-white text-3xl font-bold">Equarium</a>
  </div>
  
  <!-- Container จัดกลางทั้งแนวตั้งและแนวนอน -->
  <div class="flex items-center justify-center min-h-screen">
    <div class="bg-white text-gray-800 rounded-xl p-14 w-full max-w-lg shadow-lg">
      <h2 class="text-3xl font-semibold text-mainBlue mb-4 text-center">Register</h2>
      <!-- ฟอร์มส่งข้อมูลไปที่ reg_save.php -->
      <form action="reg_save.php" method="POST">
  <!-- Username -->
  <div class="mb-4">
    <label for="user" class="block text-sm font-medium mb-1">Username</label>
    <input 
      type="text" 
      id="user" 
      name="user" 
      placeholder="Username" 
      class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-mainBlue" 
      required
    >
  </div>

  <!-- Email -->
  <div class="mb-4">
    <label for="email" class="block text-sm font-medium mb-1">Email</label>
    <input 
      type="email" 
      id="email" 
      name="email" 
      placeholder="Email" 
      class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-mainBlue" 
      required
    >
  </div>

  <!-- Password -->
  <div class="mb-4 relative">
    <label for="password" class="block text-sm font-medium mb-1">Password</label>
    <input 
      type="password" 
      id="password" 
      name="password" 
      placeholder="••••••••" 
      class="w-full border border-gray-300 rounded px-3 py-2 pr-10 focus:outline-none focus:ring-2 focus:ring-mainBlue" 
      required
    >
  </div>

  <!-- ชื่อจริง -->
  <div class="mb-4">
    <label for="firstname" class="block text-sm font-medium mb-1">ชื่อจริง</label>
    <input 
      type="text" 
      id="firstname" 
      name="firstname" 
      placeholder="ชื่อจริง" 
      class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-mainBlue" 
      required
    >
  </div>

  <!-- นามสกุล -->
  <div class="mb-4">
    <label for="lastname" class="block text-sm font-medium mb-1">นามสกุล</label>
    <input 
      type="text" 
      id="lastname" 
      name="lastname" 
      placeholder="นามสกุล" 
      class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-mainBlue" 
      required
    >
  </div>

  <!-- เพศ (Radio) -->
  <div class="mb-4">
    <span class="block text-sm font-medium mb-1">เพศ</span>
    <div class="flex items-center space-x-4">
      <label class="inline-flex items-center">
        <input type="radio" name="gender" value="ชาย" class="form-radio" checked>
        <span class="ml-2">ชาย</span>
      </label>
      <label class="inline-flex items-center">
        <input type="radio" name="gender" value="หญิง" class="form-radio">
        <span class="ml-2">หญิง</span>
      </label>
      <label class="inline-flex items-center">
        <input type="radio" name="gender" value="อื่นๆ" class="form-radio">
        <span class="ml-2">อื่นๆ</span>
      </label>
    </div>
  </div>

  <!-- วันเดือนปีเกิด (Date) -->
  <div class="mb-4">
    <label for="birthdate" class="block text-sm font-medium mb-1">วันเดือนปีเกิด</label>
    <input 
      type="date" 
      id="birthdate" 
      name="birthdate" 
      class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-mainBlue" 
      required
    >
  </div>

  <button 
    type="submit" 
    class="w-full bg-mainBlue text-white py-2 rounded hover:bg-hoverBlue transition-colors"
  >
    Confirm
  </button>
</form>

      <p class="mt-4 text-sm text-center">
        Already have an account?
        <a href="../login/login.php" class="text-mainBlue font-semibold hover:underline">Sign in.</a>
      </p>
    </div>
  </div>
</body>
</html>
