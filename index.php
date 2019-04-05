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
        <a href="#">О магазине  |</a>
      </nav>
      <a class="header" href="index.php"><h1>Интернет-магазин</h1></a>
      <a class="header" href="cart.php"><div class="cart">Корзина<p class="cart">Пусто</p></div></a>
    </header>

    <main>
      <nav class='navigation'> <h3>КАТАЛОГ ПРОДУКЦИИ</h3>
        <?php
        ///////////PRINT CATEGORIES////////////////////////////////
        $result = queryMysql("SELECT * FROM category ORDER BY name");
        if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
            $cat = $row['name'];
            $id = $row['id'];
            echo "<p class='category' id='$id'>$cat</p>";
            ///////////FOR EACH CATEGORY PRINT SUBCATEGORIES////////////
            $resultsub = queryMysql("SELECT * FROM subcategory WHERE"
                    . " category='$cat' ORDER BY name");
            if ($resultsub->num_rows > 0) {
              echo "<ul class='subcat' id='$id' hidden>";
              while ($subrow = $resultsub->fetch_assoc()) {
                $subcat = $subrow['name'];
                $subid = $subrow['id'];
                echo "<li class='subcat' id='$subid'>$subcat</li>";
              }
              echo '</ul>';
            }
          }
        }
        ?>
      </nav>

      <div class="items">
        <h1 class="maroon">Вас приветствует интернет-магазин ""</h1>

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
      </div><?php echo date("Y-m-d"); ?>
    </footer>
    <script src="js/index.js"></script>
    <script>
                  function exit(elem) {
                    $.post("post/exit.php", {exit: true}, function (data) {
                      elem.innerHTML(data);
                    })
                  }
    </script>
  </body>
</html>
<?php
