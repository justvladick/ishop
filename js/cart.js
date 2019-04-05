$(function () {
    fillCartTable();
    $('input#delete_cart').click(function () {
        $.post('postcart.php', {delete: true}, function (data) {
            console.log(data);
        })
    });
});
//заполнение таблицы товаров в корзине
function fillCartTable() {
    $.post('postcart.php', {fill: true}, function (data) {
        $('#cart_table').html(data);
        //добавление событий кнопок удалить, плюс, минус
        $('.button').click(function (event) {
            var item_id = $(this).parent().parent().attr('id');
            if($(this).is('.minus')) {
                $.post('postcart.php', {item_id: item_id, direction: '-1'}, function (data) {
                    console.log(data);
                    fillCartTable()
                })
            } else if($(this).is('.plus')) {
                $.post('postcart.php', {item_id: item_id, direction: '+1'}, function (data) {
                    console.log(data);
                    fillCartTable()
                })
            } else if($(this).is('.cross')) {
                $.post('postcart.php', {item_id: item_id, direction: '0'}, function (data) {
                    console.log(data);
                    fillCartTable()
                })
            }
        })
    })
}