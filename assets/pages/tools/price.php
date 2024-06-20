<?php

include 'assets/pages/system_files/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['tariff_id'])) {
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['auth_error'] = "Без авторизации нельзя оформить заказ.";
    } else {
        $tariff_id = $_POST['tariff_id'];
        $query = "INSERT INTO orders (user_id, tariff_id) VALUES (?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $_SESSION['user_id'], $tariff_id);
        if ($stmt->execute()) {
            $_SESSION['order_success'] = "Спасибо за бронирование! Мы свяжемся с вами в ближайшее время. Подробности можно узнать в личном кабинете.";
        } else {
            $_SESSION['order_error'] = "Произошла ошибка при оформлении бронирования. Пожалуйста, попробуйте снова.";
        }
        $stmt->close();
    }
}

$query = "SELECT * FROM tariffs";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Ошибка при получении товаров: " . mysqli_error($conn));
}
?>


<?php
if (isset($_SESSION['order_success'])) {
    echo '<script>alert("' . $_SESSION['order_success'] . '");</script>';
    unset($_SESSION['order_success']);
}
if (isset($_SESSION['order_error'])) {
    echo '<script>alert("' . $_SESSION['order_error'] . '");</script>';
    unset($_SESSION['order_error']);
}
if (isset($_SESSION['auth_error'])) {
    echo '<script>alert("' . $_SESSION['auth_error'] . '");</script>';
    unset($_SESSION['auth_error']);
}
?>

<section class="price" id="price">
    <h1 class="heading">Наши <span>тарифы для спортивных мероприятий</span></h1>
    <div class="box-container">
        <?php
        $conn->set_charset("utf8mb4");
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<div class="box">';
                echo '<h3 class="title">' . $row['event_type'] . '</h3>';
                echo '<h3 class="amount">' . $row['amount'] . 'Р</h3>';
                echo '<ul>';
                echo '<li><i class="fas fa-check"></i>Полный сервис</li>';
                echo '<li><i class="fas fa-check"></i>Украшения</li>';
                echo '<li><i class="fas fa-check"></i>Музыка и фотографии</li>';
                echo '<li><i class="fas fa-check"></i>Еда и напитки</li>';
                echo '<li><i class="fas fa-check"></i>Пригласительная открытка</li>';
                echo '</ul>';
                echo '<form method="POST" class="order-form">';
                echo '<input type="hidden" name="tariff_id" value="' . $row['id'] . '">';
                echo '<button type="submit" class="btn order-btn">Забронировать</button>';
                echo '</form>';
                echo '</div>';
            }
        } else {
            echo "<p>Нет доступных тарифов.</p>";
        }
        ?>
    </div>
</section>

