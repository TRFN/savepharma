
/* CLASSE PRINCIPAL DE FORMULÁRIOS */

window._forms = ({
    lock: function(){
        $("body").append("<div id='pagebreaker' style='width: 100vw; height: 100vh; top: 0; left: 0; background: transparent; border: 0; outline: 0; color: transparent;position: fixed; z-index: 10000000000000000000000000000000000000000;-webkit-touch-callout: none;-webkit-user-select: none;-khtml-user-select: none;-moz-user-select: none;-ms-user-select: none;user-select: none;outline: 0;'>&nbsp;</div>");
    },
    unlock: function(){
        $("#pagebreaker").remove();
        $(".salvar, .salvar_e_editar, .salvar_e_adicionar").removeAttr("data-clique");
    },
    set: function(to,from,data){
        for(i in data){
            e = new Object();
            (typeof this[to] !== "undefined" && typeof this[to][i] !== "undefined")
                ? this[to][i](data[i])
                : ((typeof this[to] !== "undefined" && typeof this[to]._extrafields_[i] !== "undefined")
                ? (this[to]._extrafields_[i] = data[i])
                : ((e=$("#pag_"+to+" [name=" + i + "]")).css({"font-family": '"Poppins"!important'}).val(data[i]),$("#pag_"+to+" .static-" + i).css({"font-family": '"Poppins"!important'}).html(data[i])));
            e !== "null" && typeof e.prop !== "undefined" && e.prop("tagName") == "SELECT" && e.selectpicker("refresh");
        }
        (typeof this[to] == "undefined")&&(this[to] = new Object())
    },
    cfg: function(to,data){
        (typeof this[to] == "undefined")&&(this[to] = new Object());
        for(i in data){
            this[to][i] = data[i];
        }
    },
    get: function(by,fields){
        var data = {};
        for(i in _forms[by]._extrafields_){
            data[i] = _forms[by]._extrafields_[i];
        }
        fields.forEach(function(i){
            field = "#form_" + (typeof by == "undefined"?getpage():by) + " [name=" + i + "]";
            typeof $(field).attr("name") !== "undefined" && !$(field).is(":disabled") && (data[$(field).attr("name")] = $(field).val());
        });
        return data;
    },
    submitter: function(to, fields, fn, newpage){
        typeof this[to]=="undefined"
            ?(this[to]=new Object(), this[to]._extrafields_ = new Object())
            :(typeof this[to]._extrafields_ == "undefined"&&(this[to]._extrafields_=new Object()));
        typeof newpage == "undefined" && (newpage=getpage());
        var self = this;

        function getdata(){return self.get(to,fields);}
        function message(a,b){
            return setTimeout(function(){msgbox("",a,b);},400);
        }
        function subir(){
            _forms.lock();
            $("html, body").stop().animate({scrollTop:0}, 400, 'swing').promise().done(_forms.unlock);
        }

        $("#form_" + to + " .salvar").click(function(){
            if($(this).data("clique")==true)return false;
            $(this).attr("data-clique","true");
			console.log("teste");
            subir();
            redir = this.getAttribute("redir")==null?false:this.getAttribute("redir");
            fn(getdata(),message,function(){
                //setTimeout(function(){update(false)},1500);
                return redir?(window.top.location.href=redir):history.back();
            },self.closemsg);
            return false;
        });
        $("#form_" + to + " .salvar_e_editar").click(function(){
            if($(this).data("clique")==true)return false;
            $(this).attr("data-clique","true");
            subir();
            redir = this.getAttribute("redir")==null?false:this.getAttribute("redir");
            fn(getdata(),message,function(){setTimeout(function(){update(false)},1500);return redir&&(window.top.location.href=redir)},self.closemsg);
            return false;
        });
        $("#form_" + to + " .salvar_e_adicionar").click(function(){
            if($(this).data("clique")==true)return false;
            $(this).attr("data-clique","true");
            subir();
            redir = this.getAttribute("redir")==null?false:this.getAttribute("redir");
            fn(getdata(),message,function(){
                if(redir){
                    return (window.top.location.href=redir);
                }
                history.pushState("","","/" + newpage);
                update();
                $(".paginas").fadeOut("fast").promise().done(function(){$(".paginas#pag_" + getpage()).css({width:"100%"}).fadeIn()});
            },self.closemsg);
            return false;
        });
    },
    tableset: function(table,content,rewrite){
        var e = $(table).find("tbody"), template, lines = [];
        typeof rewrite == 'undefined' && (rewrite=0);
        if(e.attr("template")==null){
            /*console.log("Attempt tableset for: " + table);*/
            return setTimeout(function(){
                _forms.tableset(table,content,rewrite);
            },250);
        }
        e.hide();
        try {template = atob(e.attr("template"))}catch(e){/*console.warn(e);*/template=""}
        typeof rewrite == 'undefined' && (rewrite=0);
        !!rewrite&&(e.html(""));
        for( i in content ){
            if(content[i] == false){
                lines[i] = "<tr class='false0' style='display: none;'><td>" + $('<div>').append($(template)
                                            .find('[href^="/editar_"]').attr("onclick","history.back()").clone()
                                            ).html().toLowerCase().split(/[^a-z <>=/_().:;"]/).join('_')+"</td></tr>";
                console.log(lines[i]);
            } else {
                lines[i] = template;
                for( j in content[i] ){
                    lines[i] = lines[i].split("%" + j + "%").join(typeof (ctn=content[i][j]) == "object"&&(typeof ctn.length!=="undefined"||ctn!==null)?JSON.stringify(ctn).split('"').join("'"):String(ctn));
                }
            }
        }
        e.append(lines.join(""));
        e.find("td[data-translate]").each(function(){
            var data = $(this).data("translate"), dado = $(this).text(), e;
            switch(data.metodo){
                case "vetor":
                    dado = parseInt(dado);
                    $(this).text(data.origem[dado]);
                break;
                case "tabela":
                    dado = String(dado);
                    $(this).text((e=$(data.origem + dado)).length == 0 ? "Não especificado":e.find("td").eq(data.dado).text());
                break;
                case "chave":
                    dado = String(dado);
                    $botao = $('<label><input type="checkbox" checked="checked" name=""></label>');
                    $botao.find("input").bootstrapSwitch();
					$botao.find("input").bootstrapSwitch("state",dado=="sim");
                    $botao.find("input").on('switchChange.bootstrapSwitch', function(){
                        var estado = $(this).bootstrapSwitch("state")?"sim":"nao",
                            data = $(this).closest("td").data("translate"),
                            chave = data.origem,
                            id = data.id;

                        $.post(location.href, {"estado": chave, "id": parseInt(id), "valor": estado},function(data){
							console.log(data);
						});
                    });
                    $(this).html("");
                    $botao.appendTo(this);
                break;
            }
        });
        return e.show();
    }
});

/* EXTRATOR DE DADOS */

function extract_table(onde,dado,recuperar){
    var resultado = [];
    $("#pag_"+onde+" tbody tr:not(.false0)").each(function(){
        if(typeof $(this).attr("class")=="undefined")return;

        var id = $(this).attr("class").split(/[^0-9]/).join(''),
            nome = $(this).find("td").eq(typeof dado !== "number"?0:dado).text();

        resultado.push({"nome": nome, "id": id});

        (typeof recuperar=="function") && (resultado[resultado.length-1].extra=recuperar(this));
    });
    return resultado;
}

/* CONFIGURAÇÕES GERAIS */

Dropzone.autoDiscover = false;

/* DROPZONE */

function limpar_dropzone(para){
    Dropzone.forElement(para).emit("resetFiles");
    window.tempImageArray = {_quantidade_:0};
}

function adicionar_dropzone(para,imagemarray){
    if(typeof imagemarray == "string"){
        imagemarray = [imagemarray];
    }
    var myDropzone=Dropzone.forElement(para), j, imagem = "";
    for( j in imagemarray ){
        imagem = imagemarray[j];
        var file = {
                name: "img-" + (myDropzone.files.length).toString(4),
                size: 0,
                status: Dropzone.SUCCESS,
                accepted: true
            };

        if(myDropzone.files.length < myDropzone.options.maxFiles){
            myDropzone.emit("addedfile", file);
            myDropzone.emit("thumbnail", file, (imgsrc="/uploads/" + myDropzone.options.url.split("/imageupload/")[1].split("/")[0] + "/" + imagem));
            myDropzone.emit("complete", file);
            myDropzone.files.push(file);
            myDropzone.emit("success", file, imagem);

            $(myDropzone.element).find(".dz-preview:last img").css({width: "100%", height: "100%", "-webkit-filter": String("none"), filter: String("none"), cursor: "pointer"}).attr("alt","Expandir imagem").click(function(){
                window.open(this.src,"_blank","width=800 height=600 scrollbars=no");
            });
            $(myDropzone.element).find(".dz-size,.dz-filename").css({display: String("none")});
        }
    }
}

/*! FUNÇÕES JSPHP <?php
    function minjs($code){
        return preg_replace(array("/\s+\n/", "/\n\s+/", "/ +/"), array("", "", " "), $code);
        //return $code;
    }

    function form_ext($link,$work,$enable_preset=true){
        $defaults = $work["defaults"];
        unset($work["defaults"]);
        $result = "";
        $preset = "";
        $i = 0;
        foreach($work as $param=>$fn){
            $default = $defaults[$i];
            $i++;

            if(is_array($fn)){
                $enable_preset = !!$fn[0];
                $fn = $fn[1];
            }

            if($enable_preset){
                $preset .= "
                    _forms.adicionar_{$link}._extrafields_.{$param} = {$default};
                    _forms.editar_{$link}._extrafields_.{$param} = {$default};
                ";
            }

            $result .= preg_replace("/(\%field\%)/",$param,(preg_replace("/(\%ctx\%)/","editar_{$link}",$fn) . preg_replace("/(\%ctx\%)/","adicionar_{$link}",$fn)));
        }

        return minjs("
            \$(function(){
                {$preset}

                setInterval(function(){
                    if(getpage()=='editar_{$link}' || getpage()=='adicionar_{$link}'){
                        {$result}
                    }
                }, 500);
            });
        ");
    }

    function imageUpload($id,$folder,$width=640,$height=480,$limite){
        return minjs('
            if(typeof tempImageArray == "undefined"){
                tempImageArray = {_quantidade_: 0}
            }

            $("#dze-'.$id.', #dza-'.$id.'").dropzone({
                url: "/imageupload/'.$folder.'/'.$width.'/'.$height.'/",
				maxFilesize: 500,
                uploadMultiple: false,
                parallelUploads: 1,
                maxFiles: '.$limite.',
                acceptedFiles: "image/*",
                addRemoveLinks: true,
                paramName: "fileToUpload",
                dictCancelUploadConfirmation: "Deseja mesmo cancelar o Upload atual ?!",
                dictRemoveFile: "<i class=\\"fa fa-1x fa-trash float-left\\" style=\\"margin-top: 8px;\\"></i>&nbsp;Deletar</a>",
                dictCancelUpload: "Cancelar",
                success: function(file, response) {
                    tempImageArray[file.name] = response;
                    tempImageArray._quantidade_ > this.files.length ? (tempImageArray._quantidade_=this.files.length):(tempImageArray._quantidade_++);
                    (tempImageArray._quantidade_ > '.($limite-1).') && this.disable();
                    file.previewElement.classList.add("dz-success");
                    $(".dz-remove").addClass("btn btn-dark").css({marginTop: "16px"});
                },
                error: function (file, response) {
                    this.removeFile(file);
                },
                init: function(){
                    this.on("removedfile", function(file){
                        if(typeof window.tempImageArray[file.name] !== undefined ){
                            $.post("/imageupload/'.$folder.'/",{ "delete": window.tempImageArray[file.name] });
                            delete window.tempImageArray[file.name];
                        }
                        tempImageArray._quantidade_--;
                        tempImageArray._quantidade_ < '.($limite).' && this.enable();
                    });

                    this.on("maxfilesexceeded", function(file){
                        tempImageArray._quantidade_ = '.($limite).';
                        this.removeFile(file);
                    });

                    this.on("resetFiles", function() {
                        if(this.files.length != 0){
                            for(i=0; i<this.files.length; i++){
                                this.files[i].previewElement.remove();
                            }
                            this.files.length = 0;
                        }
                    });
                }
            }).addClass("dropzone");
        ');
    }

    function form_base($ord, $f_add, $f_edit, $campos, $conds, $posted, $upid, $tabela, $sucessomensagem, $edicaomensagem){
        return minjs('
            _forms.submitter(
                "' . $f_add . '",
                ' . json_encode($campos) . ',
                function(data,message,done,close_message){
                    msgbox("", "Salvando alterações...", "");
					var acao="add";
                    ' . $conds . '

                    $.post(location.href, data, function(retorno){
						console.log(retorno);
                        if(retorno=="ok") {
                            msgbox("Pronto!", "'  . ($sucessomensagem) .  '", "success");

                            $("#form_' . $f_add . ' input:not([type=checkbox],[type=radio])").val("").prop("readonly",true);
                            $("#form_' . $f_add . ' select[name=order] option").removeAttr("selected").parent().append("<option selected>" + String(parseInt($("#form_' . $f_add . ' select option").length) + 1) + "</option>").selectpicker("refresh");

                            update(false);
                            setTimeout(close_message, 2e3);
                            setTimeout(done, 4e3);
                        } else {
                            ' . $posted[0] . '
                            setTimeout(close_message, 1e3);
                        }
                    });
                }
            );

            _forms.cfg("' . $f_edit . '",{id: function(data){
                _forms.' . $f_edit . '._extrafields_.id = data;
            }});

            _forms.submitter(
                "' . $f_edit . '",
                ' . json_encode($campos) . ',
                function(data,message,done,close_message){
                    msgbox("", "Salvando alterações...", "");

					var acao="edit";

                    ' . $conds . '

                    $.post(location.href, data, function(retorno){
                        if(retorno=="ok") {
                            msgbox("Pronto!", "'  . ($edicaomensagem) .  '", "success");
							$("#form_' . $f_edit . ' input:not([type=checkbox],[type=radio])").val("").prop("readonly",true);
                            update(false);
                            setTimeout(close_message, 2e3);
                            setTimeout(done, 4e3);
                        } else {
                            ' . $posted[1] . '
                            setTimeout(close_message, 1e3);
                        }
                    })
                },
                "' . $f_add . '"
            );

            window.updates.push(function($data){
                data = [];

                for(i in $data['.$upid.']){
                    if(!$data['.$upid.'][i].__apagado__){
                        var newobj = new Object();

                        for( j in $data['.$upid.'][i] ){
                            newobj[j] = $data['.$upid.'][i][j];
                        }

                        newobj.id = String(i);

                        data.push(newobj);
                    } else {
                        data.push(false);
                    }
                }

                _forms.tableset("#pag_' . $tabela . ' table", data, true);

                ' . ($ord?'$("#pag_' . $f_add . ' select[name=ordem]").html((function(t,a,b,s){
                    let html = "";
                    for( var c = 1, i = 0; i < t.length; i++){
                        !!t[i]&&(html += a + String(c) + b, c+=1);
                    }
                    return html + s + String(c) + b;
                })(data,"<option>","</option>","<option selected>")).selectpicker("refresh");

                ':'') . '

				setTimeout(function(){
					obrigouParametro == 0 && obrigarParametro("id","'.$f_edit.'","'.$tabela.'");
					window.obrigouParametro = 10;
				}, 200);
            });

        ');
    }

    $formularios = array();
    $switch_ext = '_forms.%ctx%._extrafields_.%field% = $("#form_%ctx% .switch_%field%").bootstrapSwitch("state")?"sim":"nao";';

    function textarea($i=0){return "_forms.%ctx%._extrafields_.%field% = $('#form_%ctx% .summernote:eq({$i})').summernote('code');";}

    function tabela2select($tabela,$tag){
        return array(false, "setInterval(function(){
        typeof \$ !== 'undefined' && !!(c=(function(el){
            var selecionado = \$(el).val(),
                dados = extract_table('{$tabela}'),
                selecionados = [],
                primeiro = false;

            (typeof tabelas2selects == 'undefined')&&(window.tabelas2selects = {});
            (typeof tabelas2selects[el] == 'undefined')&&(window.tabelas2selects[el] = '',primeiro=true);

            if(tabelas2selects[el] == JSON.stringify(dados)){
                return 0;
            } else {
                tabelas2selects[el] = JSON.stringify(dados);
                el = \$(el).html('');
            }

            for(i in dados){
                el.append('<option ' + (((typeof selecionado=='object' && selecionado!==null && selecionado.indexOf(String(dados[i].id))!==-1) || (String(dados[i].id) == String(selecionado))) ? 'selected ' : '') + 'value=\"' + dados[i].id + '\">' + dados[i].nome + '</option>');
            }

            return primeiro?1:2;
        })(ele='#pag_'+getpage()+' #form_%ctx% select[name=\"%field%\"]'))&&(((!(e=\$(ele)).is('[multiple]'))?e.selectpicker('refresh'):(setTimeout(msfn=function(){(e=\$('#pag_'+getpage()).find('#form_%ctx% select[name=\"%field%\"]')).selectpicker('destroy'); e.select2();},300),setTimeout(msfn,600),setTimeout(msfn,900),setTimeout(msfn,1200),setTimeout(msfn,2000),setTimeout(msfn,1500),e.click(msfn),e.selectpicker('destroy'))),(c==2&&setTimeout(function(){\$([document.documentElement, document.body]).animate({scrollTop: \$('#form_%ctx% select[name=\"%field%\"]').offset().top-240}, 600);},300)));
        }, 1000);
        ");
    }
?>
*/


/* CONF USUARIOS */

_forms.cfg("editar_perfil", {
	ativo: function(data){
		$("#form_editar_perfil .switch_ativo").bootstrapSwitch('state',data=="sim");
		_forms.editar_perfil._extrafields_.ativo = data;
	}
});

/*! USUARIOS <?php
    $formularios[] = (
        form_base(
            false,
            "adicionar_perfil",
            "editar_perfil",
            array(
                "nome",
                "email",
                "tipo",
                "ativo",
                "pontos",
                "vinculo",
                "senha",
                "confsenha",
                "crf"
            ),
            "
                var errotam = (function(t,r,f,n){
                    if(data[t].length < r){
                        message(n, f);
                        \$([document.documentElement, document.body]).animate({
                            scrollTop: (error_el = \$('#pag_' + getpage() + ' [name=' + t + ']:first')).offset().top-240
                        });
                        error_el.focus().click();
                        return true;
                    }
                    return false;
                });

                if(data['tipo']==null){
                    msgbox('Ops...','Selecione um tipo de conta','error');
                    return setTimeout(close_message, 1e3);
                }

                if(!/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/.test(data['email'])){
					msgbox('Ops...','" . ('Email inválido') . "','error');
					return setTimeout(close_message, 1e3);
				}

				error=false;

				if(data['senha'].length==0){
					if(acao=='add'){
						error=true;
						msgbox('Ops...','" . ('A senha e obrigatória') . "','error');
					}

					if(acao=='edit'){
						delete data['senha'];
						delete data['confsenha'];
					}
				} else {
					data['senha']!=data['confsenha'] && (error=true,msgbox('Ops...','" . ('As senhas não conferem.') . "','error'));
					data['senha'].length < 5 && (error=true,msgbox('Ops...','" . ('Senha muito curta. Insira uma senha válida') . "', 'error'));
				}

				if(error){
					return setTimeout(close_message, 1e3);
				}
            ",
			array("
				if(retorno=='email-nao-existe'){
					msgbox('Ops...','Desculpe, mas o email inserido é inexistente.', 'error');
				} else {
                msgbox('Ops...','Ocorreu algum erro interno.','error');
				}
			","msgbox('Ops...',retorno, 'error');
            console.log(retorno);"),
            "0",
            "usuarios",
            "Usuario adicionado com sucesso!",
            "Pronto, o usuario foi modificado!"
        ) .

        form_ext("perfil", array(
            "defaults" => array("'nao'"),
            "ativo" => $switch_ext,
            "vinculo" => tabela2select("usuarios","nome")
        ))
    );
?> */ // Script Usuários

/* CONF REGRAS PONTOS */

_forms.cfg("editar_regra", {
	ativo: function(data){
		$("#form_editar_regra .switch_ativo").bootstrapSwitch('state',data=="sim");
		_forms.editar_regra._extrafields_.ativo = data;
	}
});

/*! REGRAS <?php
    $formularios[] = (
        form_base(
            false,
            "adicionar_regra",
            "editar_regra",
            array(
                "titulo",
                "meses",
                "dias",
                "prazo",
                "pontos1",
                "pontos2",
                "reacao1",
                "reacao2"
            ),
            "
				var opt = ({
					texto: 'Entendi',
					acao: ['subir'],
					cfg: {
						subir: acao=='add'?'#form_adicionar_regra ':'#form_editar_regra '
					}
				});

				if(data['titulo'].length < 1){
					opt.cfg.subir += '[name=titulo]';
                    msgbox('Ops...','Determine um titulo para a regra','error',opt);
					return;
                }

				if(parseInt(data['pontos1'])<1||data['pontos1']==''){
					opt.cfg.subir += '[name=pontos1]';
                    msgbox('Ops...','E obrigatorio especificar a quantidade de pontos','error',opt);
                    return;
                }

				if(parseInt(data['pontos2'])<1||data['pontos2']==''){
					opt.cfg.subir += '[name=pontos2]';
                    msgbox('Ops...','E obrigatorio especificar a quantidade de pontos','error',opt);
                    return;
                }
            ",
			array("",""),
            "1",
            "gestaopontos",
            "Regra adicionada com sucesso!",
            "Pronto, a regra foi modificada!"
        ) .

        form_ext("regra", array(
            "defaults" => array("'nao'"),
            "ativo" => $switch_ext
        ))
    );
?> */

/* CONF MEDICAMENTOS */

_forms.cfg("editar_medicamento", {
	ativo: function(data){
		$("#form_editar_medicamento .switch_ativo").bootstrapSwitch('state',data=="sim");
		_forms.editar_medicamento._extrafields_.ativo = data;
	}
});

/*! MEDICAMENTOS <?php
    $formularios[] = (
        form_base(
            false,
            "adicionar_medicamento",
            "editar_medicamento",
            array(
                "nome",
                "marca",
                "validade",
                "prazo"
            ),
            "
                var errotam = (function(t,r,f,n){
                    if(data[t].length < r){
                        message(n, f);
                        \$([document.documentElement, document.body]).animate({
                            scrollTop: (error_el = \$('#pag_' + getpage() + ' [name=' + t + ']:first')).offset().top-240
                        });
                        error_el.focus().click();
                        return true;
                    }
                    return false;
                });

				if(tempImageArray._quantidade_ > 0){

                    data.fotos.qtd += window.tempImageArray._quantidade_;
                    delete window.tempImageArray._quantidade_;

                    for( i in tempImageArray ){
                        let img = tempImageArray[i];
                        data.fotos.files.push(img);
                        delete window.tempImageArray[i];
                    }

                } else {
                    if(data.fotos.qtd < 1){
                        message('A nota fiscal e obrigatória', 'error');
                        setTimeout(function(){
                            \$([document.documentElement, document.body]).animate({
                                scrollTop: (error_el = \$('#pag_' + getpage() + ' .dropzone:first')).offset().top-240
                            });
                        }, 200);
                        return setTimeout(close_message, 4e3);
                    }
                }

                window.tempImageArray = {_quantidade_:0};

            ",
			array("",""),
            "2",
            "medicamentos",
            "Medicamento adicionado com sucesso!",
            "Pronto, a medicamento foi modificado!"
        ) .

        form_ext("medicamento", array(
            "defaults" => array("'sim'","({ qtd: 0, files: [] })"),
            "ativo" => $switch_ext,
			"fotos" => ""
        )) .

		imageUpload(
            "medicamento",
            "medicamentos",
            1024,
            768,
            1
        )
    );
?> */

function renderizar_formularios(){
	var f = JSON.parse(atob('<?=base64_encode(json_encode($formularios));?>'));
	for( u in f ){
		eval(f[u]);
	}

	$('input:not([type=checkbox],[type=radio])').prop('readonly',true).focus(function(){
        if (this.hasAttribute('readonly')) {
            this.removeAttribute('readonly');
            this.blur();
            this.focus();
        }
    });

    $("input[name=pontos1],input[name=pontos2]").inputmask('decimal', {
                'alias': 'numeric',
                'groupSeparator': '.',
                'autoGroup': true,
                'digits': 0,
                'radixPoint': ",",
                'digitsOptional': true,
                'allowMinus': false,
                'prefix': '',
                'placeholder': ''
    });
}
