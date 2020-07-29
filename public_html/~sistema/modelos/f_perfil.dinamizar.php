<?php
	function f_perfil($ctx){
		$u = $ctx->sessao->usuario();
?>
	<div style="padding-left: 0px;padding-right: 0px;" class="panel panel-primary col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2 col-xs-10 col-xs-offset-1 col-xl-6 col-xl-offset-3" id="form_<?=$ctx->pagina_atual();?>">
		<div class="panel-heading">
			 &nbsp;
		</div>
		<div class="panel-body">
			<div class="form-group m-form__group row">
				<label for="example-text-input" class="col-md-4 col-sm-12"></label>
				<div class="col-md-8 col-sm-12">
					<div style="display: block; width:32px; height:8px;"></div>
				</div>
			</div>
			<div class="form-group row">
				<label for="example-text-input" class="titulonome col-md-2 col-sm-12">Nome</label>
				<div class="col-md-4 col-sm-12">
					<input class="form-control m-input" type="text" name="nome" value="">
				</div>
				<label for="example-text-input" class="col-md-2 col-sm-12">Habilitado?</label>
				<div class="col-md-4 col-sm-12">
					<input data-switch="true" type="checkbox" class="switch_ativo" checked="checked">
				</div>
			</div>
			<div class="form-group row">
				<label for="example-text-input" class="col-md-2 col-sm-12">E-mail</label>
				<div class="col-md-4 col-sm-12">
					<input class="form-control m-input" type="text" name="email" value="">
					<span class="help-block text-left">O e-mail é o seu login</span>
				</div>
				<?php if((string)$u["tipo"]==0): ?>
					<label class="col-md-2 col-sm-12">Tipo de Conta</label>
					<div class="col-md-4 col-sm-12">
						<select name="tipo" class="selectpicker form-control">
							<option value="0">Administrador</option>
							<option value="1">Estabelecimento</option>
							<option value="2">Farmaceutico</option>
						</select>
					</div>
				<?php else: ?>
					<input name="tipo" type="hidden" value="2" />
				<?php endif; ?>
			</div>
			<div class="form-group row">
				<label for="example-text-input" class="col-md-2 col-sm-12">Nova Senha</label>
				<div class="col-md-4 col-sm-12">
					<input class="form-control m-input" type="password" name="senha" value="">
				</div>
				<label for="example-text-input" class="col-md-2 col-sm-12">Confirme a Senha</label>
				<div class="col-md-4 col-sm-12">
					<input class="form-control m-input" type="password" name="confsenha" value="">
					<span class="help-block text-left"></span>
				</div>
				<span class="col-md-8 col-md-offset-2 help-block text-left">Preencha somente se quiser modificar sua senha</span>
			</div>
			<div class="form-group row" id="crfdata">
				<label for="example-text-input" class="col-md-2 col-sm-12">CPF</label>
				<div class="col-md-4 col-sm-12">
					<input class="form-control m-input" type="text" name="cpf" value="">
				</div>
				<label for="example-text-input" class="col-md-2 col-sm-12">CRF</label>
				<div class="col-md-4 col-sm-12">
					<input class="form-control m-input" type="text" name="crf" value="">
				</div>
			</div>
			<div class="form-group row">
				<?php
					if((int)$u["tipo"] == 1):
				?>
					<div id="definir24h">
						<label for="example-text-input" class="col-md-2 col-sm-12">CNPJ</label>
						<div class="col-md-4 col-sm-12">
							<input class="form-control m-input" type="text" name="cnpj" value="">
						</div>
						<label for="example-text-input" class="col-md-2 col-sm-12">Funciona 24H?</label>
						<div class="col-md-4 col-sm-12">
							<input data-switch="true" type="checkbox" class="switch_est24h">
						</div>
					</div>
				<?php endif; ?>
			</div>
			<div class="form-group row">
				<?php
					if((int)$u["tipo"] == 0 && $ctx->pagina_atual()=="editar_perfil"):
						$paginas = $ctx->subpaginas();
						$id = explode(":",$paginas[0]);
						$id = (int)$id[1];
						$pnt = new sistemapontos();
						$pnt->estabelecimentoId($id);
				?>
					<div id="definirPontos" class="col-md-4 col-md-offset-4 col-xs-12 col-sm-12">
						<label class="help-block text-center">Definir pontos manualmente:</label>
						<input class="form-control m-input" type="text" name="pontos" value="" placeholder="Atualmente com <?=$pnt->ler("pontos");?> pontos">
					</div>
				<?php endif; ?>
			</div>
			<div class="form-group row">
				<?php
					if((int)$u["tipo"] == 0):
				?>
					<div id="definirVinculo" class="col-md-6 col-md-offset-3 col-xs-12 col-sm-12">
						<label class="help-block text-center">Funcionario de qual estabelecimento?</label>
						<select class="selectpicker form-control" name="vinculo" data-live-search="true"></select>
					</div>
				<?php endif; ?>
			</div>
		</div>
		<div class="panel-footer">
			<?php
				(int)$u["tipo"] !== 2
					? salvar_completo(
						"javascript:go('usuarios');",
						-1,
						"javascript:go('adicionar_perfil');",
						"javascript:go('usuarios');"
					)
					: salvar_isolado(
						"Salvar",
						"javascript:go('home');",
						"javascript:go('home');"
					);
			?>
		</div>
	</div>
<?php } ?>
