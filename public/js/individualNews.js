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
        $('#closeCommentForm').removeClass('hidden');
    });

    //klik na 'krizec'
    $('#closeCommentForm').click(function() {
        $('#postCommentContainer').addClass('hidden');
        $('#postComments').removeClass('hidden');
        $('#closeCommentForm').addClass('hidden');
    });

    $('#postCommentForm').click(function() {
        var url = document.location.origin + '/ajax/post/comment';
        var data = $('#comment').val();
        var token = $('input[name=_token]').val();
        var newsId = window.location.href.split('/');
        newsId = newsId[newsId.length - 1];

        //errors
        var emptyComment = $('#emptyComment').text();
        var addedCommentSuccess = $('#addedCommentSuccess').text();

        var commentCount = $('#commentsContainer > #childRow').length;
        var splitUrls      = window.location.href.split('?');
        var page = null;

        splitUrls.forEach(function(el) {
            if(el.substring(0,4) == 'page'){
                page = el.split('=')[1];
            }
        })

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


                    if(commentCount <= 4) {
                        $('#ajaxAdd').append(
                            '<div id="childRow" class="row">' +
                            '<div class="panel">' +
                            '<div class="col-md-2">' +
                            '<img src="' + document.location.origin + '/avatars/' + data['user'].avatar + '" style="width: 46px; height: 46px;">' +
                            '<p>' + data['user'].firstName + '</p>' +
                            '</div>' +
                            '<div class="col-md-10">' +
                            '<p>' + data['comment'].body + '</p>' +
                            '</div>' +
                            '</div>' +
                            '</div>'
                        );
                    }
                    else if(commentCount == 5) {
                        //check for page=
                        page++;
                        window.location = splitUrls[0] + '?page=' + page;

                        $('#ajaxAdd').append(
                            '<div id="childRow" class="row">' +
                            '<div class="panel">' +
                            '<div class="col-md-2">' +
                            '<img src="' + document.location.origin + '/avatars/' + data['user'].avatar + '" style="width: 46px; height: 46px;">' +
                            '<p>' + data['user'].firstName + '</p>' +
                            '</div>' +
                            '<div class="col-md-10">' +
                            '<p>' + data['comment'].body + '</p>' +
                            '</div>' +
                            '</div>' +
                            '</div>'
                        );
                    }
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