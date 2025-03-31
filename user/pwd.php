<?php
session_start();

// ถ้ายังไม่มี session['id'] แสดงว่ายังไม่ได้ล็อกอิน ให้กลับหน้า aquarium
if(!isset($_SESSION['session_id'])){
  header("Location:../homepage/aquarium.php");
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
  <main class="container mx-auto py-10 px-4  overflow-x-auto">
    <?php include 'user_page.php'?>
      
      <div class="w-full max-w-xl bg-white p-8 rounded-xl shadow-lg min-w-[300px]">
  <h3 class="text-mainBlue font-semibold text-lg mb-4">Change Password</h3>
  <form action="change_password.php" method="POST">
    <div class="mb-4">
      <label for="old_password" class="block text-sm font-medium text-gray-700">Old Password</label>
      <input 
        type="password" 
        id="old_password" 
        name="old_password" 
        class="w-full border border-gray-300 rounded px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-mainBlue" 
        placeholder="Old Password" 
        required>
    </div>
    <div class="mb-4">
      <label for="new_password" class="block text-sm font-medium text-gray-700">New Password</label>
      <input 
        type="password" 
        id="new_password" 
        name="new_password" 
        class="w-full border border-gray-300 rounded px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-mainBlue" 
        placeholder="New Password" 
        required>
    </div>
    <div class="mb-4">
      <label for="confirm_password" class="block text-sm font-medium text-gray-700">Confirm New Password</label>
      <input 
        type="password" 
        id="confirm_password" 
        name="confirm_password" 
        class="w-full border border-gray-300 rounded px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-mainBlue" 
        placeholder="Confirm New Password" 
        required>
    </div>
    <button type="submit" class="mt-6 w-full bg-mainBlue text-white py-2 rounded hover:bg-hoverBlue transition-colors">
      Save
    </button>
    <?php if (isset($_SESSION['success'])): ?>
      <div class="text-green-500 mt-2">
        <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
      </div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
      <div class="text-red-500 mt-2">
        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
      </div>
    <?php endif; ?>
  </form>
</div>

      
    </div>
  </main>
</body>
</html>
