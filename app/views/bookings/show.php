<?php require APPROOT . '/views/inc/header.php'; ?>
<div x-data="{ sidebarOpen: false }" class="relative md:flex min-h-full">
    <?php include APPROOT . '/views/inc/sidebar.php'; ?>
    <div class="flex-1 flex flex-col w-0">
        <?php include APPROOT . '/views/inc/topnav.php'; ?>
        <main class="flex-1 p-4 md:p-8 overflow-x-auto">
            <a href="<?php echo URLROOT; ?>/booking" class="text-gray-500 hover:text-gray-700 mb-4 inline-block">&larr; กลับไปหน้ารายการ</a>
            <div class="bg-white p-8 rounded-lg shadow-md max-w-4xl mx-auto">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h2 class="text-3xl font-bold text-gray-800"><?php echo $data['booking']->subject; ?></h2>
                        <p class="text-sm text-gray-500">ส่งคำขอโดย: <?php echo $data['booking']->user_full_name; ?></p>
                    </div>
                    <div>
                         <?php
                            $status_class = '';
                            $status_text = '';
                            switch ($data['booking']->status) {
                                case 'approved': $status_class = 'bg-green-200 text-green-800'; $status_text = 'อนุมัติแล้ว'; break;
                                case 'pending': $status_class = 'bg-yellow-200 text-yellow-800'; $status_text = 'รออนุมัติ'; break;
                                case 'rejected': $status_class = 'bg-red-200 text-red-800'; $status_text = 'ปฏิเสธ'; break;
                            }
                        ?>
                        <span class="px-3 py-2 text-sm font-semibold leading-tight rounded-full <?php echo $status_class; ?>">
                            <?php echo $status_text; ?>
                        </span>
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-8 text-gray-700">
                    <!-- Column 1: Booking Details -->
                    <div class="space-y-4">
                        <div><strong class="font-semibold">ห้องประชุม:</strong> <?php echo $data['booking']->room_name; ?></div>
                        <div><strong class="font-semibold">ฝ่าย/หน่วยงาน:</strong> <?php echo $data['booking']->department ?: '-'; ?></div>
                        <div><strong class="font-semibold">เบอร์โทรติดต่อ:</strong> <?php echo $data['booking']->phone ?: '-'; ?></div>
                        <div><strong class="font-semibold">จำนวนผู้เข้าร่วม:</strong> <?php echo $data['booking']->attendees; ?> คน</div>
                    </div>
                    <!-- Column 2: Time Details -->
                    <div class="space-y-4">
                        <div><strong class="font-semibold">เวลาเริ่ม:</strong> <?php echo date('d M Y, H:i', strtotime($data['booking']->start_time)); ?> น.</div>
                        <div><strong class="font-semibold">เวลาสิ้นสุด:</strong> <?php echo date('d M Y, H:i', strtotime($data['booking']->end_time)); ?> น.</div>
                    </div>
                </div>

                <hr class="my-6">
                
                <div class="space-y-4 text-gray-700">
                    <div><strong class="font-semibold">อุปกรณ์ที่ต้องการ:</strong> <?php echo $data['booking']->equipments_list ?: 'ไม่ได้ระบุ'; ?></div>
                    <div>
                        <strong class="font-semibold block mb-2">รูปแบบการจัดห้อง:</strong> 
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
                    <div><strong class="font-semibold">หมายเหตุ:</strong> <?php echo $data['booking']->note ?: '-'; ?></div>
                </div>

                <!-- Action Buttons (Only for pending requests) -->
                <?php if($data['booking']->status == 'pending'): ?>
                <hr class="my-6">
                <div class="flex items-center space-x-4">
                    <h3 class="font-semibold">การดำเนินการ:</h3>
                    <form action="<?php echo URLROOT; ?>/booking/approve/<?php echo $data['booking']->id; ?>" method="post" class="inline-block">
                        <button type="submit" class="bg-green-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-green-600 transition">อนุมัติคำขอนี้</button>
                    </form>
                    <form action="<?php echo URLROOT; ?>/booking/reject/<?php echo $data['booking']->id; ?>" method="post" class="inline-block">
                        <button type="submit" class="bg-red-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-red-600 transition">ปฏิเสธคำขอนี้</button>
                    </form>
                </div>
                <?php endif; ?>

            </div>
        </main>
    </div>
</div>
<?php require APPROOT . '/views/inc/footer.php'; ?>