<?php session_start() ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Equarium Activities</title>
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Google Fonts: Poppins -->
  <link
    href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap"
    rel="stylesheet"
  />
  <link rel="stylesheet" href="event_card.css">

  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            poppins: ["Poppins", "sans-serif"],
          },
          colors: {
            mainBlue: "#001a4d",
            hoverBlue: "#003080",
          },
        },
      },
    }
  </script>
</head>
<body class="font-poppins bg-mainBlue text-white min-h-screen">
  <!-- Header -->
  <header
    class="relative h-96 bg-fixed bg-center bg-cover bg-no-repeat"
    style="background-image: url('jellyfish-aquarium-black-background-glowing-white-amoled-3840x2160-2094.jpg');">
    <div class="absolute bottom-0 left-0 w-full h-16 bg-gradient-to-t from-[#001a4d] to-transparent"></div>
    <div class="absolute top-5 left-5 z-20 flex items-center">
      <a href="aquarium.php" class="text-white text-xl font-bold">Equarium</a>
    </div>
    <nav class="absolute top-4 right-8 z-50 flex space-x-6 text-white font-semibold text-lg">
      <div class="flex justify-end space-x-4 mt-4 relative z-10">
        <!-- ตัวอย่าง session check -->
    <?php if (isset($_SESSION['session_id'])): ?>
          <a href="user.php" class="inline-flex items-center space-x-2 text-lg hover:underline">
            <span><?php echo htmlspecialchars($_SESSION['user']); ?></span>
          </a>
        <?php else: ?>
          <a href="register.php" class="text-lg hover:underline">Register</a>
          <a href="login.php" class="text-lg hover:underline">Sign In</a>
        <?php endif; ?>
      </div>
    </nav>
  </header>

  <!-- Search Bar -->
  <div class="flex justify-center mt-4">
    <div class="bg-white text-[#001a4d] px-4 py-2 inline-flex items-center space-x-4
         rounded-lg max-w-2xl w-auto mx-auto shadow">
      <!-- Activity -->
      <div class="flex items-center space-x-1">
        <span class="font-semibold">Activity:</span>
        <select
          class="bg-transparent border-b border-[#001a4d] focus:outline-none focus:border-blue-500
                 appearance-none px-1"
        >
          <option value="">all</option>
          <option value="scuba">ชมสัตว์น้ำ</option>
          <option value="snorkel">ให้อาหารสัตว์น้ำ</option>
          <option value="show">การแสดงสัตว์น้ำ</option>
        </select>
        <svg
          xmlns="http://www.w3.org/2000/svg"
          class="h-4 w-4"
          fill="none"
          viewBox="0 0 24 24"
          stroke="currentColor"
        >
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M19 9l-7 7-7-7" />
        </svg>
      </div>

      <!-- Province -->
      <div class="flex items-center space-x-1">
        <span class="font-semibold">Province:</span>
        <select
          class="bg-transparent border-b border-[#001a4d] focus:outline-none focus:border-blue-500
                 appearance-none px-1"
        >
          <option value="">all</option>
          <option value="bangkok">Bangkok</option>
          <option value="phuket">Phuket</option>
          <option value="chiangmai">Chiang Mai</option>
        </select>
        <svg
          xmlns="http://www.w3.org/2000/svg"
          class="h-4 w-4"
          fill="none"
          viewBox="0 0 24 24"
          stroke="currentColor"
        >
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M19 9l-7 7-7-7" />
        </svg>
      </div>

      <!-- Price -->
      <div class="flex items-center space-x-1">
        <span class="font-semibold">Price:</span>
        <select
          class="bg-transparent border-b border-[#001a4d] focus:outline-none focus:border-blue-500
                 appearance-none px-1"
        >
          <option value="">all</option>
          <option value="low">Low to High</option>
          <option value="high">High to Low</option>
        </select>
        <svg
          xmlns="http://www.w3.org/2000/svg"
          class="h-4 w-4"
          fill="none"
          viewBox="0 0 24 24"
          stroke="currentColor"
        >
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M19 9l-7 7-7-7" />
        </svg>
      </div>

      <!-- ปุ่มไอคอนค้นหา -->
      <button class="ml-2 text-[#001a4d] hover:text-blue-700">
        <svg
          xmlns="http://www.w3.org/2000/svg"
          class="h-5 w-5"
          fill="none"
          viewBox="0 0 24 24"
          stroke="currentColor"
        >
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M21 21l-4.35-4.35m0 0A7.5 7.5
                   0 1 0 5.25 5.25a7.5 7.5
                   0 0 0 11.4 11.4z" />
        </svg>
      </button>
    </div>
  </div>

  <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'a'): ?>
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
    
    <!-- Card 1 -->
    <div class="event-card">
      <img src="pexels-nguyen-tran-327588-1703516.jpg" alt="Sealive" class="event-card__image">
      <div class="event-card__body">
        <p class="location-icon">Location: Phuket, Thailand</p>
        <h3 class="event-card__title">Sealive</h3>
        <p class="event-card__text">Activity: แมงกระพรุน</p>
        <div class="event-card__buttons">
          <button class="event-card__button">Review</button>
          <button class="event-card__button">Book</button>
          <button class="event-card__button">Detail</button>
        </div>
      </div>
    </div>
    
    <!-- Card 2 -->
    <div class="event-card">
      <img src="pexels-pixabay-34809.jpg" alt="Aquaria Phuket" class="event-card__image">
      <div class="event-card__body">
        <p class="location-icon">Location: Phuket, Thailand</p>
        <h3 class="event-card__title">Aquaria Phuket</h3>
        <p class="event-card__text">Activity: โชว์โลมา</p>
        <div class="event-card__buttons">
          <button class="event-card__button">Review</button>
          <button class="event-card__button">Book</button>
          <button class="event-card__button">Detail</button>
        </div>
      </div>
    </div>

      <!-- Card 3 -->
      <div class="bg-white text-black rounded-lg shadow-md overflow-hidden flex flex-col">
        <div class="h-48">
          <img src="pexels-matej-bizjak-2148520448-30417733.jpg" alt="Sealive" class="w-full h-full object-cover">
        </div>
        <div class="p-4 flex flex-col flex-1">
          <p class="location-icon">Location: Phuket, Thailand</p>
          <h3 class="text-xl font-bold text-[#001a4d] mb-2">Sealive</h3>
          <p class="mb-3 text-gray-700">Activity: อุโมงค์สัตว์น้ำ</p>
          <div class="mt-auto flex flex-wrap gap-2">
            <button class="bg-[#001a4d] text-white px-3 py-1 rounded hover:bg-hoverBlue">Review</button>
            <button class="bg-[#001a4d] text-white px-3 py-1 rounded hover:bg-hoverBlue">Book</button>
            <button class="bg-[#001a4d] text-white px-3 py-1 rounded hover:bg-hoverBlue">Detail</button>
          </div>
        </div>
      </div>

      <!-- Card 4 -->
      <div class="bg-white text-black rounded-lg shadow-md overflow-hidden flex flex-col">
        <div class="h-48">
          <img src="jellyfish-aquarium-black-background-glowing-white-amoled-3840x2160-2094.jpg" alt="Aquaria Phuket" class="w-full h-full object-cover">
        </div>
        <div class="p-4 flex flex-col flex-1">
          <p class="location-icon">Location: Phuket, Thailand</p>
          <h3 class="text-xl font-bold text-[#001a4d] mb-2">Aquaria Phuket</h3>
          <p class="mb-3 text-gray-700">Activity: แมงกระพรุนเรืองแสง</p>
          <div class="mt-auto flex flex-wrap gap-2">
            <button class="bg-[#001a4d] text-white px-3 py-1 rounded hover:bg-hoverBlue">Review</button>
            <button class="bg-[#001a4d] text-white px-3 py-1 rounded hover:bg-hoverBlue">Book</button>
            <button class="bg-[#001a4d] text-white px-3 py-1 rounded hover:bg-hoverBlue">Detail</button>
          </div>
        </div>
      </div>

    </div>
  </div>

  

  <!-- Footer -->
  <footer class="bg-mainBlue text-white py-4 text-center">
    <p>&copy; 2025 Equarium. All rights reserved.</p>
  </footer>
</body>
</html>
