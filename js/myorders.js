$(document).ready(function () {
    //////LOAD CART///////////////////////////////////////////
    $.post("postcart.php", function (data) {
        $("p.cart").html(data);
    })

});


function loadOrderDetails(elem) {
    id = elem.id;
    $(elem).parent().parent().next().toggle(200);
}