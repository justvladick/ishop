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
      <div class="enter" align="center">
        <?php
        ///ВХОД

        if (isset($_POST['enter']) && isset($_POST['email']) && isset($_POST['password'])) {
          $sanemail = sanitizeString($_POST['email']);
          $hashpas = hashPassword(sanitizeString($_POST['password']));
          $result = queryMysql("SELECT * FROM customers WHERE email='$sanemail' "
                  . "AND  password='$hashpas'");
          if ($result->num_rows) {
            while ($row = $result->fetch_assoc()) {
              ///Создаем сессию с токеном
              $_SESSION['email'] = $row['email'];
              $_SESSION['token'] = $row['password'];
              echo "<p>Вы успешно вошли</p>"
              . "<a href='myorders.php'>"
              . "<p>Теперь вы можете перейти в заказы</p></a>";
            }
          } else
            echo "<p class='red'>Неправильный логин или пароль</p>";
        } else {
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
    </main>
    <footer><div align="right">

      </div></footer>
    <script src="js/myorders.js"></script>
  </body>
</html>
<?php
