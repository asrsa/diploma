$(document).ready(function() {

    var href = null;

    $('[data-toggle="upvoteTip"]').tooltip();

    //klik na upvote
    $('.upvote').click(function() {
        href = $(this).attr('href');
        event.preventDefault();
    });

    //klik na downvote
    $('.downvote').click(function() {
        href = $(this).attr('href');
        event.preventDefault();
    });

    $('.thumbs').click(function() {
        var url = document.location.origin + '/comment/like';
        var query = href.split('?');

        $.ajax({
            type:   'GET',
            url:    url  + '?' + query[1],
            dataType: 'json',
            success: function(result) {
                var commentId = result['cid'];
                var likesCurr = $('#likes'+ commentId).text() == '' ? '0' : $('#likes'+ commentId).text();
                var newLikes = parseInt(likesCurr, 10) + result['value'];

                if(result['return'] === 2) {
                    //TODO
                    $('#likes'+ commentId).text(newLikes);
                    $('#downvote' + commentId).css('color', '');
                    $('#downvote' + commentId).css('pointer-events', '');

                    $('#downvotea' + commentId).removeClass('not-active');
                    event.preventDefault();
                }
                else if(result['return'] === 3) {
                    $('#likes'+ commentId).text(newLikes);
                    $('#upvote' + commentId).css('color', '');
                    $('#upvote' + commentId).css('pointer-events', '');

                    $('#upvotea' + commentId).removeClass('not-active');
                    event.preventDefault();
                }
                else if(result['return'] === 4){
                    //cannot like anymore
                    $('#upvote' + commentId).css('color', 'grey');
                    $('#upvotea' + commentId).addClass('not-active');
                }
                else if(result['return'] === 5){
                    //cannot dislike anymore
                    $('#downvote' + commentId).css('color', 'grey');
                    $('#downvotea' + commentId).addClass('not-active');
                }
                else {
                    $('#likes'+ commentId).text(newLikes);
                }
            }
        });
    });
});
