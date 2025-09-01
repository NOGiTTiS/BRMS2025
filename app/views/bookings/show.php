<?php require APPROOT . '/views/inc/header.php'; ?>
<div x-data="{ sidebarOpen: false }" class="relative md:flex min-h-full">
    <?php include APPROOT . '/views/inc/sidebar.php'; ?>
    <div class="flex-1 flex flex-col w-0">
        <?php include APPROOT . '/views/inc/topnav.php'; ?>
        <main class="flex-1 p-4 md:p-8">
            <a href="<?php echo URLROOT; ?>/booking" class="text-gray-500 hover:text-gray-700 mb-4 inline-block">&larr; กลับไปหน้ารายการ</a>
            <?php flash('booking_manage_message'); ?>
            <!-- START: New UI Block -->
            <div class="space-y-6">
                <!-- Header Card -->
                <div class="bg-gray-50 p-6 rounded-lg shadow">
                    <div class="flex flex-col sm:flex-row justify-between items-start">
                        <div class="mb-4 sm:mb-0">
                            <h2 class="text-2xl md:text-3xl font-bold text-gray-800"><?php echo htmlspecialchars($data['booking']->subject); ?></h2>
                            <p class="text-sm text-gray-500 mt-1">ส่งคำขอโดย: <?php echo htmlspecialchars($data['booking']->user_full_name); ?></p>
                        </div>
                        <div class="flex-shrink-0">
                            <?php
                                $status_class = '';
                                $status_text = '';
                                switch ($data['booking']->status) {
                                    case 'approved': $status_class = 'bg-green-100 text-green-800'; $status_text = 'อนุมัติแล้ว'; break;
                                    case 'pending': $status_class = 'bg-yellow-100 text-yellow-800'; $status_text = 'รออนุมัติ'; break;
                                    case 'rejected': $status_class = 'bg-red-100 text-red-800'; $status_text = 'ปฏิเสธ'; break;
                                }
                            ?>
                            <span class="px-3 py-1.5 text-sm font-bold leading-tight rounded-full <?php echo $status_class; ?>">
                                <?php echo $status_text; ?>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Details Card -->
                <div class="bg-gray-50 p-6 rounded-lg shadow">
                    <h3 class="text-xl font-semibold text-gray-700 mb-4 border-b pb-2">รายละเอียดการจอง</h3>
                    <div class="grid md:grid-cols-2 gap-x-8 gap-y-4 text-gray-700">
                        <div><strong class="font-semibold text-gray-600">ห้องประชุม:</strong> <?php echo htmlspecialchars($data['booking']->room_name); ?></div>
                        <div><strong class="font-semibold text-gray-600">เวลาเริ่ม:</strong> <?php echo date('d M Y, H:i', strtotime($data['booking']->start_time)); ?> น.</div>
                        <div><strong class="font-semibold text-gray-600">ฝ่าย/หน่วยงาน:</strong> <?php echo htmlspecialchars($data['booking']->department ?: '-'); ?></div>
                        <div><strong class="font-semibold text-gray-600">เวลาสิ้นสุด:</strong> <?php echo date('d M Y, H:i', strtotime($data['booking']->end_time)); ?> น.</div>
                        <div><strong class="font-semibold text-gray-600">เบอร์โทรติดต่อ:</strong> <?php echo htmlspecialchars($data['booking']->phone ?: '-'); ?></div>
                        <div><strong class="font-semibold text-gray-600">จำนวนผู้เข้าร่วม:</strong> <?php echo $data['booking']->attendees; ?> คน</div>
                    </div>
                </div>
                
                <!-- Equipment & Note Card -->
                <div class="bg-gray-50 p-6 rounded-lg shadow">
                    <h3 class="text-xl font-semibold text-gray-700 mb-4 border-b pb-2">ข้อมูลเพิ่มเติม</h3>
                    <div class="space-y-4 text-gray-700">
                        <div><strong class="font-semibold text-gray-600">อุปกรณ์ที่ต้องการ:</strong> <?php echo htmlspecialchars($data['booking']->equipments_list ?: 'ไม่ได้ระบุ'); ?></div>
                        <div>
                            <strong class="font-semibold text-gray-600 block mb-2">รูปแบบการจัดห้อง:</strong>
                            <?php if($data['booking']->room_layout_image): ?>
                                <a href="<?php echo URLROOT; ?>/uploads/layouts/<?php echo $data['booking']->room_layout_image; ?>" target="_blank">
                                    <img src="<?php echo URLROOT; ?>/uploads/layouts/<?php echo $data['booking']->room_layout_image; ?>" 
                                        alt="รูปแบบการจัดห้อง" 
                                        class="max-w-xs h-auto rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300">
                                </a>
                            <?php else: ?>
                                <span class="text-gray-500">ไม่ได้ระบุ</span>
                            <?php endif; ?>
                        </div>
                        <div><strong class="font-semibold text-gray-600">หมายเหตุ:</strong> <?php echo htmlspecialchars($data['booking']->note ?: '-'); ?></div>
                    </div>
                </div>
            </div>
            <!-- END: New UI Block -->
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