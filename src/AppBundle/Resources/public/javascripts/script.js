var more = false;
function getMonthTitle(month){
    if ( month == 1 ) return 'Янв';
    if ( month == 2 ) return 'Фев';
    if ( month == 3 ) return 'Мар';
    if ( month == 4 ) return 'Апр';
    if ( month == 5 ) return 'Май';
    if ( month == 6 ) return 'Июн';
    if ( month == 7 ) return 'Июл';
    if ( month == 8 ) return 'Авг';
    if ( month == 9 ) return 'Сен';
    if ( month == 10 ) return 'Окт';
    if ( month == 11 ) return 'Ноя';
    if ( month == 12 ) return 'Дек';
}
function serialize( mixed_val ) {    // Generates a storable representation of a value
    //
    // +   original by: Ates Goral (http://magnetiq.com)
    // +   adapted for IE: Ilia Kantor (http://javascript.ru)

    switch (typeof(mixed_val)){
        case "number":
            if (isNaN(mixed_val) || !isFinite(mixed_val)){
                return false;
            } else{
                return (Math.floor(mixed_val) == mixed_val ? "i" : "d") + ":" + mixed_val + ";";
            }
        case "string":
            return "s:" + mixed_val.length + ":\"" + mixed_val + "\";";
        case "boolean":
            return "b:" + (mixed_val ? "1" : "0") + ";";
        case "object":
            if (mixed_val == null) {
                return "N;";
            } else if (mixed_val instanceof Array) {
                var idxobj = { idx: -1 };
                var map = []
                for(var i=0; i<mixed_val.length;i++) {
                    idxobj.idx++;
                    var ser = serialize(mixed_val[i]);

                    if (ser) {
                        map.push(serialize(idxobj.idx) + ser)
                    }
                }

                return "a:" + mixed_val.length + ":{" + map.join("") + "}"

            }
            else {
                var class_name = get_class(mixed_val);

                if (class_name == undefined){
                    return false;
                }

                var props = new Array();
                for (var prop in mixed_val) {
                    var ser = serialize(mixed_val[prop]);

                    if (ser) {
                        props.push(serialize(prop) + ser);
                    }
                }
                return "O:" + class_name.length + ":\"" + class_name + "\":" + props.length + ":{" + props.join("") + "}";
            }
        case "undefined":
            return "N;";
    }

    return false;
}

$(document).ready(function() {
    $('.fancybox').fancybox();

    $( "#grp" ).slider({
        range: true,
        min: 0,
        max: 300,
        values: [ 75, 250 ],
        slide: function( event, ui ) {
            $( "#grp-amount" ).val( "" + ui.values[ 0 ]/100 + " - " + ui.values[ 1 ]/100 );
        }
    });
    $( "#grp-amount" ).val( "" + $( "#grp" ).slider( "values", 0 )/100 +
    " - " + $( "#grp" ).slider( "values", 1 )/100 );

    $( "#ots" ).slider({
        range: true,
        min: 0,
        max: 500,
        values: [ 75, 300 ],
        slide: function( event, ui ) {
            $( "#ots-amount" ).val( "" + ui.values[ 0 ] + " - " + ui.values[ 1 ] );
        }
    });
    $( "#ots-amount" ).val( "" + $( "#ots" ).slider( "values", 0 ) +
    " - " + $( "#ots" ).slider( "values", 1 ) );


    $( "#month" ).slider({
        range: true,
        min: 1,
        max: 12,
        values: [ 3, 57 ],
        slide: function( event, ui ) {
            $( "#month-amount" ).val( "" + getMonthTitle(ui.values[ 0 ]) + " - " + getMonthTitle(ui.values[ 1 ]) );
        }
    });
    $( "#month-amount" ).val( "" + getMonthTitle($( "#month" ).slider( "values", 0 )) +
    " - " + getMonthTitle($( "#month" ).slider( "values", 1 ) ));

    $( "#price" ).slider({
        range: true,
        min: 0,
        max: 200,
        values: [ 0, 200 ],
        slide: function( event, ui ) {
            $( "#price-amount" ).val( "" + ui.values[ 0 ] + "k - " + ui.values[ 1 ] + 'k' );
        }
    });
    $( "#price-amount" ).val( "" + $( "#price" ).slider( "values", 0 ) +
    "k - " + $( "#price" ).slider( "values", 1 )+ 'k' );


    $('body').on('click','.more',function(){
        var id = $(this).attr('data-id');
        if (more == false){
            $('#map').animate({ "width": "-=300px" }, 500);
            $('#sidebar').animate({ "width": "+=300px" }, 500, function(){
                $('#map').css('width','calc(100% - 550px)');
            });
            more = true;
            $('.close-slidebar').fadeIn();
            $('.filter-box').css('display','none');
        }

        $.ajax({
            type: "POST",
            url: Routing.generate('get_more_info'),
            data: "id="+id,
            success: function (data) {
                $('#content').html(data);
                $('.fancybox').fancybox();
            }
        });
    });
    $('.close-slidebar').click(function(){
        $('#map').animate({ "width": "+=300px" }, 500);
        $('#sidebar').animate({ "width": "-=300px" }, 500, function(){
            $('#map').css('width','calc(100% - 250px)');
            myMap.container.fitToViewport();
        });
        $('#content').html('');
        $('.filter-box').css('display','block');
        $('.close-slidebar').fadeOut();
        more = false;
    })

    $('#login').click(function () {
       $('.popup.auth').fadeIn();
        return false;
    });

    $('.popupCancel').click(function () {
        $('.popup').fadeOut();
    });

    $('body').on('click','.addBasket',function(){
        var id = $(this).attr('data-id');

        $.ajax({
            type: "POST",
            url: Routing.generate('basket_add', {'itemId' : id }),
            data: "id="+id,
            success: function (data) {
                alert('Товар добавлен')
            }
        });
    });

});