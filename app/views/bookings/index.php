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
            
            <?php flash('booking_manage_message'); ?>

            <!-- Card ครอบตาราง พร้อม overflow-x-auto -->
            <div class="bg-white shadow-md rounded-lg overflow-x-auto">
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr>
                            <th class="px-5 py-3 border-b-2 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase">หัวข้อ</th>
                            <th class="px-5 py-3 border-b-2 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase">ผู้จอง</th>
                            <th class="px-5 py-3 border-b-2 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase">ห้อง</th>
                            <th class="px-5 py-3 border-b-2 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase">เวลาเริ่ม</th>
                            <th class="px-5 py-3 border-b-2 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase">สถานะ</th>
                            <th class="px-5 py-3 border-b-2 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($data['bookings'])): ?>
                            <tr><td colspan="6" class="text-center py-10 text-gray-500">ยังไม่มีรายการจอง</td></tr>
                        <?php else: ?>
                            <?php foreach($data['bookings'] as $booking) : ?>
                            <tr>
                                <td class="px-5 py-5 border-b bg-white text-sm">
                                    <a href="<?php echo URLROOT; ?>/booking/show/<?php echo $booking->id; ?>" class="text-gray-900 hover:text-pink-600 font-semibold whitespace-nowrap">
                                        <?php echo htmlspecialchars($booking->subject); ?>
                                    </a>
                                </td>
                                <td class="px-5 py-5 border-b bg-white text-sm"><?php echo htmlspecialchars($booking->user_username); ?></td>
                                <td class="px-5 py-5 border-b bg-white text-sm"><?php echo htmlspecialchars($booking->room_name); ?></td>
                                <td class="px-5 py-5 border-b bg-white text-sm whitespace-nowrap"><?php echo date('d/m/Y H:i', strtotime($booking->start_time)); ?></td>
                                <td class="px-5 py-5 border-b bg-white text-sm">
                                    <?php
                                        $status_class = ''; $status_text = '';
                                        switch ($booking->status) {
                                            case 'approved': $status_class = 'bg-green-200 text-green-800'; $status_text = 'อนุมัติแล้ว'; break;
                                            case 'pending': $status_class = 'bg-yellow-200 text-yellow-800'; $status_text = 'รออนุมัติ'; break;
                                            case 'rejected': $status_class = 'bg-red-200 text-red-800'; $status_text = 'ปฏิเสธ'; break;
                                        }
                                    ?>
                                    <span class="px-2 py-1 font-semibold leading-tight rounded-full <?php echo $status_class; ?>"><?php echo $status_text; ?></span>
                                </td>
                                <td class="px-5 py-5 border-b bg-white text-sm">
                                    <div class="flex items-center space-x-3 whitespace-nowrap">
                                        <?php if($booking->status == 'pending') : ?>
                                            <form action="<?php echo URLROOT; ?>/booking/approve/<?php echo $booking->id; ?>" method="post" class="inline-block"><button type="submit" class="text-green-600 hover:text-green-900 text-xs font-semibold">อนุมัติ</button></form>
                                            <form action="<?php echo URLROOT; ?>/booking/reject/<?php echo $booking->id; ?>" method="post" class="inline-block"><button type="submit" class="text-yellow-600 hover:text-yellow-900 text-xs font-semibold">ปฏิเสธ</button></form>
                                        <?php endif; ?>
                                        <a href="<?php echo URLROOT; ?>/booking/edit/<?php echo $booking->id; ?>" class="text-blue-600 hover:text-blue-900 text-xs font-semibold">แก้ไข</a>
                                        <form id="delete-booking-form-<?php echo $booking->id; ?>" action="<?php echo URLROOT; ?>/booking/delete/<?php echo $booking->id; ?>" method="post" class="inline-block">
                                            <button type="button" onclick="confirmDeleteBooking(<?php echo $booking->id; ?>)" class="text-red-600 hover:text-red-900 text-xs font-semibold">ลบ</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </main>
    </div>
</div>

<script>
function confirmDeleteBooking(id) {
    Swal.fire({
        title: 'คุณแน่ใจหรือไม่?',
        text: "คุณต้องการลบการจองนี้ใช่หรือไม่?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'ใช่, ลบเลย!',
        cancelButtonText: 'ยกเลิก'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-booking-form-' + id).submit();
        }
    })
}
</script>
<?php require APPROOT . '/views/inc/footer.php'; ?>