$(document).ready(function() {

    $('#lfm').filemanager('image');

    $('#category').change(function() {
        var url = document.location.origin + '/ajax/subcategory';
        var catId = $(this).val();
        $.ajax({
            type:   'GET',
            url:    url + '?catId=' + catId,
            dataType: 'json',
            success: function(result) {
                $('#subcategory option').remove();
                $.each(result, function(i, subcat) {
                   $('#subcategory').append('<option value="' + subcat.id + '">' + subcat.desc + '</option>')
                });
            }
        });
    });
});