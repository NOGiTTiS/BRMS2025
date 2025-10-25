<?php require APPROOT . '/views/inc/header.php'; ?>
<div x-data="{ sidebarOpen: false }" class="relative md:flex min-h-full">

    <!-- Overlay for mobile -->
    <div x-show="sidebarOpen" 
         @click="sidebarOpen = false" 
         class="fixed inset-0 bg-black opacity-50 z-10 md:hidden"
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-50"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-50"
         x-transition:leave-end="opacity-0">
    </div>

    <!-- Sidebar -->
    <?php include APPROOT . '/views/inc/sidebar.php'; ?>

    <!-- Main Content Area -->
    <div class="flex flex-col flex-1 md:w-0">

        <!-- Top Navigation -->
        <?php include APPROOT . '/views/inc/topnav.php'; ?>

        <!-- Page Content Wrapper -->
        <main class="flex-1 overflow-y-auto p-4 md:p-8">
            
            <?php flash('setting_message'); ?>

            <div class="bg-white p-6 md:p-8 rounded-lg shadow-md max-w-4xl mx-auto">
                <form action="<?php echo URLROOT; ?>/setting" method="post" enctype="multipart/form-data">
                    <!-- General Settings -->
                    <div class="space-y-6">
                        <div>
                            <label for="site_name" class="block text-gray-700 font-semibold">ชื่อระบบ</label>
                            <input type="text" name="site_name" class="w-full mt-1 px-3 py-2 border rounded" value="<?php echo htmlspecialchars($data['settings']['site_name'] ?? ''); ?>">
                        </div>
                        <div>
                            <label for="public_url" class="block text-gray-700 font-semibold">Public URL</label>
                            <input type="text" name="public_url" class="w-full mt-1 px-3 py-2 border rounded" value="<?php echo htmlspecialchars($data['settings']['public_url'] ?? URLROOT); ?>">
                            <p class="text-xs text-gray-500 mt-1">URL ที่สามารถเข้าถึงได้จากภายนอก สำหรับใช้ในลิงก์แจ้งเตือน</p>
                        </div>
                        <div>
                            <label for="copyright_text" class="block text-gray-700 font-semibold">ข้อความ Copyright</label>
                            <input type="text" name="copyright_text" class="w-full mt-1 px-3 py-2 border rounded" value="<?php echo htmlspecialchars($data['settings']['copyright_text'] ?? ''); ?>">
                            <p class="text-xs text-gray-500 mt-1">ใช้ `{year}` เพื่อแสดงปีปัจจุบันโดยอัตโนมัติ</p>
                        </div>
                    </div>

                    <hr class="my-8">

                    <!-- File Uploads & Color -->
                    <div class="space-y-6">
                        <div>
                            <label class="block text-gray-700 font-semibold">โลโก้ระบบ</label>
                            <?php if(!empty($data['settings']['site_logo'])): ?>
                                <img src="<?php echo URLROOT; ?>/uploads/logos/<?php echo $data['settings']['site_logo']; ?>" alt="Current Logo" class="h-12 my-2">
                            <?php endif; ?>
                            <input type="file" name="site_logo" class="w-full mt-1 text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-pink-50 file:text-pink-700 hover:file:bg-pink-100">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold">Favicon</label>
                            <?php if(!empty($data['settings']['site_favicon'])): ?>
                                <img src="<?php echo URLROOT; ?>/uploads/favicons/<?php echo $data['settings']['site_favicon']; ?>" alt="Current Favicon" class="h-8 w-8 my-2">
                            <?php endif; ?>
                            <input type="file" name="site_favicon" class="w-full mt-1 text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-pink-50 file:text-pink-700 hover:file:bg-pink-100">
                        </div>
                         <div>
                            <label for="sidebar_color" class="block text-gray-700 font-semibold">สี Sidebar</label>
                            <input type="color" name="sidebar_color" class="w-20 h-10 mt-1 border border-gray-300 rounded" value="<?php echo htmlspecialchars($data['settings']['sidebar_color'] ?? '#DB2777'); ?>">
                        </div>
                    </div>

                    <hr class="my-8">

                    <div class="my-8">
                        <label for="grayscale_mode_enabled" class="block text-gray-700 font-semibold mb-2">โหมดสีเทา (สำหรับไว้อาลัย)</label>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="hidden" name="grayscale_mode_enabled" value="0"> <!-- ค่า default เมื่อไม่ได้ติ๊ก -->
                            <input type="checkbox" name="grayscale_mode_enabled" value="1" class="sr-only peer" <?php echo (($data['settings']['grayscale_mode_enabled'] ?? '0') == '1') ? 'checked' : ''; ?>>
                            <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-gray-600"></div>
                            <span class="ml-3 text-sm font-medium text-gray-900">เปิดใช้งานโหมดไว้อาลัย</span>
                        </label>
                    </div>
                    
                    <hr class="my-8">

                    <!-- Booking Settings -->
                    <div class="space-y-6">
                         <div>
                            <label for="default_booking_status" class="block text-gray-700 font-semibold">สถานะเริ่มต้นของการจอง</label>
                            <select name="default_booking_status" class="w-full mt-1 px-3 py-2 border rounded">
                                <option value="pending" <?php echo (($data['settings']['default_booking_status'] ?? 'pending') == 'pending') ? 'selected' : ''; ?>>รออนุมัติ (Pending)</option>
                                <option value="approved" <?php echo (($data['settings']['default_booking_status'] ?? 'pending') == 'approved') ? 'selected' : ''; ?>>อนุมัติอัตโนมัติ (Approved)</option>
                            </select>
                        </div>
                        <div>
                            <label for="booking_advance_days" class="block text-gray-700 font-semibold">ต้องจองล่วงหน้าอย่างน้อย (วัน)</label>
                            <input type="number" name="booking_advance_days" class="w-full mt-1 px-3 py-2 border rounded" value="<?php echo htmlspecialchars($data['settings']['booking_advance_days'] ?? '1'); ?>">
                            <p class="text-xs text-gray-500 mt-1">ใส่ 0 คือสามารถจองวันปัจจุบันได้</p>
                        </div>
                        <div>
                            <label for="allow_weekend_booking" class="block text-gray-700 font-semibold">การจองในวันหยุด (เสาร์-อาทิตย์)</label>
                            <select name="allow_weekend_booking" class="w-full mt-1 px-3 py-2 border rounded">
                                <option value="0" <?php echo (($data['settings']['allow_weekend_booking'] ?? '0') == '0') ? 'selected' : ''; ?>>ไม่อนุญาต</option>
                                <option value="1" <?php echo (($data['settings']['allow_weekend_booking'] ?? '0') == '1') ? 'selected' : ''; ?>>อนุญาต</option>
                            </select>
                        </div>
                    </div>

                    <hr class="my-8">

                    <!-- Telegram Settings -->
                    <div class="space-y-6">
                        <h3 class="text-xl font-semibold text-gray-700">ตั้งค่าการแจ้งเตือน Telegram</h3>
                        <div>
                            <label for="telegram_enabled" class="block text-gray-700 font-semibold">สถานะ</label>
                            <select name="telegram_enabled" class="w-full mt-1 px-3 py-2 border rounded">
                                <option value="1" <?php echo (($data['settings']['telegram_enabled'] ?? '0') == '1') ? 'selected' : ''; ?>>เปิดใช้งาน</option>
                                <option value="0" <?php echo (($data['settings']['telegram_enabled'] ?? '0') == '0') ? 'selected' : ''; ?>>ปิดใช้งาน</option>
                            </select>
                        </div>
                        <div>
                            <label for="telegram_token" class="block text-gray-700 font-semibold">Bot API Token</label>
                            <input type="text" name="telegram_token" class="w-full mt-1 px-3 py-2 border rounded" value="<?php echo htmlspecialchars($data['settings']['telegram_token'] ?? ''); ?>">
                        </div>
                        <div>
                            <label for="telegram_chat_id" class="block text-gray-700 font-semibold">Chat ID</label>
                            <input type="text" name="telegram_chat_id" class="w-full mt-1 px-3 py-2 border rounded" value="<?php echo htmlspecialchars($data['settings']['telegram_chat_id'] ?? ''); ?>">
                        </div>
                    </div>
                    
                    <div class="mt-8">
                        <button type="submit" class="w-full sm:w-auto bg-pink-500 text-white font-bold py-2 px-6 rounded-lg hover:bg-pink-600 transition">บันทึกการตั้งค่า</button>
                    </div>
                </form>
            </div>
            
        </main>
    </div>
</div>
<?php require APPROOT . '/views/inc/footer.php'; ?>