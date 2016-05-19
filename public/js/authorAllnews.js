$(document).ready(function() {
    $('#confirmDelete').on('show.bs.modal', function(e) {
        $(this).find('.btnDelete').attr('href', $(e.relatedTarget).data('href'));
    });
});