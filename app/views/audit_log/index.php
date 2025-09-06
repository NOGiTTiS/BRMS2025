<?php require APPROOT . '/views/inc/header.php'; ?>
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

    <!-- Main Content Area -->
    <div class="flex flex-col flex-1 md:w-0">

        <!-- Top Navigation -->
        <?php include APPROOT . '/views/inc/topnav.php'; ?>

        <!-- Page Content Wrapper -->
        <main class="flex-1 overflow-y-auto p-4 md:p-8">
            
            <!-- Card ครอบตาราง พร้อม overflow-x-auto -->
            <div class="bg-white shadow-md rounded-lg overflow-x-auto">
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr>
                            <th class="px-5 py-3 border-b-2 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase">เวลา</th>
                            <th class="px-5 py-3 border-b-2 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase">ผู้ใช้</th>
                            <th class="px-5 py-3 border-b-2 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase">การกระทำ (Action)</th>
                            <th class="px-5 py-3 border-b-2 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase">รายละเอียด</th>
                            <th class="px-5 py-3 border-b-2 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase">IP Address</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($data['logs'])): ?>
                            <tr><td colspan="5" class="text-center py-10 text-gray-500">ยังไม่มีข้อมูล Log</td></tr>
                        <?php else: ?>
                            <?php foreach($data['logs'] as $log) : ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-5 py-4 border-b bg-white text-sm whitespace-nowrap">
                                    <p class="text-gray-900"><?php echo date('d/m/Y H:i:s', strtotime($log->created_at)); ?></p>
                                </td>
                                <td class="px-5 py-4 border-b bg-white text-sm">
                                    <p class="text-gray-900 whitespace-nowrap"><?php echo htmlspecialchars($log->username ?: 'N/A'); ?></p>
                                </td>
                                <td class="px-5 py-4 border-b bg-white text-sm">
                                    <span class="font-mono text-xs bg-gray-200 text-gray-800 px-2 py-1 rounded">
                                        <?php echo htmlspecialchars($log->action); ?>
                                    </span>
                                </td>
                                <td class="px-5 py-4 border-b bg-white text-sm">
                                    <p class="text-gray-700 whitespace-pre-wrap"><?php echo htmlspecialchars($log->details); ?></p>
                                </td>
                                <td class="px-5 py-4 border-b bg-white text-sm">
                                    <p class="text-gray-900 whitespace-nowrap"><?php echo htmlspecialchars($log->ip_address); ?></p>
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