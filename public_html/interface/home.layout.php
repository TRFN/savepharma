<?php function layout($ctx){
    $u = $ctx->sessao->usuario();
    ?>
    <?php if(!isset($_POST["ajax"])): ?>
        <!DOCTYPE html>
        <html class="no-js" lang="">
            <?php $ctx->dinamizar->inserir("head",$ctx); ?>
            <body class="contact-page">
                <?php $ctx->dinamizar->inserir("menu",$ctx); endif; if($_POST["ajax"] !== "vitrine"||!isset($_POST["ajax"])): ?>
                <section class="contact space paginas" id="pag_home">
                <?php if((int)$u["tipo"]>0): ?>
                    <div class="container">
                        <div class="row">
                            <div class="col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2 col-xs-10 col-xs-offset-1 col-lg-6 col-lg-offset-3">
                                <h2 style="color: #1178f7;">Painel Principal</h2>
                                <p>Bem vindo! Selecione alguma opção abaixo ou senão acesse o menu no canto superior direito da página.</p>
                            </div>
                            <div style="clear: both;"></div>
                            <div class="col-md-6">
                                <div class="info-box" style="background-color: transparent;margin-bottom: 24px; border:2px solid #4412af;padding: 18px 50px;">
                                    <div class="align">
                                        <h2 style="color: #4412af; font-family: 'Open Sans';">Medicamentos
                                            <br />
                                            <span style="color: #333; font-size: 12px; line-height: -1px;">Nesta seção você pode visualizar, alterar ou cadastrar medicamentos no sistema.
                                            <br />
                                            <a class="btn btn-dark dinamizar" style="font-size: 12px;margin-top: 32px;" href="/medicamentos">Acessar esta seção</a>
                                            </span>
                                        </h2>
                                    </div>
                                    <div class="icon" style="background-color: #4412af;">
                                        <i class="la la-list"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-box" style="background-color: transparent;margin-bottom: 24px; border:2px solid #ab0202; padding: 18px 50px;">
                                    <div class="align">
                                        <h2 style="color: #ab0202; font-family: 'Open Sans';">Relatórios
                                            <br />
                                            <span style="color: #333; font-size: 12px; line-height: -1px;">Seção destinada aos relatorios/balanço, além de vencimentos e afins.
                                            <br />
                                            <a class="btn btn-dark dinamizar" style="font-size: 12px;margin-top: 32px;" href="/relatorios">Acessar esta seção</a>
                                            </span>
                                        </h2>
                                    </div>
                                    <div class="icon" style="background-color: #ab0202;">
                                        <i class="la la-tasks"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="container">
                        <div class="row">
                            <div class="col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2 col-xs-10 col-xs-offset-1 col-lg-8 col-lg-offset-2">
                                <h2 style="color: #1178f7;">Administração do Sistema</h2>
                                <p>Bem vindo administrador! Acesse o menu no canto superior direito da página para visualizar as funcionalidades administrativas disponíveis.o Você visualizar todas as contas, alterar e inclusive gerir todos os pontos. Lembre-se sempre de ter atenção ao alterar estabelecimentos ou farmaceuticos, bem como também quando for determinar as regras de pontos.</p>
                                <br /><br /><br /><br /><br /><br />
                            </div>
                        </div>
                    </div>
                <?php endif;endif; ?>
                <?php if((int)$u["tipo"]==1): ?>
                    <div class="container" id="vitrine">
                        <?php $ctx->dinamizar->inserir("remedios_vitrine",$ctx); ?>
                    </div>
                <?php endif; if($_POST["ajax"] !== "vitrine"):  ?>
                </section>
                <?php if(!isset($_POST["ajax"])): ?>
                <?php $ctx->dinamizar->inserir("footer",$ctx); ?>
            </body>
        </html>
    <?php endif;endif; ?>
<?php } ?>
