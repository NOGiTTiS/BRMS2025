<?php require APPROOT . '/views/inc/header.php'; ?>
<div x-data="{ sidebarOpen: false }" class="relative md:flex min-h-full">
    <?php include APPROOT . '/views/inc/sidebar.php'; ?>
    <div class="flex-1 flex flex-col w-0">
        <?php include APPROOT . '/views/inc/topnav.php'; ?>
        <main class="flex-1 p-4 md:p-8">
            <div class="overflow-x-auto">
                <h1 class="text-2xl font-bold mb-6"><?php echo $data['title']; ?></h1>
                <?php flash('booking_manage_message'); ?>
                <div class="bg-white shadow-md rounded-lg overflow-hidden">
                    <table class="min-w-full leading-normal">
                        <thead>
                            <tr>
                                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase">หัวข้อ</th>
                                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase">ผู้จอง</th>
                                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase">ห้อง</th>
                                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase">เวลาเริ่ม</th>
                                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase">สถานะ</th>
                                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($data['bookings'] as $booking) : ?>
                            <tr>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm"><?php echo $booking->subject; ?></td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm"><?php echo $booking->user_username; ?></td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm"><?php echo $booking->room_name; ?></td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm"><?php echo date('d/m/Y H:i', strtotime($booking->start_time)); ?></td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <?php
                                        $status_class = '';
                                        $status_text = '';
                                        switch ($booking->status) {
                                            case 'approved':
                                                $status_class = 'bg-green-200 text-green-800';
                                                $status_text = 'อนุมัติ';
                                                break;
                                            case 'pending':
                                                $status_class = 'bg-yellow-200 text-yellow-800';
                                                $status_text = 'รออนุมัติ';
                                                break;
                                            case 'rejected':
                                                $status_class = 'bg-red-200 text-red-800';
                                                $status_text = 'ปฏิเสธ';
                                                break;
                                            default:
                                                $status_text = $booking->status; // กรณีมีสถานะอื่นที่เราไม่รู้จัก
                                                break;
                                        }
                                    ?>
                                    <span class="px-2 py-1 font-semibold leading-tight rounded-full <?php echo $status_class; ?>">
                                        <?php echo $status_text; // <-- เปลี่ยนมาแสดงผลตัวแปรภาษาไทยแทน ?>
                                    </span>
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <?php if($booking->status == 'pending') : ?>
                                        <div class="flex items-center space-x-2">
                                            <form action="<?php echo URLROOT; ?>/booking/approve/<?php echo $booking->id; ?>" method="post" class="inline-block">
                                                <button type="submit" class="text-green-600 hover:text-green-900">อนุมัติ</button>
                                            </form>
                                            <form action="<?php echo URLROOT; ?>/booking/reject/<?php echo $booking->id; ?>" method="post" class="inline-block">
                                                <button type="submit" class="text-red-600 hover:text-red-900">ปฏิเสธ</button>
                                            </form>
                                        </div>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
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
<?php require APPROOT . '/views/inc/footer.php'; ?>