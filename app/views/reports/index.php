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
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-4 items-end">
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700">วันที่เริ่มต้น</label>
                            <input type="date" name="start_date" class="mt-1 block w-full rounded-md" value="<?php echo $data['startDate']; ?>" required>
                        </div>
                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700">วันที่สิ้นสุด</label>
                            <input type="date" name="end_date" class="mt-1 block w-full rounded-md" value="<?php echo $data['endDate']; ?>" required>
                        </div>
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">สถานะ</label>
                            <select name="status" class="mt-1 block w-full rounded-md">
                                <option value="all" <?php echo ($data['status'] == 'all') ? 'selected' : ''; ?>>ทั้งหมด</option>
                                <option value="approved" <?php echo ($data['status'] == 'approved') ? 'selected' : ''; ?>>อนุมัติแล้ว</option>
                                <option value="pending" <?php echo ($data['status'] == 'pending') ? 'selected' : ''; ?>>รออนุมัติ</option>
                                <option value="rejected" <?php echo ($data['status'] == 'rejected') ? 'selected' : ''; ?>>ปฏิเสธ</option>
                            </select>
                        </div>
                        <div class="col-span-1 md:col-span-2 flex space-x-2">
                            <button type="submit" name="filter" value="1" class="w-full bg-blue-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-blue-600">แสดงรายงาน</button>
                            <button type="submit" name="export" value="1" class="w-full bg-green-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-green-600">Export CSV</button>
                        </div>
                    </div>
                </form>
            </div>
            
            <?php if($data['summary']): ?>
            <!-- Summary Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white p-6 rounded-lg shadow-md"><h4 class="text-gray-500">การจองทั้งหมด</h4><p class="text-3xl font-bold text-pink-500"><?php echo $data['summary']->total_bookings; ?></p></div>
                <div class="bg-white p-6 rounded-lg shadow-md"><h4 class="text-gray-500">อนุมัติแล้ว</h4><p class="text-3xl font-bold text-green-500"><?php echo $data['summary']->approved_bookings; ?></p></div>
                <div class="bg-white p-6 rounded-lg shadow-md"><h4 class="text-gray-500">รออนุมัติ</h4><p class="text-3xl font-bold text-yellow-500"><?php echo $data['summary']->pending_bookings; ?></p></div>
                <div class="bg-white p-6 rounded-lg shadow-md"><h4 class="text-gray-500">ปฏิเสธ</h4><p class="text-3xl font-bold text-red-500"><?php echo $data['summary']->rejected_bookings; ?></p></div>
            </div>

            <!-- Charts -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h4 class="text-xl font-semibold mb-4">สัดส่วนการใช้งานห้อง</h4>
                    <div class="relative h-80"><canvas id="roomUsageChart"></canvas></div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h4 class="text-xl font-semibold mb-4">แนวโน้มการจองรายวัน</h4>
                    <div class="relative h-80"><canvas id="dailyTrendChart"></canvas></div>
                </div>
            </div>
            <?php else: ?>
                <div class="text-center py-10 bg-white rounded-lg shadow-md"><p class="text-gray-500">กรุณาเลือกช่วงวันที่แล้วกด "แสดงรายงาน"</p></div>
            <?php endif; ?>
        </main>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Room Usage Pie Chart
    if(document.getElementById('roomUsageChart')){
        const roomCtx = document.getElementById('roomUsageChart').getContext('2d');
        const roomData = <?php echo json_encode($data['roomUsage']); ?>;
        new Chart(roomCtx, {
            type: 'pie',
            data: {
                labels: roomData.map(d => d.room_name),
                datasets: [{
                    data: roomData.map(d => d.booking_count),
                    backgroundColor: ['#EC4899', '#8B5CF6', '#3B82F6', '#F59E0B', '#10B981', '#6366F1', '#EF4444'],
                }]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });
    }

    // Daily Trend Line Chart
    if(document.getElementById('dailyTrendChart')){
        const trendCtx = document.getElementById('dailyTrendChart').getContext('2d');
        const trendData = <?php echo json_encode($data['dailyTrend']); ?>;
        new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: trendData.map(d => new Date(d.booking_date).toLocaleDateString('th-TH')),
                datasets: [{
                    label: 'จำนวนการจอง',
                    data: trendData.map(d => d.booking_count),
                    borderColor: '#EC4899',
                    tension: 0.1
                }]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });
    }
});
</script>
<?php require APPROOT . '/views/inc/footer.php'; ?>