<?php
session_start();
include '../includes/navbar.php';

// ตรวจสอบว่าผู้ใช้ล็อกอินหรือยัง
if (!isset($_SESSION['session_id']) || !isset($_SESSION['id'])) {
    header("Location:  ../homepage/aquarium.php");
    exit();
}

$user_id = $_SESSION['id'];
$event_id = isset($_GET['id']) ? intval($_GET['id']) : 0; // รับค่า event_id จาก URL

// เชื่อมต่อฐานข้อมูล
$conn = new mysqli('mysql', 'user', 'password', 'aquarium');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ดึงข้อมูล event ตาม id
$sql = "SELECT * FROM events WHERE event_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();
$event = $result->fetch_assoc();
$stmt->close();

// ถ้าไม่พบข้อมูล event ที่มี id ตรงกัน
if (!$event) {
    die("Event not found.");
}

// ดึงข้อมูลรีวิวจากฐานข้อมูล
$rating_filter = isset($_GET['rating']) ? intval($_GET['rating']) : 0; // Filter ตามระดับดาว
$sql_reviews = "SELECT r.*, u.first_name FROM review r JOIN users u ON r.user_id = u.id WHERE r.event_id = ?";

if ($rating_filter > 0) {
    $sql_reviews .= " AND r.rating = ?";
}

$stmt_reviews = $conn->prepare($sql_reviews);
if ($rating_filter > 0) {
    $stmt_reviews->bind_param("ii", $event_id, $rating_filter);
} else {
    $stmt_reviews->bind_param("i", $event_id);
}
$stmt_reviews->execute();
$reviews = $stmt_reviews->get_result();
$stmt_reviews->close();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Event Detail</title>
  <!-- Tailwind CSS CDN -->
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
    }
  </script>
</head>
<body class="bg-mainBlue min-h-screen text-white font-poppins">

<!-- Hero Section -->
<header class="relative h-[300px] md:h-[400px] bg-no-repeat bg-cover bg-center" style="background-image: url('<?php echo htmlspecialchars($event['image']); ?>');">
    <div class="absolute inset-0 bg-gradient-to-b from-transparent to-[#040F53]"></div>
    <div class="absolute top-5 left-5 z-50 flex items-center">
      <a href="../homepage/aquarium.php" class="text-white text-3xl font-bold">Equarium</a>
    </div>
    <div class="absolute bottom-5 left-5 z-50">
      <a href="Event.php" class="inline-flex items-center space-x-2 text-white hover:underline">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
        </svg>
        <span>Back</span>
      </a>
    </div>
</header>

<!-- Main Content -->
<main class="max-w-6xl mx-auto px-4 py-8">
  <h2 class="text-3xl font-bold mb-6"><?php echo htmlspecialchars($event['name']); ?></h2>

  <!-- Tabs for filtering reviews by rating -->
  <div class="flex flex-wrap gap-2 mb-6">
    <a href="?id=<?php echo $event_id; ?>" class="bg-white text-black px-3 py-1 rounded-full cursor-pointer">ทั้งหมด</a>
    <?php for ($i = 5; $i >= 1; $i--): ?>
      <a href="?id=<?php echo $event_id; ?>&rating=<?php echo $i; ?>" class="bg-white text-black px-3 py-1 rounded-full cursor-pointer"><?php echo $i; ?> ดาว</a>
    <?php endfor; ?>
  </div>

  <!-- Reviews -->
  <div class="bg-white text-black rounded-lg p-4 h-[400px] overflow-y-auto">

    <!-- Display reviews -->
    <?php if ($reviews->num_rows > 0): ?>
      <?php while ($review = $reviews->fetch_assoc()): ?>
        <div class="p-4 bg-gray-100 rounded mb-4">
          <h3 class="font-bold text-gray-800">ผู้รีวิว: <?php echo htmlspecialchars($review['first_name']); ?></h3>
          <div class="flex items-center space-x-2">
            <?php for ($i = 1; $i <= 5; $i++): ?>
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="<?php echo ($review['rating'] >= $i) ? 'yellow' : 'gray'; ?>" viewBox="0 0 16 16">
                <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"></path>
              </svg>
            <?php endfor; ?>
          </div>
          <p class="text-gray-700 mt-2"><?php echo htmlspecialchars($review['content']); ?></p>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p class="text-gray-700">ยังไม่มีรีวิวสำหรับกิจกรรมนี้</p>
    <?php endif; ?>
  </div>
</main>

<!-- Footer -->
<footer class="mt-8">
  <div class="flex justify-center items-center">
    <p class="text-center text-white mt-2">&copy; 2025 Equarium. All rights reserved.</p>
  </div>
</footer>

</body>
</html>
