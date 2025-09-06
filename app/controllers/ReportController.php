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
        // ตั้งค่า Default
        $startDate = date('Y-m-01'); // วันแรกของเดือนปัจจุบัน
        $endDate = date('Y-m-t');   // วันสุดท้ายของเดือนปัจจุบัน
        $status = 'all';
        $summary = null;
        $roomUsage = [];
        $dailyTrend = [];

        // ถ้ามีการส่งฟอร์มมา
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $startDate = $_POST['start_date'];
            $endDate = $_POST['end_date'];
            $status = $_POST['status'];
        }

        // ดึงข้อมูลเมื่อมีวันที่ครบถ้วน
        if(!empty($startDate) && !empty($endDate)){
            $summary = $this->reportModel->getSummaryStats($startDate, $endDate, $status);
            $roomUsage = $this->reportModel->getRoomUsage($startDate, $endDate, $status);
            $dailyTrend = $this->reportModel->getDailyTrend($startDate, $endDate, $status);
        }

        $data = [
            'title' => 'รายงานขั้นสูง',
            'active_menu' => 'reports',
            'startDate' => $startDate,
            'endDate' => $endDate,
            'status' => $status,
            'summary' => $summary,
            'roomUsage' => $roomUsage,
            'dailyTrend' => $dailyTrend
        ];
        
        // จัดการการ Export (ย้ายมาไว้ข้างล่าง)
        if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['export'])){
            $bookings = $this->reportModel->getBookingsByDateRange($startDate, $endDate, $status);
            $this->exportToCsv($bookings, $startDate, $endDate);
            exit();
        }

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