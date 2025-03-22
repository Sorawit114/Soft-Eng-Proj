<?php ?>
<nav class="absolute top-4 inset-x-0 z-50 px-4">
      <div class="flex justify-end space-x-6 text-white font-semibold text-lg relative z-10">
        <?php if (isset($_SESSION['session_id'])): ?>
          <button id="dropdownButton" type="button" class="inline-flex justify-center px-4 py-2 text-xl font-medium text-white focus:outline-none" onclick="toggleDropdown()">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
              <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
            </svg>&nbsp;
            <span><?php echo htmlspecialchars($_SESSION['user']); ?></span>
          </button>
          <div id="dropdownMenu" class="origin-top-right absolute right-0 top-full mt-2 w-56 rounded-md shadow-lg bg-white hidden">
            <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="dropdownButton">
              <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'a'): ?>
                <a href="manager.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-hoverBlue hover:text-white" role="menuitem">Manager</a>
                <a href="check_payment.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-hoverBlue hover:text-white" role="menuitem">ตรวจสอบการชำระ</a>
                <a href="analysis.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-hoverBlue hover:text-white" role="menuitem">Analysis</a>
                <a href="editinfo_ticket.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-hoverBlue hover:text-white" role="menuitem">Edit Info & Ticket</a>
                <a href="logout.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-hoverBlue hover:text-white" role="menuitem">Sign Out</a>
              <?php else: ?>
                <a href="user.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-hoverBlue hover:text-white" role="menuitem">User</a>
                <a href="my_ticket.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-hoverBlue hover:text-white" role="menuitem">My Ticket</a>
                <a href="logout.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-hoverBlue hover:text-white" role="menuitem">Sign Out</a>
              <?php endif; ?>
            </div>
          </div>
        <?php else: ?>
          <a href="register.php" class="text-lg hover:underline">Register</a>
          <a href="login.php" class="text-lg hover:underline">Sign In</a>
        <?php endif; ?>
      </div>
    </nav>
    <script>
  function toggleDropdown() {
    const dropdown = document.getElementById('dropdownMenu');
    dropdown.classList.toggle('hidden');
  }
  // ปิด dropdown เมื่อคลิกที่นอกปุ่ม
  window.addEventListener('click', function(e) {
    const dropdown = document.getElementById('dropdownMenu');
    const button = document.getElementById('dropdownButton');
    if (button && !button.contains(e.target)) {
      dropdown.classList.add('hidden');
    }
  });
</script>