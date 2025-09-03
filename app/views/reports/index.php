<?php require APPROOT . '/views/inc/header.php'; ?>
<div x-data="{ sidebarOpen: false }" class="relative md:flex min-h-full">
    <?php include APPROOT . '/views/inc/sidebar.php'; ?>
    <div class="flex flex-col flex-1 md:w-0">
        <?php include APPROOT . '/views/inc/topnav.php'; ?>
        <main class="flex-1 overflow-y-auto p-4 md:p-8">
            <h1 class="text-2xl font-bold mb-6"><?php echo $data['title']; ?></h1>
            
            <!-- Filter Form -->
            <div class="bg-white p-6 rounded-lg shadow-md mb-8">
                <form action="<?php echo URLROOT; ?>/report" method="post">
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 items-end">
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700">วันที่เริ่มต้น</label>
                            <input type="date" name="start_date" id="start_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="<?php echo $data['startDate']; ?>" required>
                        </div>
                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700">วันที่สิ้นสุด</label>
                            <input type="date" name="end_date" id="end_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="<?php echo $data['endDate']; ?>" required>
                        </div>
                        <div class="flex space-x-2">
                            <button type="submit" name="filter" value="1" class="w-full bg-blue-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-blue-600">แสดงรายงาน</button>
                            <button type="submit" name="export" value="1" class="w-full bg-green-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-green-600">Export CSV</button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Report Table -->
            <div class="bg-white shadow-md rounded-lg overflow-x-auto">
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr>
                            <th class="px-5 py-3 border-b-2 bg-gray-100 text-left">หัวข้อ</th>
                            <th class="px-5 py-3 border-b-2 bg-gray-100 text-left">ห้อง</th>
                            <th class="px-5 py-3 border-b-2 bg-gray-100 text-left">ผู้จอง</th>
                            <th class="px-5 py-3 border-b-2 bg-gray-100 text-left">เวลาเริ่ม</th>
                            <th class="px-5 py-3 border-b-2 bg-gray-100 text-left">สถานะ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($data['bookings'])): ?>
                            <tr><td colspan="5" class="text-center py-10 text-gray-500">กรุณาเลือกช่วงวันที่แล้วกด "แสดงรายงาน"</td></tr>
                        <?php else: ?>
                            <?php foreach($data['bookings'] as $booking): ?>
                            <tr>
                                <td class="px-5 py-5 border-b bg-white text-sm"><?php echo htmlspecialchars($booking->subject); ?></td>
                                <td class="px-5 py-5 border-b bg-white text-sm"><?php echo htmlspecialchars($booking->room_name); ?></td>
                                <td class="px-5 py-5 border-b bg-white text-sm"><?php echo htmlspecialchars($booking->user_fullname); ?></td>
                                <td class="px-5 py-5 border-b bg-white text-sm whitespace-nowrap"><?php echo date('d/m/Y H:i', strtotime($booking->start_time)); ?></td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <?php
                                        $status_class = '';
                                        $status_text = '';
                                        switch ($booking->status) {
                                            case 'approved':
                                                $status_class = 'bg-green-200 text-green-800';
                                                $status_text = 'อนุมัติแล้ว';
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
                                                $status_text = htmlspecialchars($booking->status);
                                                break;
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
        </main>
    </div>
</div>
<?php require APPROOT . '/views/inc/footer.php'; ?>