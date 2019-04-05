<?php

session_start();
require '../functions.php';
///ВЫХОД//////////////////////////////////////////////////
if (isset($_POST['exit'])) {
    $_SESSION['token'] = null;
    $_SESSION['email'] = null;
    echo "<a href='index.php'> Перейти на главную</a>";
    
}
