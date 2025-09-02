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
            
            <!-- Stat Cards -->
            <!-- ปรับ Grid Layout ให้เหมาะสมกับทุกขนาดจอ -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h4 class="text-gray-500 font-semibold">การจองทั้งหมด</h4>
                    <p class="text-3xl font-bold text-pink-500"><?php echo $data['totalBookings']; ?> <span class="text-lg">รายการ</span></p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h4 class="text-gray-500 font-semibold">การจองที่รออนุมัติ</h4>
                    <p class="text-3xl font-bold text-yellow-500"><?php echo $data['pendingBookings']; ?> <span class="text-lg">รายการ</span></p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h4 class="text-gray-500 font-semibold">ห้องทั้งหมด</h4>
                    <p class="text-3xl font-bold text-purple-500"><?php echo $data['totalRooms']; ?> <span class="text-lg">ห้อง</span></p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h4 class="text-gray-500 font-semibold">ผู้ใช้ทั้งหมด</h4>
                    <p class="text-3xl font-bold text-blue-500"><?php echo $data['totalUsers']; ?> <span class="text-lg">คน</span></p>
                </div>
            </div>

            <!-- Chart -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h4 class="text-xl font-semibold mb-4">ยอดการจองรายเดือน (ปีปัจจุบัน)</h4>
                <!-- เพิ่ม div ครอบ canvas เพื่อให้ responsive ดีขึ้น -->
                <div class="relative h-96">
                    <canvas id="monthlyBookingsChart"></canvas>
                </div>
            </div>

        </main>
        <!-- END: Page Content Wrapper -->

    </div>
    <!-- END: Main Content Area -->

</div>


<!-- Chart.js Script -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('monthlyBookingsChart').getContext('2d');
        
        const monthlyData = <?php echo json_encode($data['monthlyBookings']); ?>;

        const labels = monthlyData.map(item => item.month);
        const totals = monthlyData.map(item => item.total);

        const myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'จำนวนการจอง',
                    data: totals,
                    backgroundColor: 'rgba(236, 72, 153, 0.5)',
                    borderColor: 'rgba(236, 72, 153, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                // ทำให้กราฟปรับขนาดตาม container
                responsive: true,
                maintainAspectRatio: false, // <-- สำคัญมากสำหรับกราฟใน Flexbox/Grid
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        position: 'top',
                    }
                }
            }
        });
    });
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>