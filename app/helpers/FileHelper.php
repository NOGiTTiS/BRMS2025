<?php
class FileHelper {
    public static function upload($file, $uploadDir = 'uploads/layouts/'){
        // ตรวจสอบว่ามีไฟล์ส่งมาหรือไม่ และไม่มี error
        if(isset($file) && $file['error'] === UPLOAD_ERR_OK){
            $fileName = basename($file['name']);
            $fileTmpName = $file['tmp_name'];
            $fileSize = $file['size'];
            $fileType = $file['type'];
            $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            // สร้าง Path เต็มไปยังโฟลเดอร์ public
            $fullUploadDir = dirname(APPROOT) . '/public/' . $uploadDir;

            // ตรวจสอบว่ามีโฟลเดอร์นี้อยู่จริงหรือไม่ ถ้าไม่มีให้สร้างขึ้นมา
            if (!is_dir($fullUploadDir)) {
                mkdir($fullUploadDir, 0777, true);
            }
            
            // ตั้งชื่อไฟล์ใหม่ที่ไม่ซ้ำกัน เพื่อป้องกันการเขียนทับ
            $newFileName = uniqid('', true) . '.' . $fileExt;
            $destination = $fullUploadDir . $newFileName;

            // ตรวจสอบประเภทไฟล์ที่อนุญาต
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'pdf'];
            if(in_array($fileExt, $allowedTypes)){
                // ตรวจสอบขนาดไฟล์ (เช่น ไม่เกิน 5MB)
                if($fileSize < 5000000){
                    // ย้ายไฟล์จาก temp folder ไปยัง destination
                    if(move_uploaded_file($fileTmpName, $destination)){
                        return $newFileName; // คืนค่า "ชื่อไฟล์ใหม่" กลับไปให้ Controller
                    } else {
                        return ['error' => 'ไม่สามารถย้ายไฟล์ได้'];
                    }
                } else {
                    return ['error' => 'ขนาดไฟล์ใหญ่เกิน 5MB'];
                }
            } else {
                return ['error' => 'ประเภทไฟล์ไม่ได้รับอนุญาต'];
            }
        }
        // ถ้าไม่มีไฟล์ส่งมาเลย ให้คืนค่า null
        return null; 
    }
}