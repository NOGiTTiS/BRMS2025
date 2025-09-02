<?php require APPROOT . '/views/inc/header.php'; ?>

<!-- div หลักของ Layout -->
<div x-data="{ sidebarOpen: false }" class="relative md:flex min-h-full">

    <!-- Sidebar & Overlay -->
    <?php include APPROOT . '/views/inc/sidebar.php'; ?>

    <!-- START: Main Content Area -->
    <div class="flex flex-col flex-1 md:w-0">

        <!-- Top Navigation -->
        <?php include APPROOT . '/views/inc/topnav.php'; ?>

        <!-- START: Page Content Wrapper -->
        <main class="flex-1 overflow-y-auto p-4 md:p-8">
            
            <a href="<?php echo URLROOT; ?>/user" class="text-gray-500 hover:text-gray-700 mb-4 inline-block">&larr; กลับไปหน้ารายการ</a>
            
            <div class="bg-white p-6 md:p-8 rounded-lg shadow-md max-w-2xl mx-auto">
                <h2 class="text-2xl font-bold mb-6"><?php echo $data['title']; ?></h2>
                
                <form action="<?php echo URLROOT; ?>/user/edit/<?php echo $data['id']; ?>" method="post">
                    <div class="space-y-6">
                        <div>
                            <label for="first_name" class="block text-gray-700 font-semibold">ชื่อจริง: <sup class="text-red-500">*</sup></label>
                            <input type="text" name="first_name" class="w-full mt-1 px-3 py-2 border <?php echo (!empty($data['first_name_err'])) ? 'border-red-500' : 'border-gray-300'; ?> rounded" value="<?php echo htmlspecialchars($data['first_name']); ?>">
                             <span class="text-red-500 text-sm"><?php echo $data['first_name_err']; ?></span>
                        </div>
                        <div>
                            <label for="last_name" class="block text-gray-700 font-semibold">นามสกุล:</label>
                            <input type="text" name="last_name" class="w-full mt-1 px-3 py-2 border border-gray-300 rounded" value="<?php echo htmlspecialchars($data['last_name']); ?>">
                        </div>
                        <div>
                            <label for="email" class="block text-gray-700 font-semibold">Email: <sup class="text-red-500">*</sup></label>
                            <input type="email" name="email" class="w-full mt-1 px-3 py-2 border <?php echo (!empty($data['email_err'])) ? 'border-red-500' : 'border-gray-300'; ?> rounded" value="<?php echo htmlspecialchars($data['email']); ?>">
                            <span class="text-red-500 text-sm"><?php echo $data['email_err']; ?></span>
                        </div>
                        <div>
                            <label for="role" class="block text-gray-700 font-semibold">Role:</label>
                            <select name="role" class="w-full mt-1 px-3 py-2 border border-gray-300 rounded">
                                <option value="user" <?php echo ($data['role'] == 'user') ? 'selected' : ''; ?>>User</option>
                                <option value="admin" <?php echo ($data['role'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
                            </select>
                        </div>
                        
                        <hr class="my-6">

                        <div>
                            <label for="password" class="block text-gray-700 font-semibold">ตั้งรหัสผ่านใหม่ (ไม่บังคับ):</label>
                            <input type="password" name="password" class="w-full mt-1 px-3 py-2 border border-gray-300 rounded" placeholder="กรอกเฉพาะเมื่อต้องการเปลี่ยน">
                        </div>
                        
                        <div class="mt-8">
                            <button type="submit" class="w-full sm:w-auto bg-pink-500 text-white font-bold py-2 px-6 rounded-lg hover:bg-pink-600 transition">บันทึกการเปลี่ยนแปลง</button>
                        </div>
                    </div>
                </form>
            </div>
            
        </main>
        <!-- END: Page Content Wrapper -->

    </div>
    <!-- END: Main Content Area -->

</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>