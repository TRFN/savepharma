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

            history.pushState("","",this.getAttribute("href"));

			update();

			setTimeout(function(){
				update(false);
			},1000);

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

setTimeout(function(){

	/* Atualizar relatorios */

	updates.push(function(data){
		var sis_pontos = data[3],
			transacoes = sis_pontos.transacoes,
			i, transacao, myid, user, tabela = [], op,
			pegou = 0, emprestou = 0, perdeu = 0, ganhou = 0;

		for( i in data[0] ){
			user = data[0][i];
			(!user.__apagado__ && user.sessaoatual=="sim") && (myid = user.id);
		}

		for( i in transacoes ){
			transacao = transacoes[i];
			medicacao = idsremedios[parseInt(transacao[0])][parseInt(transacao[1])];
			parseInt(transacao[0]) == myid
				? (emprestou++,ganhou += parseInt(transacao[4]))
				: (pegou++,perdeu += -parseInt(transacao[3]));
			if(perdeu <= 0){
				ganhou -= perdeu;
				perdeu = 0;
			}
			tabela.push({
				origem: parseInt(transacao[0]) == myid ? parseInt(transacao[2]):parseInt(transacao[0]),
				medicamento: parseInt(medicacao),
				valor: parseInt(transacao[0]) == myid ? Math.abs(transacao[4]):Math.abs(transacao[3]),
				status: (op=!transacao[5] ? (parseInt(transacao[0]) == myid ? 0:1):(transacao[5])),
				button: (function(btn){
					switch(parseInt(btn)){
						case 0:  return "primary"; break;
						case 1:  return "success"; break;
						case 2:  return "warning"; break;
						case 3:  return "danger";  break;
						default: return "info";    break;
					}
				})(op),
				cor: (op=(parseInt(transacao[parseInt(transacao[0])==myid?4:3])>0)) ? "#11a222":"#f72211",
				dir: op ? "plus":"minus",
				textostatus: parseInt(transacao[0])==myid ? "Emprestou":"Pegou emprestado",
				est1: parseInt(transacao[0]),
				est2: parseInt(transacao[2])
			});
		}
		tabela.reverse();
		_forms.tableset("#pag_relatorios",tabela,true);

		$("#pegou").html(pegou);
		$("#emprestou").html(emprestou);
		$("#perdeu").html(perdeu);
		$("#ganhou").html(ganhou);
	});

	/* Atualizar Label do nome */

	updates.push(function(data){
		$("label.titulonome").html(
			(e=(_forms.get('editar_perfil', ["tipo"]).tipo == "1" && getpage()=="editar_perfil"))
					? "Estabelecimento"
					: "Nome"
		);

		$("#definir24h")[e?"show":"hide"]();
		$("#crfdata")[_forms.get('editar_perfil', ["tipo"]).tipo == "2"?"show":"hide"]();

		var nf = 0;
		for(i in data[0]){
			if(!data[0][i].__apagado__){
				nf++;
				if(data[0][i].sessaoatual == "sim" && data[0][i].tipo=="1"){
					e = true;
				}
			}
		}

		e&&($("#botao-novo-perfil")[(nf=nf<5)?"show":"hide"](),$("#botao-np-desativado")[nf?"hide":"show"]());
	});
	update(false);
},1000);

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
			case 2:  msgbox("","Este produto pertence ao seu próprio estabelecimento, portanto, você não poderá pega-lo emprestado.","info"); break;
			case 3:  msgbox("","Desculpe, mas este produto foi desativado pelo estabelecimento...","warning"); break;
			default: msgbox("","Ocorreu algum erro interno. Tente novamente...","warning"); break;
		}
	});
});
