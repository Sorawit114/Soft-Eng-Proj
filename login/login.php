<?php
session_start();
if(isset($_SESSION['session_id'])){header("Location: ../homepage/aquarium.php");die();}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Sign in</title>
  <!-- เรียกใช้ Tailwind CSS ผ่าน CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- (ตัวอย่าง) เรียกใช้ฟอนต์ Poppins จาก Google Fonts -->
  <link
    href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap"
    rel="stylesheet"
  />
  <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  <!-- ตั้งค่าให้ Tailwind ใช้ฟอนต์ Poppins ตามต้องการ -->
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            poppins: ['Poppins', 'sans-serif'],
          },
          colors: {
            mainBlue: '#040F53',
            hoverBlue: '#003080',
          },
        },
      },
    }
  </script>
</head>
<body class="h-screen w-full overflow-hidden font-poppins">
<?php if (isset($_SESSION['error'])): ?>
  <!-- Popup Modal -->
  <div x-data="{ open: true }" x-show="open" 
       class="fixed inset-0 flex items-center justify-center z-50">
    <!-- Overlay -->
    <div class="absolute inset-0 bg-black opacity-50" @click="open = false"></div>
    <!-- Modal Content -->
    <div class="bg-white p-6 rounded-lg shadow-lg max-w-sm w-full z-10">
      <p class="text-red-500 text-center">
        <?php echo htmlspecialchars($_SESSION['error']); ?>
      </p>
      <button @click="open = false" 
              class="mt-4 block mx-auto bg-mainBlue text-white py-2 px-4 rounded hover:bg-hoverBlue transition-colors">
        Close
      </button>
    </div>
  </div>
  <?php unset($_SESSION['error']); ?>
<?php endif; ?>

  <div class="relative flex h-full w-full">
    
    <!-- ส่วนซ้าย: รูป BG ขยายเต็มที่ -->
    <div
      class="relative flex-1 bg-cover bg-center"
      style="background-image: url('../image/8929102.jpg ');"
    >
      <!-- ตำแหน่งโลโก้มุมบนซ้าย -->
      <div class="absolute top-5 left-5" >

        <a href="../homepage/aquarium.php" class="text-white text-xl font-bold">Equarium</a>
    
      </div>
    </div>

    <!-- ส่วนขวา: พื้นหลังสีน้ำเงินเข้ม + ฟอร์ม -->
    <!-- กำหนดความกว้างไว้คงที่ เพื่อเลย์เอาต์ดูคงที่ตามดีไซน์ -->
    <div
      class="relative w-[450px] bg-mainBlue text-white flex flex-col h-full 
             px-8 py-10 z-10 justify-center">
      
      
      <!-- ข้อความต้อนรับ -->
      <div>
        <h1 class="text-2xl font-bold uppercase mb-2">
          Welcome
        </h1>
        <p class="mb-4">
          Sign in to buy a ticket.
        </p>
      </div>
      
      <!-- กล่องฟอร์ม Sign in (พื้นหลังสีขาว) -->
      <div class="bg-white text-gray-800 rounded-xl p-6 max-w-sm">
        <h2 class="text-xl font-semibold text-mainBlue mb-4">
          Sign in
        </h2>
        <form action="verify.php" method="POST">
          <div class="mb-4">
            <label
              for="email"
              class="block text-sm font-medium mb-1"
            >
              email
            </label>
            <input
              type="email"
              id="email"
              name="email"
              placeholder="email"
              class="w-full border border-gray-300 rounded px-3 py-2
                     focus:outline-none focus:ring-2 focus:ring-mainBlue"
              required
            />
          </div>
          <div class="mb-4">
            <label
              for="password"
              class="block text-sm font-medium mb-1"
            >
              password
            </label>
            <input
              type="password"
              id="password"
              name="password"
              placeholder="password"
              class="w-full border border-gray-300 rounded px-3 py-2
                     focus:outline-none focus:ring-2 focus:ring-mainBlue"
              required
            />
          </div>
          <button
            type="submit"
            class="w-full bg-mainBlue text-white py-2 rounded
                   hover:bg-hoverBlue transition-colors"
          >
            Login
          </button>
        </form>
        <p class="mt-4 text-sm text-center">
          Don't have an account?
          <a
            href="../register/register.php"
            class="text-mainBlue font-semibold hover:underline"
          >
            Register here.
          </a>
        </p>
      </div>
    </div>
    
    <!-- เลเยอร์ Gradient เพื่อให้เฟดจากโปร่งใส -> สีน้ำเงิน -->
    <div
      class="pointer-events-none absolute top-0 bottom-0 
             right-[450px] w-24
             bg-gradient-to-r from-transparent to-mainBlue z-10"
    ></div>
  </div>
</body>
</html>
