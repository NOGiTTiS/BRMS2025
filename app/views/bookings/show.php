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
            
            <div class="mb-6">
                <a href="<?php echo URLROOT; ?>/booking" class="text-gray-500 hover:text-gray-700 inline-block">&larr; กลับไปหน้ารายการ</a>
            </div>

            <?php flash('booking_manage_message'); ?>

            <div class="max-w-4xl mx-auto">
                <!-- START: New UI Block -->
                <div class="space-y-6">
                    <!-- Header Card -->
                    <div class="bg-white p-6 rounded-lg shadow">
                        <div class="flex flex-col sm:flex-row justify-between items-start">
                            <div class="mb-4 sm:mb-0">
                                <h2 class="text-2xl md:text-3xl font-bold text-gray-800"><?php echo htmlspecialchars($data['booking']->subject); ?></h2>
                                <p class="text-sm text-gray-500 mt-1">ส่งคำขอโดย: <?php echo htmlspecialchars($data['booking']->user_full_name); ?></p>
                            </div>
                            <div class="flex-shrink-0">
                                <?php
                                    $status_class = ''; $status_text = '';
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
                    <div class="bg-white p-6 rounded-lg shadow">
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
                    <div class="bg-white p-6 rounded-lg shadow">
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
                
                <!-- Action Buttons (Only for pending requests) -->
                <?php if($data['booking']->status == 'pending'): ?>
                <div class="mt-6 bg-white p-6 rounded-lg shadow">
                    <h3 class="font-semibold mb-4">การดำเนินการ</h3>
                    <div class="flex items-center space-x-4">
                        <form action="<?php echo URLROOT; ?>/booking/approve/<?php echo $data['booking']->id; ?>" method="post" class="inline-block">
                            <button type="submit" class="w-full sm:w-auto bg-green-500 text-white font-bold py-2 px-6 rounded-lg hover:bg-green-600 transition">อนุมัติคำขอนี้</button>
                        </form>
                        <form action="<?php echo URLROOT; ?>/booking/reject/<?php echo $data['booking']->id; ?>" method="post" class="inline-block">
                            <button type="submit" class="w-full sm:w-auto bg-red-500 text-white font-bold py-2 px-6 rounded-lg hover:bg-red-600 transition">ปฏิเสธคำขอนี้</button>
                        </form>
                    </div>
                </div>
                <?php endif; ?>

            </div>

        </main>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>