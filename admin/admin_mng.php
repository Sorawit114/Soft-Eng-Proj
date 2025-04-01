<?php
session_start();
include '../includes/navbar.php';

// Check if admin
if (!isset($_SESSION['session_id']) || $_SESSION['role'] !== 'a') {
    header("Location: ../homepage/aquarium.php");
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

// ดึงข้อมูลรีวิว
$sql_reviews = "SELECT r.*, u.username, e.name AS event_name 
                FROM review r
                JOIN users u ON r.user_id = u.id
                JOIN events e ON r.event_id = e.event_id";
$result_reviews = $conn->query($sql_reviews);

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

    // ฟังก์ชันสลับแท็บ
    function toggleTab(tab) {
        if (tab === 'users') {
            document.getElementById('users-tab').classList.remove('hidden');
            document.getElementById('reviews-tab').classList.add('hidden');
            document.getElementById('tab-users').classList.add('bg-gray-700', 'text-white');
            document.getElementById('tab-reviews').classList.remove('bg-gray-700', 'text-white');
            document.getElementById('tab-reviews').classList.add('bg-gray-200', 'text-gray-700');
        } else if (tab === 'reviews') {
            document.getElementById('reviews-tab').classList.remove('hidden');
            document.getElementById('users-tab').classList.add('hidden');
            document.getElementById('tab-reviews').classList.add('bg-gray-700', 'text-white');
            document.getElementById('tab-users').classList.remove('bg-gray-700', 'text-white');
            document.getElementById('tab-users').classList.add('bg-gray-200', 'text-gray-700');
        }
    }
  </script>
</head>
<body class="bg-mainBlue text-white font-poppins min-h-screen">

<header class="relative h-32 bg-center bg-cover bg-no-repeat" style="background-image: url('../image/jellyfish-aquarium-black-background-glowing-white-amoled-3840x2160-2094.jpg');">
    <div class="absolute top-5 left-5 z-50 flex items-center">
        <a href="../homepage/aquarium.php" class="text-white text-3xl font-bold">Equarium</a>
    </div>
</header>

<main class="max-w-5xl mx-auto p-6 bg-white text-black rounded-xl mt-10">
  <!-- Tabs -->
  <div class="flex">
    <button onclick="toggleTab('users')" id="tab-users" class="flex-1 px-4 py-3 text-white bg-gray-700 font-semibold">จัดการผู้ใช้</button>
    <button onclick="toggleTab('reviews')" id="tab-reviews" class="flex-1 px-4 py-3 bg-gray-200 text-gray-700 font-semibold">จัดการรีวิว</button>
  </div>


  <!-- Users -->
  <div id="users-tab" class="tab-content">
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

    <?php while ($row = $result->fetch_assoc()): ?>
      <div class="flex justify-between items-center bg-gray-200 px-6 py-4 mt-4 rounded">
        <div>
          <p class="font-bold"><?php echo htmlspecialchars($row['first_name']); ?></p>
          <p class="text-sm"><?php echo htmlspecialchars($row['email']); ?></p>
        </div>
        <div class="flex gap-3">
          <?php if ($row['position'] !== 'b'): ?>
            <button onclick="openRoleModal('<?php echo $row['id']; ?>', '<?php echo $row['position']; ?>')" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-1 rounded-full text-sm">Role</button>
            <button onclick="openBanModal('<?php echo $row['id']; ?>')" class="bg-red-400 hover:bg-red-500 text-white px-4 py-1 rounded-full text-sm">Ban</button>
          <?php else: ?>
            <form method="POST" action="update_role.php">
              <input type="hidden" name="action" value="unban">
              <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
              <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-1 rounded-full text-sm">Unban</button>
            </form>
          <?php endif; ?>
        </div>
      </div>
    <?php endwhile; ?>
  </div>

  <!-- Reviews Tab -->
  <div id="reviews-tab" class="tab-content hidden">
    <h2 class="text-2xl font-bold mb-6">จัดการรีวิว</h2>
    <?php while ($review = $result_reviews->fetch_assoc()): ?>
      <div class="flex justify-between items-center bg-gray-200 px-6 py-4 mt-4 rounded">
        <div>
          <p class="font-bold"><?php echo htmlspecialchars($review['username']); ?></p>
          <p class="text-sm"><?php echo htmlspecialchars($review['event_name']); ?></p>
          <p class="text-sm"><?php echo htmlspecialchars($review['content']); ?></p>
          <p class="text-sm">Rating: <?php echo htmlspecialchars($review['rating']); ?> ดาว</p>
        </div>
        <div class="flex gap-3">
          <form action="delete_review.php" method="POST">
            <input type="hidden" name="review_id" value="<?php echo $review['id']; ?>">
            <button class="bg-red-400 text-white px-4 py-1 rounded-full text-sm">Delete</button>
          </form>
        </div>
      </div>
    <?php endwhile; ?>
  </div>
</main>

<!-- Modal & Scripts -->
<div id="roleModal" class="hidden fixed inset-0 bg-gray-500 text-mainBlue bg-opacity-50 flex justify-center items-center">
    <div class="bg-white p-6 rounded-lg w-96">
        <h2 class="text-xl font-bold">Change Role</h2>
        <form action="update_role.php" method="POST">
            <input type="hidden" id="roleUserId" name="user_id">
            <input type="hidden" id="currentRole" name="current_role">
            
            <div class="mt-4">
                <label for="newRole">Current Role: <span id="currentRoleLabel" class="font-bold text-blue-500"></span></label>
            </div>
            
            <div class="mt-4">
                <label for="newRole">New Role:</label>
                <select id="newRole" name="new_role" class="border p-2 mt-2">
                    <option value="admin">Admin</option>
                    <option value="member">Member</option>
                </select>
            </div>
            
            <div class="mt-4 flex justify-end">
                <button type="button" onclick="closeRoleModal()" class="bg-gray-300 px-4 py-2 rounded-md">Close</button>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md ml-2">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal for Banning User -->
<div id="banModal" class="hidden fixed inset-0 bg-gray-500 text-mainBlue bg-opacity-50 flex justify-center items-center">
    <div class="bg-white p-6 rounded-lg w-96">
        <h2 class="text-xl font-bold">Ban User</h2>
        <form action="update_role.php" method="POST">
            <input type="hidden" id="banUserId" name="user_id">
            <input type="hidden" name="action" value="ban">

            <div class="mt-4">
                <p>Are you sure you want to ban this user?</p>
            </div>
            
            <div class="mt-4 flex justify-end">
                <button type="button" onclick="closeBanModal()" class="bg-gray-300 px-4 py-2 rounded-md">Close</button>
                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-md ml-2">Ban User</button>
            </div>
        </form>
    </div>
</div>
<script>
    function openRoleModal(id, currentRole) {
      document.getElementById('roleUserId').value = id;
      document.getElementById('currentRole').value = currentRole;
      document.getElementById('currentRoleLabel').textContent = currentRole;
      document.getElementById('roleModal').classList.remove('hidden');
    }
    function closeRoleModal() {
      document.getElementById('roleModal').classList.add('hidden');
    }

    function openBanModal(userId) {
      document.getElementById('banUserId').value = userId;
      document.getElementById('banModal').classList.remove('hidden');
    }

    function closeBanModal() {
        document.getElementById('banModal').classList.add('hidden');
    }
    
</script>

</body>
</html>
