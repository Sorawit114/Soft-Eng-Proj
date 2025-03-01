<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aquarium Home</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        .font-kanit {
            font-family: 'Kanit', sans-serif !important;
        }
    </style>
</head>
<body class="bg-[#0a1f44] text-white">
    <header class="relative min-h-screen bg-fixed bg-center bg-cover bg-no-repeat" style="background-image: url('8929102.jpg');">
    <nav class="absolute top-4 right-8  z-50 flex space-x-6 text-white font-semibold text-lg"> 
    <div class="flex justify-end space-x-4 mt-4 relative z-10"> 
    <?php if (isset($_SESSION['id'])): ?> 
        <a href="user.php" class="inline-flex items-center space-x-2 text-lg hover:underline">
  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
    <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
  </svg>
  <span><?php echo htmlspecialchars($_SESSION['user']); ?></span>
</a> 
     <?php else: ?>
       <a href="register.php" class="text-lg hover:underline">Register</a> 
     <a href="login.php" class="text-lg hover:underline">Sign In</a> 
    <?php endif; ?> 
</div> 
 </nav>

        <div class="absolute inset-0 flex items-center pl-8 text-white">
            <div>
                <h1 class="text-5xl font-bold">Header Content</h1>
                <p class="text-3xl">Content</p>
            </div>
        </div>
        <div class="absolute bottom-0 left-0 w-full h-16 bg-gradient-to-t from-[#0a1f44] to-transparent"></div>
    </header>
    
    <main class="p-4">
        <!-- Introduction Section -->
        <section class="my-8">
            <div class="max-w-6xl w-full mx-auto p-10 border-4 border-white rounded-lg shadow-xl bg-cover bg-center text-white text-center"
                 style="background-image: url('recommend-bg.jpg');">
                <h2 class="font-kanit text-4xl font-bold drop-shadow-lg">แนะนำระบบ</h2>
                <p class="mt-4 text-lg font-light drop-shadow-md">
                    สัมผัสประสบการณ์สุดพิเศษกับสัตว์น้ำหลากหลายชนิดที่ไม่เคยเห็นมาก่อน!
                </p>
            </div>
        </section>
        
        <section class="py-16">
            <!-- ส่วนจองตั๋ว -->
            <a href="reservation.php" class="block max-w-4xl mx-auto p-14 border-4 border-white bg-cover bg-center text-white text-center" 
                 style="background-image: url('imgticket.png');">
                <h2 class="font-kanit text-4xl font-bold">จองตั๋ว</h2>
            </a>

            <!-- Activity Section -->
            <section class="my-4">
                <h2 class="text-5xl font-semibold text-start">Activity</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-2 w-4/5 mx-auto">
                    <!-- Card 1 -->
                    <a href="page1.php" class="relative h-56 rounded-lg shadow-lg overflow-hidden cursor-pointer hover:scale-105 transition">
                        <div class="absolute inset-0 bg-cover bg-center blur-[1px]" style="background-image: url('underwater.jpg');"></div>
                        <div class="font-kanit absolute inset-0 flex items-center justify-center text-white text-3xl text-center z-10">
                            ชมสัตว์น้ำ
                        </div>
                    </a>
                    
                    <!-- Card 2 -->
                    <a href="page2.php" class="relative h-56 rounded-lg shadow-lg overflow-hidden cursor-pointer hover:scale-105 transition">
                        <div class="absolute inset-0 bg-cover bg-center blur-[1px]" style="background-image: url('20250201ENP0002l.jpg');"></div>
                        <div class="font-kanit absolute inset-0 flex items-center justify-center text-white text-3xl text-center z-10">
                            โชว์ให้อาหารสัตว์น้ำ
                        </div>
                    </a>
                    
                    <!-- Card 3 -->
                    <a href="page3.php" class="relative h-56 rounded-lg shadow-lg overflow-hidden cursor-pointer hover:scale-105 transition">
                        <div class="absolute inset-0 bg-cover bg-center blur-[1px]" style="background-image: url('pexels-pixabay-34809.jpg');"></div>
                        <div class="font-kanit absolute inset-0 flex items-center justify-center text-white text-3xl text-center z-10">
                            การแสดงสัตว์น้ำ
                        </div>
                    </a>
                </div>
            </section>
        
            <!-- Recommend Section -->
            <section class="my-8">
                <h2 class="text-5xl font-semibold">Recommend</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
                    <!-- Card 1 -->
                    <div class="relative flex bg-white text-black rounded-lg overflow-hidden">
                        <img src="jellyfish-aquarium-black-background-glowing-white-amoled-3840x2160-2094.jpg" class="w-2/3 object-cover" alt="Jellyfish">
                        <div class="p-4 flex-1 flex flex-col justify-between">
                            <!-- Rating: ด้านบนสุด -->
                            <div class="text-xl text-yellow-500 font-bold text-right">⭐ 5</div>
                            <!-- Caption: ตรงกลาง -->
                            <h3 class="text-md text-center w-full">*Caption*</h3>
                            <!-- Location: ซ้ายล่าง พร้อมไอคอน -->
                            <p class="text-sm text-left w-full flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 mr-2">
                                    <path fill-rule="evenodd" d="m11.54 22.351.07.04.028.016a.76.76 0 0 0 .723 0l.028-.015.071-.041a16.975 16.975 0 0 0 1.144-.742 19.58 19.58 0 0 0 2.683-2.282c1.944-1.99 3.963-4.98 3.963-8.827a8.25 8.25 0 0 0-16.5 0c0 3.846 2.02 6.837 3.963 8.827a19.58 19.58 0 0 0 2.682 2.282 16.975 16.975 0 0 0 1.145.742ZM12 13.5a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" clip-rule="evenodd" />
                                </svg>
                                location
                            </p>
                        </div>
                    </div>
            
                    <!-- Card 2 -->
                    <div class="relative flex bg-white text-black rounded-lg overflow-hidden">
                        <img src="pexels-nguyen-tran-327588-1703516.jpg" class="w-2/3 object-cover" alt="Fish">
                        <div class="p-4 flex-1 flex flex-col justify-between">
                            <!-- Rating: ด้านบนสุด -->
                            <div class="text-xl text-yellow-500 font-bold text-right">⭐ 4.9</div>
                            <!-- Caption: ตรงกลาง -->
                            <h3 class="text-md text-center w-full">*Caption*</h3>
                            <!-- Location: ซ้ายล่าง พร้อมไอคอน -->
                            <p class="text-sm text-left w-full flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 mr-2">
                                    <path fill-rule="evenodd" d="m11.54 22.351.07.04.028.016a.76.76 0 0 0 .723 0l.028-.015.071-.041a16.975 16.975 0 0 0 1.144-.742 19.58 19.58 0 0 0 2.683-2.282c1.944-1.99 3.963-4.98 3.963-8.827a8.25 8.25 0 0 0-16.5 0c0 3.846 2.02 6.837 3.963 8.827a19.58 19.58 0 0 0 2.682 2.282 16.975 16.975 0 0 0 1.145.742ZM12 13.5a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" clip-rule="evenodd" />
                                </svg>
                                location
                            </p>
                        </div>
                    </div>
            
                    <!-- Card 3 -->
                    <div class="relative flex bg-white text-black rounded-lg overflow-hidden">
                        <img src="underwater.jpg" class="w-2/3 object-cover" alt="Dolphin">
                        <div class="p-4 flex-1 flex flex-col justify-between">
                            <!-- Rating: ด้านบนสุด -->
                            <div class="text-xl text-yellow-500 font-bold text-right">⭐ 4.5</div>
                            <!-- Caption: ตรงกลาง -->
                            <h3 class="text-md text-center w-full">*Caption*</h3>
                            <!-- Location: ซ้ายล่าง พร้อมไอคอน -->
                            <p class="text-sm text-left w-full flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 mr-2">
                                    <path fill-rule="evenodd" d="m11.54 22.351.07.04.028.016a.76.76 0 0 0 .723 0l.028-.015.071-.041a16.975 16.975 0 0 0 1.144-.742 19.58 19.58 0 0 0 2.683-2.282c1.944-1.99 3.963-4.98 3.963-8.827a8.25 8.25 0 0 0-16.5 0c0 3.846 2.02 6.837 3.963 8.827a19.58 19.58 0 0 0 2.682 2.282 16.975 16.975 0 0 0 1.145.742ZM12 13.5a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" clip-rule="evenodd" />
                                </svg>
                                location
                            </p>
                        </div>
                    </div>
            
                    <!-- Card 4 -->
                    <div class="relative flex bg-white text-black rounded-lg overflow-hidden">
                        <img src="pexels-pixabay-34809.jpg" class="w-2/3 object-cover" alt="Dolphin">
                        <div class="p-4 flex-1 flex flex-col justify-between">
                            <!-- Rating: ด้านบนสุด -->
                            <div class="text-xl text-yellow-500 font-bold text-right">⭐ 4.5</div>
                            <!-- Caption: ตรงกลาง -->
                            <h3 class="text-md text-center w-full">*Caption*</h3>
                            <!-- Location: ซ้ายล่าง พร้อมไอคอน -->
                            <p class="text-sm text-left w-full flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 mr-2">
                                    <path fill-rule="evenodd" d="m11.54 22.351.07.04.028.016a.76.76 0 0 0 .723 0l.028-.015.071-.041a16.975 16.975 0 0 0 1.144-.742 19.58 19.58 0 0 0 2.683-2.282c1.944-1.99 3.963-4.98 3.963-8.827a8.25 8.25 0 0 0-16.5 0c0 3.846 2.02 6.837 3.963 8.827a19.58 19.58 0 0 0 2.682 2.282 16.975 16.975 0 0 0 1.145.742ZM12 13.5a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" clip-rule="evenodd" />
                                </svg>
                                location
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    
    <footer class="bg-gray-800 text-white py-8">
        <div class="container mx-auto text-center">
            <!-- ข้อมูลติดต่อ -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold">Contact Us</h3>
                <p class="mt-2">Email: <a href="mailto:contact@example.com" class="text-blue-400 hover:text-blue-600">sinbatzz5678@hotmail.com</a></p>
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
