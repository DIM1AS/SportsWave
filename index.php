<?php
session_start();
$role = isset($_SESSION['role']) ? $_SESSION['role'] : null;
$is_admin = ($role === 'admin');
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Главная | SportsWave</title>
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="assets/css/index/index.css">
</head>
<body>
<?php include 'assets/pages/tools/header.php'; ?>
<?php include 'assets/pages/tools/home.php'; ?>
<?php include 'assets/pages/tools/services.php'; ?>
<?php include 'assets/pages/tools/about.php'; ?>
<?php include 'assets/pages/tools/price.php'; ?>
<?php include 'assets/pages/tools/contact.php'; ?>
<?php include 'assets/pages/tools/footer.php'; ?>

<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<script src="assets/js/index/index.js"></script>
</body>
</html>