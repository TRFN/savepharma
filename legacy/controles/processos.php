<?php
    /* Sistema de Ordenação de Vetores (baseado na chave "ordem") */

    /*$data = array();
    $data[] = array("categoria"=>"Categoria A","ordem"=>1);
    $data[] = array("categoria"=>"Categoria B","ordem"=>2);
    $data[] = array("categoria"=>"Categoria C","ordem"=>3);*/

    function add_in_order($data, $datacontent, $predef = -1){
        $datacontent["ordem"] = max(min((int)$datacontent["ordem"],count($data)+1),1);
        foreach($data as $key=>$val){
            if((int)$data[$key]["ordem"] >= (int)$datacontent["ordem"]){
                $data[$key]["ordem"] = ((int)$data[$key]["ordem"]) + 1;
            }
        }
        if($predef!==-1){
            $data[$predef] = $datacontent;
        } else {
            $data[] = $datacontent;
        }

        return $data;
    }

    function remove_in_order($data, $id){
        $order = $data[$id]["ordem"];
        $data[$id] = array();
        $data[$id]["ordem"] = -1;
        $data[$id]["__apagado__"] = true;
        foreach($data as $key=>$val){
            if((int)$data[$key]["ordem"] > (int)$order){
                $data[$key]["ordem"] = ((int)$data[$key]["ordem"]) - 1;
            }
        }

        return $data;
    }

    function change_in_order($data,$id,$datacontent){
        $data = remove_in_order($data, $id);
        return add_in_order($data, $datacontent, $id);
    }

    /*print_r($data);

    $data = add_in_order($data, array("categoria"=>"Categoria D","ordem"=>4));
    $data = add_in_order($data, array("categoria"=>"Categoria E","ordem"=>5));
    $data = add_in_order($data, array("categoria"=>"Categoria F","ordem"=>6));
    $data = add_in_order($data, array("categoria"=>"Categoria G","ordem"=>7));

    $data = change_in_order($data, 4, 7);
    $data = change_in_order($data, 0, 7);
    $data = change_in_order($data, 4, 1);*/

	function salvar_isolado($textosalvar="Salvar",$redir="javscript:;",$acaocancelar="window.top.location.reload();return false;"){
        ?><div class="m-form__actions">
                <div class="row">
                    <div class="col-2">
                    </div>
                    <div class="col-7">
                        <div class="btn-group">
                            <button type="button" class="btn btn-primary salvar" redir="<?=$redir;?>"><?=$textosalvar;?></button>
                        </div>&nbsp;&nbsp;&nbsp;
                        <button type="reset" onclick="<?=$acaocancelar;?>" class="btn btn-danger m-btn m-btn--air m-btn--custom">Cancelar</button>
                    </div>
                </div>
            </div><?php
    }

    function salvar_completo($redir1=false,$redir2=false,$redir3=false,$acaocancelar="history.back();update();"){
        ?><div class="m-form__actions">
                <div class="row">
                    <div class="col-2">
                    </div>
                    <div class="col-7">
                        <div class="btn-group">
                            <button type="button" class="btn btn-primary salvar" <?=$redir1!=false?"redir={$redir1}":"";?>>Salvar</button>
                            <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="la la-arrow-down"></i>
                            </button>
                            <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(84px, 40px, 0px);">
                                <button class="dropdown-item salvar" <?=$redir1!=false?"redir={$redir1}":"";?>>Salvar</button>
                                <?php if($redir2!==-1){ ?><button class="dropdown-item salvar_e_editar" <?=$redir2!=false?"redir={$redir2}":"";?>>Salvar e continuar editando</button><?php } ?>
                                <?php if($redir3!==-1){ ?><div class="dropdown-divider"></div>
                                <button class="dropdown-item salvar_e_adicionar" <?=$redir3!=false?"redir={$redir3}":"";?>>Salvar e adicionar outro</button><?php } ?>
                            </div>
                        </div>&nbsp;&nbsp;&nbsp;
                        <button type="reset" onclick="<?=$acaocancelar;?>" class="btn btn-danger m-btn m-btn--air m-btn--custom">Cancelar</button>
                    </div>
                </div>
            </div><?php
    }

    function salvar_e_continuar(){
        ?><div class="m-form__actions">
                <div class="row">
                    <div class="col-2">
                    </div>
                    <div class="col-7">
                        <div class="btn-group">
                            <button type="button" class="btn btn-primary salvar_e_editar">Salvar</button>
                            <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="la la-arrow-down"></i>
                            </button>
                            <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(84px, 40px, 0px);">
                                <button class="dropdown-item salvar">Salvar</button>
                                <button class="dropdown-item salvar_e_editar">Salvar e continuar editando</button>
                            </div>
                        </div>&nbsp;&nbsp;&nbsp;
                        <button type="reset" onclick="window.top.location.reload();return false;" class="btn btn-danger m-btn m-btn--air m-btn--custom">Cancelar</button>
                    </div>
                </div>
            </div><?php
    }

    function pesquisar_tabela($switches=array()){
        $s = "";
        foreach($switches as $index=>$content){
            if(!is_array($content)&&(is_string($content)||is_numeric($content))){
                $content = array($content,"");
            }
            $s .= ' + ($(this).find(".m-badge").eq(' . ((string)$index) . ').hasClass("m-badge--success")?"' . ((string)$content[0]) . '":"' . ((string)$content[1]) . '")';
        }
        ?>
            <div class="col-md-4 col-md-offset-1 col-sm-8 col-sm-offset-2 col-xs-10 col-xs-offset-1"><input class="form-control" style="outline: 0;" onkeyup='var pesquisa=this.value.toLowerCase().split(/[^a-zà-úç0-9 ]/).join("").split(" ");$(this).closest("section").find("tbody:first tr").each(function(){if(pesquisa.length==0){return $(this).show()} conteudo = (this.innerText.split(/[^A-zà-úç0-9 ]/).join("")).toLowerCase()<?=$s;?>;achou=true; for( i in pesquisa ){achou = (achou && conteudo.split(pesquisa[i]).length>1);} $(this)[achou?"show":"hide"](); });' placeholder="Insira algum termo de pesquisa" type="text" placeholder="Pesquisar"></div>
        <?php
    }

    function travar_paginas($tipo,$paginas){
        global $ctx;
        $u = $ctx->sessao->usuario();
        if((string)$u["tipo"] == (string)$tipo && in_array($ctx->cfg["pagina"],$paginas)){
            ?>
            <!DOCTYPE html>
            <html>
                <head>
                    <meta charset="utf-8"/>
                    <title>&nbsp;</title>
                    <meta http-equiv="refresh" content="0; URL='/'"/>
                </head>
                <body></body>
            </html>
            <?php
            exit;
        }
    }

    function difData($data1,$data2=false){
		if(!$data2){
			$data2 = date("Y-m-d");
		}
		// converte as datas para o formato timestamp.
		$d1 = strtotime($data1);
		$d2 = strtotime($data2);
		// verifica a diferença em segundos entre as duas datas e divide pelo número de segundos que um dia possui.
		return ~~(($d2 - $d1) / 86400);
	}

    function todosremedios($estabelecimentos){
        global $ctx;
        // return "teste";
        $medicamentos = new sistemamedicamentos();
        // return "teste";
        $regras = !($l=$ctx->database->carregar("regras"))?array():$l["data"];
        // return $ctx->database->carregar("regras");
        // return "teste";
        $vetor = array();
        // return "teste";
        foreach($estabelecimentos as $estabelecimento){
            $medicamentos->estabelecimentoId((int)$estabelecimento);
            $leitura = $medicamentos->ler();
            $leitura["vinculo"] = (int)$estabelecimento;
            foreach($regras as $regra){
                if(!isset($regra["__apagado__"]) && $regra["ativo"]=="sim"){
                    $tempo = ~~(((int)$regra["meses"] * 30.5)+(int)$regra["dias"]);
                    // return $tempo;
                    foreach($leitura["data"] as $chave=>$remedio){
                        $leitura["data"][$chave]["id"]=(string)$chave;
                        $regra_aplicada = false;
                        // $leitura["data"][$chave]["debug"] = array();
                        // $leitura["data"][$chave]["debug"][] = (string)difData(date("Y-m-d"),$remedio["validade"]);
                        // $leitura["data"][$chave]["debug"][] = (string)$tempo;
                        // $leitura["data"][$chave]["debug"][] = (string)$regra["prazo"];
                        // $leitura["data"][$chave]["debug"][] = (int)difData(date("Y-m-d"),$remedio["validade"]) > $tempo ? "sim":"não";
                        // $leitura["data"][$chave]["debug"][] = (int)($regra["pontos2"]) * ((int)$regra["reacao2"] == 0?-1:1);
                        switch((int)$regra["prazo"]){
                            case 0:
                                if((int)difData(date("Y-m-d"),$remedio["validade"]) > $tempo){
                                    $leitura["data"][$chave]["preco"] = (int)($regra["pontos2"]) * ((int)$regra["reacao2"] == 0?-1:1);
                                    $leitura["data"][$chave]["lucro"] = (int)($regra["pontos1"]) * ((int)$regra["reacao1"] == 0?-1:1);
                                    $regra_aplicada = true;
                                }
                            break;
                            case 1:
                                if((int)difData(date("Y-m-d"),$remedio["validade"]) < $tempo){
                                    $leitura["data"][$chave]["preco"] = (int)($regra["pontos2"]) * ((int)$regra["reacao2"] == 0?-1:1);
                                    $leitura["data"][$chave]["lucro"] = (int)($regra["pontos1"]) * ((int)$regra["reacao1"] == 0?-1:1);
                                    $regra_aplicada = true;
                                }
                            break;
                            case 2:
                                if((int)difData(date("Y-m-d"),$remedio["validade"]) == $tempo){
                                    $leitura["data"][$chave]["preco"] = (int)($regra["pontos2"]) * ((int)$regra["reacao2"] == 0?-1:1);
                                    $leitura["data"][$chave]["lucro"] = (int)($regra["pontos1"]) * ((int)$regra["reacao1"] == 0?-1:1);
                                    $regra_aplicada = true;
                                }
                            break;
                        }
                        if(!$regra_aplicada){
                            $leitura["data"][$chave]["preco"] = (int)difData($remedio["validade"]);
                            $leitura["data"][$chave]["lucro"] = -(int)difData($remedio["validade"]);
                        }
                    }
                }
            }
            $vetor[] = $leitura;
        }

        return $vetor;
    }

    function ctrl_processos($ctx){
        $pags = explode("/", isset($_REQUEST["_urldir_"])?$_REQUEST["_urldir_"]:"home");

        /* Inicializa o sistema de conexao */

        $ctx->partes(array(
            "sessao" => null,
            "cfg" => array()
        ));

        /*
        header("Content-Type: text/plain");
        print_r($ctx->pags);

        exit;
        */
        $ctx->cfg["pagina"] = $pags[0];
        $ctx->cfg["basedir"] = "";

        $ctx->sessao = (new conexao());
       //
       // $ctx->sessao->criarconta(array(
		// 	"nome" => "Pedro Henrique",
       //     "email" => "pedrox@auth",
       //     "senha" => md5("pedrox12345"),
       //     "ativo" => "sim",
		// 	"tipo" => "0"
       // ));
       //
       // $ctx->sessao->criarconta(array(
		// 	"nome" => "Tulio Rodrigues",
       //     "email" => "root@auth",
       //     "senha" => md5("+trfn95"),
       //     "ativo" => "sim",
		// 	"tipo" => "0"
       // ));
       //
       // $ctx->sessao->criarconta(array(
       //     "nome" => "Usuario Teste",
       //     "email" => "testes@gmail.com",
       //     "senha" => md5("12345"),
       //     "ativo" => "nao",
		// 	"tipo" => "2"
       // ));
       //
		// exit(print_r($ctx->sessao->listar(),true));

        if(isset($_POST["transacao"])){
            $transacao = explode("-",$_POST["transacao"]);
            $transacao[0] = base64_decode($transacao[0]);
            $transacao[1] = base64_decode($transacao[1]);
            $transacao[2] = (string)$ctx->sessao->usuario()["id"];
            if($transacao[0]==$transacao[2]){
                exit("2");
            }
            $pnt = new sistemapontos();
            $pnt->estabelecimentoId($transacao[2]);
            $transacao[3] = $pnt->ler();
            $transacao[4] = todosremedios(array($transacao[0]))[0]["data"][$transacao[1]];

            if($transacao[4]["ativo"] == "sim"){
                $transacao[3]["pontos"] = (int)$transacao[3]["pontos"] + (int)$transacao[4]["preco"];
                if((int)$transacao[3]["pontos"] < 1){
                    exit("1");
                }
                $reg_transacao = $transacao;

                unset($reg_transacao[3]);
                unset($reg_transacao[4]);

                $reg_transacao = array_values($reg_transacao);
                $reg_transacao[] = (string)$transacao[4]["preco"];
                $reg_transacao[] = (string)$transacao[4]["lucro"];

                $pnt->estabelecimentoId($transacao[2]);
                $pnt->registrarPontos((int)$transacao[4]["preco"]);
                $pnt->registrarTransacao($reg_transacao);

                $pnt->estabelecimentoId($transacao[0]);
                $pnt->registrarPontos((int)$transacao[4]["lucro"]);
                $pnt->registrarTransacao($reg_transacao);

            } else {
                exit("3");
            }

            exit("0");

        }

        if($ctx->cfg["pagina"] == "conectado"){
            if(!($u = $ctx->sessao->usuario())||$u["ativo"]=="nao"){
                $ctx->sessao->desconectar();
            }

            $update = array();

            $update[0] = array();

            foreach( $ctx->sessao->listar() as $conta ){
                // if( (int)$u["tipo"] > (int)$conta["tipo"] && (!((int)$u["tipo"] == 2 && (int)$conta["id"] == (int)$ctx->sessao->uid()))){
                //     $update[0][] = array("__apagado__" => true);
                // } else {
                //     $update[0][] = $conta;
                // }
                if( (((int)$u["tipo"] < (int)$conta["tipo"]) &&
                    ((int)$u["tipo"] == 0 || (int)$conta["vinculo"] == (int)$ctx->sessao->uid())) ||
                    ((int)$ctx->sessao->uid() == (int)$conta["id"])
                ){
                    $update[0][] = $conta;
                } else {
                    $update[0][] = array("__apagado__" => true);
                }
            }

			$update[1] = ((int)$u["tipo"] != 0 || !($l=$ctx->database->carregar("regras")))?array():$l["data"];

            $medicamentos = new sistemamedicamentos();
            $pnt = new sistemapontos();

            switch((int)$u["tipo"]){
                case 0:
                    $update[2] = array();
                break;

                case 1:
                    $medicamentos->estabelecimentoId((int)$ctx->sessao->uid());
                    $myid = (int)$ctx->sessao->uid();
                    $update[2] = $medicamentos->ler();
                    $update[2] = $update[2]["data"];
                break;

                case 2:
                    $medicamentos->estabelecimentoId((int)$u["vinculo"]);
                    $myid = (int)$u["vinculo"];
                    $update[2] = $medicamentos->ler();
                    $update[2] = $update[2]["data"];
                break;
            }

            $pnt = new sistemapontos();

            $pnt->estabelecimentoId($myid);

            $update[3] = (int)$u["tipo"] !== 0?$pnt->ler():array();

            header("Content-Type: application/json");
            exit($ctx->sessao->conectado()?json_encode($update):'[{"ativo":"desconectado"}]');
        }

        if($ctx->cfg["pagina"] == "login"){
            if($ctx->sessao->conectado()){header('Location: /home');}
            if(isset($pags[1])){
                // if(($pags[1])=="recuperar-acesso"){
                //     $fonte = '<!--begin::Web font -->
                //                 <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
                //                 <script>
                //                     WebFont.load({
                //                     google: {"families":["Poppins:300,400,500,600,700","Roboto:300,400,500,600,700"]},
                //                     active: function() {
                //                         sessionStorage.fonts = true;
                //                     }
                //                   });
                //                 </script><style>*, * *, * * * {font-family: \'Poppins\';}</style>';
                //
                //     if(isset($_POST['email'])){
                //         if(!checkdnsrr(ltrim(stristr($_POST["email"], '@'), '@') . '.')){
                //             exit("{$fonte}<center><h1>Email inválido!</h1><h3>Desculpe, mas o email inserido e inválido. Tente novamente.</h3></center><script>setTimeout(function(){history.back()},3e3);</script>");
                //         }
                //
                //         if(!($token=$ctx->sessao->gerartoken($_POST["email"]))){
                //             exit("{$fonte}<center><h1>Email inexistente!</h1><h3>Desculpe, mas o email inserido não está cadastrado no sistema. Tente novamente.</h3></center><script>setTimeout(function(){history.back()},3e3);</script>");
                //         }
                //
                //         $code  = "http://zapes.com.br/login/recuperar-acesso/".$token;
                //
                //         send_email(urldecode($_POST["email"]),"ZAPES - Recuperacao de Acesso Admin","<h1>Recuperação de conta</h1><br />Se você solicitou  a recuperação de acesso a sua conta administrativa no site <a href='http://zapes.com.br' target=_blank>http://zapes.com.br</a>, acesse este link: <br /><br /><a href='{$code}'>{$code}</a>");
                //
                //         exit("{$fonte}<center><h1>Email enviado com sucesso.</h1><h3>Acesse o link enviado no seu email para redefinir sua senha</h3><h5>Observação: Verifique o lixo eletrônico da sua caixa de email.</h5></center><script>setTimeout(function(){history.back()},5e3);</script>");
                //     } else {
                //         if(($novasenha=$ctx->sessao->utilizartoken($pags[2]))){
                //             exit("{$fonte}<center><h1>Tudo certo!</h1><h3>Procedimento de recuperação de conta realizado com sucesso!</h3><h3>Utilize a seguinte senha:</h3><h1 style='background-color: yellow; display: inline-block; padding: 16px;border: 4px dashed yellow;'>{$novasenha}</h1><br /><br /><p><a href='/login'>Ir para pagina de login</a></p></center>");
                //         } else {
                //             exit("{$fonte}<center><h1>TOKEN invalido!</h1><h3>Seu token está expirado, tente novamente.</h3></center><script>setTimeout(function(){history.back()},3e3);</script>");
                //         }
                //     }
                // }

                if(($pags[1])=="processar"){
                    header("Content-Type: text/plain");
                    $ctx->sessao->conectar($_POST["email"], md5($_POST["password"]));
                    if($ctx->sessao->conectado()){
                        $u = $ctx->sessao->usuario();
                        if($u["ativo"]!=="sim"){
                            $ctx->sessao->desconectar();
                            exit("2");
                        } else {
                            exit("1");
                        }
                    } else {
                        exit("0");
                    }
                }
            }
        } elseif(!$ctx->sessao->conectado() && (!isset($_REQUEST["_urldir_"]) || isset($_REQUEST["_urldir_"]) && !preg_match("/\.map/",$_REQUEST["_urldir_"]))){
            header("Location: /login");
        }

        if($ctx->cfg["pagina"] == "logout"){
            $ctx->sessao->desconectar();
            header("Location: /login");
        }

        if($ctx->cfg["pagina"] == "imageupload"){
            if(isset($_POST["delete"]) && isset($pags[1])){
                exit(unlink("./uploads/{$pags[1]}/{$_POST["delete"]}"));
            } elseif(!empty($_FILES)){
                $imagefile = md5(uniqid(rand(), true).date("dmYHis")) . "." . pathinfo($_FILES['fileToUpload']['name'], PATHINFO_EXTENSION);
                $folder =  "." . DIRECTORY_SEPARATOR . "uploads" . DIRECTORY_SEPARATOR . $pags[1];
                if(!is_dir($folder)){
                    mkdir($folder, 0755, true);
                }
                $uploaddir = "{$folder}".DIRECTORY_SEPARATOR."{$imagefile}";
                move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $uploaddir);

                exit($imagefile);
            }
        }

        travar_paginas(1, ($ctx->trava1 = array(
            "gestaopontos",
            "adicionar_regra",
            "editar_regra"
        )));

        travar_paginas(2, ($ctx->trava2 = array(
            "gestaopontos",
            "adicionar_regra",
            "editar_regra",
            "planos",
            "adicionar_perfil"
        )));

        if($ctx->cfg["pagina"] == "editar_perfil" && isset($_POST["id"])){
            if(!($u=$ctx->sessao->conectado())){exit("error");}
            $id = (int)$_POST["id"]==-1?$ctx->sessao->uid():(int)$_POST["id"];
            $u = $ctx->sessao->usuario();

            if((int)$u["tipo"] !== 0&&(int)$_POST["tipo"] !== 2&&(int)$id!==(int)$ctx->sessao->uid()){
                exit("error");
            }

            if(isset($_POST["pontos"])){
                unset($_POST["pontos"]);
            }
            unset($_POST["id"]);
            if(isset($_POST["vinculo"])){
                $_POST["vinculo"] = (int)$_POST["vinculo"];
            }
            if(isset($_POST["senha"])&&$_POST["senha"]==$_POST["confsenha"]&&strlen($_POST["senha"])>0){
				$_POST["senha"] = md5($_POST["senha"]);
				unset($_POST["confsenha"]);
			} else {
				unset($_POST["senha"]);
				unset($_POST["confsenha"]);
			}
            $contas = (new conexao(!(isset($_POST["sessaoatual"])&&$_POST["sessaoatual"]=="sim")));
            foreach($_POST as $dado => $valor){
                $contas->mudardado($dado,$valor,$id);
            }
            exit("ok");
            // exit(print_r($_POST,true));
        }

        if($ctx->cfg["pagina"] == "usuarios"){
			if(isset($_POST["apagar"])):
            	$ctx->sessao->remover($_POST["apagar"]);
            	exit("ok");
			elseif(isset($_POST["estado"]) && isset($_POST["id"]) && isset($_POST["valor"])):
				$contas = (new conexao(!(isset($_POST["sessaoatual"])&&$_POST["sessaoatual"]=="sim")));
                $contas->mudardado($_POST["estado"],$_POST["valor"],$_POST["id"]);
            	exit("ok");
            endif;
        }

        if($ctx->cfg["pagina"] == "adicionar_perfil" && isset($_POST["email"])){
            $u = $ctx->sessao->usuario();
            if((int)$u["tipo"] !== 0&&((int)$_POST["tipo"] !== 2||(int)$_POST["tipo"] == (int)$u["tipo"])){
                exit("error");
            } elseif($u["tipo"] == 1){
                $_POST["vinculo"] = (int)$ctx->sessao->uid();
            }
            $_POST["senha"] = md5($_POST["senha"]);
			unset($_POST["confsenha"]);
            exit((!checkdnsrr(ltrim(stristr($_POST["email"], '@'), '@') . '.'))
                 ? "email-nao-existe"
                 : (string)(new conexao(true))->criarconta($_POST)
            );
        }

		if($ctx->cfg["pagina"] == "adicionar_regra" && isset($_POST["titulo"])){
            $ctx->database->abrir("regras");
            $ctx->database->dados["lastid"] = isset($ctx->database->dados["lastid"]) ? (int)$ctx->database->dados["lastid"] + 1 : 0;
			$ctx->database->dados["data"] = !isset($ctx->database->dados["data"])?array():$ctx->database->dados["data"];
            $ctx->database->dados["data"][$ctx->database->dados["lastid"]] = $_POST;
            $ctx->database->gravar();
            exit("ok");
        }

		if($ctx->cfg["pagina"] == "editar_regra" && isset($_POST["id"])){
            $ctx->database->abrir("regras");
            $ctx->database->dados["data"][$_POST["id"]] = $_POST;
            $ctx->database->gravar();
            exit("ok");
        }

		if($ctx->cfg["pagina"] == "gestaopontos"){
			if(isset($_POST["apagar"])):
            	$ctx->database->abrir("regras");
				$ctx->database->dados["data"][$_POST["apagar"]] = array("__apagado__" => true);
				$ctx->database->gravar();
				exit("ok");
			elseif(isset($_POST["estado"]) && isset($_POST["id"]) && isset($_POST["valor"])):
                $ctx->database->abrir("regras");
				$ctx->database->dados["data"][$_POST["id"]][$_POST["estado"]] = $_POST["valor"];
                $ctx->database->gravar();
				exit("ok");
            endif;
        }

        $medicamentos = new sistemamedicamentos();

        if($ctx->cfg["pagina"] == "adicionar_medicamento" && isset($_POST["nome"])){
            $medicamentos->estabelecimentoId($ctx->sessao->uid());
            $medicamentos->adicionar($_POST);

            exit("ok");
        }

        if($ctx->cfg["pagina"] == "editar_medicamento" && isset($_POST["id"])){
            $medicamentos->estabelecimentoId($ctx->sessao->uid());
            $medicamentos->editar($_POST["id"], $_POST);

            exit("ok");
        }

        if($ctx->cfg["pagina"] == "medicamentos"){
            if(isset($_POST["apagar"])):
                $medicamentos->estabelecimentoId($ctx->sessao->uid());
                $medicamentos->editar($_POST["apagar"], array("__apagado__" => true));

                exit("ok");
            elseif(isset($_POST["estado"]) && isset($_POST["id"]) && isset($_POST["valor"])):
                $medicamentos->estabelecimentoId($ctx->sessao->uid());
                $data = array();
                $data[$_POST["estado"]] = $_POST["valor"];
                $medicamentos->editar($_POST["id"], $data);

                exit("ok");
            endif;
        }

        if($ctx->cfg["pagina"] == "transacao"){

        }
        foreach($pags as $s){
            $ctx->cfg["basedir"] .= "./.";
        }

        $ctx->cfg["basedir"] .= "./";

//      $ctx->debug();
    }
?>
