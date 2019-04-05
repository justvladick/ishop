<?php

session_start();
require 'functions.php';
///ДОБАВЛЯЕМ В КОРЗИНУ//////////////////////////////////////////////////
if (isset($_POST['itemid']) && isset($_POST['quantity'])) {
    $itemid = sanitizeString($_POST['itemid']);
    $quantity = sanitizeString($_POST['quantity']);
    ////Проверяем в корзину уже добавляли//////////////////////////////////
    if (isset($_SESSION['cartcode'])) {
        $cartcode = sanitizeString($_SESSION['cartcode']);
    } else {
        $cartcode = mt_rand();
        $_SESSION['cartcode'] = $cartcode;
    }
    ///ЕСТЬ ЛИ ТАКОЕ НАИМЕНОВАНИЕ С КОДОМ УЖЕ В КОРЗИНЕ?////////////////
    $res = queryMysql("SELECT * FROM cart WHERE code_number='$cartcode' "
        . "AND item_id='$itemid'");
    if ($res->num_rows) {
        while ($row = $res->fetch_assoc()) {
            $quantity_incart = $row['quantity'];
            $quantity += $quantity_incart; ////////Обновляем количество в корзине
            $uptres = queryMysql("UPDATE cart SET quantity='$quantity' "
                . "WHERE code_number='$cartcode' AND item_id='$itemid'");
        }
    } else {
        /// ИНАЧЕ Добавляем в корзину новое наименование////////////////////////////////
        $result = queryMysql("INSERT INTO cart (code_number, item_id, quantity) "
            . "VALUES ($cartcode, $itemid, $quantity)");
        if (!$result)
            die('???');
    }
}
////ОБНОВЛЯЕМ КОРЗИНУ В ЗАГОЛОВКЕ (header)///////////////////////////////////////////
if (isset($_SESSION['cartcode'])) {
    //Получаем данные о покупках по коду корзины/////////////////////////////
    $cartcode = sanitizeString($_SESSION['cartcode']);
    $result = queryMysql("SELECT * FROM cart WHERE code_number='$cartcode'");
    $itemsquantity = 0;
    $sum = 0;
    while ($row = $result->fetch_assoc()) {
        $itemsquantity += $row['quantity'];
        $item_id = $row['item_id'];
        ///////Получаем информацию о цене наименования//////////////////////
        $res_price = queryMysql("SELECT * FROM items WHERE id='$item_id'");
        while ($rowitem = $res_price->fetch_assoc()) { //Умножаем кол-во на цену
            $sum += $rowitem['price'] * $row['quantity'];
        }
    }
    echo "Всего $itemsquantity наименов.<br> На сумму $sum руб.";

}

///ЗАПОЛНЕНИЕ СОДЕРЖИМОГО КОРЗИНЫ/////////////////////////////////////////
if (isset($_SESSION['cartcode']) && isset($_POST['fill'])) {
    //Получаем данные из корзины, сгруппированные по наименованию //
    $cartcode = sanitizeString($_SESSION['cartcode']);
    $result = queryMysql("SELECT items.id, items.name, quantity, "
        . "items.price, items.available "
        . "FROM cart JOIN items ON cart.item_id=items.id WHERE "
        . "code_number='$cartcode' GROUP BY items.name");
    $sum = 0;
    if($result->num_rows) {
        echo "<tr><th> </th><th>Наименование</th><th>Кол-во</th><th>Цена</th>"
            . "<th>Сумма</th></tr>";
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
            } else
                $itemsum = 'нет в наличии';
            $itemprice = number_format($itemprice, 2, ",", '');

            echo <<<_END
<tr class='item' id="$itemid">
<td><img src='images/$itemid.jpg' height='50' width='50'></td>
<td>$itemname</td>
<td><img class="minus button" src="images/minus.png">
<input type='number' name='$itemid' value='$itemquantity' min='1' step='1'>
<img class="plus button" src="images/plus.png">
</td>
<td align='right'>$itemprice</td><td align='right'>$itemsum</td>
<td align='right'><input class='cross button' type="button" name="delete" ></td></tr>
_END;
        }
        echo "<tfoot><tr><td></td><td colspan=3 align='right'>ИТОГО</td>"
            . "<td><b>$sum руб.</b></td></tr></tfoot>";
    } else
        echo '<p>Ваша корзина Пуста!</p>';
}
?>

<?php ///ИЗМЕНЕНИЕ КОРЗИНЫ ///////////////////////////////////////////////
if (isset($_SESSION['cartcode']) && isset($_POST['item_id']) && isset($_POST['direction'])) {
    $cart_code = sanitizeString($_SESSION['cartcode']);
    $direction = $_POST['direction'];
    $item_id = $_POST['item_id'];
    if ($result = queryMysql("SELECT quantity FROM cart "
        . "WHERE code_number=$cart_code AND item_id=$item_id")) {
        while ($row = $result->fetch_array()) {
            $quantity = $row[0];
            if($direction == '+1' && $quantity > 0) {
                $res = queryMysql("UPDATE cart SET quantity='".($quantity + 1)."' "
                    . "WHERE code_number='$cart_code' AND  item_id='$item_id'");
            } elseif ($direction == '-1' && $quantity > 1) {
                $res = queryMysql("UPDATE cart SET quantity='".($quantity - 1)."' "
                    . "WHERE code_number='$cart_code' AND  item_id='$item_id'");
            } elseif ($direction == '0' && $quantity > 0) {
                $res = queryMysql("DELETE FROM cart  "
                    . "WHERE code_number='$cart_code' AND  item_id='$item_id'");
            }
        }
    }

}
?>

<?php ///  УДАЛЕНИЕ ВСЕЙ КОРЗИНЫ ///////////////////////////////////////////////
if (isset($_SESSION['cartcode']) && isset($_POST['delete'])) {
    $cart_code = sanitizeString($_SESSION['cartcode']);
    echo $cart_code;
    $result = queryMysql("DELETE FROM cart WHERE code_number=$cart_code ");
    unset($_SESSION['cartcode']);
}
?>