var catId = $('#catId').val();
var token = $('input[name=_token]').val();
var url= document.location.origin + '/ajax/subscribe/cat';

$(document).ready(function() {
    subButtons();

    //subscribe
    $('#subscribe').click(function() {
        $.ajax({
            type:   'GET',
            url:    url  + '?cat=' + catId + '&sub=1',
            dataType: 'json',
            success: function(result) {
                subscribe(result);
            }
        })
    });

    //unsubscribe
    $('#unsubscribe').click(function() {
        $.ajax({
            type:   'GET',
            url:    url  + '?cat=' + catId + '&sub=0',
            dataType: 'json',
            success: function(result) {
                unsubscribe(result);
            }
        })
    });

});

function subscribe(data) {
    if(data == 'success') {
        $('#subscribe').addClass('hidden');
        $('#unsubscribe').removeClass('hidden');
    }
}

function unsubscribe(data) {
    if(data == 'success') {
        $('#subscribe').removeClass('hidden');
        $('#unsubscribe').addClass('hidden');
    }
}

function subButtons() {
    var unsub = $('#unsubText').val();
    var subed = $('#unsubscribe').text();

    $('#unsubscribe').hover(
        function() {
            $(this).text(unsub);
            $(this).removeClass('btn-success');
            $(this).addClass('btn-danger');
        },
        function() {
            $(this).text(subed);
            $(this).removeClass('btn-danger');
            $(this).addClass('btn-success');
        }
    );

    $('#subscribe').hover(
        function() {
            $(this).removeClass('btn-danger');
            $(this).addClass('btn-success');
        },
        function() {
            $(this).removeClass('btn-success');
            $(this).addClass('btn-danger');
        }
    );
}