<?php
session_start();
// เชื่อมต่อฐานข้อมูล
include '../includes/navbar.php';

if (!isset($_SESSION['session_id']) || $_SESSION['role'] !== 'a') {
    header("Location:  ../homepage/aquarium.php");
    exit();
}

$conn = new mysqli('mysql', 'user', 'password', 'aquarium');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT event_id, name, image FROM events";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit</title>
    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts: Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
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
        };
    </script>
</head>
<body class="bg-mainBlue text-white">
    <header class="relative h-32 bg-center bg-cover bg-no-repeat" style="background-image: url('../image/jellyfish-aquarium-black-background-glowing-white-amoled-3840x2160-2094.jpg');">
        <div class="absolute top-5 left-5 z-50 flex items-center">
            <a href="../homepage/aquarium.php" class="text-white text-3xl font-bold">Equarium</a>
        </div>
    </header>

    <main class="max-w-5xl mx-auto px-6 py-8">
        <h2 class="text-3xl font-semibold text-white mb-6">รายการอีเว้นต์</h2>
        <div class="flex justify-center mt-10 my-10">
      <button id="openModal" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow">
        Add Event
      </button>
    </div>

  <!-- Modal: ซ่อนอยู่โดยค่าเริ่มต้น -->
  <div id="modal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white text-black rounded-lg shadow-lg w-full max-w-lg p-6">
        <h2 class="text-2xl font-bold mb-4">Add New Event</h2>
        <form action="../event/add_event.php" method="POST" enctype="multipart/form-data">
            <!-- Event Name -->
            <div class="mb-4">
                <label for="name" class="block font-semibold mb-1">Event Name</label>
                <input type="text" id="name" name="name" class="w-full p-2 border rounded" placeholder="Event Name" required>
            </div>

            <!-- Image -->
            <div class="mb-4">
                <label for="image" class="block font-semibold mb-1">Image File</label>
                <input type="file" id="image" name="image" class="w-full p-2 border rounded" required>
            </div>

            <!-- Location -->
            <div class="mb-4">
                <label for="location" class="block font-semibold mb-1">Location</label>
                <input type="text" id="location" name="location" class="w-full p-2 border rounded" placeholder="Location" required>
            </div>

            <!-- Activity (Checkboxes for multiple selection) -->
            <div class="mb-4">
                <label class="block font-semibold mb-1">Activity</label>
                <div class="flex space-x-4">
                    <label>
                        <input type="checkbox" name="activity[]" value="ชมสัตว์น้ำ" class="mr-2">ชมสัตว์น้ำ
                    </label>
                    <label>
                        <input type="checkbox" name="activity[]" value="โชว์ให้อาหารสัตว์น้ำ" class="mr-2">โชว์ให้อาหารสัตว์น้ำ
                    </label>
                    <label>
                        <input type="checkbox" name="activity[]" value="การแสดงสัตว์น้ำ" class="mr-2">การแสดงสัตว์น้ำ
                    </label>
                </div>
            </div>

            <!-- Price -->
            <div class="mb-4">
                <label for="price" class="block font-semibold mb-1">Price</label>
                <input type="number" step="0.01" id="price" name="price" class="w-full p-2 border rounded" placeholder="Price" required>
            </div>

            <!-- Event Description -->
            <div class="mb-4">
                <label for="description" class="block font-semibold mb-1">Event Description</label>
                <textarea id="description" name="description" class="w-full p-2 border rounded" placeholder="Event Description" required></textarea>
            </div>

            <!-- Buttons for Submit and Cancel -->
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
        <div class="space-y-4">
            <?php while ($event = $result->fetch_assoc()): ?>
                <div class="flex items-center bg-white p-4 rounded-lg shadow-lg ">
                    <img src="<?php echo htmlspecialchars($event['image']); ?>" alt="<?php echo htmlspecialchars($event['name']); ?>" class="w-32 h-32 object-cover rounded-lg mr-4">
                    <div class="flex-1">
                        <h3 class="text-xl font-semibold text-black"><?php echo htmlspecialchars($event['name']); ?></h3>
                    </div>
                    <div class="flex flex-row space-x-2 px-5">
                        <button type="button" onclick="window.location.href='edit_event.php?id=<?php echo $event['event_id']; ?>'" class="w-32 h-10 text-white bg-blue-500 hover:bg-blue-600 px-4 py-2 rounded-full">
                            แก้ไข
                        </button>
                        <form action="../event/delete-event.php" method="POST" onsubmit="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบอีเว้นต์นี้?');">
                            <input type="hidden" name="event_id" value="<?php echo $event['event_id']; ?>">
                            <button type="submit" class="text-white bg-red-500 hover:bg-red-600 px-4 py-2 rounded-full w-32">ลบ</button>
                    </form>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </main>
</body>
</html>
<?php $conn->close(); ?>

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