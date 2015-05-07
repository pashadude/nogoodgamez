/**
 * jTinder initialization
 */
$("#tinderslide").jTinder({
	// dislike callback
    onDislike: function () {

        var gamenumber = $("#gamelist li").length;
        var gamename = $.trim($("#gamelist li:last").text());

        $.ajax({
            url: 'liker.php',//abs path
            type: 'post',
            data: {'action': 'dislike', 'gamename':gamename, 'pane': gamenumber},
            success: function(data, status) {
                $('#gamelist').prepend(data);
            },
            error: function(xhr, desc, err) {
                console.log(xhr);
                console.log("Details: " + desc + "\nError:" + err);
            }
        });



    },
	// like callback
    onLike: function () {

        var gamenumber = $("#gamelist li").length;
        var gamename = $.trim($("#gamelist li:last").text());

        $.ajax({
            url: 'liker.php',
            type: 'post',
            data: {'action': 'like', 'gamename': gamename, 'pane': gamenumber},
            success: function(data, status) {
                $('#gamelist').prepend(data);
            },
            error: function(xhr, desc, err) {
                console.log(xhr);
                console.log("Details: " + desc + "\nError:" + err);
            }
        });

    },
	animationRevertSpeed: 200,
	animationSpeed: 400,
	threshold: 1,
	likeSelector: '.like',
	dislikeSelector: '.dislike',
    refreshOnNext: true
});

/**
 * Set button action to trigger jTinder like & dislike.
 */
$('.actions .like, .actions .dislike').click(function(e){
	e.preventDefault();
	$("#tinderslide").jTinder($(this).attr('class'));
});