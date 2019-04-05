$(document).ready(function () {
  //////LOAD CART///////////////////////////////////////////
  $.post("postcart.php", function (data) {
    $("p.cart").html(data);
  })
  ///////SHOW SUBCATEGORIES ON CLICK/////////////////////////
  $("p.category").each(function (index) {
    $(this).bind("click", function () {
      $ul = $(this).next("ul");
      $ul.toggle(200);
      //////ADD EVENTS TO SUBCATEGORIES LI class=id////////////////
      $ul.children().each(function (index) {
        $(this).bind("click", function () {
          $subcatname = $(this).text();
          $("div.items").slideUp();
          $.get("post/postitems.php", {subcatname: $subcatname}, function (data) {
            $("div.items").html(data);
            $("div.items").slideDown();
          });
        })
      })
      //POST DATA AND SHOW IT IN DIV ITEMS/////////////
      $catname = $(this).text();
      $("div.items").slideUp();
      $.get("post/postitems.php", {catname: $catname}, function (data) {
        $("div.items").html(data);
        //$("div.items").removeAttr(hidden);
        $("div.items").slideDown();

      });
    })
  })

});

//quantity = 1;

function buyitem(elem) {
  quant = $(elem).parent().prev().find("input").val();
  $.post("postcart.php", {itemid: elem.id, quantity: quant}, function (data) {
    $("div.cart p").html(data);
    $(elem).val("В корзине");
    $(elem).attr('disabled', true);
    addingMassage($("div.cart p"));
  });
}

//function setQuantity(itemid, element) {
////    thisval = $(this).val()
////    quantityArray[itemid] = thisval;
////console.log(element.val());
//    quantity = itemid;
//}

function addingMassage(element)
{
  // if ($("body").find("div#win")) {
  position = $(element).parent().parent().offset();
  $divwin = $("<div id='win'>");
  $divwin.hide();
  $left = screen.availWidth / 2;
  $top = screen.availWidth / 2;
  $("body").append($divwin.append("<h2>Товар добавлен в корзину!</h2>"));
  $divwin.css('border', 'solid red');
  $divwin.css('width', '300px');
  $divwin.css('text-align', 'center');
  //$divwin.css('height', '300px');
  $divwin.css('position', 'fixed');
  $divwin.css('left', position['left']);
  $divwin.css('top', position['top']);
  $divwin.css('background', 'yellow');
  $divwin.fadeIn(200);
  $('#result').css('border', 'solid red');
  $('#result').css('background', 'yellow');
  $('#result').html('Вы щелкнули на кнопке!');
  setTimeout(function () {
    $divwin.fadeOut(200);
  }, 1000)
  setTimeout(function () {
    $divwin.remove();
  }, 1000)
  // }
}

//Search button
function findItems(button) {
  search_text = $(button).prev("input").val();
  if (search_text.length > 1) {
    $("div.items").slideUp();
    $.get("post/postitems.php", {searchtext: search_text}, function (data) {
      $("div.items").html(data);
      //$("div.items").removeAttr(hidden);
      $("div.items").slideDown();

    });
  }
}
//If enter pressed
$("input[name='search']").keypress(function (event) {
  var search_text = $(this).val();
  if (event.key == 'Enter' && search_text.length > 1) {
    $("div.items").slideUp();
    $.get("post/postitems.php", {searchtext: search_text}, function (data) {
      $("div.items").html(data);
      //$("div.items").removeAttr(hidden);
      $("div.items").slideDown();

    });
  }
});

//function addEventsToButtons() {
//    $("div.items").find("tr.item").each(function () {
//        $quantity = $(this).find(":number").val();
//        $(this).find(":button").bind("click", function () {
//            $itemid = $(":button").attr("id");
//            $.post("postcart.php", {
//                itemid: $itemid, quantity: $quantity
//            }, function (data) {
//                $("div.cart p").html(data);
//            });
//        })
//    })
//
//}