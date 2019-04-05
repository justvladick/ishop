<?php
session_start();
echo "<!DOCTYPE html>";
//require_once 'functions.php';
require_once 'sendmail.php';
?>
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
                <a href="manager.php"><?php echo $appname ?> Manager |</a>
                <a href="myorders.php">Мои заказы |</a>
                <a href="#">Something |</a>
            </nav>
            <a class="header" href="index.php"><h1>Интернет-магазин</h1></a>
            <a class="header" href="cart.php"><div class="cart">Корзина<p class="cart">Пусто</p></div></a>
        </header>

        <main>
            <?php
///ПРОверить еще раз наличие, добавить запись в таблицу orders, и обновить доступное 
//количество в таблице items по каждой позиции наименования
            if (isset($_SESSION['cartcode']) && isset($_SESSION['token'])) {
                $cartcode = sanitizeString($_SESSION['cartcode']);
                $santoken = sanitizeString($_SESSION['token']);
                $pasresult = queryMysql("SELECT * FROM customers WHERE password='$santoken'");
                if ($pasresult->num_rows) {
                    $customer_id = null;
                    while ($customers = $pasresult->fetch_assoc()) {
                        $customer_id = $customers['id'];
                        $customeremail = $customers['email'];
                    }
                    ///BLOCK DATABASE FROM CHANGING BY OTHER CONNECTIONS/////////////////
                    //$connection->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
                    $result = queryMysql("SELECT items.id, items.name, quantity, "
                            . "items.price, items.available "
                            . "FROM cart JOIN items ON cart.item_id=items.id "
                            . "WHERE code_number='$cartcode' GROUP BY items.name");
                    $checkavailability = TRUE;
                    $sum = 0;
                    $emailtextinside = '';
                    if ($result->num_rows) {
                        while ($row = $result->fetch_array()) {
                            $itemid = $row[0];
                            $itemname = $row[1];
                            $itemquantity = $row[2];
                            $itemprice = $row[3];
                            $available = $row[4];
                            $sqldate = sqldate();
                            if ($itemquantity > $available) {
                                $checkavailability = FALSE;
                                echo "Нет в наличии " . $itemname . "Перейдите в корзину "
                                . "и удалите отсутствующие позиции";
                            } else {  //Добавляем запись в таблицу заказов по каждому наименованию
                                $addquery = queryMysql("INSERT INTO orders (customer_id, cart_number, item_id, "
                                        . "quantity, price, creation_date, status) VALUES "
                                        . "('$customer_id', '$cartcode', '$itemid', '$itemquantity', "
                                        . "'$itemprice', '$sqldate', 'ЗАКАЗАН')");
                                //и обновляем доступное количество товаров в таблице items
                                //РЕШЕНО НЕ ИЗМЕНЯТЬ ДОСТУПНОЕ КОЛИЧЕСТВО в таблице items
                                //это должен делать администратор сайта в документе управления заказами
//                                $item_avail = $available - $itemquantity;
//                                $itemsquery = queryMysql("UPDATE items SET available='$item_avail' "
//                                        . "WHERE id='$itemid'");
                                $itemsum = $itemquantity * $itemprice;
                                $sum += $itemsum;
                                $itemsum = number_format($itemsum, 2, ",", '');
                                $emailtextinside .= "<tr class='item'><td></td>"
                                        . "<td>$itemname</td><td>$itemquantity</td>"
                                        . "<td align='right'>$itemprice</td><td align='right'>$itemsum</td>"
                                        . "<td align='right'></td></tr>";
                            }
                        }//Посылаем письмо на почту клиента о заказе
                        $emailtextend = "<tfoot><tr><td></td><td colspan=3 align='right'>ИТОГО</td>"
                                . "<td><b>$sum руб.</b></td>"
                                . "</tr></tfoot></table><p>В ближайшее время с вами свяжется оператор "
                                . "для подтверждения заказа</p>";
                        if ($checkavailability) {
                            //удаляем товары из корзины этого покупателя
                            $cartquery = queryMysql("DELETE FROM cart WHERE code_number='$cartcode'");
                            //удаляем сессию корзины
                            $_SESSION['cartcode'] = null;
                            //Отправляем на почтовый адрес подтверждение
                            $emailtextbegin = "<h3>Вы сделали заказ № $cartcode</h3>"
                                    . "<h2>Информация о заказе</h2>"
                                    . "<table><tr><th> </th><th>Наименование</th><th>Кол-во</th>"
                                    . "<th>Цена</th><th>Сумма</th></tr>";
                            $emailtext = $emailtextbegin . $emailtextinside . $emailtextend;
                            echo sendEmail($customeremail, 'Информация о заказе №' . $cartcode, $emailtext);
                            file_put_contents($customeremail.'.html', $emailtext);
//Возвращаемся в мои заказы
                            echo <<<_END
<form method='post' action='myorders.php' name="confirmed">  
<h3>Ваш заказ № $cartcode подтвержден</h3>
<p align="right"><input type='submit' name="order" value='Обратно в мои заказы'></p>
</form>                    
_END;
                        } else {

                            echo "Произошла ошибка в заказе, повторите попытку или обратитесь в поддержку.";
                        }
                    }
                } else
                    echo '<p>Ваша корзина Пуста!</p>';
            } else
                echo 'Вы не авторизованы';
            ?>

        </main>
        <footer>

        </footer>
        <script>
            $(document).ready(function () {
                //////LOAD CART///////////////////////////////////////////
                $.post("postcart.php", function (data) {
                    $("p.cart").html(data);
                })
            });
        </script>
    </body>
</html>
<?php
