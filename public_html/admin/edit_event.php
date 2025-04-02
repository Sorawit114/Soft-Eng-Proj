<?php
session_start();
include '../includes/navbar.php';

if (!isset($_SESSION['session_id']) || $_SESSION['role'] !== 'a') {
    header("Location:  ../homepage/aquarium.php");
    exit();
}

$conn = new mysqli('mysql', 'user', 'password', 'aquarium');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// รับข้อมูลจาก URL
$event_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($event_id === 0) {
    die("Invalid event ID.");
}

// ดึงข้อมูล event
$sql = "SELECT * FROM events WHERE event_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $event_id);
$stmt->execute();
$event = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$event) {
    die("Event not found.");
}

// รายการกิจกรรม
$categories = [
  'ชมสัตว์น้ำ',
  'โชว์ให้อาหารสัตว์น้ำ',
  'การแสดงสัตว์น้ำ'
];

// ดึงข้อมูลจังหวัด
$province_sql = "SELECT * FROM provinces";
$province_result = $conn->query($province_sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Event Detail</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            poppins: ['Poppins', 'sans-serif'],
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
<body class="bg-mainBlue text-white font-poppins">

  <!-- Header -->
  <header class="relative h-32 bg-center bg-cover bg-no-repeat" style="background-image: url('../image/jellyfish-aquarium-black-background-glowing-white-amoled-3840x2160-2094.jpg');">
        <div class="absolute top-5 left-5 z-50 flex items-center">
            <a href="../homepage/aquarium.php" class="text-white text-3xl font-bold">Equarium</a>
        </div>
        <div class="absolute bottom-5 left-5 z-50">
            <a href="editinfo_ticket.php" class="inline-flex items-center space-x-2 text-white hover:underline">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" 
                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" 
                    d="M15 19l-7-7 7-7" />
            </svg>
            <span>Back</span>
            </a>
        </div>
  </header>

  <!-- Main Content -->
  <main class="max-w-6xl mx-auto px-4 py-8">

    <section class="bg-white text-black rounded-2xl p-6 mb-8 max-w-3xl mx-auto flex flex-col md:flex-row shadow-md">
      <div class="md:w-1/2">
        <img 
          src="<?php echo htmlspecialchars($event['image']) . '?timestamp=' . time(); ?>" 
          alt="<?php echo htmlspecialchars($event['name']); ?>"
          class="w-full h-full object-cover rounded-2xl md:rounded-r-none"
        >
      </div>

      <div class="md:w-1/2 p-4 flex flex-col justify-between">
        <h2 class="text-xl font-bold mb-2 text-mainBlue"><?php echo htmlspecialchars($event['name']); ?></h2>
        <p class="text-gray-700 mb-4">Location: <?php echo htmlspecialchars($event['location']); ?></p>
        <p class="text-lg mb-4">Price: ฿<?php echo htmlspecialchars($event['price']); ?></p>
        <p class="text-lg mb-4">จำนวนตั๋วที่มี: <?php echo htmlspecialchars($event['ticket_quantity']); ?> ใบ</p>
        <?php
        if (isset($_GET['error']) && $_GET['error'] == 'negative_quantity') {
      echo '<p class="text-red-500">จำนวนตั๋วไม่สามารถเป็นค่าติดลบได้</p>';
    }?>
      </div>
    </section>

    <section class="bg-white text-black rounded-lg p-6 mt-8" id="editSection" style="display:none;">
  <h3 class="text-2xl font-semibold mb-4">แก้ไขข้อมูล Event</h3>
  <form action="../ticket/add_ticket.php" method="POST" enctype="multipart/form-data">
  <input type="hidden" name="event_id" value="<?php echo $event['event_id']; ?>" />

<!-- ชื่อกิจกรรม -->
<div class="mb-4">
  <label for="event_name" class="block text-sm font-medium text-gray-700">Event Name</label>
  <input type="text" id="event_name" name="event_name" class="w-full p-2 mt-2 border rounded-md" value="<?php echo htmlspecialchars($event['name']); ?>" required />
</div>

<!-- จังหวัด -->
<div class="mb-4">
        <label for="location" class="block text-sm font-medium text-gray-700">Location (Province)</label>
        <select name="event_province" id="event_province" class="w-full p-2 mt-2 border rounded-md" required>
          <option value="">Select a province</option>
          <?php while ($province = $province_result->fetch_assoc()): ?>
            <option value="<?php echo $province['province_name']; ?>" <?php echo $province['province_name'] == $event['province'] ? 'selected' : ''; ?>>
              <?php echo htmlspecialchars($province['province_name']); ?>
            </option>
          <?php endwhile; ?>
        </select>
</div>

<!-- สถานที่ -->
<div class="mb-4">
  <label for="event_location" class="block text-sm font-medium text-gray-700">Location</label>
  <input type="text" id="event_location" name="event_location" class="w-full p-2 mt-2 border rounded-md" value="<?php echo htmlspecialchars($event['location']); ?>" required />
</div>

<!-- ราคา -->
<div class="mb-4">
  <label for="event_price" class="block text-sm font-medium text-gray-700">Price</label>
  <input type="number" id="event_price" name="event_price" class="w-full p-2 mt-2 border rounded-md" value="<?php echo htmlspecialchars($event['price']); ?>" required />
</div>

<!-- จำนวนตั๋ว -->
<div class="mb-4">
  <label for="event_ticket_quantity" class="block text-sm font-medium text-gray-700">Ticket Quantity</label>
  <input type="number" id="event_ticket_quantity" name="event_ticket_quantity" class="w-full p-2 mt-2 border rounded-md" value="<?php echo htmlspecialchars($event['ticket_quantity']); ?>" required />
</div>

<!-- คำอธิบาย -->
<div class="mb-4">
  <label for="event_description" class="block text-sm font-medium text-gray-700">Description</label>
  <textarea id="event_description" name="event_description" class="w-full p-2 mt-2 border rounded-md" rows="4" required><?php echo htmlspecialchars($event['description']); ?></textarea>
</div>

<!-- เลือกหมวดหมู่ -->
<div class="mb-4">
      <label class="block text-sm font-medium text-gray-700">Categories</label>
      <?php foreach ($categories as $category): ?>
        <div class="flex items-center space-x-2">
          <input 
            type="checkbox" 
            name="event_activity[]" 
            value="<?php echo htmlspecialchars($category); ?>" 
            <?php echo in_array($category, explode(',', $event['activity'])) ? 'checked' : ''; ?>
          />
          <span><?php echo htmlspecialchars($category); ?></span>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- ฟิลด์สำหรับอัปโหลดรูปภาพ -->
    <div class="mb-4">
        <label for="event_image" class="block text-sm font-medium text-gray-700">Event Image</label>
        <input type="file" id="event_image" name="event_image" class="w-full p-2 mt-2 border rounded-md" />
        <?php if (!empty($event['image'])): ?>
            <div class="mt-4">
                <p>Current Image:</p>
                <img src="<?php echo htmlspecialchars($event['image']); ?>" alt="Event Image" class="w-32 h-32 object-cover rounded-md">
            </div>
        <?php endif; ?>
    </div>

    <!-- ปุ่มยืนยัน -->
    <div class="flex justify-between mt-6">
      <button type="submit" class="bg-mainBlue text-white px-4 py-2 rounded-md hover:bg-hoverBlue transition">ยืนยันการแก้ไข</button>
      <button type="button" id="cancelEditButton" class="bg-gray-300 text-black px-4 py-2 rounded-md hover:bg-gray-400">ยกเลิก</button>
    </div>
  </form>
</section>

    <!-- แสดงปุ่มแก้ไข (ซ่อนปุ่มเพิ่ม/ลดจำนวนตั๋ว) -->
        <div class="flex justify-between items-center mt-5">
          <button id="editButton" 
            class="bg-white text-mainBlue px-4 py-2 rounded-2xl hover:bg-hoverBlue transition w-full">
            แก้ไข
          </button>
        </div>
    </div>
  </main>

  <footer class="bg-[#001a4d] text-white py-4 text-center">
    <p>&copy; 2025 Equarium. All rights reserved.</p>
  </footer>

  <script>
  document.addEventListener('DOMContentLoaded', function() {
    const editButton = document.getElementById('editButton');
    const ticketCountInput = document.getElementById('ticketCount');
    const confirmButton = document.getElementById('confirmButton');
    const editSection = document.getElementById('editSection');
    const cancelEditButton = document.getElementById('cancelEditButton');
    
    // แสดง/ซ่อนฟอร์มแก้ไข
    editButton.addEventListener('click', function() {
        editButton.classList.add('hidden'); // ซ่อนปุ่มแก้ไข
        editSection.style.display = 'block'; // แสดงฟอร์มแก้ไข
    });

    // ยกเลิกการแก้ไข
    cancelEditButton.addEventListener('click', function() {
        editButton.classList.remove('hidden'); // แสดงปุ่มแก้ไข
        editSection.style.display = 'none'; // ซ่อนฟอร์มแก้ไข
    });

    // ยืนยันการเลือก
    confirmButton.addEventListener('click', function() {
        const selectedCount = parseInt(ticketCountInput.value);
        
        if (isNaN(selectedCount) || selectedCount < 0) {
            alert("จำนวนตั๋วต้องมากกว่าหรือเท่ากับ 0");
            ticketCountInput.value = 0; // รีเซ็ตค่าเป็น 1
            return;
        }

        // ส่งไปที่ add_ticket.php พร้อมข้อมูลที่เลือก
        window.location.href = '../ticket/add_ticket.php?id=<?php echo $event['event_id']; ?>&count=' + selectedCount;
    });
});
</script>

</body>
</html>
