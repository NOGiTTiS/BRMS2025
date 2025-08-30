<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="bg-pink-50">
    <div x-data="{ sidebarOpen: false }" class="relative md:flex h-screen">

        <!-- START: Overlay for mobile -->
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
        <!-- END: Overlay for mobile -->

        <!-- START: Sidebar -->
        <aside id="sidebar"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="absolute inset-y-0 left-0 bg-pink-600 text-white w-64 p-4 space-y-6 z-20
                   transform transition duration-300 ease-in-out
                   md:relative md:translate-x-0">
            
            <!-- Logo -->
            <a href="<?php echo URLROOT; ?>" class="text-white text-2xl font-bold flex items-center space-x-2">
                <span>BRMS</span>
            </a>

            <!-- Navigation Links -->
            <nav>
                <ul>
                    <?php if(isLoggedIn()) : ?>
                        <!-- เมนูหลัง Login -->
                        <li class="mb-2"><a href="#" class="flex items-center p-2 hover:bg-pink-700 rounded transition-colors"><span>📊</span><span class="ml-2">Dashboard</span></a></li>
                        <li class="mb-2"><a href="<?php echo URLROOT; ?>" class="flex items-center p-2 bg-pink-700 rounded transition-colors"><span>📅</span><span class="ml-2">ปฏิทิน</span></a></li>
                        <li class="mb-2"><a href="<?php echo URLROOT; ?>/user/logout" class="flex items-center p-2 hover:bg-pink-700 rounded transition-colors"><span>🚪</span><span class="ml-2">ออกจากระบบ</span></a></li>
                    <?php else : ?>
                        <!-- เมนูก่อน Login -->
                        <li class="mb-2"><a href="<?php echo URLROOT; ?>" class="flex items-center p-2 bg-pink-700 rounded transition-colors"><span>📅</span><span class="ml-2">ปฏิทิน</span></a></li>
                        <li class="mb-2"><a href="<?php echo URLROOT; ?>/user/login" class="flex items-center p-2 hover:bg-pink-700 rounded transition-colors"><span>🔒</span><span class="ml-2">เข้าสู่ระบบ</span></a></li>
                        <li class="mb-2"><a href="<?php echo URLROOT; ?>/user/register" class="flex items-center p-2 hover:bg-pink-700 rounded transition-colors"><span>👤</span><span class="ml-2">สมัครสมาชิก</span></a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </aside>
        <!-- END: Sidebar -->

        <!-- START: Main Content Area -->
        <div class="flex-1 flex flex-col">

            <!-- START: Top Navigation -->
            <header class="flex justify-between items-center p-4 bg-white shadow-md">
                <!-- Mobile Menu Button -->
                <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 focus:outline-none md:hidden">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 6H20M4 12H20M4 18H20" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </button>
                
                <!-- Page Title -->
                <h1 class="text-xl font-semibold text-gray-700 ml-2 md:ml-0">
                    <?php echo $data['title']; ?>
                </h1>

                <!-- Right side -->
                <div>
                    <?php if(isLoggedIn()) : ?>
                        <div class="flex items-center space-x-4">
                            <span class="text-gray-700">สวัสดี, <?php echo $_SESSION['user_name']; ?></span>
                            <a href="<?php echo URLROOT; ?>/user/logout" class="bg-red-500 text-white font-bold py-2 px-4 rounded-lg shadow-md hover:bg-red-600 transition">
                                ออกจากระบบ
                            </a>
                        </div>
                    <?php else : ?>
                        <a href="<?php echo URLROOT; ?>/user/login" class="bg-pink-500 text-white font-bold py-2 px-4 rounded-lg shadow-md hover:bg-pink-600 transition">
                            เข้าสู่ระบบ
                        </a>
                    <?php endif; ?>
                </div>
            </header>
            <!-- END: Top Navigation -->

            <!-- START: Page Content -->
            <main class="flex-1 p-4 md:p-8 overflow-y-auto">
                <div class="bg-white/50 backdrop-blur-xl p-6 rounded-2xl shadow-lg h-full">
                    <div id="calendar-container" class="h-full"></div>
                </div>
            </main>
            <!-- END: Page Content -->

        </div>
        <!-- END: Main Content Area -->

    </div>
</div>

<!-- Alpine.js for simple interactivity -->
<script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>

<!-- FullCalendar script from previous steps -->
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
                        <p><strong>อุปกรณ์ที่จอง:</strong> ${props.equipments_list || '-'}</p>
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