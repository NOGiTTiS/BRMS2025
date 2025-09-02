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
            
            <?php flash('user_message'); ?>

            <!-- Card ครอบตาราง พร้อม overflow-x-auto -->
            <div class="bg-white shadow-md rounded-lg overflow-x-auto">
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr>
                            <th class="px-5 py-3 border-b-2 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase">Username</th>
                            <th class="px-5 py-3 border-b-2 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase">ชื่อ-นามสกุล</th>
                            <th class="px-5 py-3 border-b-2 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase">Email</th>
                            <th class="px-5 py-3 border-b-2 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase">Role</th>
                            <th class="px-5 py-3 border-b-2 bg-gray-100"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($data['users'] as $user) : ?>
                        <tr>
                            <td class="px-5 py-5 border-b bg-white text-sm"><?php echo htmlspecialchars($user->username); ?></td>
                            <td class="px-5 py-5 border-b bg-white text-sm"><?php echo htmlspecialchars($user->first_name . ' ' . $user->last_name); ?></td>
                            <td class="px-5 py-5 border-b bg-white text-sm"><?php echo htmlspecialchars($user->email); ?></td>
                            <td class="px-5 py-5 border-b bg-white text-sm">
                               <span class="relative inline-block px-3 py-1 font-semibold leading-tight <?php echo ($user->role == 'admin') ? 'text-green-900' : 'text-gray-700'; ?>">
                                    <span aria-hidden class="absolute inset-0 <?php echo ($user->role == 'admin') ? 'bg-green-200' : 'bg-gray-200'; ?> opacity-50 rounded-full"></span>
                                    <span class="relative"><?php echo htmlspecialchars($user->role); ?></span>
                                </span>
                            </td>
                            <td class="px-5 py-5 border-b bg-white text-sm text-right">
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

        </main>
    </div>
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