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
        <!-- ส่วนนี้จะมี Scrollbar แนวตั้งถ้าเนื้อหายาว -->
        <main class="flex-1 overflow-y-auto p-4 md:p-8">
            
            <!-- Wrapper สำหรับจัดการเนื้อหาที่อาจจะกว้างเกินไป (เช่น ปฏิทิน) -->
            <div class="overflow-x-auto">
                
                <?php flash('booking_message'); ?>

                <!-- Glassmorphism Card -->
                <div class="bg-white/50 backdrop-blur-xl p-6 rounded-2xl shadow-lg h-full">
                    <!-- Calendar Container -->
                    <!-- กำหนดความกว้างขั้นต่ำเพื่อให้ปฏิทินไม่ถูกบีบจนเสียรูปบนจอมือถือ -->
                    <div id="calendar-container" class="h-full min-w-[700px]"></div>
                </div>
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

            let eventHtml = `
                <div class="fc-event-main-frame p-1 overflow-hidden" style="background-color: ${bgColor}; color: ${textColor}; border-color: ${bgColor};">
                    <b>${timeText}</b> <span>${title} (${roomName})</span>
                </div>
            `;
            return { html: eventHtml };
        },

        eventClick: function(info) {
            info.jsEvent.preventDefault();
            let props = info.event.extendedProps;
            let title = info.event.title;
            let startTime = formatThaiForModal(info.event.start);
            let endTime = formatThaiForModal(info.event.end);
            
            let layoutImageHtml = '';
            if (props.room_layout_image) {
                let imageUrl = `<?php echo URLROOT; ?>/uploads/layouts/${props.room_layout_image}`;
                layoutImageHtml = `<a href="${imageUrl}" target="_blank" class="text-blue-500 hover:underline">ดูรูปภาพ</a>`;
            } else {
                layoutImageHtml = 'ไม่มี';
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
                        <p><strong>สถานะ:</strong> <span class="px-2 py-1 bg-green-200 text-green-800 rounded-full text-sm">${props.status}</span></p>
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