<?php
session_start();
include '../../pages/system_files/db_connect.php';

// Проверяем, авторизован ли пользователь
if (!isset($_SESSION['user_id'])) {
    die("Пожалуйста, авторизуйтесь для просмотра бронирований.");
}

$user_id = $_SESSION['user_id'];

$query = "SELECT orders.id, orders.timestamp AS order_date, tariffs.event_type AS order_name, orders.status
          FROM orders
          JOIN tariffs ON orders.tariff_id = tariffs.id
          WHERE orders.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    die("Ошибка при получении бронирований: " . mysqli_error($conn));
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Мой профиль | SportsWave</title>
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../../../assets/css/index/index.css">
</head>
<body>
<?php include '../../../assets/pages/tools/header.php'; ?>
<section class="bookings" id="bookings">
        <h1 class="heading"> <span></span></h1>
    <h1 class="heading"> <span></span></h1>

    <h1 class="heading">Список <span>бронирований</span></h1>
    <table>
        <tr>
            <th>Номер</th>
            <th>Дата заказа</th>
            <th>Название заказа</th>
            <th>Статус</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            $num_rows = $result->num_rows;
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $num_rows-- . "</td>";
                echo "<td>" . $row['order_date'] . "</td>";
                echo "<td>" . $row['order_name'] . "</td>";
                $status_translation = array(
                    'pending' => 'Ожидание',
                    'confirmed' => 'Подтверждено',
                    'cancelled' => 'Отменено'
                );
                echo "<td>" . (isset($status_translation[$row['status']]) ? $status_translation[$row['status']] : 'Неизвестный статус') . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4'>Нет доступных бронирований.</td></tr>";
        }
        ?>
    </table>
</section>
</body>
</html>
