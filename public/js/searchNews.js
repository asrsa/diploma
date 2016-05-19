$(document).ready(function() {

    $('#searchText').hide();

    $('#searchButton').click(function() {
        event.preventDefault();
        $('#searchText').animate({width: 'toggle'}, 200);
        $('#searchText').focus();

        $('#searchButton').css('pointer-events', 'none');
    });

    $('#searchText').focusout(function() {
        $('#searchText').animate({width: 'toggle'}, 200);
        $('#searchText').val('');
        $('#searchButton').css('pointer-events', '');
    });


    $('#searchText').keypress(function(event) {
        var query = $('#searchText').val();
        //enter press
        if(event.which == 13) {
            if(query == '') {
                event.preventDefault();
            }
        }
    });
});