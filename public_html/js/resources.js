function setTopo(){
    $(window).scrollLeft(0);
}

function redrawfn(){
    $("#pag_home .info-box .icon").each(function(){
        $(this).css({
            height: String($(this).width()) + "px",
            "line-height": String($(this).width()) + "px",
            "margin-top": String(~~($(this).width()*.6)) + "px",
            "font-size": String(~~($(this).width()*.4)) + "px"
        });
    });

    $("button.toggle").each(function(){
        if($(this).hasClass("active") && !$(this).hasClass("changed")){
            $(this).addClass("changed").css({"color": "#333"}).find('i').removeClass("fa-bars").addClass("fa-times");
        }
        if(!$(this).hasClass("active") && $(this).hasClass("changed")){
            $(this).removeClass("changed").css({"color": "#1178f7"}).find('i').removeClass("fa-times").addClass("fa-bars");
        }
    });
}

$(function(){
    $(window).bind('scroll', setTopo);
	setInterval(redrawfn, 600);
});
