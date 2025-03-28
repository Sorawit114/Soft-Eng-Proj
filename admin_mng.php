<?php
session_start();
include 'navbar.php';

// Check if admin
if (!isset($_SESSION['session_id']) || $_SESSION['role'] !== 'a') {
    header("Location: aquarium.php");
    exit;
}

// Connect DB
$conn = new mysqli("localhost", "root", "", "aquarium");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$search = $_GET['search'] ?? '';
$filter = $_GET['filter'] ?? '';

// ทำให้ค่าเป็น string ป้องกัน SQL Injection
$search = $conn->real_escape_string($search);

// กำหนดเงื่อนไขการค้นหาตาม filter
$sql = "SELECT id, first_name, username, email, position FROM users WHERE 1=1";

// ถ้ามีการค้นหาผู้ใช้ (username หรือ email)
if (!empty($search)) {
    $sql .= " AND (username LIKE '%$search%' OR email LIKE '%$search%')";
}

// ถ้ามีการเลือก filter
if (!empty($filter)) {
    if ($filter === 'ban') {
        $sql .= " AND position = 'b'";
    } elseif ($filter === 'member') {
        $sql .= " AND position = 'm'";
    } elseif ($filter === 'admin') {
        $sql .= " AND position = 'a'";
    }
}

// ดึงข้อมูลผู้ใช้จากฐานข้อมูล
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Admin Management</title>
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
<body class="bg-mainBlue text-white font-poppins min-h-screen">
<header
  class="relative h-32 bg-center bg-cover bg-no-repeat"
  style="background-image: url('image/jellyfish-aquarium-black-background-glowing-white-amoled-3840x2160-2094.jpg');"
>

  <!-- ลิงก์ Aquarium มุมบนซ้าย -->
  <div class="absolute top-5 left-5 z-50 flex items-center">
    <a href="aquarium.php" class="text-white text-xl font-bold">Equarium</a>
  </div>

  </header>
<main class="max-w-5xl mx-auto p-6 bg-white text-black rounded-xl mt-10">
  <!-- Tabs -->
  <div class="flex">
    <button onclick="toggleTab('users')" id="tab-users" class="flex-1 px-4 py-3 text-white bg-gray-700 font-semibold">จัดการผู้ใช้</button>
    <button onclick="toggleTab('reviews')" id="tab-reviews" class="flex-1 px-4 py-3 bg-gray-200 text-gray-700 font-semibold">จัดการรีวิว</button>
  </div>

    <!-- ฟอร์มค้นหาผู้ใช้ -->
    <form class="flex gap-4 my-6" method="GET">
        <input type="text" name="search" placeholder="ค้นหาผู้ใช้..." value="<?= htmlspecialchars($search) ?>" class="px-4 py-2 rounded border w-full">
        
        <select name="filter" class="px-3 py-2 rounded border">
            <option value="" <?= empty($filter) ? 'selected' : '' ?>>ทั้งหมด</option>
            <option value="member" <?= $filter === 'member' ? 'selected' : '' ?>>Member</option>
            <option value="admin" <?= $filter === 'admin' ? 'selected' : '' ?>>Admin</option>
            <option value="ban" <?= $filter === 'ban' ? 'selected' : '' ?>>Ban</option>
        </select>
        
        <button type="submit" class="bg-mainBlue text-white px-4 rounded">ค้นหา</button>
    </form> 

  <!-- Users -->
  <?php while ($row = $result->fetch_assoc()): ?>
  <div class="flex justify-between items-center bg-gray-200 px-6 py-4 mt-4 rounded">
    <div>
      <!-- แสดงชื่อผู้ใช้และอีเมล์ -->
      <p class="font-bold"><?php echo htmlspecialchars($row['first_name']); ?></p>
      <p class="text-sm"><?php echo htmlspecialchars($row['email']); ?></p>
    </div>
    <div class="flex gap-3">
      <?php if ($row['position'] !== 'b'): ?>
        <!-- หากยังไม่ถูกแบน, ให้แสดงปุ่มเปลี่ยน role และ ban -->
        <button onclick="openRoleModal('<?php echo $row['id']; ?>', '<?php echo $row['position']; ?>')" 
                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-1 rounded-full text-sm">
          Role
        </button>

        <button onclick="openBanModal('<?php echo $row['id']; ?>')" 
                class="bg-red-400 hover:bg-red-500 text-white px-4 py-1 rounded-full text-sm">
          Ban
        </button>
      <?php else: ?>
        <!-- หากถูกแบน, ให้แสดงปุ่ม unban -->
        <form method="POST" action="update_role.php">
          <input type="hidden" name="action" value="unban">
          <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
          <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-1 rounded-full text-sm">Unban</button>
        </form>
      <?php endif; ?>
    </div>
  </div>
  <?php endwhile; ?>
</main>

<!-- Role Modal -->
<div id="roleModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
  <div class="bg-white text-black rounded-lg p-6 w-80">
    <h2 class="font-semibold text-lg mb-4">เปลี่ยน Role</h2>
    <form method="POST" action="update_role.php">
      <input type="hidden" name="action" value="update">
      <input type="hidden" name="user_id" id="roleUserId" />

      <label for="new_role" class="block mb-2 font-semibold">เลือก Role:</label>
      <select name="new_role" id="currentRole" class="w-full mb-4 border p-2 rounded">
        <option value="m">Member</option>
        <option value="a">Admin</option>
      </select>

      <div class="flex justify-end space-x-3">
        <button type="button" onclick="closeRoleModal()" class="px-4 py-2 text-gray-600">ยกเลิก</button>
        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">ยืนยัน</button>
      </div>
    </form>
  </div>
</div>

<!-- Ban Modal -->
<div id="banModal" class="flex fixed inset-0 bg-black bg-opacity-40 hidden justify-center items-center z-50">
    <div class="bg-white text-black rounded-md p-6 w-full max-w-sm">
        <h2 class="text-xl font-semibold mb-4">ยืนยันการแบนผู้ใช้</h2>
        <p class="mb-4">ต้องการที่จะทำสิ่งนี้หรือไม่?</p>
        <form method="POST" action="update_role.php">
            <input type="hidden" name="action" value="ban">
            <input type="hidden" name="user_id" id="banUserId">
            <input type="hidden" name="position" value="ban">
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeBanModal()" class="text-gray-500">ไม่</button>
                <button type="submit" class="bg-red-500 text-white px-4 py-1 rounded">ใช่</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openRoleModal(id, currentRole) {
      document.getElementById('roleUserId').value = id;
      document.getElementById('currentRole').value = currentRole;
      document.getElementById('roleModal').classList.remove('hidden');
    }
    function closeRoleModal() {
      document.getElementById('roleModal').classList.add('hidden');
    }

    function openBanModal(userId) {
    document.getElementById('banUserId').value = userId;  // ตั้งค่า user_id
    document.getElementById('banModal').classList.remove('hidden');  // เปิด modal
    }

    function closeBanModal() {
        document.getElementById('banModal').classList.add('hidden');  // ซ่อน modal
    }
</script>

</body>
</html>
