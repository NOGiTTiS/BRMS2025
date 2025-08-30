<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="bg-pink-50">
    <div x-data="{ sidebarOpen: false }" class="relative md:flex min-h-full">

        <!-- Overlay & Sidebar (คัดลอกจากหน้าปฏิทินมาได้เลย) -->
        <?php include APPROOT . '/views/inc/sidebar.php'; ?>

        <!-- START: Main Content Area -->
        <div class="flex-1 flex flex-col w-0">

            <!-- Top Navigation -->
            <?php include APPROOT . '/views/inc/topnav.php'; ?>

            <!-- START: Page Content -->
            <main class="flex-1 p-4 md:p-8">
                <!-- Stat Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
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
                    <canvas id="monthlyBookingsChart"></canvas>
                </div>
            </main>
            <!-- END: Page Content -->

        </div>
        <!-- END: Main Content Area -->
    </div>
</div>

<!-- Alpine.js -->
<script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>

<!-- Chart.js Script -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('monthlyBookingsChart').getContext('2d');
        
        // แปลงข้อมูลจาก PHP เป็น JavaScript
        const monthlyData = <?php echo json_encode($data['monthlyBookings']); ?>;

        // เตรียมข้อมูลสำหรับ Chart.js
        const labels = monthlyData.map(item => item.month);
        const totals = monthlyData.map(item => item.total);

        const myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'จำนวนการจอง',
                    data: totals,
                    backgroundColor: 'rgba(236, 72, 153, 0.5)', // Pink with transparency
                    borderColor: 'rgba(236, 72, 153, 1)',     // Solid Pink
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: false,
                        text: 'ยอดการจองรายเดือน'
                    }
                }
            }
        });
    });
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>