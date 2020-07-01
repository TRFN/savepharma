<?php function layout($ctx){ ?>
    <?php if(!isset($_POST["ajax"])): ?>
        <!DOCTYPE html>
        <html class="no-js" lang="">
            <?php $ctx->dinamizar->inserir("head",$ctx); ?>
            <body class="contact-page">
                <?php $ctx->dinamizar->inserir("menu",$ctx); endif; ?>
                <section class="contact space paginas" id="pag_<?=$ctx->pagina_atual();?>">
                    <div class="row">
						<div class="col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2 col-xs-10 col-xs-offset-1 col-lg-6 col-lg-offset-3">
							<h2 style="color: #1178f7;">Modificar Regra</h2>
							<p>Nesta página você pode modificar uma <strong>regra</strong> selecionada para o sistema de pontos. Tenha muita atenção ao alterar pois as regras refletem diretamente nos pontos dos medicamentos.</p>
						</div>
                        <?php $ctx->dinamizar->inserir("f_regras",$ctx); ?>
					</div>
                </section>
                <?php if(!isset($_POST["ajax"])): ?>
                <?php $ctx->dinamizar->inserir("footer",$ctx); ?>
            </body>
        </html>
    <?php endif; ?>
<?php } ?>