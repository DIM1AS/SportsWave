<?php
include '../../pages/system_files/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fio = $_POST['fio'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    if (!preg_match("/^[\p{Cyrillic}\s]+$/u", $fio)) {
        $error_message = "ФИО должно содержать только кириллические символы и пробелы.";
    } elseif (!preg_match("/^\d{11}$/", $phone)) {
        $error_message = "Номер телефона должен содержать 11 цифр.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $error_message = "Этот email уже зарегистрирован.";
        } else {
            $stmt = $conn->prepare("INSERT INTO users (fio, phone, password, email) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $fio, $phone, $hashed_password, $email);
            if ($stmt->execute()) {

                header("Location: login.php");
                exit();
            } else {
                $error_message = "Ошибка: " . $stmt->error;
            }
        }
        $stmt->close();
        $conn->close();
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Регистрация | HelpMe</title>
    <link rel="stylesheet" href="../../css/registration/registration.css">
</head>
<body>
<div class="container">
    <h2>Регистрация нового пользователя</h2>
    <?php if (!empty($error_message)): ?>
        <p class="error"><?= $error_message ?></p>
    <?php endif; ?>
    <?php if (!empty($success_message)): ?>
        <p class="success"><?= $success_message ?></p>
    <?php endif; ?>
    <form id="registrationForm" action="" method="POST">
        <label for="fio">ФИО:</label>
        <input type="text" id="fio" name="fio" placeholder="Иванов Иван Иванович" required>

        <label for="phone">Телефон:</label>
        <input type="tel" id="phone" name="phone" pattern="\d{7,15}" placeholder="88002002316" required>

        <label for="password">Пароль:</label>
        <input type="password" id="password" name="password" minlength="5" placeholder="Ваш пароль" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" placeholder="VovaTop@mail.ru" required>

        <button type="submit">Зарегистрироваться</button>
    </form>
</div>
</body>
</html>
