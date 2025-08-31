<?php require APPROOT . '/views/inc/header.php'; ?>
<div x-data="{ sidebarOpen: false }" class="relative md:flex min-h-full">
    <?php include APPROOT . '/views/inc/sidebar.php'; ?>
    <div class="flex-1 flex flex-col w-0">
        <?php include APPROOT . '/views/inc/topnav.php'; ?>
        <main class="flex-1 p-4 md:p-8">
             <a href="<?php echo URLROOT; ?>/user" class="text-gray-500 hover:text-gray-700 mb-4 inline-block">&larr; กลับไปหน้ารายการ</a>
            <div class="bg-white p-8 rounded-lg shadow-md max-w-2xl mx-auto">
                <h2 class="text-2xl font-bold mb-6"><?php echo $data['title']; ?></h2>
                <form action="<?php echo URLROOT; ?>/user/edit/<?php echo $data['id']; ?>" method="post">
                    <div class="mb-4">
                        <label for="first_name" class="block text-gray-700">ชื่อจริง:</label>
                        <input type="text" name="first_name" class="w-full mt-1 px-3 py-2 border rounded" value="<?php echo $data['first_name']; ?>">
                    </div>
                    <div class="mb-4">
                        <label for="last_name" class="block text-gray-700">นามสกุล:</label>
                        <input type="text" name="last_name" class="w-full mt-1 px-3 py-2 border rounded" value="<?php echo $data['last_name']; ?>">
                    </div>
                    <div class="mb-4">
                        <label for="email" class="block text-gray-700">Email:</label>
                        <input type="email" name="email" class="w-full mt-1 px-3 py-2 border rounded" value="<?php echo $data['email']; ?>">
                    </div>
                    <div class="mb-4">
                        <label for="role" class="block text-gray-700">Role:</label>
                        <select name="role" class="w-full mt-1 px-3 py-2 border rounded">
                            <option value="user" <?php echo ($data['role'] == 'user') ? 'selected' : ''; ?>>User</option>
                            <option value="admin" <?php echo ($data['role'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
                        </select>
                    </div>
                    <hr class="my-6">
                    <div class="mb-4">
                        <label for="password" class="block text-gray-700">ตั้งรหัสผ่านใหม่ (ไม่บังคับ):</label>
                        <input type="password" name="password" class="w-full mt-1 px-3 py-2 border rounded" placeholder="กรอกเฉพาะเมื่อต้องการเปลี่ยน">
                    </div>
                    <button type="submit" class="bg-pink-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-pink-600 transition">บันทึกการเปลี่ยนแปลง</button>
                </form>
            </div>
        </main>
    </div>
</div>
<?php require APPROOT . '/views/inc/footer.php'; ?>