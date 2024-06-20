<?php
include 'assets/pages/system_files/db_connect.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    $sql = "INSERT INTO contact_messages (name, email, phone, subject, message) 
            VALUES ('$name', '$email', '$phone', '$subject', '$message')";
    if (mysqli_query($conn, $sql)) {
        echo "";
    } else {
        echo "Ошибка: " . $sql . "<br>" . mysqli_error($conn);
    }
}
?>

<section class="contact" id="contact">
    <h1 class="heading"> <span>свяжитесь</span> с нами </h1>
    <form action="" method="post">
        <div class="inputBox">
            <input type="text" name="name" placeholder="имя">
            <input type="email" name="email" placeholder="электронная почта">
        </div>
        <div class="inputBox">
            <input type="tel" name="phone" placeholder="номер">
            <input type="text" name="subject" placeholder="тема">
        </div>
        <textarea name="message" placeholder="ваше сообщение" cols="30" rows="10"></textarea>
        <input type="submit" value="отправить сообщение" class="btn">
    </form>
</section>
