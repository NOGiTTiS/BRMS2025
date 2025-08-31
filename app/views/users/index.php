<?php require APPROOT . '/views/inc/header.php'; ?>
<div x-data="{ sidebarOpen: false }" class="relative md:flex min-h-full">
    <?php include APPROOT . '/views/inc/sidebar.php'; ?>
    <div class="flex-1 flex flex-col w-0">
        <?php include APPROOT . '/views/inc/topnav.php'; ?>
        <main class="flex-1 p-4 md:p-8">
            <div class="overflow-x-auto">
                <h1 class="text-2xl font-bold mb-6"><?php echo $data['title']; ?></h1>
                <?php flash('user_message'); ?>
                <div class="bg-white shadow-md rounded-lg overflow-hidden">
                    <table class="min-w-full leading-normal">
                        <thead>
                            <tr>
                                <th class="px-5 py-3 border-b-2 bg-gray-100 text-left">Username</th>
                                <th class="px-5 py-3 border-b-2 bg-gray-100 text-left">ชื่อ-นามสกุล</th>
                                <th class="px-5 py-3 border-b-2 bg-gray-100 text-left">Email</th>
                                <th class="px-5 py-3 border-b-2 bg-gray-100 text-left">Role</th>
                                <th class="px-5 py-3 border-b-2 bg-gray-100"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($data['users'] as $user) : ?>
                            <tr>
                                <td class="px-5 py-5 border-b bg-white text-sm"><?php echo $user->username; ?></td>
                                <td class="px-5 py-5 border-b bg-white text-sm"><?php echo $user->first_name . ' ' . $user->last_name; ?></td>
                                <td class="px-5 py-5 border-b bg-white text-sm"><?php echo $user->email; ?></td>
                                <td class="px-5 py-5 border-b bg-white text-sm"><?php echo $user->role; ?></td>
                                <td class="px-5 py-5 border-b bg-white text-sm text-right">
                                    <a href="<?php echo URLROOT; ?>/user/edit/<?php echo $user->id; ?>" class="text-indigo-600 hover:text-indigo-900 mr-4">แก้ไข</a>
                                    <form id="delete-user-<?php echo $user->id; ?>" action="<?php echo URLROOT; ?>/user/delete/<?php echo $user->id; ?>" method="post" class="inline-block">
                                        <button type="button" onclick="confirmDeleteUser(<?php echo $user->id; ?>, '<?php echo $user->username; ?>')" class="text-red-600 hover:text-red-900">ลบ</button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</div>
<script>
function confirmDeleteUser(id, username) {
    Swal.fire({
        title: 'คุณแน่ใจหรือไม่?',
        text: `คุณต้องการลบผู้ใช้ "${username}" ใช่หรือไม่?`,
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