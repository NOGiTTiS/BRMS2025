<?php require APPROOT . '/views/inc/header.php'; ?>
<div x-data="{ sidebarOpen: false }" class="relative md:flex min-h-full">
    <?php include APPROOT . '/views/inc/sidebar.php'; ?>
    <div class="flex-1 flex flex-col w-0">
        <?php include APPROOT . '/views/inc/topnav.php'; ?>
        <main class="flex-1 p-4 md:p-8">
            <div class="overflow-x-auto">
                <h1 class="text-2xl font-bold mb-6"><?php echo $data['title']; ?></h1>
                <?php flash('setting_message'); ?>
                <div class="bg-white p-8 rounded-lg shadow-md max-w-4xl mx-auto">
                    <form action="<?php echo URLROOT; ?>/setting" method="post" enctype="multipart/form-data">
                        <!-- Site Name -->
                        <div class="mb-4">
                            <label for="site_name" class="block text-gray-700 font-semibold">ชื่อระบบ</label>
                            <input type="text" name="site_name" class="w-full mt-1 px-3 py-2 border rounded" value="<?php echo htmlspecialchars($data['settings']['site_name'] ?? ''); ?>">
                        </div>
                        <!-- Copyright -->
                        <div class="mb-6">
                            <label for="copyright_text" class="block text-gray-700 font-semibold">ข้อความ Copyright</label>
                            <input type="text" name="copyright_text" class="w-full mt-1 px-3 py-2 border rounded" value="<?php echo htmlspecialchars($data['settings']['copyright_text'] ?? ''); ?>">
                            <p class="text-xs text-gray-500 mt-1">ใช้ `{year}` เพื่อแสดงปีปัจจุบันโดยอัตโนมัติ</p>
                        </div>
                        <hr class="my-6">
                        <!-- Logo Upload -->
                        <div class="mb-4">
                            <label class="block text-gray-700 font-semibold">โลโก้ระบบ</label>
                            <?php if(!empty($data['settings']['site_logo'])): ?>
                                <img src="<?php echo URLROOT; ?>/uploads/logos/<?php echo $data['settings']['site_logo']; ?>" alt="Current Logo" class="h-12 my-2">
                            <?php endif; ?>
                            <input type="file" name="site_logo" class="w-full mt-1 text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-pink-50 file:text-pink-700 hover:file:bg-pink-100">
                        </div>
                        <!-- Favicon Upload -->
                        <div class="mb-6">
                            <label class="block text-gray-700 font-semibold">Favicon</label>
                            <?php if(!empty($data['settings']['site_favicon'])): ?>
                                <img src="<?php echo URLROOT; ?>/uploads/favicons/<?php echo $data['settings']['site_favicon']; ?>" alt="Current Favicon" class="h-8 w-8 my-2">
                            <?php endif; ?>
                            <input type="file" name="site_favicon" class="w-full mt-1 text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-pink-50 file:text-pink-700 hover:file:bg-pink-100">
                        </div>
                         <!-- Sidebar Color -->
                        <div class="mb-6">
                            <label for="sidebar_color" class="block text-gray-700 font-semibold">สี Sidebar</label>
                            <input type="color" name="sidebar_color" class="w-20 h-10 border border-gray-300 rounded" value="<?php echo htmlspecialchars($data['settings']['sidebar_color'] ?? '#DB2777'); ?>">
                        </div>
                        <hr class="my-6">
                        <!-- Default Booking Status -->
                        <div class="mb-6">
                            <label for="default_booking_status" class="block text-gray-700 font-semibold">สถานะเริ่มต้นของการจอง</label>
                            <select name="default_booking_status" class="w-full mt-1 px-3 py-2 border rounded">
                                <option value="pending" <?php echo (($data['settings']['default_booking_status'] ?? 'pending') == 'pending') ? 'selected' : ''; ?>>รออนุมัติ (Pending)</option>
                                <option value="approved" <?php echo (($data['settings']['default_booking_status'] ?? 'pending') == 'approved') ? 'selected' : ''; ?>>อนุมัติอัตโนมัติ (Approved)</option>
                            </select>
                        </div>
                        <!-- Booking Advance Days -->
                        <div class="mb-6">
                            <label for="booking_advance_days" class="block text-gray-700 font-semibold">ต้องจองล่วงหน้าอย่างน้อย (วัน)</label>
                            <input type="number" name="booking_advance_days" class="w-full mt-1 px-3 py-2 border rounded" value="<?php echo htmlspecialchars($data['settings']['booking_advance_days'] ?? '1'); ?>">
                            <p class="text-xs text-gray-500 mt-1">เช่น ใส่ 1 คือต้องจองล่วงหน้า 1 วัน (จองวันนี้เพื่อใช้พรุ่งนี้), ใส่ 0 คือสามารถจองวันปัจจุบันได้</p>
                        </div>

                        <button type="submit" class="bg-pink-500 text-white font-bold py-2 px-6 rounded-lg hover:bg-pink-600 transition">บันทึกการตั้งค่า</button>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>
<?php require APPROOT . '/views/inc/footer.php'; ?>