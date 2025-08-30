<?php require APPROOT . '/views/inc/header.php'; ?>
<div x-data="{ sidebarOpen: false }" class="relative md:flex min-h-full">
    <?php include APPROOT . '/views/inc/sidebar.php'; ?>
    <div class="flex-1 flex flex-col w-0">
        <?php include APPROOT . '/views/inc/topnav.php'; ?>
        <main class="flex-1 p-4 md:p-8 overflow-x-auto">
            <a href="<?php echo URLROOT; ?>/room" class="text-gray-500 hover:text-gray-700 mb-4 inline-block">&larr; กลับไปหน้ารายการ</a>
            <div class="bg-white p-8 rounded-lg shadow-md max-w-2xl mx-auto">
                <h2 class="text-2xl font-bold mb-6"><?php echo $data['title']; ?></h2>
                <form action="<?php echo URLROOT; ?>/room/add" method="post">
                    <div class="mb-4">
                        <label for="name" class="block text-gray-700">ชื่อห้อง: <sup class="text-red-500">*</sup></label>
                        <input type="text" name="name" class="w-full px-3 py-2 border <?php echo (!empty($data['name_err'])) ? 'border-red-500' : 'border-gray-300'; ?> rounded" value="<?php echo $data['name']; ?>">
                        <span class="text-red-500 text-sm"><?php echo $data['name_err']; ?></span>
                    </div>
                    <div class="mb-4">
                        <label for="capacity" class="block text-gray-700">ความจุ (คน): <sup class="text-red-500">*</sup></label>
                        <input type="number" name="capacity" class="w-full px-3 py-2 border <?php echo (!empty($data['capacity_err'])) ? 'border-red-500' : 'border-gray-300'; ?> rounded" value="<?php echo $data['capacity']; ?>">
                        <span class="text-red-500 text-sm"><?php echo $data['capacity_err']; ?></span>
                    </div>
                    <div class="mb-4">
                        <label for="description" class="block text-gray-700">รายละเอียด:</label>
                        <textarea name="description" class="w-full px-3 py-2 border border-gray-300 rounded"><?php echo $data['description']; ?></textarea>
                    </div>
                    <div class="mb-6">
                        <label for="color" class="block text-gray-700">สีปฏิทิน:</label>
                        <input type="color" name="color" class="w-20 h-10 border border-gray-300 rounded" value="<?php echo $data['color']; ?>">
                    </div>
                    <button type="submit" class="bg-pink-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-pink-600 transition">บันทึก</button>
                </form>
            </div>
        </main>
    </div>
</div>
<?php require APPROOT . '/views/inc/footer.php'; ?>