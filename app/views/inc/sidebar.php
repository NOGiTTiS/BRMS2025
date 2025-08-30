<!-- START: Sidebar -->
<aside id="sidebar"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    class="absolute inset-y-0 left-0 bg-pink-600 text-white w-64 p-4 space-y-6 z-20
           transform transition duration-300 ease-in-out
           md:relative md:translate-x-0 flex-shrink-0">

    <!-- Logo -->
    <a href="<?php echo URLROOT; ?>" class="text-white text-2xl font-bold flex items-center space-x-2">
        <span>BRMS</span>
    </a>

    <!-- Navigation Links -->
    <nav>
        <ul>
            <?php if(isLoggedIn()) : ?>
                <!-- เมนูหลัง Login -->
                <li class="mb-2">
                    <a href="<?php echo URLROOT; ?>/dashboard" 
                    class="flex items-center p-2 hover:bg-pink-700 rounded transition-colors whitespace-nowrap <?php echo (isset($data['active_menu']) && $data['active_menu'] == 'dashboard') ? 'bg-pink-700' : ''; ?>">
                        <span>📊</span><span class="ml-2">Dashboard</span>
                    </a>
                </li>
                <li class="mb-2">
                    <a href="<?php echo URLROOT; ?>" 
                    class="flex items-center p-2 hover:bg-pink-700 rounded transition-colors whitespace-nowrap <?php echo (isset($data['active_menu']) && $data['active_menu'] == 'calendar') ? 'bg-pink-700' : ''; ?>">
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
                    class="flex items-center p-2 hover:bg-pink-700 rounded transition-colors whitespace-nowrap <?php echo (isset($data['active_menu']) && $data['active_menu'] == 'calendar') ? 'bg-pink-700' : ''; ?>">
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
