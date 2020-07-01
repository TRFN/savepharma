<?php function layout($ctx){ ?>
	<!DOCTYPE html>
	<html class="no-js" lang="">
		<?php $ctx->dinamizar->inserir("head",$ctx); ?>
		<body class="contact-page">
			<section class="contact space">
				<div class="row">
					<div class="col-xs-12 text-center">
						<div>
							<span style="color: #1178f7;font-family: 'Open Sans',Verdana;font-size: 48px;"><i class="fa fa-hospital-o"></i>&nbsp;&nbsp;&nbsp;SavePharma</span>
						</div>
					</div>
					<div class="col-xs-12"><br /><br /></div>
					<div class="col-md-4 col-md-offset-4 col-sm-8 col-sm-offset-2 col-xs-10 col-xs-offset-1 col-xl-2 col-xl-offset-6">
						<div class="row">
							<form action="javascript:processar_login();" method=post>
								<div class="col-md-12">
									<input type="text" class="form-control" placeholder="Email">
								</div>
								<div class="col-md-12">
									<input type="password" class="form-control" placeholder="Senha">
								</div>
								<div class="col-md-12">
									<br />
								</div>
								<div class="col-md-12 text-center">
									<button type="submit" name="submit" class="btn btn-default btn-colored" style="text-transform: none;font-size: 16px;font-family: 'Montserrat';"><i class="la la-user" style="margin-right: 8px;"></i>&nbsp;Acessar</button>
								</div>
							</form>
						</div>
					</div>
					<div class="col-xs-12">&nbsp;</div>
				</div>
			</section>
			<?php $ctx->dinamizar->inserir("footer",$ctx); ?>
		</body>
	</html>
<?php } ?>