$(document).ready(function() {

    //klik na 'pokazi komentarje'
    $('#showComments').click(function() {
        $('#commentsContainer').removeClass('hidden');
        $('#showComments').addClass('hidden');
        $('#hideComments').removeClass('hidden');
    });

    //klik na 'skrij komentarje'
    $('#hideComments').click(function() {
        $('#commentsContainer').addClass('hidden');
        $('#showComments').removeClass('hidden');
        $('#hideComments').addClass('hidden');
    });

    //klik na 'objavi komentar'
    $('#postComments').click(function() {
        $('#postCommentContainer').removeClass('hidden');
        $('#postComments').addClass('hidden');
    });

    $('#postCommentForm').click(function() {
        var url = document.location.origin + '/ajax/post/comment';
        var data = $('#comment').val();
        var token = $('input[name=_token]').val();
        var newsId = window.location.href.split('/');
        newsId = newsId[newsId.length - 1];

        console.log(newsId);
        //errors
        var emptyComment = $('#emptyComment').text();
        var addedCommentSuccess = $('#addedCommentSuccess').text();

        if(data != '') {
            $.ajax({
                type: 'POST',
                url: url,
                dataType: 'json',
                data: {'_token':token, 'body':data, 'news':newsId},
                success: function (data) {

                    $('#comment').val('');
                    $('#postCommentContainer').addClass('hidden');
                    $('#postComments').removeClass('hidden');

                    $('#feedback').append('<div id="commentAddAjaxSuccess" class="alert alert-success col-md-7">' +
                            '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' +
                            '<strong>' + addedCommentSuccess + '</strong>' +
                            '</div>'
                    );

                    console.log(data);
                }
            });
        }
        else {
            $('#feedback').append('<div id="commentAddAjaxFail" class="alert alert-danger col-md-7">' +
                    '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' +
                    '<strong id="emptyComment">' + emptyComment + '</strong>' +
                    '</div>'
            );
        }
    });
});