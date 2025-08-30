<?php
class RoomController extends Controller {
    private $roomModel;

    public function __construct(){
        if(!isLoggedIn() || $_SESSION['user_role'] !== 'admin'){
            header('location: ' . URLROOT . '/dashboard');
            exit(); // <-- เพิ่มบรรทัดนี้ เพื่อให้แน่ใจว่าโค้ดหยุดทำงานตรงนี้
        }
        
        $this->roomModel = $this->model('Room');
    }

    // หน้าแสดงรายการห้องทั้งหมด
    public function index(){
        $rooms = $this->roomModel->getRooms();
        $data = [
            'title' => 'จัดการห้องประชุม',
            'active_menu' => 'rooms',
            'rooms' => $rooms
        ];
        $this->view('rooms/index', $data);
    }

    // หน้าเพิ่มห้อง
    public function add(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $sanitized_post = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $data = [
                'name' => trim($sanitized_post['name']),
                'capacity' => trim($sanitized_post['capacity']),
                'description' => trim($sanitized_post['description']),
                'color' => $sanitized_post['color'],
                'name_err' => '', 'capacity_err' => ''
            ];

            if(empty($data['name'])){ $data['name_err'] = 'กรุณากรอกชื่อห้อง'; }
            if(empty($data['capacity'])){ $data['capacity_err'] = 'กรุณากรอกความจุ'; }

            if(empty($data['name_err']) && empty($data['capacity_err'])){
                if($this->roomModel->addRoom($data)){
                    flash('room_message', 'เพิ่มห้องประชุมสำเร็จ', 'swal-success');
                    header('location: ' . URLROOT . '/room');
                } else {
                    die('Something went wrong');
                }
            } else {
                $this->view('rooms/add', $data);
            }
        } else {
            $data = [
                'title' => 'เพิ่มห้องประชุมใหม่',
                'active_menu' => 'rooms',
                'name' => '', 'capacity' => '', 'description' => '', 'color' => '#3788d8',
                'name_err' => '', 'capacity_err' => ''
            ];
            $this->view('rooms/add', $data);
        }
    }

    // หน้าแก้ไขห้อง
    public function edit($id){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $sanitized_post = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $data = [
                'id' => $id,
                'name' => trim($sanitized_post['name']),
                'capacity' => trim($sanitized_post['capacity']),
                'description' => trim($sanitized_post['description']),
                'color' => $sanitized_post['color'],
                'name_err' => '', 'capacity_err' => ''
            ];

            if(empty($data['name'])){ $data['name_err'] = 'กรุณากรอกชื่อห้อง'; }
            if(empty($data['capacity'])){ $data['capacity_err'] = 'กรุณากรอกความจุ'; }

            if(empty($data['name_err']) && empty($data['capacity_err'])){
                if($this->roomModel->updateRoom($data)){
                    flash('room_message', 'อัปเดตข้อมูลห้องสำเร็จ', 'swal-success');
                    header('location: ' . URLROOT . '/room');
                } else {
                    die('Something went wrong');
                }
            } else {
                $this->view('rooms/edit', $data);
            }
        } else {
            $room = $this->roomModel->getRoomById($id);
            $data = [
                'title' => 'แก้ไขข้อมูลห้องประชุม',
                'active_menu' => 'rooms',
                'id' => $id,
                'name' => $room->name,
                'capacity' => $room->capacity,
                'description' => $room->description,
                'color' => $room->color,
                'name_err' => '', 'capacity_err' => ''
            ];
            $this->view('rooms/edit', $data);
        }
    }

    // ลบห้อง
    public function delete($id){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            if($this->roomModel->deleteRoom($id)){
                flash('room_message', 'ลบห้องประชุมสำเร็จ', 'swal-success');
                header('location: ' . URLROOT . '/room');
            } else {
                die('Something went wrong');
            }
        } else {
            header('location: ' . URLROOT . '/room');
        }
    }
}