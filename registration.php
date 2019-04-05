<?php
session_start();
require 'functions.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $appname ?></title>
        <style> @import url('style.css');</style>
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

                <h2>Регистрация новых клиентов</h2>
                <form method='post' action='myorders.php' onsubmit="return validateForm(this)" name="new_order">
                    <table><th colspan="3" align="center">Введите свои данные и адрес доставки</th>
                        <tr><td>Адрес электронной почты</td>
                            <td><input type="text" name="email" size="30" maxlength="32"></td> 
                            <td><span class="new_order">*</span></td>
                        </tr>
                        <tr><td>Номер вашего телефона</td>
                            <td><input type="tel" name="phone" size="20" maxlength="12" value="+7"></td> 
                            <td><span class="new_order">*</span></td>
                        </tr>
                        <tr><td>Придумайте пароль <br>для просмотра состояния заказа</td>
                            <td><input type="text" name="password" size="20" maxlength="32" ></td> 
                            <td><span class="new_order">*</span></td>
                        </tr>
                        <tr><td>Ваше имя</td>
                            <td><input type="text" name="name" size="30" maxlength="32"></td> 
                            <td><span class="new_order">*</span></td>
                        </tr>
                        <tr><td>Ваша фамилия</td>
                            <td><input type="text" name="surname" size="30" maxlength="32"></td> 
                            <td><span></span></td>
                        </tr>
                        <tr><td>Город, область</td>
                            <td><input type="text" name="city" size="30" maxlength="32"></td> 
                            <td><span class="new_order">*</span></td>
                        </tr>
                        <tr><td>Почтовый индекс</td>
                            <td><input type="text" name="postalcode" size="10" maxlength="8"></td> 
                            <td><span class="new_order">*</span></td>
                        </tr>
                        <tr><td>Адрес доставки</td>
                            <td><input type="text" name="address" size="30" maxlength="32"></td> 
                            <td><span class="new_order">*</span></td>
                        </tr>
                        <tr><td>Дополнительная информация</td>
                            <td><textarea name='other' cols='31' rows='3'></textarea></td> 
                            <td><input type="text" name="confirm" value="false" hidden></td>
                        </tr>
                        <tfoot>
                            <tr>
                                <td colspan="3" align="center">
                                    <input type='submit' name="placeorder" value='ЗАРЕГИСТРИРОВАТЬСЯ'>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </form></div>
        </main>
        <footer>
            <div align="right">

                <?php
                if (!isset($_SESSION['token'])) {
                    echo "<a href='enter.php'> Войти</a>";
                } else {
                    echo "<span onclick=exit(this)><a href='index.php'> Выйти</a></span>";
                }
                ?>
            </div>
        </footer>
        <script src="js/registration.js"></script>
        <script>
                    function exit(elem) {
                        $.post("post/exit.php",{exit: true} , function (data) {
                            elem.innerHTML(data);
                        })
                    }
        </script>
    </body>
</html>