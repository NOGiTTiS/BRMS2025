<?php require APPROOT . '/views/inc/header.php'; ?>
<div x-data="{ sidebarOpen: false }" class="relative md:flex min-h-full">
    <?php include APPROOT . '/views/inc/sidebar.php'; ?>
    <div class="flex flex-col flex-1 md:w-0">
        <?php include APPROOT . '/views/inc/topnav.php'; ?>
        <main class="flex-1 overflow-y-auto p-4 md:p-8">
            <div class="max-w-4xl mx-auto space-y-8">
                <!-- Edit Profile Card -->
                <div class="bg-white p-6 md:p-8 rounded-lg shadow-md">
                    <h2 class="text-2xl font-bold mb-6">แก้ไขข้อมูลส่วนตัว</h2>
                    <form action="<?php echo URLROOT; ?>/profile/update" method="post">
                        <div class="space-y-6">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div>
                                    <label for="first_name" class="block text-gray-700 font-semibold">ชื่อจริง</label>
                                    <input type="text" name="first_name" class="w-full mt-1 px-3 py-2 border rounded" value="<?php echo htmlspecialchars($data['user']->first_name); ?>">
                                </div>
                                <div>
                                    <label for="last_name" class="block text-gray-700 font-semibold">นามสกุล</label>
                                    <input type="text" name="last_name" class="w-full mt-1 px-3 py-2 border rounded" value="<?php echo htmlspecialchars($data['user']->last_name); ?>">
                                </div>
                            </div>
                            <div>
                                <label for="email" class="block text-gray-700 font-semibold">Email</label>
                                <input type="email" name="email" class="w-full mt-1 px-3 py-2 border rounded" value="<?php echo htmlspecialchars($data['user']->email); ?>">
                            </div>
                            <div class="pt-2">
                                <button type="submit" class="w-full sm:w-auto bg-pink-500 text-white font-bold py-2 px-6 rounded-lg hover:bg-pink-600">บันทึกข้อมูล</button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Change Password Card -->
                <div class="bg-white p-6 md:p-8 rounded-lg shadow-md">
                    <h2 class="text-2xl font-bold mb-6">เปลี่ยนรหัสผ่าน</h2>
                    <form action="<?php echo URLROOT; ?>/profile/changePassword" method="post">
                        <div class="space-y-6">
                            <div>
                                <label for="current_password" class="block text-gray-700 font-semibold">รหัสผ่านปัจจุบัน</label>
                                <input type="password" name="current_password" class="w-full mt-1 px-3 py-2 border rounded" required>
                            </div>
                             <div>
                                <label for="new_password" class="block text-gray-700 font-semibold">รหัสผ่านใหม่</label>
                                <input type="password" name="new_password" class="w-full mt-1 px-3 py-2 border rounded" required>
                            </div>
                             <div>
                                <label for="confirm_password" class="block text-gray-700 font-semibold">ยืนยันรหัสผ่านใหม่</label>
                                <input type="password" name="confirm_password" class="w-full mt-1 px-3 py-2 border rounded" required>
                            </div>
                            <div class="pt-2">
                                <button type="submit" class="w-full sm:w-auto bg-gray-700 text-white font-bold py-2 px-6 rounded-lg hover:bg-gray-800">เปลี่ยนรหัสผ่าน</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>
<?php require APPROOT . '/views/inc/footer.php'; ?>