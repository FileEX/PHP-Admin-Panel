(function() {
    var startingTime = new Date().getTime();
    var script = document.createElement("SCRIPT");
    script.src = 'https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js';
    script.type = 'text/javascript';
    document.getElementsByTagName("head")[0].appendChild(script);

    var checkReady = function(callback) {
        if (window.jQuery) {
            callback(jQuery);
        }
        else {
            window.setTimeout(function() { checkReady(callback); }, 20);
        }
    };

    checkReady(function($) {
        $(function() {
			 $(window).scroll(function(){
			    $('header').css({
			        'left': $(this).scrollLeft()
			    });

			    if($(this).scrollLeft() == 15)
				{
				   $('header').css({
				      'left': 0
				    });
				}
			});

        });
    });
})();

