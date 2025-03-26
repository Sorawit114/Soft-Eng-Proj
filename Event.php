<?php
session_start();
include 'navbar.php';
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
    $conditions[] = "location = ?";
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
$conn = new mysqli("localhost", "root", "", "aquarium");
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
<body class="font-poppins bg-mainBlue text-white min-h-screen">
  <!-- Header -->
  <header class="relative h-96 bg-fixed bg-center bg-cover bg-no-repeat" style="background-image: url('image/jellyfish-aquarium-black-background-glowing-white-amoled-3840x2160-2094.jpg');">
    <div class="absolute bottom-0 left-0 w-full h-16 bg-gradient-to-t from-[#001a4d] to-transparent"></div>
    <div class="absolute top-5 left-5 z-50 flex items-center">
      <a href="aquarium.php" class="text-white text-xl font-bold">Equarium</a>
    </div>
  </header>

  <!-- Search Bar -->
  <div class="flex justify-center mt-4">
    <form action="event.php" method="GET" class="bg-white text-[#001a4d] px-4 py-2 inline-flex items-center space-x-4 rounded-lg max-w-3xl w-auto mx-auto shadow">
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
          <option value="bangkok" <?php if($province == 'bangkok') echo "selected"; ?>>Bangkok</option>
          <option value="phuket" <?php if($province == 'phuket') echo "selected"; ?>>Phuket</option>
          <option value="chiangmai" <?php if($province == 'chiangmai') echo "selected"; ?>>Chiang Mai</option>
        </select>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
      </div>

      <!-- Price: ลบ option "all" ออก -->
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

      <!-- ปุ่มไอคอนค้นหา -->
      <button type="submit" class="ml-2 text-[#001a4d] hover:text-blue-700">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1 0 5.25 5.25a7.5 7.5 0 0 0 11.4 11.4z" />
        </svg>
      </button>
    </form>
  </div>

  <?php if(isset($_SESSION['role']) && $_SESSION['role'] == 'a'): ?>
    <div class="flex justify-center mt-10">
      <button id="openModal" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow">
        Add Event
      </button>
    </div>
  <?php endif; ?>

  <!-- Modal: ซ่อนอยู่โดยค่าเริ่มต้น -->
  <div id="modal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white text-black rounded-lg shadow-lg w-full max-w-lg p-6">
      <h2 class="text-2xl font-bold mb-4">Add New Event</h2>
      <form action="add_event.php" method="POST" enctype="multipart/form-data">
        <div class="mb-4">
          <label for="name" class="block font-semibold mb-1">Event Name</label>
          <input type="text" id="name" name="name" class="w-full p-2 border rounded" placeholder="Event Name" required>
        </div>
        <div class="mb-4">
          <label for="image" class="block font-semibold mb-1">Image File</label>
          <input type="file" id="image" name="image" class="w-full p-2 border rounded" required>
        </div>
        <div class="mb-4">
          <label for="location" class="block font-semibold mb-1">Location</label>
          <input type="text" id="location" name="location" class="w-full p-2 border rounded" placeholder="Location" required>
        </div>
        <div class="mb-4">
          <label for="activity" class="block font-semibold mb-1">Activity</label>
          <input type="text" id="activity" name="activity" class="w-full p-2 border rounded" placeholder="Activity" required>
        </div>
        <div class="mb-4">
          <label for="price" class="block font-semibold mb-1">Price</label>
          <input type="number" step="0.01" id="price" name="price" class="w-full p-2 border rounded" placeholder="Price" required>
        </div>
        <div class="flex justify-end space-x-2">
          <button type="button" id="closeModal" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
            Cancel
          </button>
          <button type="submit" class="bg-mainBlue hover:bg-hoverBlue text-white px-4 py-2 rounded">
            Submit
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- JavaScript สำหรับเปิด/ปิด Modal -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const openModalBtn = document.getElementById('openModal');
      const closeModalBtn = document.getElementById('closeModal');
      const modal = document.getElementById('modal');

      if (openModalBtn) {
        openModalBtn.addEventListener('click', () => {
          modal.classList.remove('hidden');
        });
      }

      if (closeModalBtn) {
        closeModalBtn.addEventListener('click', () => {
          modal.classList.add('hidden');
        });
      }

      // ปิด modal เมื่อคลิกที่พื้นหลัง
      modal.addEventListener('click', (e) => {
        if(e.target === modal) {
          modal.classList.add('hidden');
        }
      });
    });
  </script>

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
            <p class="location-icon mb-1">
              Location: <?php echo htmlspecialchars($events[$i]['location']); ?>
            </p>
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
              <a href="review.php?id=<?php echo $events[$i]['id']; ?>" 
                class="event-card__button px-3 py-1 rounded"
                style="background-color: #D8AC34;">
                Review
              </a>

              <!-- ปุ่ม Detail -->
              <a href="detail.php?id=<?php echo $events[$i]['id']; ?>" 
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
