document.createElement('header');
document.createElement('nav');
document.createElement('aside');
document.createElement('section');
document.createElement('footer');

$(document).ready(function () {
	
    anti_ie7();

    $("#owl-slider").owlCarousel({

        navigation: true,
        pagination: true,
        responsive: true,
        slideSpeed: 300,
        paginationSpeed: 400,
        singleItem: true,
        navigationText: ["&lt;", "&gt;"],
        transitionStyle: false, // Effets disponibles : "fade", "backSlider", "goDown", "fadeUp"
        autoPlay: 3000,
        stopOnHover: true
    });
});


$(window).load(function () {
    var ScrollToBottom = $('<div id="scrollToBottom"><a href="#" title="Scroll to bottom">Bas du site</a></div>');
    var ScrollToTop = $('<div id="scrollToTop"><a href="#" title="Scroll to top">Haut du site</a></div>');
    var result = insertScroll(ScrollToTop, ScrollToBottom);
    $('#footer').after(ScrollToBottom);
    $('#footer').after(ScrollToTop);
    ScrollToBottom.click(function () {
        $('html,body').animate({'scrollTop': result.size + result.windowsHeight}, 1000, function () {
            ScrollToBottom.fadeOut("fast");
            ScrollToTop.fadeIn("fast");
        });
    });
    ScrollToTop.click(function () {
        $('html,body').animate({'scrollTop': 0}, 1000, function () {
            ScrollToTop.fadeOut("fast");
            ScrollToBottom.fadeIn("fast");

        });
    });
    $(window).scroll(function () {
        insertScroll(ScrollToTop, ScrollToBottom)
    });

    function insertScroll(ScrollToTop, ScrollToBottom) {
        var windowsHeight = $(window).height();
        var size = $(document).height();
        var scrollTop = $(window).scrollTop();
        if (scrollTop + windowsHeight >= size - 20) {
            ScrollToTop.css({'display': 'block'});
            ScrollToBottom.css({'display': 'none'});
        } else {
            ScrollToBottom.css({'display': 'block'});
            ScrollToTop.css({'display': 'none'});
        }
        return {"size": size, "windowsHeight": windowsHeight};
    }
});