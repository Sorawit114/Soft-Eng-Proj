<?php
session_start();
include '../includes/navbar.php';

// เชื่อมต่อฐานข้อมูล
$conn = new mysqli('mysql', 'user', 'password', 'aquarium');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ดึงข้อมูลรีวิวจากฐานข้อมูลพร้อมกับข้อมูลกิจกรรม
$sql = "SELECT r.*, u.first_name, u.username, e.name AS event_name, e.image AS event_image
        FROM review r
        JOIN users u ON r.user_id = u.id
        JOIN events e ON r.event_id = e.event_id
        WHERE r.rating BETWEEN 3 AND 5
        ORDER BY RAND() LIMIT 4";
$result = $conn->query($sql);
 
if (!$result) {
    die("Error: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Equarium Home</title>
  <script src="https://cdn.tailwindcss.com"></script>
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
    };
  </script>
</head>
<body class="bg-mainBlue text-white">
  <!-- Header Section -->
  <header class="relative min-h-screen bg-fixed bg-center bg-cover bg-no-repeat text" style="background-image: url('../image/8929102.jpg');">
    <!-- Header Content -->
    <div class="absolute inset-0 flex items-center justify-center pl-8">
        <div>
            <h1 class="text-6xl font-bold text-white">Equarium</h1>
        </div>
    </div>
    <div class="absolute bottom-0 left-0 w-full h-16 bg-gradient-to-t from-[#040F53] to-transparent"></div>
  </header>

  <!-- Main Content Wrapper ใช้ container แบบเดียวกันทั่วทั้งหน้า -->
  <main class="p-4 max-w-6xl mx-auto">
    <!-- Introduction Section -->
    <section class="my-8">
      <div class="w-full p-4 border-4 border-white rounded-lg shadow-xl bg-cover bg-center text-mainBlue text-center bg-white">
        <!-- Row 3 Parts with Dividers -->
        <p class="text-5xl">สำรวจฟีเจอร์หลักของเว็บไซต์เรา!</p>
        <div class="flex space-x-4 mt-4">
          
          <div class="flex-1 text-center flex flex-col justify-center items-center">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-10">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 0 1 0 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 0 1 0-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375Z" />
              </svg>
            <p>สำรองที่นั่งล่วงหน้าได้ง่าย ๆ</p>
          </div>
          <div class="flex-1 text-center flex flex-col justify-center items-center">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-10">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 0 1-2.25 2.25M16.5 7.5V18a2.25 2.25 0 0 0 2.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 0 0 2.25 2.25h13.5M6 7.5h3v3H6v-3Z" />
          </svg>
            <p>ค้นหาข้อมูลเกี่ยวกับสถานที่และบริการต่าง ๆ</p>
          </div>
          <div class="flex-1 text-center flex flex-col justify-center items-center">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-10 ">
            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
          </svg>
            <p>อ่านรีวิวจากผู้ใช้จริงหรือเขียนรีวิวของคุณ</p>
          </div>
        </div>
      </div>
    </section>

    <!-- จองตั๋ว Section -->
    <section class="py-16">
      <a href="../event/Event.php" class="block w-full p-14 rounded-xl bg-cover bg-center text-white text-center" style="background-image: url('../image/imgticket.png');">
        <h2 class="font-kanit text-4xl font-bold">จองตั๋ว</h2>
      </a>

      <!-- Activity Section -->
      <section class="my-4">
        <h2 class="text-5xl font-semibold text-start">Activity</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-2">
          <!-- Card 1 -->
          <a href="../event/Event.php?activity=ชมสัตว์น้ำ" class="relative h-56 rounded-lg shadow-lg overflow-hidden cursor-pointer hover:scale-105 transition">
            <div class="absolute inset-0 bg-cover bg-center blur-[1px]" style="background-image: url('../image/underwater.jpg');"></div>
            <div class="font-kanit absolute inset-0 flex items-center justify-center text-white text-3xl text-center z-10">
              ชมสัตว์น้ำ
            </div>
          </a>
          <!-- Card 2 -->
          <a href="../event/Event.php?activity=โชว์ให้อาหารสัตว์น้ำ" class="relative h-56 rounded-lg shadow-lg overflow-hidden cursor-pointer hover:scale-105 transition">
            <div class="absolute inset-0 bg-cover bg-center blur-[1px]" style="background-image: url('../image/20250201ENP0002l.jpg');"></div>
            <div class="font-kanit absolute inset-0 flex items-center justify-center text-white text-3xl text-center z-10">
              โชว์ให้อาหารสัตว์น้ำ
            </div>
          </a>
          <!-- Card 3 -->
          <a href="../event/Event.php?activity=การแสดงสัตว์น้ำ" class="relative h-56 rounded-lg shadow-lg overflow-hidden cursor-pointer hover:scale-105 transition">
            <div class="absolute inset-0 bg-cover bg-center blur-[1px]" style="background-image: url('../image/pexels-pixabay-34809.jpg');"></div>
            <div class="font-kanit absolute inset-0 flex items-center justify-center text-white text-3xl text-center z-10">
              การแสดงสัตว์น้ำ
            </div>
          </a>
        </div>
      </section>

      <!-- Recommend Section -->
      <section class="my-8">
        <h2 class="text-5xl font-semibold">รีวิวล่าสุด</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mt-8">
            <?php 
            while ($review = $result->fetch_assoc()):
                $event_name = isset($review['event_name']) ? htmlspecialchars($review['event_name']) : 'ไม่ระบุชื่อกิจกรรม';
                $first_name = isset($review['first_name']) ? htmlspecialchars($review['first_name']) : 'ไม่ระบุชื่อ';
                $event_image = isset($review['event_image']) ? htmlspecialchars($review['event_image']) : 'default.jpg';
            ?>
            <div class="relative bg-white rounded-lg overflow-hidden shadow-lg">
                <img src="<?php echo $event_image; ?>" alt="Event Image" class="w-full h-40 object-cover">
                <div class="p-4">
                    <h3 class="text-xl text-black font-semibold"><?php echo $event_name; ?></h3>
                    <p class="text-md text-black mb-4"><?php echo $first_name; ?> - <?php echo date("d M Y", strtotime($review['created_at'])); ?></p>
                    <p class="text-gray-700"><?php echo htmlspecialchars($review['content']); ?></p>
                    <div class="mt-2 flex items-center space-x-2">
                      <div class="flex">
                        <?php 
                          $rating = floor($review['rating']); // ปัดเศษลงเพื่อแสดงดาว
                          for ($i = 0; $i < 5; $i++): ?>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="<?php echo $i < $rating ? 'yellow' : 'gray'; ?>" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                              <path d="M9.049 2.927a1 1 0 011.902 0l2.255 4.557 5.033.73a1 1 0 01.553 1.707l-3.63 3.538.858 5.034a1 1 0 01-1.451 1.054L10 14.347l-4.519 2.375a1 1 0 01-1.451-1.054l.858-5.034-3.63-3.538a1 1 0 01.553-1.707l5.033-.73 2.255-4.557z"/>
                            </svg>
                        <?php endfor; ?>
                      </div>
                      <span class="text-gray-500"><?php echo number_format($review['rating'], 1); ?></span>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </section>
    </section>
  </main>

  <!-- Footer Section -->
  <footer class="bg-[#030D45] text-white py-8">
    <div class="container mx-auto text-center">
      <!-- ข้อมูลติดต่อ -->
      <div class="mb-6">
        <h3 class="text-lg font-semibold">Contact Us</h3>
        <p class="mt-2">Email: <a href="mailto:contact@example.com" class="text-blue-400 hover:text-blue-600">Equarium@hotmail.com</a></p>
        <p>Phone: <a href="tel:+123456789" class="text-blue-400 hover:text-blue-600">+66 94-830-1760</a></p>
      </div>
      <!-- โซเชียลมีเดีย -->
      <div>
        <h3 class="text-lg font-semibold">Follow Us</h3>
        <div class="flex justify-center space-x-6 mt-2">
          <a href="https://www.facebook.com" target="_blank" class="text-blue-600 hover:text-blue-800">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
              <path d="M22 12c0-5.523-4.478-10-10-10S2 6.477 2 12c0 4.992 3.657 9.146 8.485 9.898V15.5h-2.54v-3h2.54v-2.29c0-3.22 1.905-5.03 4.767-5.03 1.378 0 2.814.284 2.814.284v3.108h-1.588c-1.572 0-2.062.975-2.062 2.014v2.416h3.374l-.538 3h-2.836v6.398C18.343 21.146 22 16.992 22 12z"/>
            </svg>
          </a>
          <a href="https://www.twitter.com" target="_blank" class="text-blue-400 hover:text-blue-600">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
              <path d="M23 3a10.992 10.992 0 0 1-3.14 1.129 4.948 4.948 0 0 0 2.161-2.726 10.07 10.07 0 0 1-3.15 1.196 4.935 4.935 0 0 0-8.394 4.49A13.973 13.973 0 0 1 1.671 3.149a4.915 4.915 0 0 0-.664 2.485 4.925 4.925 0 0 0 2.188 4.105A4.93 4.93 0 0 1 1 9.295v.062a4.936 4.936 0 0 0 3.95 4.838 4.97 4.97 0 0 1-2.224.084c.624 1.948 2.435 3.372 4.572 3.411a9.87 9.87 0 0 1-6.1 2.106c-.395 0-.787-.023-1.17-.069a13.964 13.964 0 0 0 7.48 2.185c8.966 0 13.876-7.436 13.876-13.876 0-.21 0-.42-.014-.628A9.88 9.88 0 0 0 23 3z"/>
            </svg>
          </a>
          <a href="https://www.instagram.com" target="_blank" class="text-pink-500 hover:text-pink-700">
            <span class="[&>svg]:h-5 [&>svg]:w-5">
              <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 448 512">
                <path d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z"/>
              </svg>
            </span>
          </a>
        </div>
      </div>
    </div>
  </footer>
</body>
</html>
