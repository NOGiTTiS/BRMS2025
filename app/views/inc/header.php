<!DOCTYPE html>
<html lang="th" class="h-full bg-pink-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- เปลี่ยน Title -->
    <title><?php echo $data['title']; ?> | <?php echo setting('site_name'); ?></title>
    <!-- เพิ่ม Favicon -->
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

    <!-- START: Flash Message Handler -->
    <?php if(isset($_SESSION['notification'])): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: '<?php echo $_SESSION['notification_type']; ?>',
                    title: '<?php echo $_SESSION['notification']; ?>',
                    confirmButtonText: 'ตกลง',
                    confirmButtonColor: '#a855f7'
                });
            });
        </script>
        <?php
            // ล้าง session หลังจากเตรียมสคริปต์แล้ว
            unset($_SESSION['notification']);
            unset($_SESSION['notification_type']);
        ?>
    <?php endif; ?>
    <!-- END: Flash Message Handler -->

</head>
<body class="h-full">