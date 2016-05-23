$(document).ready(function() {
    $('#confirmDelete').on('show.bs.modal', function(e) {
        $(this).find('.btnDelete').attr('href', $(e.relatedTarget).data('href'));
    });


    $('#search').keypress(function(event) {
        if(event.which == 13) {
            var query = $(this).val();
            var url = window.location.href;
            var get = url.split('?');

            if(get.length == 1) {
                var append = '?search='
                url = window.location.href.split('?');
                url = url[0] + append + query;
            }
            else if(get.length > 1) {
                var gets = get[1].split('&');
                jQuery.each(gets, function(index, value) {
                   if(value.substring(0,6) == 'search') {
                       gets.splice(index, 1);
                   }
                });
                var append = '&search='
                get = gets.join('&');
                get = '?' + get;

                url = url.split('?');
                url = url[0] + get + append + query;
            }

            location.assign(url);
        }
    });
});