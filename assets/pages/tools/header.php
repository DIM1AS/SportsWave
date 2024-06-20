<?php
session_start();
$role = isset($_SESSION['role']) ? $_SESSION['role'] : null;
$is_admin = ($role === 'admin');
?>
<header class="header">
    <a href="#" class="logo"><span>S</span>portsWave</a>
    <nav class="navbar">
        <a href="../../../index.php">Главная</a>
        <a href="#service">Услуги</a>
        <a href="#about">О нас</a>
        <a href="#price">Цены</a>
        <a href="#contact">Контакты</a>
        <?php
        if (isset($_SESSION['username'])) {
            if ($is_admin) {
                echo '<a href="../../../assets/pages/admin/admin.php">Админ панель</a>';
            }
            echo '<a href="../../../assets/pages/tools/profile.php">Профиль</a>';
            echo '<a href="../../../assets/pages/tools/logout.php">Выйти</a>';
        } else {
            echo '<a href="../../../assets/pages/tools/login.php">Авторизация</a>';
        }
        ?>
    </nav>
    <div id="menu-bars" class="fas fa-bars"></div>
</header>