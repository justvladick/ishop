

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