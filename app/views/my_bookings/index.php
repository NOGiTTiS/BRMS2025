<?php require APPROOT . '/views/inc/header.php'; ?>
<div x-data="{ sidebarOpen: false }" class="relative md:flex min-h-full">
    <?php include APPROOT . '/views/inc/sidebar.php'; ?>
    <div class="flex-1 flex flex-col w-0">
        <?php include APPROOT . '/views/inc/topnav.php'; ?>
        <main class="flex-1 p-4 md:p-8">
            <div class="overflow-x-auto">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold"><?php echo $data['title']; ?></h1>
                    <a href="<?php echo URLROOT; ?>/booking/create" class="bg-green-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-green-600 transition">จองห้องประชุม</a>
                </div>

                <div class="bg-white shadow-md rounded-lg overflow-hidden">
                    <table class="min-w-full leading-normal">
                        <thead>
                            <tr>
                                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase">หัวข้อ</th>
                                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase">ห้อง</th>
                                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase">เวลาเริ่ม</th>
                                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase">สถานะ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($data['bookings'])): ?>
                                <tr>
                                    <td colspan="4" class="text-center py-10 text-gray-500">
                                        คุณยังไม่มีประวัติการจอง
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach($data['bookings'] as $booking) : ?>
                                <tr>
                                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm"><?php echo htmlspecialchars($booking->subject); ?></td>
                                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm"><?php echo htmlspecialchars($booking->room_name); ?></td>
                                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm"><?php echo date('d/m/Y H:i', strtotime($booking->start_time)); ?></td>
                                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                        <?php
                                            $status_class = '';
                                            $status_text = '';
                                            switch ($booking->status) {
                                                case 'approved': $status_class = 'bg-green-200 text-green-800'; $status_text = 'อนุมัติแล้ว'; break;
                                                case 'pending': $status_class = 'bg-yellow-200 text-yellow-800'; $status_text = 'รออนุมัติ'; break;
                                                case 'rejected': $status_class = 'bg-red-200 text-red-800'; $status_text = 'ปฏิเสธ'; break;
                                            }
                                        ?>
                                        <span class="px-2 py-1 font-semibold leading-tight rounded-full <?php echo $status_class; ?>">
                                            <?php echo $status_text; ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</div>
<?php require APPROOT . '/views/inc/footer.php'; ?>