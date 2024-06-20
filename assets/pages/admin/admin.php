<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../../assets/pages/tools/login.php");
    exit;
}

include '../../pages/system_files/db_connect.php';

$current_page = isset($_GET['page']) ? $_GET['page'] : 1;
$records_per_page = 5;
$offset = ($current_page - 1) * $records_per_page;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['order_id']) && isset($_POST['new_status'])) {
    // Получаем данные из формы
    $order_id = $_POST['order_id'];
    $new_status = $_POST['new_status'];

    $update_query = "UPDATE orders SET status = '$new_status' WHERE id = $order_id";
    $update_result = mysqli_query($conn, $update_query);

    if ($update_result) {
        echo "Статус бронирования успешно обновлен.";
    } else {
        echo "Ошибка при обновлении статуса бронирования: " . mysqli_error($conn);
    }
}

$query = "SELECT orders.id, orders.timestamp AS order_date, tariffs.event_type AS order_name, orders.status
          FROM orders
          JOIN tariffs ON orders.tariff_id = tariffs.id
          LIMIT $records_per_page OFFSET $offset";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Ошибка при получении бронирований: " . mysqli_error($conn));
}

$query_requests = "SELECT * FROM contact_messages LIMIT $records_per_page OFFSET $offset";
$result_requests = mysqli_query($conn, $query_requests);

if (!$result_requests) {
    die("Ошибка при получении обращений: " . mysqli_error($conn));
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Админ Панель| SportsWave</title>
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../../../assets/css/index/index.css">
</head>
<body>
<?php include '../../../assets/pages/tools/header.php'; ?>

<section class="bookings" id="bookings">
    <h1 class="heading"> <span></span></h1>
    <h1 class="heading"> <span></span></h1>
    <h1 class="heading"> <span></span></h1>
    <h1 class="heading">Список <span>бронирований</span></h1>
    <table>
        <tr>
            <th>Номер</th>
            <th>Дата заказа</th>
            <th>Название заказа</th>
            <th>Статус</th>
            <th>Изменить статус</th>
        </tr>
        <?php
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . $row['order_date'] . "</td>";
                echo "<td>" . $row['order_name'] . "</td>";
                // Переводим статус на русский
                $status_rus = "";
                switch ($row['status']) {
                    case 'pending':
                        $status_rus = "Ожидание";
                        break;
                    case 'confirmed':
                        $status_rus = "Подтверждено";
                        break;
                    case 'cancelled':
                        $status_rus = "Отменено";
                        break;
                    default:
                        $status_rus = "Неизвестный";
                        break;
                }
                echo "<td>" . $status_rus . "</td>";
                echo "<td>";
                echo "<form method='POST' action='" . $_SERVER['PHP_SELF'] . "'>";
                echo "<input type='hidden' name='order_id' value='" . $row['id'] . "'>";
                echo "<select name='new_status'>";
                echo "<option value='pending'>Ожидание</option>";
                echo "<option value='confirmed'>Подтверждено</option>";
                echo "<option value='cancelled'>Отменено</option>";
                // Другие ваши варианты статусов
                echo "</select>";
                echo "<button type='submit'>Изменить</button>";
                echo "</form>";
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5'>Нет доступных бронирований.</td></tr>";
        }
        ?>
    </table>
    <?php
    $total_rows_query = "SELECT COUNT(*) as total FROM orders";
    $total_result = mysqli_query($conn, $total_rows_query);
    $total_rows = mysqli_fetch_assoc($total_result)['total'];
    $total_pages = ceil($total_rows / $records_per_page);

    echo "<div class='pagination'>";
    for ($i = 1; $i <= $total_pages; $i++) {
        echo "<a href='?page=$i'>$i</a>";
    }
    echo "</div>";
    ?>
</section>

<section class="requests" id="requests">
    <h1 class="heading">Список <span>обращений</span></h1>
    <table>
        <tr>
            <th>Имя</th>
            <th>Email</th>
            <th>Сообщение</th>
            <th>Номер телефона</th>
        </tr>
        <?php
        if (mysqli_num_rows($result_requests) > 0) {
            while ($request = mysqli_fetch_assoc($result_requests)) {
                echo "<tr>";
                echo "<td>" . $request['name'] . "</td>";
                echo "<td>" . $request['email'] . "</td>";
                echo "<td>" . $request['message'] . "</td>";
                echo "<td>" . $request['phone'] . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4'>Нет обращений.</td></tr>";
        }
        ?>
    </table>
    <!-- Пагинация -->
    <?php
    $total_rows_requests_query = "SELECT COUNT(*) as total FROM contact_messages";
    $total_requests_result = mysqli_query($conn, $total_rows_requests_query);
    $total_rows_requests = mysqli_fetch_assoc($total_requests_result)['total'];
    $total_pages_requests = ceil($total_rows_requests / $records_per_page);

    echo "<div class='pagination'>";
    for ($i = 1; $i <= $total_pages_requests; $i++) {
        echo "<a href='?page=$i'>$i</a>";
    }
    echo "</div>";
    ?>
</section>


</body>
</html>
