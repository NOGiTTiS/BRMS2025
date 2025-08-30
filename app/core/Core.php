<?php
  /*
   * App Core Class
   * สร้าง URL และโหลด Core Controller
   * รูปแบบ URL - /controller/method/params
   */
  class Core {
    protected $currentController = 'PageController'; // Controller เริ่มต้น
    protected $currentMethod = 'index'; // Method เริ่มต้น
    protected $params = []; // Parameters

    public function __construct(){
      // print_r($this->getUrl());
      $url = $this->getUrl();

      // มองหา Controller จากส่วนแรกของ URL
      if(isset($url[0]) && file_exists('../app/controllers/' . ucwords($url[0]). 'Controller.php')){
        // ถ้าเจอ, ให้ตั้งเป็น Controller ปัจจุบัน
        $this->currentController = ucwords($url[0]) . 'Controller';
        // ลบออกจาก array เพราะเราใช้ไปแล้ว
        unset($url[0]);
      }

      // เรียกใช้ Controller
      require_once '../app/controllers/'. $this->currentController . '.php';

      // สร้าง instance ของ controller
      $this->currentController = new $this->currentController;

      // มองหา Method จากส่วนที่สองของ URL
      if(isset($url[1])){
        // ตรวจสอบว่ามี method นี้อยู่ใน controller หรือไม่
        if(method_exists($this->currentController, $url[1])){
          $this->currentMethod = $url[1];
          // ลบออกจาก array เพราะเราใช้ไปแล้ว
          unset($url[1]);
        }
      }

      // ดึงเอา Parameters ที่เหลือ
      $this->params = $url ? array_values($url) : [];

      // เรียกใช้งาน method พร้อมกับส่ง params ไปด้วย
      call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
    }

    public function getUrl(){
      if(isset($_GET['url'])){
        $url = rtrim($_GET['url'], '/'); // ตัด / ตัวสุดท้ายออก
        $url = filter_var($url, FILTER_SANITIZE_URL); // กรองอักขระที่ไม่ใช่ URL
        $url = explode('/', $url); // แยก URL ด้วย / แล้วแปลงเป็น Array
        return $url;
      }
    }
  }