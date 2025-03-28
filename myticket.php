<?php
session_start();
include 'navbar.php';

if (!isset($_SESSION['session_id'])) {
    header("Location: aquarium.php");
    die();
}

if (!isset($_SESSION['id'])) {
    die("User not found.");
}
$user_id = intval($_SESSION['id']);

$conn = new mysqli("localhost", "root", "", "aquarium");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sqlUser = "SELECT * FROM users WHERE id = ?";
$stmtUser = $conn->prepare($sqlUser);
$stmtUser->bind_param("i", $user_id);
$stmtUser->execute();
$resultUser = $stmtUser->get_result();
$user = $resultUser->fetch_assoc();
$stmtUser->close();

// ดึงเฉพาะตั๋วที่มี used = 'ยังไม่ได้ใช้งาน'
$sqlTickets = "SELECT t.*, e.name AS event_name, e.image AS event_image, e.location AS event_location 
               FROM ticket t 
               JOIN events e ON t.event_id = e.id
               WHERE t.user_id = ? AND (t.used = 'ยังไม่ได้ใช้งาน' OR t.used = 'ใช้งานแล้ว')
               ORDER BY t.created_at DESC";
$stmtTickets = $conn->prepare($sqlTickets);
$stmtTickets->bind_param("i", $user_id);
$stmtTickets->execute();
$resultTickets = $stmtTickets->get_result();
$tickets = [];
while ($row = $resultTickets->fetch_assoc()) {
    $tickets[] = $row;
}
$stmtTickets->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>My Orders</title>
  <!-- Tailwind CSS via CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Google Fonts: Poppins -->
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
<body class="font-poppins min-h-screen bg-mainBlue text-white">
  <!-- Header -->
  <header class="relative h-32 bg-center bg-cover bg-no-repeat" style="background-image: url('image/8929102.jpg');">

    <div class="absolute top-5 left-5 z-50 flex items-center">
      <a href="aquarium.php" class="text-white text-xl font-bold">Equarium</a>
    </div>
  </header>

  <!-- Main Container -->
  <main class="py-10 px-4">
    <!-- Flex Container กำหนด max-width และจัดกึ่งกลางด้วย mx-auto -->
    <div class="max-w-4xl mx-auto flex flex-col lg:flex-row justify-center items-start space-y-8 lg:space-y-0 lg:space-x-8">

      <!-- Left Column: User Profile -->
      <div class="bg-white text-black w-full max-w-md p-6 rounded-xl shadow-lg space-y-4 lg:space-y-6">
        <div class="flex flex-col items-center space-y-2">
          <!-- User Avatar -->
          <div class="mb-4 text-mainBlue">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0
                   A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0
                   3 3 0 0 1 6 0z" />
            </svg>
          </div>
          <!-- User Name -->
          <h2 class="text-xl font-semibold text-black">
            <?php echo htmlspecialchars($user['username']); ?>
          </h2>
          <!-- User Email -->
          <p class="text-gray-600">
            <?php echo htmlspecialchars($user['email']); ?>
          </p>
        </div>
        <hr class="my-6" />
        <!-- My Account Links -->
        <div class="mb-4">
          <h3 class="text-mainBlue font-semibold mb-2">My Account</h3>
          <ul class="space-y-2">
            <li>
            <a href="myorder.php" class="flex items-center text-gray-700 hover:text-mainBlue">
                <svg xmlns="http://www.w3.org/2000/svg" 
                     fill="none" viewBox="0 0 24 24" 
                     stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2 text-mainBlue">
                  <path stroke-linecap="round" stroke-linejoin="round"
                    d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 
                       1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 
                       0 0 1-1.12-1.243l1.264-12A1.125 1.125 
                       0 0 1 5.513 7.5h12.974c.576 0 1.059.435 
                       1.119 1.007ZM8.625 10.5a.375.375 0 
                       1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 
                       0a.375.375 0 1 1-.75 0 .375.375 
                       0 0 1 .75 0Z" />
                </svg>
                My Order
              </a>
            </li>
            <li>
              <a href="myticket.php" class="flex items-center text-gray-700 hover:text-mainBlue">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" 
                     viewBox="0 0 24 24" stroke-width="1.5" 
                     stroke="currentColor" class="w-5 h-5 mr-2 text-mainBlue">
                  <path stroke-linecap="round" stroke-linejoin="round" 
                    d="M16.5 6v.75m0 3v.75m0 3v.75m0 
                       3V18m-9-5.25h5.25M7.5 15h3M3.375 
                       5.25c-.621 0-1.125.504-1.125 
                       1.125v3.026a2.999 2.999 0 0 
                       1 0 5.198v3.026c0 .621.504 
                       1.125 1.125 1.125h17.25c.621 
                       0 1.125-.504 1.125-1.125v-3.026a2.999 
                       2.999 0 0 1 0-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375Z" />
                </svg>
                My Tickets
              </a>
            </li>
            <li>
              <a href="mngpro.php" class="flex items-center text-gray-700 hover:text-mainBlue">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" 
                     viewBox="0 0 24 24" stroke-width="1.5" 
                     stroke="currentColor" class="w-5 h-5 mr-2 text-mainBlue">
                  <path stroke-linecap="round" stroke-linejoin="round" 
                    d="M15 19.128a9.38 9.38 0 0 
                       0 2.625.372 9.337 9.337 0 0 
                       0 4.121-.952 4.125 4.125 0 
                       0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 
                       19.128v.106A12.318 12.318 0 0 
                       1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 
                       6.375 0 0 1 11.964-3.07M12 6.375a3.375 
                       3.375 0 1 1-6.75 0 3.375 
                       3.375 0 0 1 6.75 0Zm8.25 
                       2.25a2.625 2.625 0 1 1-5.25 
                       0 2.625 2.625 0 0 
                       1 5.25 0Z" />
                </svg>
                Manage Profile
              </a>
            </li>
            <li>
              <a href="pwd.php" class="flex items-center text-gray-700 hover:text-mainBlue">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" 
                     viewBox="0 0 24 24" stroke-width="1.5" 
                     stroke="currentColor" class="w-5 h-5 mr-2 text-mainBlue">
                  <path stroke-linecap="round" stroke-linejoin="round" 
                    d="M16.5 10.5V6.75a4.5 4.5 
                       0 1 0-9 0v3.75m-.75 
                       11.25h10.5a2.25 2.25 0 0 
                       0 2.25-2.25v-6.75a2.25 
                       2.25 0 0 0-2.25-2.25H6.75a2.25 
                       2.25 0 0 0-2.25 
                       2.25v6.75a2.25 2.25 
                       0 0 0 2.25 2.25Z" />
                </svg>
                Change Password
              </a>
            </li>
          </ul>
        </div>
        
        <hr class="my-6" />
        
        <!-- ส่วน Support -->
        <div>
          <h3 class="text-mainBlue font-semibold mb-2">Support</h3>
          <ul class="space-y-2">
            <li>
              <a href="issue.php" class="flex items-center text-gray-700 hover:text-mainBlue">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" 
                     viewBox="0 0 24 24" stroke-width="1.5" 
                     stroke="currentColor" class="w-5 h-5 mr-2 text-mainBlue">
                  <path stroke-linecap="round" stroke-linejoin="round"
                    d="M11.42 15.17 17.25 
                       21A2.652 2.652 0 0 0 
                       21 17.25l-5.877-5.877M11.42 
                       15.17l2.496-3.03c.317-.384.74-.626 
                       1.208-.766M11.42 15.17l-4.655 
                       5.653a2.548 2.548 0 1 1-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 
                       1.163-.188 1.743-.14a4.5 
                       4.5 0 0 0 4.486-6.336l-3.276 
                       3.277a3.004 3.004 0 0 1-2.25-2.25l3.276-3.276a4.5 
                       4.5 0 0 0-6.336 4.486c.091 
                       1.076-.071 2.264-.904 
                       2.95l-.102.085m-1.745 
                       1.437L5.909 7.5H4.5L2.25 
                       3.75l1.5-1.5L7.5 4.5v1.409l4.26 
                       4.26m-1.745 1.437 1.745-1.437m6.615 
                       8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008Z" />
                </svg>
                Report Issues
              </a>
            </li>
            <li>
              <a href="logout.php" class="flex items-center text-gray-700 hover:text-mainBlue">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" 
                     viewBox="0 0 24 24" stroke-width="1.5" 
                     stroke="currentColor" class="w-5 h-5 mr-2 text-mainBlue">
                  <path stroke-linecap="round" stroke-linejoin="round"
                    d="M15.75 9V5.25A2.25 2.25 
                       0 0 0 13.5 3h-6a2.25 2.25 
                       0 0 0-2.25 2.25v13.5A2.25 
                       2.25 0 0 0 7.5 21h6a2.25 
                       2.25 0 0 0 2.25-2.25V15m3 
                       0 3-3m0 0-3-3m3 3H9" />
                </svg>
                Logout
              </a>
            </li>
          </ul>
        </div>
      </div>
      
      <!-- Right Column: Ticket Details -->
      <div class="flex flex-col lg:w-full max-h-[600px] overflow-auto">
  <?php if (empty($tickets)): ?>
    <p class="text-white">ไม่มีการสั่งซื้อบัตร</p>
  <?php else: ?>
    <div class="space-y-10 w-full max-w-lg">
      <?php foreach ($tickets as $ticket): ?>
        <?php
        $status = htmlspecialchars($ticket['used']); // ดึงสถานะจากคอลัมน์ used

        // กำหนดสีตามสถานะ
        if ($status === "ใช้งานแล้ว") {
            $statusClass = "text-gray-500"; // สีเทา
        } else {
            $statusClass = "text-green-600"; // สีเขียวสำหรับ "ยังไม่ได้ใช้งาน"
        }
      ?>

        <!-- Ticket Card -->
        <div class="bg-white text-black rounded-xl shadow-md p-4 w-full">
          <div class="flex flex-col md:flex-row">
            <!-- Left Column: รูป event -->
            <div class="flex flex-col items-center md:w-1/3">
              <img src="<?php echo htmlspecialchars($ticket['event_image']); ?>" 
                   alt="<?php echo htmlspecialchars($ticket['event_name']); ?>" 
                   class="w-32 h-32 object-cover rounded-md" />
            </div>
            <!-- Right Column: รายละเอียดตั๋ว -->
            <div class="md:w-2/3 md:pl-6 mt-4 md:mt-0">
              <h2 class="text-2xl font-bold text-mainBlue mb-1">
                <?php echo htmlspecialchars($ticket['event_name']); ?>
              </h2>
              <p class="text-gray-700 mb-1">สถานที่: <?php echo htmlspecialchars($ticket['event_location']); ?></p>
              <p class="text-gray-700 mb-1"><?php echo htmlspecialchars($ticket['ticket_quantity']); ?> tickets</p>
              <p class="text-gray-700 mb-2">วันที่ชำระเงิน: <?php echo htmlspecialchars($ticket['ticket_date']); ?></p>
            </div>
          </div>
          <!-- เส้นแบ่ง (hr) -->
          <hr class="w-full my-2 border-t-2 border-black" />
          <!-- สถานะและปุ่ม Detail -->
          <div class="flex items-center justify-between mt-2">
            <p class="font-semibold">
              <span class="text-mainBlue">สถานะ:</span>
              <span class="<?php echo $statusClass; ?>">
                <?php echo $status; ?>
              </span>
            </p>
            <a href="ticket_detail.php?id=<?php echo $ticket['id']; ?>" 
               class="bg-mainBlue text-white px-4 py-2 rounded hover:bg-hoverBlue transition-colors">
              Detail
            </a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>

    </div>
  </main>
</body>
</html>

