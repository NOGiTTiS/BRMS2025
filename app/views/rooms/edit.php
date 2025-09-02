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
            
            <a href="<?php echo URLROOT; ?>/room" class="text-gray-500 hover:text-gray-700 mb-4 inline-block">&larr; กลับไปหน้ารายการ</a>
            
            <div class="bg-white p-6 md:p-8 rounded-lg shadow-md max-w-2xl mx-auto">
                <h2 class="text-2xl font-bold mb-6"><?php echo $data['title']; ?></h2>
                
                <form action="<?php echo URLROOT; ?>/room/edit/<?php echo $data['id']; ?>" method="post">
                    <div class="space-y-6">
                        <div>
                            <label for="name" class="block text-gray-700 font-semibold">ชื่อห้อง: <sup class="text-red-500">*</sup></label>
                            <input type="text" name="name" class="w-full mt-1 px-3 py-2 border <?php echo (!empty($data['name_err'])) ? 'border-red-500' : 'border-gray-300'; ?> rounded" value="<?php echo htmlspecialchars($data['name']); ?>">
                            <span class="text-red-500 text-sm"><?php echo $data['name_err']; ?></span>
                        </div>
                        
                        <div>
                            <label for="capacity" class="block text-gray-700 font-semibold">ความจุ (คน): <sup class="text-red-500">*</sup></label>
                            <input type="number" name="capacity" class="w-full mt-1 px-3 py-2 border <?php echo (!empty($data['capacity_err'])) ? 'border-red-500' : 'border-gray-300'; ?> rounded" value="<?php echo htmlspecialchars($data['capacity']); ?>">
                            <span class="text-red-500 text-sm"><?php echo $data['capacity_err']; ?></span>
                        </div>
                        
                        <div>
                            <label for="description" class="block text-gray-700 font-semibold">รายละเอียด:</label>
                            <textarea name="description" rows="4" class="w-full mt-1 px-3 py-2 border border-gray-300 rounded"><?php echo htmlspecialchars($data['description']); ?></textarea>
                        </div>
                        
                        <div>
                            <label for="color" class="block text-gray-700 font-semibold">สีปฏิทิน:</label>
                            <input type="color" name="color" class="w-20 h-10 mt-1 border border-gray-300 rounded" value="<?php echo htmlspecialchars($data['color']); ?>">
                        </div>
                        
                        <div class="mt-8">
                            <button type="submit" class="w-full sm:w-auto bg-pink-500 text-white font-bold py-2 px-6 rounded-lg hover:bg-pink-600 transition">อัปเดตข้อมูล</button>
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