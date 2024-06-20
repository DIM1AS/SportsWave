<?php
// Подключаемся к базе данных
include '../../../assets/pages/system_files/db_connect.php';

// Проверяем, была ли отправлена форма для добавления тарифа
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_tariff'])) {
    $event_type = $_POST['event_type'];
    $amount = $_POST['amount'];

    // Подготавливаем и выполняем SQL-запрос для вставки данных
    $stmt = $conn->prepare("INSERT INTO tariffs (event_type, amount) VALUES (?, ?)");
    $stmt->bind_param("sd", $event_type, $amount);

    if ($stmt->execute()) {
        echo '<script>alert("Тариф успешно добавлен!");</script>';
    } else {
        echo '<script>alert("Произошла ошибка при добавлении тарифа. Пожалуйста, попробуйте снова.");</script>';
    }

    $stmt->close();
}

// Проверяем, была ли отправлена форма для удаления тарифа
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_tariff'])) {
    $tariff_id = $_POST['tariff_id'];

    // Удаляем связанные записи из таблицы orders
    $delete_orders_query = "DELETE FROM orders WHERE tariff_id = ?";
    $stmt = $conn->prepare($delete_orders_query);
    $stmt->bind_param("i", $tariff_id);
    $stmt->execute();

    // Удаляем запись из таблицы tariffs
    $delete_tariff_query = "DELETE FROM tariffs WHERE id = ?";
    $stmt = $conn->prepare($delete_tariff_query);
    $stmt->bind_param("i", $tariff_id);

    if ($stmt->execute()) {
        echo '<script>alert("Тариф и связанные заказы успешно удалены!");</script>';
    } else {
        echo '<script>alert("Произошла ошибка при удалении тарифа. Пожалуйста, попробуйте снова.");</script>';
    }

    $stmt->close();
}

$conn->close();
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Управление Тарифами | SportsWave</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../../../assets/css/index/index.css">
</head>
<body>
<?php include '../../../assets/pages/tools/header.php'; ?>

<section class="manage-tariffs">
    <h1 class="heading">Добавить или Удалить <span>Тарифы</span></h1>
    
    <div class="form-container">
        <!-- Форма для добавления тарифа -->
        <form method="POST" action="" class="tariff-form">
            <h2>Добавить Новый Тариф</h2>
            <label for="event_type">Тип События:</label>
            <input type="text" id="event_type" name="event_type" required>
            
            <label for="amount">Сумма:</label>
            <input type="number" step="0.01" id="amount" name="amount" required>
            
            <input type="hidden" name="add_tariff" value="1">
            <button type="submit" class="btn">Добавить Тариф</button>
        </form>
        
        <!-- Форма для удаления тарифа -->
        <form method="POST" action="" class="tariff-form">
            <h2>Удалить Тариф</h2>
            <label for="tariff_id">ID Тарифа:</label>
            <input type="number" id="tariff_id" name="tariff_id" required>
            
            <input type="hidden" name="delete_tariff" value="1">
            <button type="submit" class="btn">Удалить Тариф</button>
        </form>
    </div>
</section>
</body>
</html>
