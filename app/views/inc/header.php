<!DOCTYPE html>
<html lang="th" class="h-full <?php echo (setting('grayscale_mode_enabled', '0') == '1') ? 'grayscale-mode' : ''; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $data['title']; ?> | <?php echo setting('site_name'); ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo URLROOT; ?>/uploads/favicons/<?php echo setting('site_favicon'); ?>?v=<?php echo time(); ?>">
    
    <!-- CSS & Fonts -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/style.css">

    <!-- JS Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.14/index.global.min.js'></script>
    
    <style>
        body { font-family: 'Prompt', sans-serif; }
    </style>

        <!-- 
        ============================================================
        START: Flash Message Handler (เรียกใช้ฟังก์ชันใหม่)
        ============================================================
        -->
        <?php display_flash_messages(); ?>
        <!-- END: Flash Message Handler -->

    </head>
    <body class="h-full">