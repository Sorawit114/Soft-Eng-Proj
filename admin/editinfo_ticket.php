<?php
// เชื่อมต่อฐานข้อมูล
include 'navbar.php';

if (!isset($_SESSION['session_id']) || $_SESSION['role'] !== 'a') {
    // ถ้าไม่มี session_id หรือ role ไม่ใช่ 'a' ให้ redirect ไปหน้า home
    header("Location: aquarium.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "aquarium");
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
    <title>Buy Tickets</title>
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
    <header class="relative h-32 bg-center bg-cover bg-no-repeat" style="background-image: url('image/jellyfish-aquarium-black-background-glowing-white-amoled-3840x2160-2094.jpg');">
        <div class="absolute top-5 left-5 z-50 flex items-center">
            <a href="aquarium.php" class="text-white text-xl font-bold">Equarium</a>
        </div>
    </header>

    <main class="max-w-5xl mx-auto px-6 py-8">
        <h2 class="text-3xl font-semibold text-white mb-6">รายการอีเว้นต์</h2>
        <div class="space-y-4">
            <?php while ($event = $result->fetch_assoc()): ?>
                <div class="flex items-center bg-white p-4 rounded-lg shadow-lg">
                    <img src="<?php echo htmlspecialchars($event['image']); ?>" alt="<?php echo htmlspecialchars($event['name']); ?>" class="w-32 h-32 object-cover rounded-lg mr-4">
                    <div class="flex-1">
                        <h3 class="text-xl font-semibold text-black"><?php echo htmlspecialchars($event['name']); ?></h3>
                    </div>
                    <div>
                        <a href="edit_event.php?id=<?php echo $event['event_id']; ?>" class="text-white bg-blue-500 hover:bg-blue-600 px-4 py-2 rounded-full">แก้ไข</a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </main>
</body>
</html>
<?php $conn->close(); ?>
