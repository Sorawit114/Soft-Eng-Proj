<?php
session_start();
include '../includes/navbar.php';

if (!isset($_SESSION['session_id']) || $_SESSION['role'] !== 'a') {
    header("Location: ../homepage/aquarium.php");
    exit();
}

// ตรวจสอบว่ามี ticket_id ส่งมาหรือไม่
$ticket_id = $_GET['id'] ?? null;
if (!$ticket_id) {
  die("ไม่พบตั๋วที่ร้องขอ");
}

// เชื่อมต่อฐานข้อมูล
$conn = new mysqli("localhost", "root", "", "aquarium");
if ($conn->connect_error) {
  die("เชื่อมต่อฐานข้อมูลล้มเหลว: " . $conn->connect_error);
}

// ดึงข้อมูลตั๋ว พร้อม JOIN ตาราง events และ users
$sql = "SELECT t.*, 
               e.name AS event_name, e.image AS event_image, e.location AS event_location,
               u.username AS full_name
        FROM ticket t
        JOIN events e ON t.event_id = e.event_id
        JOIN users u ON t.user_id = u.id
        WHERE t.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $ticket_id);
$stmt->execute();
$result = $stmt->get_result();
$ticket = $result->fetch_assoc();

$stmt->close();
$conn->close();

// ตรวจสอบว่ามีข้อมูลหรือไม่
if (!$ticket) {
  die("ไม่พบข้อมูลตั๋วในระบบ");
}

// กำหนดคลาสสีตามสถานะ
$status = $ticket['status'] ?? "รอการตรวจสอบ";
$statusClass = "text-yellow-600";
if ($status === "อนุมัติ") {
  $statusClass = "text-green-600";
} elseif ($status === "ไม่อนุมัติ") {
  $statusClass = "text-red-600";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Admin Review Tickets</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: { poppins: ["Poppins", "sans-serif"] },
          colors: { mainBlue: "#040F53", hoverBlue: "#003080" },
        },
      },
    }
  </script>
</head>
<body class="font-poppins bg-mainBlue text-white min-h-screen">
  <header class="relative h-32 bg-cover bg-center" style="background-image: url('../image/jellyfish-aquarium-black-background-glowing-white-amoled-3840x2160-2094.jpg');">
    <div class="absolute top-5 left-5 z-50">
      <a href="../homepage/aquarium.php" class="text-white text-3xl font-bold">Equarium</a>
    </div>
  </header>
  <a href="javascript:history.back()" class="inline-flex items-center space-x-2 text-white hover:underline">
      <!-- ไอคอนลูกศรซ้าย -->
      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 md-5" fill="none" 
           viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" 
              d="M15 19l-7-7 7-7" />
      </svg>
      <span>Back</span>
    </a>
  <main class="flex items-center justify-center py-12 bg-mainBlue max-h-screen">
    
  <div class="bg-white w-full max-w-3xl rounded-xl shadow-lg p-8 border border-gray-300">
  <!-- หัวข้อ -->
  <h2 class="text-lg font-semibold text-center mb-6 text-gray-800">
    รายละเอียดคำสั่งซื้อตั๋ว
  </h2>

  <!-- เนื้อหาแบบ 2 คอลัมน์ -->
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <!-- สลิป -->
    <div class="md:col-span-2 bg-gray-100 h-80 flex items-center justify-center rounded-md overflow-hidden">
      <?php if (!empty($ticket['slip_image'])): ?>
        <img src="uploads/<?php echo htmlspecialchars($ticket['slip_image']); ?>" 
             alt="สลิป" 
             class="object-contain max-h-full max-w-full" />
      <?php else: ?>
        <span class="text-gray-500">ยังไม่มีสลิป</span>
      <?php endif; ?>
    </div>

    <!-- ข้อมูลการเงิน -->
        <div class="bg-gray-100 h-80 p-4 rounded-md flex flex-col justify-between">
        <div class="space-y-2 text-sm text-gray-700">
            <p><strong>เลขออเดอร์:</strong> <?php echo htmlspecialchars($ticket['ticket_code']); ?></p>
            <p><strong>ชื่อผู้ซื้อ:</strong> <?php echo htmlspecialchars($ticket['full_name']); ?></p>
            <p><strong>อีเวนต์:</strong> <?php echo htmlspecialchars($ticket['event_name']); ?></p>
            <p><strong>สถานที่:</strong> <?php echo htmlspecialchars($ticket['event_location']); ?></p>
            <p><strong>วันที่ชำระเงิน:</strong> <?php echo htmlspecialchars($ticket['ticket_date']); ?></p>
            <p><strong>จำนวนบัตร:</strong> <?php echo htmlspecialchars($ticket['ticket_quantity']); ?> ใบ</p>
            <p><strong>สถานะ:</strong> 
            <span class="<?php echo $statusClass; ?>">
                <?php echo $status; ?>
            </span>
            </p>
        </div>
        </div>
    </div>

    <!-- ปุ่ม -->
    <div class="flex justify-end gap-4 mt-6">
        <form action="../ticket/ticket_action.php" method="POST">
        <input type="hidden" name="ticket_id" value="<?php echo $ticket['id']; ?>">
        <button type="submit" name="action" value="reject"
            class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded">
            ไม่อนุมัติ
        </button>
        <button type="submit" name="action" value="approve"
            class="bg-mainBlue hover:bg-hoverBlue text-white px-6 py-2 rounded">
            อนุมัติ
        </button>
        </form>
    </div>
</div>
</main>

</body>
</html>
