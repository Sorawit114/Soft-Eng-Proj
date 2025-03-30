<?php
session_start();
include '../includes/navbar.php';

// ตรวจสอบว่าผู้ใช้ล็อกอินหรือยัง
if (!isset($_SESSION['session_id']) || !isset($_SESSION['id'])) {
    header("Location: ../home/aquarium.php");
    exit();
}

$user_id = $_SESSION['id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Review</title>
  <script src="https://kit.fontawesome.com/a076d05399.js"></script>
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

<header class="relative h-32 bg-center bg-cover bg-no-repeat" style="background-image: url('image/8929102.jpg');">
    <div class="absolute top-5 left-5 z-50 flex items-center">
      <a href="../home/aquarium.php" class="text-white text-xl font-bold">Equarium</a>
    </div>
  </header>

<main class="max-w-xl mx-auto py-10 px-6">
  <h2 class="text-3xl font-bold mb-6">My Review</h2>

  <form action="review_save.php" method="POST" class="space-y-4">
  <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">

  <label class="block">
    <span class="text-white font-semibold">ให้คะแนน (1-5 ดาว)</span>
    <div class="flex justify-start items-center">
      <!-- Star 1 -->
      <input id="star-1" type="radio" name="rating" value="1" class="hidden" />
      <label for="star-1" class="star w-8 h-8 cursor-pointer text-gray-300" data-rating="1">
        <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" fill="currentColor" viewBox="0 0 16 16">
          <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"></path>
        </svg>
      </label>

      <!-- Star 2 -->
      <input id="star-2" type="radio" name="rating" value="2" class="hidden" />
      <label for="star-2" class="star w-8 h-8 cursor-pointer text-gray-300" data-rating="2">
        <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" fill="currentColor" viewBox="0 0 16 16">
          <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"></path>
        </svg>
      </label>

      <!-- Star 3 -->
      <input id="star-3" type="radio" name="rating" value="3" class="hidden" />
      <label for="star-3" class="star w-8 h-8 cursor-pointer text-gray-300" data-rating="3">
        <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" fill="currentColor" viewBox="0 0 16 16">
          <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"></path>
        </svg>
      </label>

      <!-- Star 4 -->
      <input id="star-4" type="radio" name="rating" value="4" class="hidden" />
      <label for="star-4" class="star w-8 h-8 cursor-pointer text-gray-300" data-rating="4">
        <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" fill="currentColor" viewBox="0 0 16 16">
          <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"></path>
        </svg>
      </label>

      <!-- Star 5 -->
      <input id="star-5" type="radio" name="rating" value="5" class="hidden" />
      <label for="star-5" class="star w-8 h-8 cursor-pointer text-gray-300" data-rating="5">
        <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" fill="currentColor" viewBox="0 0 16 16">
          <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"></path>
        </svg>
      </label>
    </div>

    <!-- Hidden input to store the rating -->
    <input type="hidden" name="rating" id="ratingInput" required />
  </label>

  <label class="block">
    <span class="text-white font-semibold">เขียนความคิดเห็นของคุณ</span>
    <textarea name="content" rows="4" required class="w-full px-4 py-2 rounded text-black"></textarea>
  </label>

  <button type="submit" class="bg-white text-mainBlue font-semibold px-6 py-2 rounded hover:bg-gray-200">ส่งรีวิว</button>
</form>

<script>
  const stars = document.querySelectorAll('.star'); // Get all star elements
  const ratingInput = document.getElementById('ratingInput'); // Hidden input for rating
  let selectedRating = 0;

  stars.forEach(star => {
    star.addEventListener('click', () => {
      selectedRating = parseInt(star.getAttribute('data-rating')); // Get rating from data attribute
      updateStars(selectedRating);
      ratingInput.value = selectedRating; // Set the hidden input value
    });
  });

  function updateStars(rating) {
    stars.forEach((star, i) => {
      if (i < rating) {
        star.classList.add('text-yellow-500'); // Highlight selected stars
      } else {
        star.classList.remove('text-yellow-500'); // Remove highlight from non-selected stars
      }
    });
  }
</script>
</body>
</html>
