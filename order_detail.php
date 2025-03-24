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
  <title>order detail</title>
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
  <div class="absolute top-5 left-5 z-20">
    <a href="aquarium.php" class="text-white text-xl font-bold">Equarium</a>
  </div>

  </header>
  


  <!-- ส่วนเนื้อหา (Main) -->
  <main class="flex flex-col items-center justify-center py-10">
        <?php if ($ticket_id): ?>

          <div class="space-y-10 w-full max-w-xl">

              <!-- Ticket Card -->
              <div class="bg-white text-black rounded-xl shadow-md p-4 w-full">
                <div class="flex flex-col md:flex-row">
                  <!-- Left Column: รูป event -->
                  <div class="flex flex-col items-center md:w-1/3">
                    <img src="<?php echo htmlspecialchars($ticket['event_image']); ?>" 
                         alt="<?php echo htmlspecialchars($ticket['event_name']); ?>" 
                         class="w-32 h-32 object-cover rounded-md" />
                  </div>
                  <!-- Right Column: รายละเอียดตั๋ว -->
                  <div class="md:w-2/3 md:pl-6 mt-4 md:mt-0">
                    <h2 class="text-2xl font-bold text-mainBlue mb-1">
                      <?php echo htmlspecialchars($ticket['event_name']); ?>
                    </h2>
                    <p class="location-icon text-gray-700 mb-1 "><?php echo htmlspecialchars($ticket['event_location']); ?></p>
                    <p class="text-gray-700 mb-1"><?php echo htmlspecialchars($ticket['ticket_quantity']); ?> tickets</p>
                    <p class="text-gray-700 mb-2">วันที่ชำระเงิน: <?php echo htmlspecialchars($ticket['ticket_date']); ?></p>
                  </div>
                </div>
                <!-- เส้นแบ่ง (hr) -->
                <hr class="w-full my-2 border-t-2 border-black" />
                <!-- สถานะและปุ่ม Detail -->
                <div class="flex items-center justify-between mt-2">
                  <p class="font-semibold">
                    <span class="text-mainBlue">สถานะ:</span>
                    <span class="<?php echo $statusClass; ?>">
                      <?php
                        // สมมติว่าตัวแปร $status, $statusClass ถูกกำหนดก่อนแล้ว
                        echo $status;
                      ?>
                    </span>
                  </p>
                </div>
              </div>
          </div>
        <?php endif; ?>

        <!-- ตั๋วแบบสวยเหมือนในรูป -->
            <div class="relative inline-block mt-8">
            <!-- รูปพื้นหลังตั๋ว -->
            <img src="image/ticket1.png" alt="Ticket" class="w-[700px]" />

            <!-- เนื้อหาซ้อนบนรูปตั๋ว -->
            <div class="absolute top-0 left-0 w-full h-full px-6 py-4 flex justify-between">
                <!-- ฝั่งซ้าย -->
                <div class="flex flex-col w-3/4 space-y-3">
                <!-- รูป event -->
                <img src="<?php echo htmlspecialchars($ticket['event_image']); ?>" 
                    alt="Event Image" 
                    class="w-full h-40 object-cover rounded-lg" />

                <!-- วันที่ -->
                <div class="flex items-center space-x-2 text-md text-mainBlue font-semibold">
                    <span class="location-icon"><?php echo htmlspecialchars($ticket['ticket_date']);?></span>
                </div>

                <!-- สถานที่ -->
                <div class="flex items-center space-x-2 text-md text-mainBlue font-semibold">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 11.25a2.25 2.25 0 100-4.5 2.25 2.25 0 000 4.5zM19.5 11.25c0 7.25-7.5 10.5-7.5 10.5S4.5 18.5 4.5 11.25a7.5 7.5 0 1115 0z" />
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

                <!-- ฝั่งขวา -->
                <div class="flex flex-col items-center justify-center w-1/4 space-y-4 text-center">
                <p class="text-sm font-bold text-mainBlue leading-tight">PRESS TO<br>DISPLAY QR<br>CODE</p>
                <a href="payconfirm.php?id=<?php echo $ticket_id?>" class="border border-mainBlue px-4 py-1 rounded-full text-mainBlue text-sm font-semibold hover:bg-mainBlue hover:text-white transition">
                    Detail
                </a>
                </div>
            </div>
            </div>
        </div>
    </div>
  </main>
</body>
</html>
