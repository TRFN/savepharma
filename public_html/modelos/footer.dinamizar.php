<?php function footer($ctx){ ?>
    <footer>
        <div class="footer-copyright">
            <div class="container">
                <div class="row">
                    <p>© 2020 SavePharma - Todos os direitos reservados.</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- JQUERY / PLUGINS -->
    <script src="js/vendor/jquery-1.11.3.min.js"></script>
    <script src="js/vendor/bootstrap.min.js"></script>
	<script src="vendors/sweetalert2/dist/sweetalert2.min.js" type="text/javascript"></script>
	<script>

		window.msgbox = function(titulo,texto,tipo,confirm){
			if(typeof titulo=="undefined")return false;
			Sweetalert2({
				title: (typeof texto=="undefined" ? "":titulo),
				text: (typeof texto=="undefined" ? titulo:texto),
				type: (typeof tipo=="undefined" ? "success":tipo),
				showCancelButton: (typeof confirm=="function"),
				confirmButtonText: (typeof confirm=="function"?"Confirmar":(typeof confirm == "object"?confirm.texto:"OK")),
				cancelButtonText: "Cancelar",
			}).then(function(e){
				(!e.dismiss)&&((typeof confirm=="function")&&confirm());
				setTimeout('document.activeElement.blur();',200);
				if(typeof confirm == "object" && typeof confirm.acao == "object"){
					if(confirm.acao.indexOf("subir")!==-1){
						setTimeout('$([document.documentElement, document.body]).animate({ \
							scrollTop: parseInt($("' + String((confirm.cfg.subir)) + '").offset().top) - 150 \
						}).promise().done(function(){$("' + String((confirm.cfg.subir)) + '").click().focus();});', 250);
					}
				}
			});
		}


	</script>

    <script>
		setInterval(window.fnUpdateProfile=function(){

            if(typeof $ == "undefined")return;
			var t, p, c, e, d, r;

			t = (p=$("#form_editar_perfil,#form_adicionar_perfil")).find("[name=tipo]");
			c = p.find("#definirPontos");
            d = p.find("#definirVinculo");

			if(c.length > 0){
                c[(e=(parseInt(t.val())==1))?"show":"hide"]().find("input").prop("disabled",!e);
            }

            d[parseInt(t.val())==2?"show":"hide"]();

            d.find("select option").each(function(){
                if((u=String(sessionData[0][parseInt($(this).attr("value"))].tipo)) !== "1"){
                    $(this).remove();
                    r = true;
                }
            });

            r && d.find("select:first").selectpicker('refresh');

		},1200);
	</script>
    <!-- MAIN JS FOLDER -->
	<?php if($ctx->pagina_atual()!=="login"): ?>
		<script src="js/vendor/owl.carousel.min.js"></script>
		<script src="js/vendor/jquery.magnific-popup.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-switch/3.3.4/js/bootstrap-switch.min.js" integrity="sha256-AKUJYz2DyEoZYHh2/+zPHm1tTdYb4cmG8HC2ydmTzM4=" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/js/select2.min.js" integrity="sha256-nAS1wDQBPBqa1fnZq8wd1Z6CN+PgmHXLFdMo0g2pYf0=" crossorigin="anonymous"></script>
		<script src="vendors/summernote/dist/summernote.js" type="text/javascript"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.10/dist/js/bootstrap-select.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.7.0/min/dropzone.min.js" integrity="sha256-OG/103wXh6XINV06JTPspzNgKNa/jnP1LjPP5Y3XQDY=" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.4-beta.33/jquery.inputmask.min.js" integrity="sha256-L4kpZP1BsqygY+/b55A6N3o7zGWuRQcJGZaVomcwKD4=" crossorigin="anonymous"></script>
		<script src="js/main.js"></script>
		<script>
			window.paginasDisponiveis = [];
            <?php
                $u = $ctx->sessao->usuario();
                foreach($ctx->paglist() as $pagina){
                    if(!((in_array($pagina,$ctx->trava1) && (string)$u["tipo"] == "1") || (in_array($pagina,$ctx->trava2) && (string)$u["tipo"] == "2"))){
                        ?> paginasDisponiveis.push("<?=$pagina;?>"); <?php
                    }
                }
            ?>
		</script>
		<script src="js/pageloader.js?id=<?=md5(uniqid());?>"></script>
		<script><?php include("js/resources.js"); ?></script>
		<script><?php include("js/forms.js"); ?></script>
	<?php else: ?>
		<script src="js/login.js"></script>
	<?php endif; ?>
<?php } ?>
