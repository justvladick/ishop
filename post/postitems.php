<?php

session_start();
require '../functions.php';

if (isset($_GET['catname'])) {
    $catname = sanitizeString($_GET['catname']);
    $result = queryMysql("SELECT * FROM items WHERE category='$catname' ORDER BY name");
} elseif (isset($_GET['subcatname'])) {
    $subcatname = sanitizeString($_GET['subcatname']);
    $result = queryMysql("SELECT * FROM items WHERE subcategory='$subcatname' ORDER BY name");
} elseif (isset($_GET['searchtext'])) {
    $search = sanitizeString($_GET['searchtext']);
     $result = queryMysql("SELECT * FROM items WHERE name LIKE '%$search%' OR description LIKE '%$search%' "
             . "OR category LIKE '%$search%' OR subcategory LIKE '%$search%' ORDER BY name");
}
if ($result->num_rows > 0) {
    echo "<table>";
    while ($row = $result->fetch_assoc()) {
        $id = $row['id'];
        $name = $row['name'];
        $descr = $row['description'];
        $price = $row['price'];
        echo <<<_END
<tr><th></th>
<th colspan='3' align='center'>$name</th>
<tr class='item'><td><img src='images/$id.jpg' height='100' width='100'></td>
<td>$descr</td><td>Кол-во: <input type='number' id='$id' name='order_quantity' 
value='1' onchange=setQuantity($id) min='1' step='1'></td>
<td><span class='price'>$price,00 <br><br></span>
<input type='button' id='$id' name='buy' value='Добавить в корзину' 
onclick=buyitem(this)></td></tr>
_END;
    }
    echo "</table>";
    //////////////////EVENTS PRESSING BUY BUTTON/////////////////////////////////////
    echo <<<_END
<script>

</script>
_END;
}

