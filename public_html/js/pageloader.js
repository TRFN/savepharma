window.click_meus_dados = false;

window.go = (function(to){
	$(".paginas").hide();
    $(".paginas#pag_" + to).css({width:"100%"}).show().addClass("ativo");
	history.pushState('','',to);
	update(false);
});

window.dinamizarFn = (function(){
    $("a.dinamizar:not(.dinamizado)").each(function(){
        $(this).addClass("dinamizado");
        $(this).click(function(e){

            update();

            history.pushState("","",this.getAttribute("href"));

			$("header button.toggle.active").click();

            $(".paginas").fadeOut("fast").promise().done(function(){$(".paginas#pag_" + getpage()).css({width:"100%"}).fadeIn();});

			return false;
        });
    });
});

window.getpage = (function(){
    var _page = (top.location.href.split("/")[3]);
    try{(paginasDisponiveis.indexOf(_page)==-1||_page=="login")&&(_page="home")}catch(e){_page="home"}
    return _page;
});

window.updates = [];

window.update = (function(lds){
    (typeof lds == "undefined" || lds) && loading_effect();
    $.post("/conectado", function(data){
        return(typeof data[0]!=="undefined" && data[0]["ativo"] == "desconectado")
            ? (window.top.location.href="/login")
            : (window.sessionData = data, (function($data){
                for( z in updates ){
                    updates[z]($data);
                }
				(getpage()=="home"&&$.post(location.href,{ajax:"vitrine"},function(data){$("#vitrine").replaceWith($(data));}));
				dinamizarFn();
        })(data));
    });
});

window.irPara = (function(pagina,count,reload,param){
	typeof param === "undefined" && (param=false);
    typeof count=="undefined"&&!!(count=0);
	typeof reload=="undefined"&&!!(reload=false);
	return (e = $((!!param&&!!reload?"table tbody td ":'') + '[href^="/' + pagina + (!!param?"/"+param+":":'') + '"]').eq(count)).length>0
		? (
			(
				!!reload
					? (eval(c=e.attr(reload))/*,console.log(c)*/,c)
					: (e.click(),/*console.log(e),*/e)
			 )
		):false;

});

window.loading_effect = function(){
    $('#preloader').fadeIn().promise().done(function(){
        setTimeout(function(){
            $('#preloader').fadeOut('slow',function(){$(this).hide();});
        });
    });
}

window.obrigouParametro = 0;

setInterval(function(){
	obrigouParametro > 0 && obrigouParametro--;
},150);

window.obrigarParametro = (function(parametro,pagina,redirecionamento){
    if(getpage() === pagina){
        return typeof (param=parametro,parametro=location.href.split("/" + parametro + ":")[1]) !== "undefined"
            ? (!irPara(pagina,parseInt(parametro),"onclick",param) && irPara(redirecionamento))
            : irPara(redirecionamento);
    }
    return false;
});

window.addEventListener("popstate", function(){
	$([document.documentElement, document.body]).animate({scrollTop: 0}, 600);
	irPara(getpage());
});

window.loadPlugins = function(ctx){
	typeof ctx=="undefined"&&(ctx=$("section.paginas"));
	ctx.find("[data-switch=true]:not(.changed)").addClass("changed").bootstrapSwitch();
	ctx.find(".selectpicker:not(.changed)").addClass("changed").selectpicker();
	ctx.find(".bootstrap-select button.btn.btn-default").css({marginBottom: "0px",fontFamily: "'Open Sans',sans-serif"}).removeClass("btn-default").addClass("btn-primary");
	setTimeout(loadPlugins, 600);
}

window.updateTables = function(){
    $("table template").each(function(){
        $(this).parent().attr("template", btoa($(this).html()));
        $(this).remove();
    });
	update(false);
};

$(function(){
    window.paginasCarregadas = 0;
    for( i in paginasDisponiveis ){
        pagina = paginasDisponiveis[i];
        pagina!==getpage()&&pagina!=="login"?$.post("/" + pagina,{ajax:true},function(data){
			data = $(data);
			data.hide().insertAfter("section.contact.space:last");
			paginasCarregadas++
			if(paginasCarregadas==paginasDisponiveis.length){
				updateTables();
				renderizar_formularios();
				loadPlugins();
				update(false);
				setTimeout(function(){
					$('#preloader').fadeOut('slow',function(){$(this).hide();});
				}, 1000);
			}
		}):(paginasCarregadas++);
    }
    update();
});

setInterval(function(){
	document.title = (function(p){
		switch(p){
			case "login": return "Login"; break;
			case "home": return "Painel"; break;
			case "editar_perfil": return "Editar Perfil"; break;
			case "adicionar_perfil": return "Adicionar Perfil"; break;
			case "usuarios": return "Usuários"; break;
		}
		return "Painel";
	}(getpage())) + " - SavePharma"
},1000);

window.processar_transacao = (function(a){
	$.post(top.location.href,{transacao: a}, function(data){
		console.log(data);
		switch(parseInt(data)){
			case 0:  msgbox("","Transação realizada com sucesso!"); break;
			case 1:  msgbox("","Transação não realizada por falta de saldo!","error"); break;
			case 2:  msgbox("","Este medicamento pertence ao seu próprio estabelecimento, portanto, você não poderá pega-lo emprestado.","info"); break;
			case 3:  msgbox("","Desculpe, mas este medicamento foi desativado pelo estabelecimento...","warning"); break;
			default: msgbox("","Ocorreu algum erro interno. Tente novamente...","warning"); break;
		}
	});
});
