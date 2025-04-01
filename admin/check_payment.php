<?php
session_start();
include '../includes/navbar.php';

if (!isset($_SESSION['session_id']) || $_SESSION['role'] !== 'a') {
    header("Location:  ../homepage/aquarium.php");
    exit();
}

// เชื่อมต่อฐานข้อมูล
$conn = new mysqli("localhost", "root", "", "aquarium");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ดึงข้อมูลตั๋วที่รอตรวจสอบ พร้อมข้อมูล event และ user
$sql = "SELECT t.*, e.name AS event_name, e.image AS event_image, e.location AS event_location, u.username AS user_name
        FROM ticket t
        JOIN events e ON t.event_id = e.event_id
        JOIN users u ON t.user_id = u.id
        WHERE t.status = 'รอตรวจสอบ'
        ORDER BY t.created_at DESC";

$result = $conn->query($sql);
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

  <main class="container mx-auto px-4 py-10">
    <h1 class="text-3xl font-bold mb-6">รายการตั๋วที่รอตรวจสอบ</h1>

    <?php if ($result->num_rows > 0): ?>
      <div class="space-y-6">
        <?php while ($ticket = $result->fetch_assoc()): ?>
          <div class="bg-white text-black rounded-xl shadow-md p-4 flex flex-col md:flex-row">
            <!-- รูป Event -->
            <div class="md:w-1/4">
              <img src="<?php echo htmlspecialchars($ticket['event_image']); ?>" alt="event" class="w-full h-32 object-cover rounded-md">
               <!-- แสดงสถานะใต้รูป -->
            <p class="font-semibold text-yellow-600">สถานะ: <?php echo htmlspecialchars($ticket['status']); ?></p>
            </div>
            <!-- รายละเอียดตั๋ว -->
            <div class="md:w-3/4 md:pl-6 mt-4 md:mt-0">
              <h2 class="text-xl font-bold text-mainBlue"><?php echo htmlspecialchars($ticket['event_name']); ?></h2>
              <p>สถานที่: <?php echo htmlspecialchars($ticket['event_location']); ?></p>
              <p>จำนวนตั๋ว: <?php echo htmlspecialchars($ticket['ticket_quantity']); ?></p>
              <p>วันที่ชำระเงิน: <?php echo htmlspecialchars($ticket['ticket_date']); ?></p>
              <p>ผู้ซื้อ: <span class="italic text-mainBlue"><?php echo htmlspecialchars($ticket['user_name']); ?></span></p>
              <div class="flex justify-end mt-3">
                <a href="admin_ticket_detail.php?id=<?php echo $ticket['id']; ?>" class="bg-mainBlue text-white px-4 py-2 rounded hover:bg-hoverBlue transition">ดูรายละเอียด</a>
              </div>
            </div>
          </div>
        <?php endwhile; ?>
      </div>
    <?php else: ?>
      <p class="text-white">ไม่มีตั๋วที่รอตรวจสอบในขณะนี้</p>
    <?php endif; ?>

    <?php $conn->close(); ?>
  </main>
</body>
</html>
