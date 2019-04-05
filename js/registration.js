$(document).ready(function () {
    //////LOAD CART///////////////////////////////////////////
    $.post("postcart.php", function (data) {
        $("p.cart").html(data);
    })


});

function validateEnter(form) { ///НЕ работает
//    $("span.enter").text("*");
//    var cheked = "";
    var email = document.forms["enter"]["email"].value;
    var pas = document.forms["enter"]["password"].value;
    $.post("checkemail.php", {checklogin: email, checkpass: pas}, function (data) {
        $("span.enter").eq(0).text(data);
        cheked = data;
        
    });
    cheked = $("span.enter").eq(0).val();
    if (cheked !== "Success") {
        $("span.enter").eq(0).text(cheked);
        return false;
    }
}

function validateForm(form) {
    $("span.new_order").text("*");
    isUsed = false;
    var email = document.forms["new_order"]["email"].value;
    $.post("checkemail.php", {checkemail: email}, function (data) {
        isUsed = data;
    });
    if (isUsed) {
        $("span.new_order").eq(0).text("Этот адрес электронной почты уже зарегистрирован");
        return false;
    }
    var re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!re.test(email)) {
        $("span.new_order").eq(0).text("Заполните правильно адрес электронной почты");
        return false;
    }
    var phone = document.forms["new_order"]["phone"].value;
    if (phone.length < 4) {
        $("span.new_order").eq(1).text("Заполните номер телефона");
        return false;
    }
    ////ПРОВЕРКА ПАРОЛЯ/////////////////////////////////////
    var password = document.forms["new_order"]["password"].value;
    if (password != "") {
        if (password.length < 6) {
            $("span.new_order").eq(2).text("Пароль должен быть длиннее 6 символов!");
            return false;
        }
        re = /[0-9]/;
        if (!re.test(password)) {
            $("span.new_order").eq(2).text("Пароль должен содержать хотя бы одно число (0-9)!");
            return false;
        }
        re = /[a-z]/;
        if (!re.test(password)) {
            $("span.new_order").eq(2).text("Пароль должен содержать хотя бы одну букву (a-z)!");
            return false;
        }
        re = /[A-Z]/;
        if (!re.test(password)) {
            $("span.new_order").eq(2).text("Пароль должен содержать хотя бы одну заглавную букву (A-Z)!");
            return false;
        }
    } else {
        $("span.new_order").eq(2).text("Не введен пароль!");
        return false;
    }
    /////////////////////////////////////////////////////////////
    var name = document.forms["new_order"]["name"].value;
    if (name.length < 2) {
        $("span.new_order").eq(3).text("Заполните ваше имя");
        return false;
    }
    var city = document.forms["new_order"]["city"].value;
    if (city.length < 2) {
        $("span.new_order").eq(4).text("Заполните город");
        return false;
    }
    var postalcode = document.forms["new_order"]["postalcode"].value;
    if (postalcode.length < 4) {
        $("span.new_order").eq(5).text("Заполните почтовый индекс");
        return false;
    }
    var address = document.forms["new_order"]["address"].value;
    if (address.length < 6) {
        $("span.new_order").eq(6).text("Заполните адрес доставки");
        return false;
    }

}