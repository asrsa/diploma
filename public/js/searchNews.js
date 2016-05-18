$(document).ready(function() {

    $('#searchText').hide();

    $('#searchButton').click(function() {
        event.preventDefault();
        $('#searchText').animate({width: 'toggle'}, 200);
        $('#searchText').focus();
    });

    $('#searchText').focusout(function() {
        $('#searchText').animate({width: 'toggle'}, 200);
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