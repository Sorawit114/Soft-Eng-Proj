<?php
session_start();
include '../includes/navbar.php';

// รับค่า search parameters จาก GET
$activity = isset($_GET['activity']) ? trim($_GET['activity']) : '';
$province = isset($_GET['province']) ? trim($_GET['province']) : '';
$price = isset($_GET['price']) ? trim($_GET['price']) : '';

// สร้างเงื่อนไขสำหรับ SQL query
$conditions = [];
$params = [];
$types = '';

if ($activity !== '') {
    // แยกคำโดยใช้ช่องว่างหรือ comma
    $keywords = preg_split('/[\s,]+/', $activity);
    foreach ($keywords as $keyword) {
        if ($keyword !== '') {
            $conditions[] = "activity LIKE ?";
            $params[] = "%" . $keyword . "%";
            $types .= 's';
        }
    }
}

if ($province !== '') {
    // ตรวจสอบว่า $province ที่ได้รับมานั้นตรงกับค่าที่เก็บในฐานข้อมูล
    $conditions[] = "province = ?";  // ใช้ "province" สำหรับกรองข้อมูล
    $params[] = $province;
    $types .= 's';
}

// กำหนดเงื่อนไขสำหรับการเรียงลำดับราคา
$order = "";
if ($price === 'low') {
    $order = " ORDER BY price ASC";
} elseif ($price === 'high') {
    $order = " ORDER BY price DESC";
} else {
    $order = " ORDER BY created_at DESC";
}

// สร้าง SQL query แบบ dynamic
$sql = "SELECT * FROM events";
if (count($conditions) > 0) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}
$sql .= $order;

// เชื่อมต่อฐานข้อมูล
$conn = new mysqli('mysql', 'user', 'password', 'aquarium');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$stmt = $conn->prepare($sql);
if($stmt === false) {
    die("Prepare failed: " . $conn->error);
}

if(count($params) > 0) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

$events = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
    }
}

// ดึงจังหวัดที่มีจากฐานข้อมูล
$province_sql = "SELECT DISTINCT province FROM events";
$province_result = $conn->query($province_sql);

$stmt->close();
$conn->close();
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Equarium Activities</title>
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Google Fonts: Poppins -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../src/event_card.css">
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
<body class="font-poppins bg-mainBlue text-white min-h-screen">
  <!-- Header -->
  <header class="relative h-96 bg-fixed bg-center bg-cover bg-no-repeat" style="background-image: url('../image/jellyfish-aquarium-black-background-glowing-white-amoled-3840x2160-2094.jpg');">
    <div class="absolute bottom-0 left-0 w-full h-16 bg-gradient-to-t from-[#001a4d] to-transparent"></div>
    <div class="absolute top-5 left-5 z-50 flex items-center">
      <a href="../homepage/aquarium.php" class="text-white text-3xl font-bold">Equarium</a>
    </div>
  </header>

  <!-- Search Bar -->
  <div class="flex justify-center mt-4">
    <form action="Event.php" method="GET" class="bg-white text-[#001a4d] px-4 py-2 inline-flex items-center space-x-4 rounded-lg max-w-3xl w-auto mx-auto shadow">
      <!-- Activity -->
      <div class="flex items-center space-x-1">
        <span class="font-semibold">Activity:</span>
        <select name="activity" class="bg-transparent border-b border-[#001a4d] focus:outline-none focus:border-blue-500 appearance-none px-1">
          <option value="" <?php if($activity == '') echo "selected"; ?>>all</option>
          <option value="ชมสัตว์น้ำ" <?php if($activity == 'ชมสัตว์น้ำ') echo "selected"; ?>>ชมสัตว์น้ำ</option>
          <option value="โชว์ให้อาหารสัตว์น้ำ" <?php if($activity == 'โชว์ให้อาหารสัตว์น้ำ') echo "selected"; ?>>โชว์ให้อาหารสัตว์น้ำ</option>
          <option value="การแสดงสัตว์น้ำ" <?php if($activity == 'การแสดงสัตว์น้ำ') echo "selected"; ?>>การแสดงสัตว์น้ำ</option>
        </select>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
      </div>

      <!-- Province -->
      <div class="flex items-center space-x-1">
        <span class="font-semibold">Province:</span>
        <select name="province" class="bg-transparent border-b border-[#001a4d] focus:outline-none focus:border-blue-500 appearance-none px-1">
          <option value="" <?php if($province == '') echo "selected"; ?>>all</option>
          <?php while ($row = $province_result->fetch_assoc()): ?>
            <option value="<?php echo $row['province']; ?>" <?php if($province == $row['province']) echo "selected"; ?>>
              <?php echo htmlspecialchars($row['province']); ?>
            </option>
          <?php endwhile; ?>
        </select>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
      </div>

      <div class="flex items-center space-x-1">
        <span class="font-semibold">Price:</span>
        <select name="price" class="bg-transparent border-b border-[#001a4d] focus:outline-none focus:border-blue-500 appearance-none px-1">
          <option value="low" <?php if($price == 'low') echo "selected"; ?>>Low to High</option>
          <option value="high" <?php if($price == 'high') echo "selected"; ?>>High to Low</option>
        </select>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
      </div>

      <!-- Search Button -->
      <button type="submit" class="ml-2 text-[#001a4d] hover:text-blue-700">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1 0 5.25 5.25a7.5 7.5 0 0 0 11.4 11.4z" />
        </svg>
      </button>
    </form>
  </div>

  <!-- Cards Section -->
  <div class="px-4 py-8 md:px-16 md:py-12 bg-mainBlue">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
      <?php for ($i = 0; $i < count($events); $i++): ?>
        <?php
          // แยก activity เป็นอาร์เรย์ กรณีมีหลายกิจกรรมในฟิลด์เดียวกัน
          $activityList = preg_split('/[\s,]+/', $events[$i]['activity']);
        ?>
        <div class="event-card bg-white text-black rounded-lg shadow-md overflow-hidden flex flex-col">
          <div class="h-48">
            <img src="<?php echo htmlspecialchars($events[$i]['image']); ?>" 
                 alt="<?php echo htmlspecialchars($events[$i]['name']); ?>" 
                 class="w-full h-full object-cover">
          </div>
          <div class="event-card__body p-4 flex flex-col flex-1">
            <div class="flex flex-row space-x-2">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                    </svg>
              <p class="mb-1">
                Location: <?php echo htmlspecialchars($events[$i]['location']); ?>
              </p>
            </div>
            <h3 class="event-card__title text-xl font-bold text-[#001a4d] mb-2">
              <?php echo htmlspecialchars($events[$i]['name']); ?>
            </h3>
            
            <!-- แสดง Activity แบบ pill -->
            <div class="flex items-center gap-2 mb-3">
              <span class="text-[#001a4d] font-semibold">Activity:</span>
              <?php foreach ($activityList as $act): ?>
                <?php
                  $act = trim($act);
                  // กำหนดสีพื้นหลังตามประเภทของ activity
                  if ($act === 'ชมสัตว์น้ำ') {
                    $activityBg = "#040F53";
                  } elseif ($act === 'โชว์ให้อาหารสัตว์น้ำ') {
                    $activityBg = "#162375";
                  } elseif ($act === 'การแสดงสัตว์น้ำ') {
                    $activityBg = "#394693";
                  } else {
                    $activityBg = "#000000"; // default
                  }
                ?>
                <span class="inline-block px-3 py-1 text-white rounded-full" 
                      style="background-color: <?php echo $activityBg; ?>;">
                  <?php echo htmlspecialchars($act); ?>
                </span>
              <?php endforeach; ?>
            </div>

            <div class="event-card__buttons mt-auto flex flex-wrap justify-end gap-2">
              <!-- ปุ่ม Review -->
              <a href="user_review.php?id=<?php echo $events[$i]['event_id']; ?>" 
                class="event-card__button px-3 py-1 rounded"
                style="background-color: #D8AC34;">
                Review
              </a>

              <!-- ปุ่ม Detail -->
              <a href="detail.php?id=<?php echo $events[$i]['event_id']; ?>" 
                class="event-card__button px-3 py-1 rounded"
                style="background-color: #001a4d;">
                Detail
              </a>
            </div>
          </div>
        </div>
      <?php endfor; ?>
    </div>
  </div>

  <!-- Footer -->
  <footer class="bg-mainBlue text-white py-4 text-center">
    <p>&copy; 2025 Equarium. All rights reserved.</p>
  </footer>
</body>
</html>

