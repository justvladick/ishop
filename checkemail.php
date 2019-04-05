<?php

session_start();
require 'functions.php';

//ПРОВЕРИТЬ ДОСТУПНОСТЬ ЭЛЕКТРОННОЙ ПОЧТЫ////////////////////////////////
if (isset($_POST['checkemail'])) {
    $email = sanitizeString($_POST['checkemail']);
    $result = queryMysql("SELECT email FROM customers WHERE email='$email'");
    while ($row = $result->fetch_array()) {
        echo TRUE;
    }
}

//ПРОВЕРИТЬ ЛОГИН И ПАРОЛЬ НА ВХОДЕ////////////////////////////////
if (isset($_POST['checklogin'])) {
    $email = sanitizeString($_POST['checklogin']);
    $pas = sanitizeString($_POST['checkpass']);

    $result = queryMysql("SELECT * FROM customers WHERE email='$email'");
    if ($result->num_rows) {
        while ($row = $result->fetch_assoc()) {
            $storedtoken = $row['password'];
            if ($storedtoken === hashPassword($pas)) {
                $_SESSION['token'] = $storedtoken;
                echo "Success";
            } else
                echo "Неправильно введен пароль";
        }
    } else
        echo "Такой адрес не зарегистрирован";
}

