<?php
session_start();
include 'navbar.php';

if (!isset($_SESSION['session_id'])) {
    header("Location: aquarium.php");
    exit;
}

$user_id = $_SESSION['id'] ?? null;
if (!$user_id) {
    die("User not found.");
}

$conn = new mysqli("localhost", "root", "", "aquarium");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$ticket_id = $_GET['id'] ?? null; // รับค่า ID จาก URL
if (!$ticket_id) {
    die("Ticket not found.");
}

$status = isset($ticket['status']) ? htmlspecialchars($ticket['status']) : "รอการตรวจสอบ";
$statusClass = "text-yellow-600"; // default = รอการตรวจสอบ

if ($status === "อนุมัติ") {
    $statusClass = "text-green-600";
} elseif ($status === "ไม่อนุมัติ") {
    $statusClass = "text-red-600";
}

// ดึงข้อมูลตั๋วเฉพาะอันที่เลือก
$sql = "SELECT t.*, e.name AS event_name, e.image AS event_image, e.location AS event_location, 
               u.username AS user_name
        FROM ticket t
        JOIN events e ON t.event_id = e.id
        JOIN users u ON t.user_id = u.id
        WHERE t.user_id = ? AND t.id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $user_id, $ticket_id);
$stmt->execute();
$result = $stmt->get_result();
$ticket = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Ticket detail</title>
  <!-- Tailwind CSS via CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  
  <!-- (ตัวอย่าง) Google Fonts: Poppins -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="event_card.css">
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
  <header
  class="relative h-32 bg-center bg-cover bg-no-repeat"
  style="background-image: url('image/8929102.jpg');"
>

  <!-- ลิงก์ Aquarium มุมบนซ้าย -->
  <div class="absolute top-5 left-5 z-50 flex items-center">
    <a href="aquarium.php" class="text-white text-xl font-bold">Equarium</a>
  </div>

  </header>
  


  <!-- ส่วนเนื้อหา (Main) -->
  <main class="flex flex-col items-center justify-center py-10">

        <!-- ตั๋วแบบสวยเหมือนในรูป -->
            <div class="relative inline-block mt-8">
            <!-- รูปพื้นหลังตั๋ว -->
            <img src="image/ticket1.png" alt="Ticket" class="w-[800px] " />

            <!-- เนื้อหาซ้อนบนรูปตั๋ว -->
            <div class="absolute text-xl top-0 left-0 w-full h-full px-6 py-7 flex justify-between">
                <!-- ฝั่งซ้าย -->
                <div class="flex flex-col w-3/4 space-y-3">
                <!-- รูป event -->
                <img src="<?php echo htmlspecialchars($ticket['event_image']); ?>" 
                    alt="Event Image" 
                    class="w-full h-40 object-cover rounded-lg" />

                <!-- วันที่ -->
                <div class="flex items-center space-x-2 text-mainBlue font-semibold">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
                </svg>

                    <span><?php echo htmlspecialchars($ticket['ticket_date']);?></span>
                </div>

                <!-- สถานที่ -->
                <div class="flex items-center space-x-2 text-mainBlue font-semibold">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                    </svg>
                    <span><?php echo htmlspecialchars($ticket['event_location']); ?></span>
                </div>

                <!-- ผู้ซื้อ -->
                <div class="flex flex-row space-x-2">
                    <span class="text-sm font-semibold text-mainBlue">ผู้ซื้อ</span>
                    <div class="bg-mainBlue text-white italic px-3 py-1 rounded-full w-fit">
                    <?php echo htmlspecialchars($ticket['user_name']); ?>
                    </div>
                </div>
                </div>

            </div>
            </div>

        <!-- Card -->
        <div class="space-y-10 w-full max-w-3xl py-10">
              <!-- Ticket Card -->
              <div class="bg-white text-black rounded-xl shadow-md p-4 w-full">
                <div class="flex flex-col md:flex-row justify-bettween">
                  <!-- Left Column: รูป event -->
                    <p class="mt-2 text-2xl text-mainBlue font-semibold md:w-1/2 text-center">
                        รหัสบัตร <br>
                        <?php echo htmlspecialchars($ticket['ticket_code']); ?></p>
                    </p>
                    <?php if ($ticket['used'] === 'ใช้งานแล้ว'): ?>
                        <div class="mt-4 text-center">
                            <a href="user_review.php?ticket_id=<?php echo $ticket['id']; ?>" class="bg-green-600 text-white px-6 py-2 rounded-full hover:bg-green-700 transition-colors shadow-md font-semibold">
                            Review
                            </a>
                        </div>
                        <?php endif; ?>
                  <!-- Right Column: รายละเอียดตั๋ว -->
                <div class="md:w-1/2 md:pl-6 mt-5 md:mt-0 py-5 text-mainBlue text-2xl">
                    <div class="flex flex-row">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                    </svg>
                    <p class=" mb-2 px-5"><?php echo htmlspecialchars($ticket['event_location']); ?></p>
                    </div>
                    <div class="flex flex-row">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 0 1 0 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 0 1 0-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375Z" />
                    </svg>
                    <p class=" mb-1 px-5"><?php echo htmlspecialchars($ticket['ticket_quantity']); ?> tickets</p>
                    </div>
                    <div class="flex flex-row">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
                        </svg>
                        <p class=" mb-5 px-5"><?php echo htmlspecialchars($ticket['ticket_date']); ?></p>
                    </div>
                  </div>
                </div>
              </div>
          </div>

            <!-- Close Button -->
            <div class="mt-6 text-center">
                <a href="myticket.php" class="bg-white text-mainBlue px-6 py-2 rounded-full hover:bg-gray-100 transition-colors shadow-md font-semibold">
                close
                </a>
            </div>

  </main>
</body>
</html>
