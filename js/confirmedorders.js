function showCustomer(cusid) {
    $.post("post/postconfirmedorders.php", {customerid: cusid}, function (data) {
        $("div.persona").html(data);
    })
    $.post("post/postconfirmedorders.php", {customerid_orders: cusid}, function (data) {
        $("div.orders").html(data);
    })
}
function updateItemQuantity(elem) {
    quantity = $(elem).val();
    ordercartnumber = $(elem).attr('data-cartnumber');
    $itemid = $(elem).attr('id');
    $.post("post/updateorderquantity.php", {ordercart_number: ordercartnumber, itemid: $itemid,
        order_quantity: quantity}, function (data) {
        $(elem).parent().next().next().text(data);
        $("input[type='submit']").attr('disabled', true);
    })
}
function deleteItem(button) {
    ordercartnumber = $(button).attr('data-cartnumber');
    $itemid = $(button).attr('id');
    $.post("post/updateorderquantity.php", {ordercart_number: ordercartnumber, itemid: $itemid,
        deleteitem: true}, function (data) {
        $(button).parent().text(data);
        $("input[type='submit']").attr('disabled', true);
    })
}

function deleteOrder(elem) {
    var order_number = $(elem).attr('id');
    if (!confirm("Вы действительно хотите отменить заказ № " + order_number + "?"))
        return false;
    $.post("post/deleteorder.php", {ordernumber: order_number}, function (data) {
        $(elem).parent().html(data);
    })
}
function askForConfirmation(elem) {
    return confirm("Вы действительно хотите подтвердить отправку заказа № " + elem.id + " ?");
}

function refreshButton($customerid) {
    showCustomer($customerid);
    $("input[type='submit']").attr('disabled', false);
}