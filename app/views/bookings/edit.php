<?php require APPROOT . '/views/inc/header.php'; ?>
<div x-data="{ sidebarOpen: false }" class="relative md:flex min-h-full">
    <?php include APPROOT . '/views/inc/sidebar.php'; ?>
    <div class="flex-1 flex flex-col w-0">
        <?php include APPROOT . '/views/inc/topnav.php'; ?>
        <main class="flex-1 p-4 md:p-8">
            <div class="overflow-x-auto">
                <a href="<?php echo URLROOT; ?>/booking/show/<?php echo $data['booking']->id; ?>" class="text-gray-500 hover:text-gray-700 mb-4 inline-block">&larr; กลับไปหน้ารายละเอียด</a>
                <div class="bg-white p-6 md:p-8 rounded-lg shadow-md max-w-4xl mx-auto">
                    <h2 class="text-2xl font-bold mb-6"><?php echo $data['title']; ?>: <?php echo htmlspecialchars($data['booking']->subject); ?></h2>
                    
                    <form action="<?php echo URLROOT; ?>/booking/edit/<?php echo $data['booking']->id; ?>" method="post" enctype="multipart/form-data">
                        <!-- Room & Subject -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                            <div>
                                <label for="room_id" class="block text-gray-700">ห้องประชุม <sup class="text-red-500">*</sup></label>
                                <select name="room_id" class="w-full mt-1 px-3 py-2 border rounded">
                                    <?php foreach($data['rooms'] as $room): ?>
                                        <option value="<?php echo $room->id; ?>" <?php echo ($data['booking']->room_id == $room->id) ? 'selected' : ''; ?>>
                                            <?php echo $room->name; ?> (<?php echo $room->capacity; ?> คน)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label for="subject" class="block text-gray-700">หัวข้อการประชุม <sup class="text-red-500">*</sup></label>
                                <input type="text" name="subject" class="w-full mt-1 px-3 py-2 border rounded" value="<?php echo htmlspecialchars($data['booking']->subject); ?>">
                            </div>
                        </div>
                        
                        <!-- Date & Time -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                            <div>
                                <label class="block text-gray-700">วัน-เวลา เริ่ม</label>
                                <div class="flex flex-col sm:flex-row gap-2 mt-1">
                                    <input type="date" name="start_date" class="w-full px-3 py-2 border rounded" value="<?php echo date('Y-m-d', strtotime($data['booking']->start_time)); ?>">
                                    <input type="time" name="start_time" class="w-full px-3 py-2 border rounded" value="<?php echo date('H:i', strtotime($data['booking']->start_time)); ?>">
                                </div>
                            </div>
                             <div>
                                <label class="block text-gray-700">วัน-เวลา สิ้นสุด</label>
                                <div class="flex flex-col sm:flex-row gap-2 mt-1">
                                    <input type="date" name="end_date" class="w-full px-3 py-2 border rounded" value="<?php echo date('Y-m-d', strtotime($data['booking']->end_time)); ?>">
                                    <input type="time" name="end_time" class="w-full px-3 py-2 border rounded" value="<?php echo date('H:i', strtotime($data['booking']->end_time)); ?>">
                                </div>
                            </div>
                        </div>

                        <!-- Contact & Attendees -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-4">
                            <div>
                                <label for="department" class="block text-gray-700">ฝ่าย/หน่วยงาน</label>
                                <input type="text" name="department" class="w-full mt-1 px-3 py-2 border rounded" value="<?php echo htmlspecialchars($data['booking']->department); ?>">
                            </div>
                            <div>
                                <label for="phone" class="block text-gray-700">เบอร์โทรติดต่อ</label>
                                <input type="text" name="phone" class="w-full mt-1 px-3 py-2 border rounded" value="<?php echo htmlspecialchars($data['booking']->phone); ?>">
                            </div>
                            <div>
                                <label for="attendees" class="block text-gray-700">จำนวนผู้เข้าร่วม</label>
                                <input type="number" name="attendees" class="w-full mt-1 px-3 py-2 border rounded" value="<?php echo $data['booking']->attendees; ?>">
                            </div>
                        </div>

                        <!-- Equipments -->
                        <div class="mb-4">
                            <label class="block text-gray-700">อุปกรณ์ที่ต้องการ</label>
                            <div class="mt-2 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                                <?php foreach($data['all_equipments'] as $equipment): ?>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="equipments[]" value="<?php echo $equipment->id; ?>" class="h-4 w-4 rounded border-gray-300" <?php echo in_array($equipment->id, $data['selected_equipments']) ? 'checked' : ''; ?>>
                                        <span class="ml-2 text-gray-700"><?php echo $equipment->name; ?></span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        
                        <!-- Room Layout Image Upload -->
                        <div class="mb-4">
                            <label class="block text-gray-700">รูปแบบการจัดห้อง (ถ้ามี)</label>
                            <?php if($data['booking']->room_layout_image): ?>
                                <p class="text-sm text-gray-500 my-2">ไฟล์ปัจจุบัน: <a href="<?php echo URLROOT; ?>/uploads/layouts/<?php echo $data['booking']->room_layout_image; ?>" target="_blank" class="text-blue-500"><?php echo $data['booking']->room_layout_image; ?></a></p>
                                <input type="hidden" name="existing_layout_image" value="<?php echo $data['booking']->room_layout_image; ?>">
                            <?php endif; ?>
                            <input type="file" name="room_layout_image" class="w-full mt-1 text-sm ...">
                        </div>
                        
                        <!-- Note -->
                        <div class="mb-6">
                            <label for="note" class="block text-gray-700">หมายเหตุ</label>
                            <textarea name="note" rows="3" class="w-full mt-1 px-3 py-2 border rounded"><?php echo htmlspecialchars($data['booking']->note); ?></textarea>
                        </div>
                        
                        <div class="mt-6">
                            <button type="submit" class="w-full sm:w-auto bg-pink-500 text-white font-bold py-2 px-6 rounded-lg hover:bg-pink-600">บันทึกการเปลี่ยนแปลง</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>
<?php require APPROOT . '/views/inc/footer.php'; ?>