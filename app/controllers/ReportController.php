<?php
class ReportController extends Controller {
    private $reportModel;

    public function __construct(){
        if(!isLoggedIn() || $_SESSION['user_role'] !== 'admin'){
            header('location: ' . URLROOT . '/dashboard');
            exit();
        }
        $this->reportModel = $this->model('Report');
    }

    public function index(){
        $bookings = [];
        // ตั้งค่าวันที่เริ่มต้นและสิ้นสุดเป็นค่าว่าง
        $startDate = '';
        $endDate = '';

        // ตรวจสอบว่ามีการส่งฟอร์มมาหรือไม่
        if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['filter'])){
            $startDate = $_POST['start_date'];
            $endDate = $_POST['end_date'];
            if(!empty($startDate) && !empty($endDate)){
                $bookings = $this->reportModel->getBookingsByDateRange($startDate, $endDate);
            }
        }
        
        // จัดการการ Export
        if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['export'])){
            $startDate = $_POST['start_date'];
            $endDate = $_POST['end_date'];
            if(!empty($startDate) && !empty($endDate)){
                $bookings = $this->reportModel->getBookingsByDateRange($startDate, $endDate);
                $this->exportToCsv($bookings, $startDate, $endDate);
            }
            // หยุดการทำงานหลังจาก export เสร็จ
            exit();
        }

        $data = [
            'title' => 'รายงานการจองห้องประชุม',
            'active_menu' => 'reports',
            'bookings' => $bookings,
            'startDate' => $startDate,
            'endDate' => $endDate
        ];
        $this->view('reports/index', $data);
    }

    // ฟังก์ชันสำหรับสร้างและดาวน์โหลดไฟล์ CSV
    private function exportToCsv($data, $start, $end){
        $filename = "booking_report_{$start}_to_{$end}.csv";
        
        // ตั้งค่า Header สำหรับการดาวน์โหลด
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        // สร้าง file pointer เพื่อเขียนข้อมูล
        $output = fopen('php://output', 'w');
        
        // เพิ่ม BOM (Byte Order Mark) สำหรับ Excel เพื่อให้อ่านภาษาไทยได้ถูกต้อง
        fputs($output, "\xEF\xBB\xBF");
        
        // เขียนหัวข้อคอลัมน์
        fputcsv($output, ['ID', 'หัวข้อ', 'ห้อง', 'ผู้จอง', 'จำนวน', 'เวลาเริ่ม', 'เวลาสิ้นสุด', 'สถานะ']);
        
        // วนลูปเขียนข้อมูล
        foreach($data as $row){
            fputcsv($output, [
                $row->id,
                $row->subject,
                $row->room_name,
                $row->user_fullname,
                $row->attendees,
                date('Y-m-d H:i', strtotime($row->start_time)),
                date('Y-m-d H:i', strtotime($row->end_time)),
                $row->status
            ]);
        }
        
        fclose($output);
    }
}