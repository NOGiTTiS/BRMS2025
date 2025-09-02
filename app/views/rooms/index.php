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
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
                    <h1 class="text-2xl font-bold mb-4 sm:mb-0"><?php echo $data['title']; ?></h1>
                    <a href="<?php echo URLROOT; ?>/room/add" class="bg-pink-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-pink-600 transition whitespace-nowrap">เพิ่มห้องใหม่</a>
                </div>
                
                <?php flash('room_message'); ?>

                <div class="bg-white shadow-md rounded-lg overflow-hidden">
                    <table class="min-w-full leading-normal">
                        <thead>
                            <tr>
                                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">สี</th>
                                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ชื่อห้อง</th>
                                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ความจุ</th>
                                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">รายละเอียด</th>
                                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($data['rooms'] as $room) : ?>
                            <tr>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <div class="w-6 h-6 rounded-full" style="background-color: <?php echo htmlspecialchars($room->color); ?>"></div>
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <p class="text-gray-900 font-semibold whitespace-nowrap"><?php echo htmlspecialchars($room->name); ?></p>
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <p class="text-gray-900 whitespace-nowrap"><?php echo $room->capacity; ?> คน</p>
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <!-- ทำให้ข้อความยาวๆ ตัดคำอย่างสวยงาม -->
                                    <p class="text-gray-900 whitespace-pre-wrap w-64"><?php echo htmlspecialchars($room->description); ?></p>
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-right">
                                    <div class="flex justify-end items-center space-x-4 whitespace-nowrap">
                                        <a href="<?php echo URLROOT; ?>/room/edit/<?php echo $room->id; ?>" class="text-indigo-600 hover:text-indigo-900">แก้ไข</a>
                                        <form id="delete-form-<?php echo $room->id; ?>" action="<?php echo URLROOT; ?>/room/delete/<?php echo $room->id; ?>" method="post" class="inline-block">
                                            <button type="button" onclick="confirmDelete(<?php echo $room->id; ?>, '<?php echo htmlspecialchars($room->name, ENT_QUOTES); ?>')" class="text-red-600 hover:text-red-900">ลบ</button>
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
function confirmDelete(id, roomName) {
    Swal.fire({
        title: `คุณแน่ใจหรือไม่?`,
        text: `คุณต้องการลบห้อง "${roomName}" ใช่หรือไม่? การกระทำนี้ไม่สามารถย้อนกลับได้!`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'ใช่, ลบเลย!',
        cancelButtonText: 'ยกเลิก'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form-' + id).submit();
        }
    })
}
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>