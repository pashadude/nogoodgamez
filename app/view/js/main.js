/**
 * jTinder initialization
 */
$("#tinderslide").jTinder({
	// dislike callback
    onDislike: function (item) {
	    // set the status text
        var gamenumber = $("#gamelist li").length;
        var gamepane ='#'+'gamepane'+(gamenumber - 1);

        $('#status').html('Dislike game ' +  $(gamepane.toString()).text());
        $.ajax({
            url: 'liker.php',
            type: 'post',
            data: {'action': 'dislike', 'gamename': $(gamepane.toString()).text(), 'pane': gamenumber},
            success: function(data, status) {
                $('#gamelist').append(data);
            },
            error: function(xhr, desc, err) {
                console.log(xhr);
                console.log("Details: " + desc + "\nError:" + err);
            }
        });

        //init view with 'pane'.(item.index()+2) class
    },
	// like callback
    onLike: function (item) {
	    // set the status text
        var gamenumber = $("#gamelist li").length;
        var gamepane ='#'+'gamepane'+(gamenumber - 1);


        $('#status').html('Like game ' +  $(gamepane.toString()).text() );

        $.ajax({
            url: 'liker.php',
            type: 'post',
            data: {'action': 'like', 'gamename': $(gamepane.toString()).text(), 'pane': gamenumber},
            success: function(data, status) {
                $('#gamelist').append(data);
            },
            error: function(xhr, desc, err) {
                console.log(xhr);
                console.log("Details: " + desc + "\nError:" + err);
            }
        });
    },
	animationRevertSpeed: 200,
	animationSpeed: 400,
	threshold: 35,
	likeSelector: '.like',
	dislikeSelector: '.dislike'
});

/**
 * Set button action to trigger jTinder like & dislike.
 */
$('.actions .like, .actions .dislike').click(function(e){
	e.preventDefault();
	$("#tinderslide").jTinder($(this).attr('class'));
});