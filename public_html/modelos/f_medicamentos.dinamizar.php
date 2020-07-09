<?php function f_medicamentos($ctx){ ?>
	<div style="padding-left: 0px;padding-right: 0px;" class="panel panel-primary col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2 col-xs-10 col-xs-offset-1 col-xl-6 col-xl-offset-3" id="form_<?=$ctx->pagina_atual();?>">
		<div class="panel-heading">
			 &nbsp;
		</div>

		<div class="panel-body">
			<label class="help-block">Dados do produto</label>
			<div class="form-group row">
				<div class="col-md-7 col-sm-12">
					<label class="help-block">Nome do produto</label>
					<input class="form-control m-input" type="text" name="nome" value="">
				</div>
				<div class="col-md-5 col-sm-12">
					<label class="help-block">Lote</label>
					<input class="form-control m-input" type="text" name="lote" value="">
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-6 col-sm-12">
					<label class="help-block">Marca</label>
					<input class="form-control m-input" type="text" name="marca" value="">
				</div>
				<div class="col-md-3 col-sm-12">
					<label class="help-block">Validade</label>
					<center><input class="form-control m-input" type="date" style="max-width: 170px!important;" name="validade"
						min="<?=($finaldate = date("Y-") . (($m=(int)date("m") + 1)<10?"0{$m}":"{$m}") . date("-d"));?>" value="<?=$finaldate;?>"></center>
				</div>
				<div class="col-md-3 col-sm-12">
					<label class="help-block">Prazo</label>
					<center><input class="form-control m-input" type="date" style="max-width: 170px!important;" name="prazo" value="<?=date("Y-m-d");?>"></center>
				</div>
			</div>
			<label class="help-block">A opção abaixo deve estar habilitada somente se realmente houver disponibilidade do produto.</label>
			<div class="form-group row">
				<div class="col-md-6 col-xs-12">
					<div class="form-group">
						<div>
							<label class="help-block">Disponível ?</label>
							<input data-switch="true" type="checkbox" class="switch_ativo" checked="checked">
						</div>
					</div>
				</div>
				<div class="col-md-6 col-xs-12">
					<div class="form-group m-form__group">
						<div>
							<label class="help-block">Faça o upload do comprovante fiscal do produto.</label>
							<div class="col-lg-12 col-md-9 col-sm-12">
								<div class="dropzone" style="border: 4px dashed #888;" id="dz<?=$ctx->pagina_atual()=="adicionar_medicamento"?"a":"e";?>-medicamento">
									<div class="dz-message needsclick">
										<strong>Clique aqui para carregar</strong>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="form-group m-form__group row">
				<label for="example-text-input" class="col-md-4 col-sm-12"></label>
				<div class="col-md-8 col-sm-12">
					<div style="display: block; width:32px; height:12px;"></div>
				</div>
			</div>
		</div>
		<div class="panel-footer">
			<?php
				salvar_completo(
					"javascript:go('medicamentos');",
					-1,
					"javascript:go('adicionar_medicamento');",
					"javascript:go('medicamentos');"
				);
			?>
		</div>
	</div>
<?php } ?>
