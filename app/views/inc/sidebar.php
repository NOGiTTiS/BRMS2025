<!-- START: Sidebar -->
<aside id="sidebar"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    class="absolute inset-y-0 left-0 text-white w-64 p-4 space-y-6 z-20
           transform transition duration-300 ease-in-out
           md:relative md:translate-x-0 flex-shrink-0"
    style="background-color: <?php echo setting('sidebar_color', '#DB2777'); ?>">

    <!-- Logo -->
    <a href="<?php echo URLROOT; ?>" class="text-white text-2xl font-bold flex items-center space-x-2">
        <img src="<?php echo URLROOT; ?>/uploads/logos/<?php echo setting('site_logo'); ?>" alt="Logo" class="h-8">
        <span><?php echo setting('site_name'); ?></span>
    </a>

    <!-- Navigation Links -->
    <nav>
        <ul>
            <?php if(isLoggedIn()) : ?>
                <!-- เมนูหลัง Login -->
                <li class="mb-2">
                    <a href="<?php echo URLROOT; ?>/dashboard" 
                    class="flex items-center p-2 hover:bg-pink-700 rounded transition-colors whitespace-nowrap <?php echo (isset($data['active_menu']) && $data['active_menu'] == 'dashboard') ? 'active-menu' : ''; ?>">
                        <span>📊</span><span class="ml-2">Dashboard</span>
                    </a>
                </li>
                
                <!-- เมนูสำหรับผู้ใช้ทั่วไป -->
                <li class="mb-2">
                    <a href="<?php echo URLROOT; ?>/mybooking" 
                    class="flex items-center p-2 hover:bg-pink-700 rounded transition-colors whitespace-nowrap <?php echo (isset($data['active_menu']) && $data['active_menu'] == 'my_bookings') ? 'active-menu' : ''; ?>">
                        <span>📖</span><span class="ml-2">การจองของฉัน</span>
                    </a>
                </li>

                <!-- เมนูสำหรับ Admin เท่านั้น -->
                <?php if($_SESSION['user_role'] == 'admin') : ?>
                <li class="mb-2">
                    <a href="<?php echo URLROOT; ?>/user" class="flex items-center p-2 hover:bg-pink-700 rounded transition-colors whitespace-nowrap <?php echo ($data['active_menu'] == 'users') ? 'active-menu' : ''; ?>">
                        <span>👥</span><span class="ml-2">จัดการผู้ใช้</span>
                    </a>
                </li>    
                <li class="mb-2">
                    <a href="<?php echo URLROOT; ?>/room" 
                    class="flex items-center p-2 hover:bg-pink-700 rounded transition-colors whitespace-nowrap <?php echo (isset($data['active_menu']) && $data['active_menu'] == 'rooms') ? 'active-menu' : ''; ?>">
                        <span>🏢</span><span class="ml-2">จัดการห้องประชุม</span>
                    </a>
                </li>
                <li class="mb-2">
                    <a href="<?php echo URLROOT; ?>/booking" 
                    class="flex items-center p-2 hover:bg-pink-700 rounded transition-colors whitespace-nowrap <?php echo (isset($data['active_menu']) && $data['active_menu'] == 'manage_bookings') ? 'active-menu' : ''; ?>">
                        <span>📋</span><span class="ml-2">จัดการการจอง</span>
                    </a>
                </li>
                <li class="mb-2">
                    <a href="<?php echo URLROOT; ?>/setting" class="flex items-center p-2 hover:bg-pink-700 rounded transition-colors whitespace-nowrap <?php echo ($data['active_menu'] == 'settings') ? 'active-menu' : ''; ?>">
                        <span>⚙️</span><span class="ml-2">ตั้งค่าระบบ</span>
                    </a>
                </li>
                <?php endif; ?>

                <li class="mb-2">
                    <a href="<?php echo URLROOT; ?>/page/calendar" class="flex items-center p-2 hover:bg-pink-700 rounded transition-colors whitespace-nowrap <?php echo (isset($data['active_menu']) && $data['active_menu'] == 'calendar') ? 'active-menu' : ''; ?>">
                        <span>📅</span><span class="ml-2">ปฏิทิน</span>
                    </a>
                </li>
                <li class="mb-2">
                    <a href="<?php echo URLROOT; ?>/user/logout" 
                    class="flex items-center p-2 hover:bg-pink-700 rounded transition-colors whitespace-nowrap">
                        <span>🚪</span><span class="ml-2">ออกจากระบบ</span>
                    </a>
                </li>
            <?php else : ?>
                <!-- เมนูก่อน Login -->
                <li class="mb-2">
                    <a href="<?php echo URLROOT; ?>" 
                    class="flex items-center p-2 hover:bg-pink-700 rounded transition-colors whitespace-nowrap <?php echo (isset($data['active_menu']) && $data['active_menu'] == 'calendar') ? 'active-menu' : ''; ?>">
                        <span>📅</span><span class="ml-2">ปฏิทิน</span>
                    </a>
                </li>
                <li class="mb-2">
                    <a href="<?php echo URLROOT; ?>/user/login" 
                    class="flex items-center p-2 hover:bg-pink-700 rounded transition-colors whitespace-nowrap">
                        <span>🔒</span><span class="ml-2">เข้าสู่ระบบ</span>
                    </a>
                </li>
                <li class="mb-2">
                    <a href="<?php echo URLROOT; ?>/user/register" 
                    class="flex items-center p-2 hover:bg-pink-700 rounded transition-colors whitespace-nowrap">
                        <span>👤</span><span class="ml-2">สมัครสมาชิก</span>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>

</aside>
<!-- END: Sidebar -->
