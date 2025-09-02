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
            
            <!-- Wrapper สำหรับจัดการเนื้อหาที่กว้าง (ตาราง) -->
            <div class="overflow-x-auto">
                <h1 class="text-2xl font-bold mb-6"><?php echo $data['title']; ?></h1>
                
                <?php flash('user_message'); ?>

                <div class="bg-white shadow-md rounded-lg overflow-hidden">
                    <table class="min-w-full leading-normal">
                        <thead>
                            <tr>
                                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Username</th>
                                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ชื่อ-นามสกุล</th>
                                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Email</th>
                                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Role</th>
                                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($data['users'] as $user) : ?>
                            <tr>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap"><?php echo htmlspecialchars($user->username); ?></p>
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap"><?php echo htmlspecialchars($user->first_name . ' ' . $user->last_name); ?></p>
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap"><?php echo htmlspecialchars($user->email); ?></p>
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <span class="relative inline-block px-3 py-1 font-semibold leading-tight <?php echo ($user->role == 'admin') ? 'text-green-900' : 'text-gray-700'; ?>">
                                        <span aria-hidden class="absolute inset-0 <?php echo ($user->role == 'admin') ? 'bg-green-200' : 'bg-gray-200'; ?> opacity-50 rounded-full"></span>
                                        <span class="relative"><?php echo htmlspecialchars($user->role); ?></span>
                                    </span>
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-right">
                                    <div class="flex justify-end items-center space-x-4 whitespace-nowrap">
                                        <a href="<?php echo URLROOT; ?>/user/edit/<?php echo $user->id; ?>" class="text-indigo-600 hover:text-indigo-900">แก้ไข</a>
                                        <form id="delete-user-<?php echo $user->id; ?>" action="<?php echo URLROOT; ?>/user/delete/<?php echo $user->id; ?>" method="post" class="inline-block">
                                            <button type="button" onclick="confirmDeleteUser(<?php echo $user->id; ?>, '<?php echo htmlspecialchars($user->username, ENT_QUOTES); ?>')" class="text-red-600 hover:text-red-900">ลบ</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
        <!-- END: Page Content Wrapper -->

    </div>
    <!-- END: Main Content Area -->

</div>

<script>
function confirmDeleteUser(id, username) {
    Swal.fire({
        title: 'คุณแน่ใจหรือไม่?',
        text: `คุณต้องการลบผู้ใช้ "${username}" ใช่หรือไม่? การกระทำนี้อาจไม่สำเร็จหากผู้ใช้มีการจองอยู่`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'ใช่, ลบเลย!',
        cancelButtonText: 'ยกเลิก'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-user-' + id).submit();
        }
    })
}
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>