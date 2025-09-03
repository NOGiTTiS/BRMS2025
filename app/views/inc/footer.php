    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Alpine.js for simple interactivity -->
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>

    <!-- เพิ่มฟังก์ชันยืนยันการ Logout -->
    <script>
    function confirmLogout() {
        Swal.fire({
            title: 'ยืนยันการออกจากระบบ',
            text: "คุณต้องการออกจากระบบใช่หรือไม่?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'ใช่, ออกจากระบบ',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                // ถ้ากดยืนยัน ให้ไปที่ URL Logout
                window.location.href = '<?php echo URLROOT; ?>/user/logout';
            }
        })
    }
    </script>

    <!-- Custom JS -->
    <script src="<?php echo URLROOT; ?>/js/main.js"></script>
</body>
</html>