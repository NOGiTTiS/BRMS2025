<?php require APPROOT . '/views/inc/header.php'; ?>
<div x-data="{ sidebarOpen: false }" class="relative md:flex min-h-full">
    <?php include APPROOT . '/views/inc/sidebar.php'; ?>
    <div class="flex-1 flex flex-col w-0">
        <?php include APPROOT . '/views/inc/topnav.php'; ?>
        <main class="flex-1 p-4 md:p-8 overflow-x-auto">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold"><?php echo $data['title']; ?></h1>
                <a href="<?php echo URLROOT; ?>/room/add" class="bg-pink-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-pink-600 transition">เพิ่มห้องใหม่</a>
            </div>

            <!-- เพิ่มบรรทัดนี้เพื่อแสดง Flash Message -->
            <?php flash('room_message'); ?>

            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ชื่อห้อง</th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ความจุ</th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">สี</th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($data['rooms'] as $room) : ?>
                        <tr>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm"><?php echo $room->name; ?></td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm"><?php echo $room->capacity; ?> คน</td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <div class="w-6 h-6 rounded-full" style="background-color: <?php echo $room->color; ?>"></div>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-right">
                                <a href="<?php echo URLROOT; ?>/room/edit/<?php echo $room->id; ?>" class="text-indigo-600 hover:text-indigo-900 mr-4">แก้ไข</a>
                                <!-- ให้ ID กับ form เพื่อให้ JavaScript เรียกใช้ได้ -->
                                <form id="delete-form-<?php echo $room->id; ?>" action="<?php echo URLROOT; ?>/room/delete/<?php echo $room->id; ?>" method="post" class="inline-block">
                                    <!-- เปลี่ยน type เป็น button และลบ onclick เดิมออก -->
                                    <button type="button" 
                                            onclick="confirmDelete(<?php echo $room->id; ?>, '<?php echo htmlspecialchars($room->name, ENT_QUOTES); ?>')"
                                            class="text-red-600 hover:text-red-900">
                                        ลบ
                                    </button>
                                </form>
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
            // ถ้าผู้ใช้กดยืนยัน ให้ส่งฟอร์มที่มี ID ตรงกัน
            document.getElementById('delete-form-' + id).submit();
        }
    })
}
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>