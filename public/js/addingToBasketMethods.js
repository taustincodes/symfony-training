$(".audioToggle").on('click', function(){
    $("#audio").toggle();
});

function popUp(){
    $("#popUp").delay(400).slideDown(400).delay(1500).slideUp(400);
 }

$('.add-to-basket').on('click', function (){
    var pid = $(this).data('key');
    var idData = {"add": pid};

    $.ajax({
        type:'POST',
        url:'/productajax',
        data: idData,
        dataType: 'json',
        success: function (result) {
            popUp();
            showQuantity();
            console.log("added to basket");
        },
        error : function (){
            console.log("Error: Cannot get basket quantity.");
        }
    });
});

$('.removeFromBasket').on('click', function(){
    var pid = $(this).data('key');
    var idData = {"del": pid};

    $.ajax({
        type:'POST',
        url:'/mybasket/remove/ajax',
        data: idData,
        dataType: 'json',
        success:  function (result){
            var rowId = "#row" + result.key;
            $(rowId).remove();
            updatePrice();
            showQuantity();
        }
    });
});

$('.quantityUp').on('click', function(){
    pid = $(this).data('key');
    var idData = {"qUp": pid};

    $.ajax({
        type:'POST',
        url:'/mybasket/quantityup/ajax',
        data: idData,
        dataType: 'json',
        success:  function (result){
            var quantityId = "#quantity" + result.key;
            $(quantityId).text(result.quantity)
            //
            showQuantity();
            updatePrice();
        }
    });
});
$('.quantityDown').on('click', function(){
    pid = $(this).data('key');
    var idData = {"qDown": pid};

    $.ajax({
        type:'POST',
        url:'/mybasket/quantitydown/ajax',
        data: idData,
        dataType: 'json',
        success:  function (result){
            var rowId = "#row" + result.key;
            var quantityId = "#quantity" + result.key;

            if(result.action == 'removed'){
                $(rowId).remove();
                // updatePrice();


            }else {
            $(quantityId).text(result.quantity)
            }
            showQuantity();
            updatePrice();

        }
    });
});
function updatePrice(){
    $.ajax({
        type:'POST',
        url:'/mybasket/updateprice/ajax',
        data:"",
        dataType:'json',
        success: function (result){
            if(result.total == 0){
                $('#total').remove();
            }else {
                $('#totalPrice').text('Total: £' + (result.total).toFixed(2))
            }

        },
        error: function(){
            console.log("Broken");
        }
    });
}

function showQuantity(){
    $.ajax({
        url: "/mybasket/getbasketquantity/ajax",
        type: 'POST',
        data: "",
        dataType: 'json',
        success: function (result) {
            $('#circle').text(result.itemsInBasket);
        },
        error : function (){
            console.log("Error: Cannot get basket quantity.");
        }
    });
}