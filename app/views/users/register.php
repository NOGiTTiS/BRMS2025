<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="flex items-center justify-center min-h-screen bg-gradient-to-br from-pink-400 to-purple-500">
    <div class="w-full max-w-lg p-8 space-y-6 bg-white/30 backdrop-blur-xl rounded-2xl shadow-lg">
        <h2 class="text-3xl font-bold text-center text-white">สร้างบัญชีผู้ใช้</h2>
        <p class="text-center text-pink-100">กรอกข้อมูลด้านล่างเพื่อสมัครสมาชิก</p>
        <form action="<?php echo URLROOT; ?>/user/register" method="post" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="first_name" class="block mb-2 text-sm font-medium text-white">ชื่อ</label>
                    <input type="text" name="first_name" class="w-full px-4 py-2 text-gray-700 bg-white/80 border <?php echo (!empty($data['first_name_err'])) ? 'border-red-500' : 'border-gray-300'; ?> rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500" value="<?php echo $data['first_name']; ?>">
                    <span class="text-red-300 text-sm"><?php echo $data['first_name_err']; ?></span>
                </div>
                <div>
                    <label for="last_name" class="block mb-2 text-sm font-medium text-white">นามสกุล</label>
                    <input type="text" name="last_name" class="w-full px-4 py-2 text-gray-700 bg-white/80 border <?php echo (!empty($data['last_name_err'])) ? 'border-red-500' : 'border-gray-300'; ?> rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500" value="<?php echo $data['last_name']; ?>">
                    <span class="text-red-300 text-sm"><?php echo $data['last_name_err']; ?></span>
                </div>
            </div>
            <div>
                <label for="username" class="block mb-2 text-sm font-medium text-white">ชื่อผู้ใช้งาน</label>
                <input type="text" name="username" class="w-full px-4 py-2 text-gray-700 bg-white/80 border <?php echo (!empty($data['username_err'])) ? 'border-red-500' : 'border-gray-300'; ?> rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500" value="<?php echo $data['username']; ?>">
                <span class="text-red-300 text-sm"><?php echo $data['username_err']; ?></span>
            </div>
            <div>
                <label for="email" class="block mb-2 text-sm font-medium text-white">อีเมล</label>
                <input type="email" name="email" class="w-full px-4 py-2 text-gray-700 bg-white/80 border <?php echo (!empty($data['email_err'])) ? 'border-red-500' : 'border-gray-300'; ?> rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500" value="<?php echo $data['email']; ?>">
                <span class="text-red-300 text-sm"><?php echo $data['email_err']; ?></span>
            </div>
            <div>
                <label for="password" class="block mb-2 text-sm font-medium text-white">รหัสผ่าน</label>
                <input type="password" name="password" class="w-full px-4 py-2 text-gray-700 bg-white/80 border <?php echo (!empty($data['password_err'])) ? 'border-red-500' : 'border-gray-300'; ?> rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500" value="<?php echo $data['password']; ?>">
                <span class="text-red-300 text-sm"><?php echo $data['password_err']; ?></span>
            </div>
            <div>
                <label for="confirm_password" class="block mb-2 text-sm font-medium text-white">ยืนยันรหัสผ่าน</label>
                <input type="password" name="confirm_password" class="w-full px-4 py-2 text-gray-700 bg-white/80 border <?php echo (!empty($data['confirm_password_err'])) ? 'border-red-500' : 'border-gray-300'; ?> rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500" value="<?php echo $data['confirm_password']; ?>">
                <span class="text-red-300 text-sm"><?php echo $data['confirm_password_err']; ?></span>
            </div>
            <div>
                <button type="submit" class="w-full px-4 py-2 font-bold text-white bg-pink-600 rounded-lg hover:bg-pink-700">สมัครสมาชิก</button>
            </div>
        </form>
        <div class="text-center">
            <a href="<?php echo URLROOT; ?>/user/login" class="text-sm text-pink-100 hover:underline">มีบัญชีอยู่แล้ว? เข้าสู่ระบบ</a>
        </div>

        <!-- เพิ่มส่วนนี้เข้ามา -->
        <p class="mt-6 text-center text-sm text-white/70">
            <?php echo htmlspecialchars(str_replace('{year}', date('Y'), setting('copyright_text', '© {year} BRMS Project'))); ?>
        </p>
        <!-- จบส่วนที่เพิ่ม -->
         
    </div>
</div>
<?php require APPROOT . '/views/inc/footer.php'; ?>