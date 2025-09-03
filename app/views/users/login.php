<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="flex items-center justify-center min-h-screen bg-gradient-to-br from-pink-400 to-purple-500 p-4">
    <div class="w-full max-w-md p-8 space-y-6 bg-white/30 backdrop-blur-xl rounded-2xl shadow-lg">
        <h2 class="text-3xl font-bold text-center text-white">เข้าสู่ระบบ</h2>
        
        <!-- ส่วนนี้จะแสดง SweetAlert ที่ส่งมาจากหน้า Register -->
        <?php flash('notification'); ?>

        <form action="<?php echo URLROOT; ?>/user/login" method="post" class="space-y-4">
            <div>
                <label for="username_email" class="block mb-2 text-sm font-medium text-white">ชื่อผู้ใช้งาน หรือ อีเมล</label>
                <input type="text" name="username_email" id="username_email" 
                       class="w-full px-4 py-2 text-gray-700 bg-white/80 border <?php echo (!empty($data['username_email_err'])) ? 'border-red-500' : 'border-gray-300'; ?> rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500"
                       value="<?php echo htmlspecialchars($data['username_email']); ?>" required>
                <span class="text-red-300 text-sm font-semibold"><?php echo $data['username_email_err']; ?></span>
            </div>
            <div>
                <label for="password" class="block mb-2 text-sm font-medium text-white">รหัสผ่าน</label>
                <input type="password" name="password" id="password" 
                       class="w-full px-4 py-2 text-gray-700 bg-white/80 border <?php echo (!empty($data['password_err'])) ? 'border-red-500' : 'border-gray-300'; ?> rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500"
                       value="" required>
                <span class="text-red-300 text-sm font-semibold"><?php echo $data['password_err']; ?></span>
            </div>
            <div>
                <button type="submit" class="w-full px-4 py-2 font-bold text-white bg-pink-600 rounded-lg hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-opacity-50 transition duration-300">
                    เข้าสู่ระบบ
                </button>
            </div>
        </form>
        <div class="text-center">
            <a href="<?php echo URLROOT; ?>/user/register" class="text-sm text-pink-100 hover:underline">ยังไม่มีบัญชี? สมัครสมาชิก</a>
        </div>
        
        <p class="mt-6 text-center text-sm text-white/70">
            <?php echo htmlspecialchars(str_replace('{year}', date('Y'), setting('copyright_text', '© {year} BRMS Project'))); ?>
        </p>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>