<?php function menu($ctx){ ?>
<?php $u = $ctx->sessao->usuario(); ?>
    <!--[if lt IE 8]>
        <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->
    <div id="preloader" style="z-index:2147483648!important;background-color: rgba(255,255,255,0.95);"> <div class="la la-refresh fa-spin" style="font-size: 20vw;position: fixed;top: calc(50% - 10vw);left: calc(50% - 10vw);color: #27f;"></div></div>
    <header>
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="logo col-md-4">
                        <span style="color: #1178f7;font-family: 'Open Sans',Verdana;font-size: 26px;"><i class="fa fa-hospital-o"></i>&nbsp;&nbsp;&nbsp;SavePharma</span>
                    </div>
                    <?php
                        if((int)$u["tipo"] !== 0):
                            $pnt = new sistemapontos();
                            $pnt->estabelecimentoId($u[(int)$u["tipo"]==1?"id":"vinculo"]);
                            $pontos = $pnt->ler("pontos");
                            $textopontos = "Pontos disponíveis: <span id='pontosatualizar'>{$pontos}</span>";
                        endif;
                    ?>
                    <div style="color: #7811f7;text-decoration: underline;text-shadow: 0 0 1px #000; font-family: 'Open Sans',Verdana;font-size: 26px;" class="hidden-sm hidden-xs col-md-4 text-center">
                        <?=$textopontos;?>
                    </div>
                    <div style="color: #fff; background-color: #333; width: 100%; padding: 8px; position: fixed; display: block; left: 0; bottom: 0; font-family: 'Open Sans',Verdana;font-size: 18px;" class="hidden-md hidden-lg hidden-xl text-center">
                        <?=$textopontos;?>
                    </div>
                    <button class="toggle" type="button" style="border: 2px solid;border-radius: 100%;padding: 2px 10px;color: #1178f7;">
                        <i class="fa fa-bars"></i>
                    </button>
                </div>
            </div>
        </div>

        <nav class="sidebar">
            <ul class="list">
                <li><strong><?=$u["email"];?></strong><div style="color: #fff; font-size: 10px">(<?php
					switch((string)$u["tipo"]){
						case "0": echo "Administrador"; break;
    				    case "1": echo "Estabelecimento"; break;
        				case "2": echo "Farmaceutico"; break;
						default: echo "Usuário Comum"; break;
					}

                    function makemenu($options){
                        global $ctx;
                        foreach($options as $option){
                            switch((string)$option){
                                case "1": ?><li><a href="/" class="dinamizar">Painel Principal</a></li><?php break;
                                case "2": ?><li><a href="/gestaopontos" class="dinamizar">Gestão dos pontos</a></li><?php break;
                                case "3": ?><li><a href="/usuarios" class="dinamizar">Usuarios</a></li><?php break;
                                case "4": ?><li><a href="/editar_perfil/id:<?=(string)$ctx->sessao->uid();?>" class="dinamizar">Minha Conta</a></li><?php break;
                                case "5": ?><li><a href="/planos" class="dinamizar">Planos</a></li><?php break;
                                case "6": ?><li><a href="/logout">Sair</a></li><?php break;
                            }
                        }
                    }
				?>)</div></li>
                <li>&nbsp;</li>
                <?php
                    switch((string)$u["tipo"]){
                        case "0": makemenu(array(1,2,3,4,5,6)); break;
                        case "1": makemenu(array(1,3,4,5,6)); break;
                        case "2": makemenu(array(1,4,6)); break;
                        default : $ctx->sessao->desconectar(); break;
                    }
                ?>
            </ul>
        </nav>
    </header>
<?php } ?>
