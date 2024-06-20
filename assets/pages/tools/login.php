<?php
// Установка настроек сессии и параметров куки до старта сессии
ini_set('session.gc_maxlifetime', 3600);
session_set_cookie_params(3600);
session_start();

include '../../pages/system_files/db_connect.php';
$error_message = '';
$success_message = '';

// Функция для записи отладочной информации в лог-файл
function debug_to_console($data) {
    $output = $data;
    if (is_array($output)) {
        $output = implode(',', $output);
    }
    error_log($output);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password, fio, role FROM users WHERE email = ?");
    if ($stmt === false) {
        error_log('Ошибка подготовки запроса: ' . $conn->error);
        die('Ошибка подготовки запроса: ' . $conn->error);
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $hashed_password, $username, $role);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['username'] = $username;
            $_SESSION['user_id'] = $user_id;
            $_SESSION['role'] = $role;

            // Запись отладочной информации в лог-файл
            debug_to_console($_SESSION);

            header("Location: ../../../index.php");
            exit();
        } else {
            $error_message = "Неправильный пароль. Пожалуйста, попробуйте еще раз.";
        }
    } else {
        $error_message = "Пользователь с указанным email не найден.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Авторизация | HelpMe</title>
    <link rel="stylesheet" href="../../css/login/login.css">
</head>
<body>
<div class="container">
    <h2>Авторизация</h2>
    <?php if (!empty($error_message)): ?>
        <p class="error"><?= $error_message ?></p>
    <?php endif; ?>
    <?php if (!empty($success_message)): ?>
        <p class="success"><?= $success_message ?></p>
    <?php endif; ?>
    <form id="loginForm" action="" method="POST">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" placeholder="Введите ваш email" required>

        <label for="password">Пароль:</label>
        <input type="password" id="password" name="password" placeholder="Введите ваш пароль" required>

        <button type="submit">Войти</button>
    </form>
    <p>Еще не зарегистрированы? <a href="registration.php">Зарегистрируйтесь</a></p>
</div>
</body>
</html>
