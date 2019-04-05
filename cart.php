<?php
session_start();
echo "<!DOCTYPE html>";
require 'functions.php';
?>
<html>
<head>
    <title><?php echo $appname ?></title>
    <link href="style.css" rel="stylesheet" type="text/css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="js/jquery-3.3.1.js"></script>
</head>
<body>
<header>
    <nav>
        <a href="index.php">Home |</a>
        <a href="#">Доставка и оплата |</a>
        <a href="myorders.php">Мои заказы |</a>
        <a href="#">О магазине |</a>
    </nav>
    <a href="index.php"><h1>Интернет-магазин</h1></a>
    <div><h2>Корзина</h2></div>
</header>

<main><div align="center">
        <?php if (isset($_SESSION['cartcode'])) { ?>

        <form method='post' name="refresh" enctype='multipart/form-data'>
            <table id="cart_table"></table>
            <!--                    Ajax postcart-->
            <p align="right"><input type='submit' id="delete_cart" value='Очистить корзину'></p>
        </form>
        <?php } ?>

        <div align="center">
            <?php
            //ДОБАВЛЯЕМ КНОПКИ ДЛЯ ЗАКАЗА ИЛИ РЕГИСТРАЦИИ
            if (isset($_SESSION['cartcode']) && isAuthorized()) {
                ?>
                <form method='post' action='myorders.php' name="refresh">
                    <p align="right"><input type='submit' name="refresh" value='Заказать'></p>
                </form>

            <?php } elseif (isset($_SESSION['cartcode'])) { ?>
                <h3>Чтобы заказать войдите или зарегистрируйтесь</h3>
                <form method='post' action='registration.php' name="refresh">
                    <p align="center"><input type='submit' name="refresh" value='Зарегистрироваться'></p>
                </form>
                <form method='post' action='enter.php' name="refresh">
                    <p align="center"><input type='submit' name="refresh" value='Войти'></p>
                </form>
            <?php } else { ?>
                <p>КОРЗИНА ПУСТА</p>
            <?php } ?>

        </div>
        <script>

        </script>
    </div>
</main>
<footer>
    <div align="right">

    </div>
</footer>
<script src="js/cart.js"></script>
</body>
</html>
