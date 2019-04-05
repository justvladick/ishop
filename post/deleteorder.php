<?php

session_start();
require '../functions.php';
///ПОМЕЧАЕМ ЗАКАЗ КАК ОТМЕНЕННЫЙ в таблице orders//////////////////////////////////////////////////
if (isset($_POST['ordernumber'])) {
    $ordernumber = sanitizeString($_POST['ordernumber']);
    $uptres = queryMysql("UPDATE orders SET status='ОТМЕНЕН' "
            . "WHERE cart_number='$ordernumber'");
    if ($uptres)
        echo "Отменен";
    else
        echo "Не удалось отменить";
}
