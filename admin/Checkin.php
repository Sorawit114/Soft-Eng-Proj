<?php
session_start();
include '../includes/navbar.php';

if (!isset($_SESSION['session_id']) || $_SESSION['role'] !== 'a') {
    header("Location:  ../homepage/aquarium.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "aquarium");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$search = $_GET['search'] ?? '';

$sql = "SELECT t.*, e.name AS event_name, u.username AS user_name
        FROM ticket t
        JOIN events e ON t.event_id = e.event_id
        JOIN users u ON t.user_id = u.id
        WHERE t.used = 'ยังไม่ได้ใช้งาน'";

if ($search) {
    $sql .= " AND t.ticket_code LIKE '%" . $conn->real_escape_string($search) . "%'";
}

$sql .= " ORDER BY t.created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Check-in Tickets</title>
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
      <a href="../homepage/aquarium.php" class="text-white text-xl font-bold">Equarium</a>
    </div>
  </header>

  <main class="p-6 max-w-5xl mx-auto">
    <h1 class="text-2xl font-semibold mb-6">ตรวจสอบการใช้งานบัตร</h1>

    <!-- Search Box -->
    <form method="GET" class="mb-6">
      <input type="text" name="search" placeholder="ค้นหารหัสบัตร..." value="<?php echo htmlspecialchars($search); ?>"
             class="px-4 py-2 rounded text-black w-80">
      <button type="submit" class="ml-2 px-4 py-2 bg-white text-mainBlue font-semibold rounded hover:bg-gray-200">
        ค้นหา
      </button>
    </form>

    <?php if ($result && $result->num_rows > 0): ?>
      <div class="space-y-4">
        <?php while ($ticket = $result->fetch_assoc()): ?>
          <div class="bg-white text-black rounded shadow p-4 flex flex-col md:flex-row justify-between items-center">
            <div class="text-left">
              <p><strong>รหัสบัตร:</strong> <?php echo htmlspecialchars($ticket['ticket_code']); ?></p>
              <p><strong>อีเวนต์:</strong> <?php echo htmlspecialchars($ticket['event_name']); ?></p>
              <p><strong>ผู้ซื้อ:</strong> <?php echo htmlspecialchars($ticket['user_name']); ?></p>
            </div>
            <form method="POST" action="checkin_update.php">
              <input type="hidden" name="ticket_id" value="<?php echo $ticket['id']; ?>">
              <button type="submit" class="mt-4 md:mt-0 px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                ยืนยันการใช้งาน
              </button>
            </form>
          </div>
        <?php endwhile; ?>
      </div>
    <?php else: ?>
      <p class="text-white mt-6">ไม่พบบัตรที่ตรงกับเงื่อนไข</p>
    <?php endif; ?>
  </main>
</body>
</html>
