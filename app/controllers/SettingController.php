<?php
class SettingController extends Controller {
    private $settingModel;

    public function __construct(){
        if(!isLoggedIn() || $_SESSION['user_role'] !== 'admin'){
            header('location: ' . URLROOT . '/dashboard');
            exit();
        }
        $this->settingModel = $this->model('Setting');
    }

    public function index(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            // จัดการการอัปเดตข้อมูล
            $settings = $_POST;
            
            // จัดการการอัปโหลดไฟล์ Logo
            if(isset($_FILES['site_logo']) && $_FILES['site_logo']['error'] === UPLOAD_ERR_OK){
                $newLogoName = FileHelper::upload($_FILES['site_logo'], 'uploads/logos/');
                if(!is_array($newLogoName)){
                    $this->settingModel->updateSetting('site_logo', $newLogoName);
                }
            }

            // จัดการการอัปโหลดไฟล์ Favicon
            if(isset($_FILES['site_favicon']) && $_FILES['site_favicon']['error'] === UPLOAD_ERR_OK){
                $newFaviconName = FileHelper::upload($_FILES['site_favicon'], 'uploads/favicons/');
                 if(!is_array($newFaviconName)){
                    $this->settingModel->updateSetting('site_favicon', $newFaviconName);
                }
            }
            
            // อัปเดตค่าอื่นๆ ที่เป็นข้อความ
            foreach($settings as $name => $value){
                $this->settingModel->updateSetting($name, $value);
            }
            AuditLogHelper::logAction('UPDATE_SETTINGS', 'Admin updated system settings.');
            flash('setting_message', 'บันทึกการตั้งค่าสำเร็จ', 'swal-success');
            header('location: ' . URLROOT . '/setting');
            exit();

        } else {
            // แสดงฟอร์ม
            $settings = $this->settingModel->getSettings();
            $data = [
                'title' => 'ตั้งค่าระบบ',
                'active_menu' => 'settings',
                'settings' => $settings
            ];
            $this->view('settings/index', $data);
        }
    }
}