<!-- START: Top Navigation -->
<header class="flex justify-between items-center p-4 bg-white shadow-md">
    <!-- Mobile Menu Button -->
    <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 focus:outline-none md:hidden">
        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M4 6H20M4 12H20M4 18H20" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
        </svg>
    </button>
                
    <!-- Page Title -->
    <h1 class="text-xl font-semibold text-gray-700 ml-2 md:ml-0">
        <?php echo $data['title']; ?>
    </h1>

    <!-- Right side -->
    <div>
        <?php if(isLoggedIn()) : ?>
            <div class="flex items-center space-x-2 sm:space-x-4">
                <!-- ปุ่มจองห้อง -->
                <a href="<?php echo URLROOT; ?>/booking/create" class="bg-green-500 text-white font-bold py-2 px-3 sm:px-4 rounded-lg shadow-md hover:bg-green-600 transition whitespace-nowrap text-sm sm:text-base">
                    จองห้อง
                </a>
                
                <!-- ซ่อนข้อความสวัสดีบนจอมือถือ, แสดงเมื่อเป็น sm ขึ้นไป -->
                <span class="hidden sm:inline text-gray-700 whitespace-nowrap">สวัสดี, <?php echo explode(' ', $_SESSION['user_name'])[0]; ?></span>
                
                <a href="<?php echo URLROOT; ?>/user/logout" class="bg-red-500 text-white font-bold py-2 px-3 sm:px-4 rounded-lg shadow-md hover:bg-red-600 transition whitespace-nowrap text-sm sm:text-base">
                    ออกจากระบบ
                </a>
            </div>
        <?php else : ?>
            <a href="<?php echo URLROOT; ?>/user/login" class="bg-pink-500 text-white font-bold py-2 px-4 rounded-lg shadow-md hover:bg-pink-600 transition whitespace-nowrap">
                เข้าสู่ระบบ
            </a>
        <?php endif; ?>
    </div>
</header>
<!-- END: Top Navigation -->