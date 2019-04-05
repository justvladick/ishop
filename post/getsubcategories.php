<?php
//Формирует список подкатегорий в зависимости от выбранной категории при добавлении нового наименования
session_start();
require '../functions.php';
if (isset($_GET['category'])) {
    $catname = sanitizeString($_GET['category']);
    $result = queryMysql("SELECT * FROM subcategory WHERE category='$catname' ORDER BY name");
}
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $name = $row['name'];
        echo "<option value='$name'>$name</option>";
    }
}