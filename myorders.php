<?php
session_start();
require 'functions.php';
?>
<!DOCTYPE html>
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
            <a class="header" href="index.php"><h1>Интернет-магазин</h1></a>
            <a class="header" href="cart.php"><div class="cart">Корзина<p class="cart">Пусто</p></div></a>
        </header>

        <main>

            <div align="center">
                <?php
                ////ЗАПОЛНЯЕМ ДАННЫЕ О ПОКУПАТЕЛЕ КОТОРЫЙ ПРИШЕЛ СО СТРАНИЦЫ РЕГИСТРАЦИИ/////
                if (isset($_POST['email']) && isset($_POST['placeorder']) &&
                        isset($_SESSION['cartcode'])) {
                    $email = sanitizeString($_POST['email']);
                    $phone = sanitizeString($_POST['phone']);
                    $pass_token = hashPassword($_POST['password']); ///PROTECT PASSWORD
                    $name = sanitizeString($_POST['name']);
                    $surname = sanitizeString($_POST['surname']);
                    $city = sanitizeString($_POST['city']);
                    $postalcode = sanitizeString($_POST['postalcode']);
                    $address = sanitizeString($_POST['address']);
                    $other = sanitizeString($_POST['other']);
                    $cart_number = sanitizeString($_SESSION['cartcode']);
                    $result = queryMysql("INSERT INTO customers(cart_number, email, phone, password, "
                            . "name, surname, city, postalcode, address, other) VALUES ('$cart_number', "
                            . "'$email', '$phone', '$pass_token', '$name', '$surname', '$city', "
                            . "'$postalcode', '$address', '$other')");
                    if ($result) {
                        ///REGISTER SESSION
                        $_SESSION['email'] = $email;
                        $_SESSION['token'] = $pass_token;
                        echo <<<_END
<h2>ВЫ ЗАРЕГИСТРИРОВАНЫ</h2>
<p>Адрес электронной почты :$email
<br>(используется в качестве логина для входа на сайт)</p>
<p>Номер вашего телефона: $phone</p>
<p>Получатель: $name, $surname</p>
<p>Адрес доставки: $postalcode, $city<br>
$address</p>
_END;
                    } else
                        die("Пользователь не был зарегистрирован");
//////////////////////ДЛЯ ТЕХ КТО УЖЕ ЗАРЕГИСТРИРОВАН ИЛИ ВОШЕЛ///////////////////
                } elseif (isset($_SESSION['token']) && isset($_SESSION['email'])) {
                    $santoken = sanitizeString($_SESSION['token']);
                    $sanemail = sanitizeString($_SESSION['email']);
                    $result = queryMysql("SELECT * FROM customers WHERE email='$sanemail' "
                            . "AND password='$santoken'");
                    while ($row = $result->fetch_assoc()) {
                        echo "<h2>Здравствуйте, " . $row['name'] . " " . $row['surname'] . "</h2>"
                        . "<p>Ваш адрес электронной почты: " . $row['email']
                        . "<p>Номер вашего телефона: " . $row['phone'] . "</p>"
                        . "<p>Адрес доставки: " . $row['postalcode'] . ", " . $row['city']
                        . "<br>" . $row['address'] . "</p>";
                    }
                } else echo "<p class='red'>Вы не авторизованы для просмотра этой страницы</p>";
                ?>
            </div>
            <div class="enter" align="center">
                <?php
                ///ВХОД ДЛЯ НЕЗАРЕГИСТРИРОВАННЫХ
                if (!isAuthorized()) {
                    echo <<<_END
<h2>Вход для клиентов</h2>
<form method='post' action='enter.php' name="enter">
<table><th colspan="3" align="center">Введите адрес электронной почты и пароль, <br> 
указанные вами при регистрации</th>
<tr><td>Адрес электронной почты</td>
<td><input type="text" name="email" size="30" maxlength="32"></td> 
<td><span class="enter">*</span></td></tr>
<tr><td>Пароль</td>
<td><input type="password" name="password" size="30" maxlength="32" ></td> 
<td><span class="enter">*</span></td></tr>
<tfoot><tr><td colspan="3" align="center">
<input type='submit' name="enter" value='ВОЙТИ'>
</td></tr></tfoot></table></form>
_END;
                }
                ?>
            </div>
            <div class="order_info" align="center">

                <?php
                ///Заполняем информацию о заказе в корзине, кто ранее вошел или зарегистрировался
                if (isset($_SESSION['cartcode']) && isset($_SESSION['token'])) {
                    <<<_END
<script>
    $("div.enter").hide();
</script>                            
_END;
                    echo "<h2>Информация о заказе</h2>"
                    . "<table><tr><th> </th><th>Наименование</th><th>Кол-во</th>"
                    . "<th>Цена</th><th>Сумма</th></tr>";
                    //Получаем данные из корзины, сгруппированные по наименованию //
                    $cartcode = sanitizeString($_SESSION['cartcode']);
                    $result = queryMysql("SELECT items.id, items.name, quantity, "
                            . "items.price, items.available "
                            . "FROM cart JOIN items ON cart.item_id=items.id WHERE "
                            . "code_number='$cartcode' GROUP BY items.name");
                    $sum = 0;
                    $checkavailability = TRUE;
                    while ($row = $result->fetch_array()) {
                        $itemid = $row[0];
                        $itemname = $row[1];
                        $itemquantity = $row[2];
                        $itemprice = $row[3];
                        $available = $row[4];
                        if ($itemquantity <= $available) {
                            $itemsum = $itemquantity * $itemprice;
                            $sum += $itemsum;
                            $itemsum = number_format($itemsum, 2, ",", '');
                        } else {
                            $itemsum = 'нет в наличии';
                            $checkavailability = FALSE;
                        }
                        $itemprice = number_format($itemprice, 2, ",", '');

                        echo <<<_END
<tr class='item'><td><img src='images/$itemid.jpg' height='50' width='50'></td>
<td>$itemname</td><td>$itemquantity</td>
<td align='right'>$itemprice</td><td align='right'>$itemsum</td>
<td align='right'></td></tr>
_END;
                    }
                    echo "<tfoot><tr><td></td><td colspan=3 align='right'>ИТОГО</td>"
                    . "<td><b>$sum руб.</b></td>"
                    . "</tr></tfoot></table>";
                    /////ДОБАВИТЬ КНОПКУ ЗАКАЗА///
                    if ($checkavailability) {
                        echo <<<_END
<form method='post' action='order.php' name="refresh">  
<p align="center"><input type='submit' name="refresh" value='Подтвердить заказ'></p>
</form>
_END;
                    } else
                        echo "<p>Чтобы сдалать заказ, перейдите в корзину "
                        . "и удалите отсутствующие на складе товары</p>";
                }
                ?>
            </div>
            <div align="center">
                
                <?php
                ///Заполняем информацию об истории заказов
                if (isset($_SESSION['token']) && isset($_SESSION['email'])) {
                    $santoken = sanitizeString($_SESSION['token']);
                    $sanemail = sanitizeString($_SESSION['email']);
                    $result = queryMysql("SELECT orders.cart_number, orders.creation_date, "
                            . "orders.status FROM orders JOIN customers ON orders.customer_id=customers.id "
                            . "WHERE customers.email='$sanemail' "
                            . "GROUP BY orders.cart_number ORDER BY orders.creation_date DESC");
                    echo "<h2>История заказов</h2><table>";
                    while ($row = $result->fetch_array()) {
                        $ordercart_number = $row[0];
                        echo "<tr><th class='hover' colspan='4' onclick=loadOrderDetails(this)>Заказ №"
                        . $ordercart_number . " от " . $row[1];
                        if ($row[2] === "ЗАКАЗАН")
                            echo "<th class='hover' id=$ordercart_number "
                            . "</th>";
                        else
                            echo "<th></th>";
                        echo "</tr>";
                        //Заполняем детали заказа
                        $detailed = queryMysql("SELECT items.name, orders.quantity, orders.price "
                                . "FROM orders JOIN customers ON orders.customer_id=customers.id "
                                . "JOIN items ON orders.item_id=items.id "
                                . "AND orders.cart_number='$ordercart_number'");
                        echo "<tbody class='order' id='$ordercart_number' hidden>";
                        $sum = 0;
                        while ($detrow = $detailed->fetch_array()) {
                            $itemprice = number_format($detrow[2], 2, ",", '');
                            $value = $detrow[1] * $detrow[2];
                            $sum += $value;
                            $value = number_format($value, 2, ",", '');
                            echo "<tr><td>" . $detrow[0] . "</td>" . "<td>" . $detrow[1] . "</td>"
                            . "<td align='right'>$itemprice /шт.</td><td align='right'>$value</td></tr>";
                        }
                        echo "<tr><td colspan='3' align='right'>Стоимость (руб.)</td>"
                        . "<td  align='right'>" . number_format($sum, 2, ",", '') . "</td></tr>"
                        . "</tbody>";
                    }
                    echo "</table>";
                }
                ?>

            </div>
        </main>
        <footer>
            <div align="right">

                <?php
                if (!isset($_SESSION['token']) || !isset($_SESSION['email'])) {
                    echo "<a href='enter.php'> Войти</a>";
                } else {
                    echo "<span onclick=exit(this)><a href='index.php'> Выйти</a></span>";
                }
                ?>
            </div>
        </footer>

        <script>
            function exit(elem) {
                $.post("post/exit.php", {exit: true}, function (data) {
                    elem.innerHTML(data);
                })
            }
        </script>
        <script src="js/myorders.js"></script>
    </body>
</html>
<?php
