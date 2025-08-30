<?php
  /*
   * Base Controller
   * โหลด Models และ Views
   */
  class Controller {
    // โหลด model
    public function model($model){
      // Require model file
      require_once '../app/models/' . $model . '.php';

      // Instatiate model
      return new $model();
    }

    // โหลด view
    public function view($view, $data = []){
      // ตรวจสอบว่ามีไฟล์ view อยู่จริงหรือไม่
      if(file_exists('../app/views/' . $view . '.php')){
        require_once '../app/views/' . $view . '.php';
      } else {
        // ถ้าไม่มีไฟล์ view ให้หยุดการทำงานและแจ้งเตือน
        die('View does not exist');
      }
    }
  }