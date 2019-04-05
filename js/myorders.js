$(document).ready(function () {
    //////LOAD CART///////////////////////////////////////////
    $.post("postcart.php", function (data) {
        $("p.cart").html(data);
    })

});

function deleteOrder(elem) {
    var order_number = $(elem).attr('id');
    if (!confirm("Вы действительно хотите отменить заказ №" + order_number + "?"))
        return false;
    $.post("post/deleteorder.php", {ordernumber: order_number}, function (data) {
        elem.innerText = data;
    })
}

function loadOrderDetails(elem) {
    id = elem.id;
    $(elem).parent().parent().next().toggle(200);
}