<?php require APPROOT . '/views/inc/header.php'; ?>

<!-- div หลักของ Layout -->
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

    <!-- START: Main Content Area -->
    <div class="flex flex-col flex-1 md:w-0">

        <!-- Top Navigation -->
        <?php include APPROOT . '/views/inc/topnav.php'; ?>

        <!-- START: Page Content Wrapper -->
        <main class="flex-1 overflow-y-auto p-4 md:p-8">
            <?php flash('booking_message'); ?>

            <!-- Wrapper สำหรับจัดการเนื้อหาที่กว้าง (ปฏิทิน) -->
            <div class="bg-white/50 backdrop-blur-xl p-6 rounded-2xl shadow-lg h-full">
                <div class="overflow-x-auto">
                    <!-- ปรับแก้: เอา h-full ออกจาก calendar-container -->
                    <div id="calendar-container" class="min-w-[700px]"></div>
                </div>

                <!-- 
                ============================================================
                START: เพิ่มส่วน Legend เข้ามาใหม่
                ============================================================
                -->
                <div class="mt-4 border-t pt-4">
                    <h4 class="font-semibold text-gray-600 mb-2">สีประจำห้อง:</h4>
                    <div class="flex flex-wrap gap-x-4 gap-y-2">
                        <?php if (!empty($data['rooms'])) : ?>
                            <?php foreach ($data['rooms'] as $room) : ?>
                                <div class="flex items-center">
                                    <span class="w-4 h-4 rounded-full mr-2" style="background-color: <?php echo htmlspecialchars($room->color); ?>"></span>
                                    <span class="text-sm text-gray-700"><?php echo htmlspecialchars($room->name); ?></span>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
                <!-- END: ส่วน Legend -->

            </div>
        </main>
        <!-- END: Page Content Wrapper -->

    </div>
    <!-- END: Main Content Area -->

</div>

<!-- FullCalendar script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar-container');
    
    const formatThaiForModal = (dateStr) => {
        const date = new Date(dateStr);
        const options = { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit', hour12: false };
        const formattedDate = new Intl.DateTimeFormat('th-TH', options).format(date);
        return formattedDate.replace(',', ' เวลา') + ' น.';
    };

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'th',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,listWeek'
        },
        eventTimeFormat: {
            hour: '2-digit',
            minute: '2-digit',
            hour12: false
        },
        events: '<?php echo URLROOT; ?>/booking/getEvents',
        
        eventContent: function(arg) {
            let timeText = arg.timeText;
            let title = arg.event.title;
            let roomName = arg.event.extendedProps.room_name;
            let bgColor = arg.event.backgroundColor || '#3788d8'; 
            let textColor = '#ffffff';

            // สร้าง Element หลักของ Event
            let eventEl = document.createElement('div');
            eventEl.classList.add('fc-event-main', 'flex', 'items-center', 'w-full', 'h-full', 'p-1', 'overflow-hidden');
            eventEl.style.backgroundColor = bgColor;
            eventEl.style.color = textColor;
            
            // 1. สร้าง "แถบสี" ด้านซ้าย
            let borderEl = document.createElement('div');
            borderEl.classList.add('w-1', 'h-full', 'mr-2');
            // ทำให้แถบสีเข้มขึ้นเล็กน้อยเพื่อความสวยงาม
            borderEl.style.backgroundColor = 'rgba(0,0,0,0.2)';

            // 2. สร้าง "เนื้อหา" (เวลา + หัวข้อ)
            let contentEl = document.createElement('div');
            contentEl.innerHTML = `<b>${timeText}</b> <span>${title} (${roomName})</span>`;

            // 3. ประกอบร่าง
            eventEl.appendChild(borderEl);
            eventEl.appendChild(contentEl);
            
            // ส่งกลับไปในรูปแบบของ DOM Nodes
            return { domNodes: [eventEl] };
        },

        eventClick: function(info) {
            info.jsEvent.preventDefault();
            let props = info.event.extendedProps;
            let title = info.event.title;
            let startTime = formatThaiForModal(info.event.start);
            let endTime = formatThaiForModal(info.event.end);
            
            // ---- 1. แก้ไขส่วนรูปภาพ ----
            let layoutImageHtml = '';
            if (props.room_layout_image) {
                let imageUrl = `<?php echo URLROOT; ?>/uploads/layouts/${props.room_layout_image}`;
                layoutImageHtml = `
                    <a href="${imageUrl}" target="_blank">
                        <img src="${imageUrl}" alt="รูปแบบการจัดห้อง" class="max-w-xs mt-2 h-auto rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300">
                    </a>
                `;
            } else {
                layoutImageHtml = '<span class="text-gray-500">ไม่ได้ระบุ</span>';
            }

            // ---- 2. แก้ไขส่วนสถานะ ----
            let status_class = '';
            let status_text = '';
            switch (props.status) {
                case 'approved':
                    status_class = 'bg-green-100 text-green-800';
                    status_text = 'อนุมัติแล้ว';
                    break;
                case 'pending':
                    status_class = 'bg-yellow-100 text-yellow-800';
                    status_text = 'รออนุมัติ';
                    break;
                case 'rejected':
                    status_class = 'bg-red-100 text-red-800';
                    status_text = 'ปฏิเสธ';
                    break;
                default:
                    status_text = props.status;
            }

            Swal.fire({
                title: `<strong class="text-xl">${title}</strong>`,
                html: `
                    <div class="text-left p-2 space-y-3">
                        <p><strong>ห้องประชุม:</strong> ${props.room_name}</p>
                        <p><strong>ผู้จอง:</strong> ${props.user_full_name}</p>
                        <p><strong>ฝ่าย/งาน:</strong> ${props.department || '-'}</p>
                        <p><strong>เบอร์โทร:</strong> ${props.phone}</p>
                        <p><strong>จำนวนผู้เข้าใช้:</strong> ${props.attendees} คน</p>
                        <hr class="my-2">
                        <p><strong>เวลาเริ่ม:</strong> ${startTime}</p>
                        <p><strong>เวลาสิ้นสุด:</strong> ${endTime}</p>
                        <hr class="my-2">
                        <p><strong>อุปกรณ์ที่ต้องการ:</strong> ${props.equipments_list || '-'}</p>
                        <p><strong>หมายเหตุ:</strong> ${props.note || '-'}</p>
                        <p><strong>รูปแบบการจัดห้อง:</strong> ${layoutImageHtml}</p>
                        <hr class="my-2">
                        <p><strong>สถานะ:</strong> <span class="px-3 py-1.5 text-sm font-bold leading-tight rounded-full ${status_class}">${status_text}</span></p>
                    </div>
                `,
                showCloseButton: true,
                focusConfirm: false,
                confirmButtonText: 'ปิด',
                confirmButtonColor: '#a855f7',
            });
        }
    });

    calendar.render();
});
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>