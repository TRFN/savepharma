<?php function f_regras($ctx){ ?>
	<div style="padding-left: 0px;padding-right: 0px;" class="panel panel-primary col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2 col-xs-10 col-xs-offset-1 col-xl-6 col-xl-offset-3" id="form_<?=$ctx->pagina_atual();?>">
		<div class="panel-heading">
			 &nbsp;
		</div>
		<div class="panel-body">
			<label class="help-block">Aspectos Gerais</label>
			<div class="form-group row">
				<div class="col-md-8 col-sm-12">
					<label class="help-block">Titulo</label>
					<input class="form-control m-input" type="text" name="titulo" value="">
				</div>
				<!--div class="col-md-2 col-sm-12">
					<label class="help-block">Pontos</label>
					<input class="form-control m-input" type="text" name="pontos" value="0">
				</div-->
				<!--div class="col-md-3 col-sm-12">
					<label class="help-block">Ganho?</label>
					<input data-switch="true" type="checkbox" class="switch_ganho" checked="checked">
					<small class="help-block text-left">Caso desabilite, a pontuação será debitada do estabelecimento</small>
				</div-->
				<div class="col-md-4 col-sm-12">
					<label class="help-block">Regra ativa?</label>
					<input data-switch="true" type="checkbox" class="switch_ativo">
				</div>
			</div>
			<label class="help-block">Determine a regra do prazo de validade do produto para aplicação</label>
			<div class="form-group row">
				<div class="col-md-4 col-sm-12 col-xs-12">
					<label class="help-block">(Regra) do prazo</label>
					<select name="prazo" class="selectpicker form-control">
						<option value="0">Prazo a partir de</option>
						<option value="1">Prazo antes de</option>
						<option value="2">O prazo exato é</option>
					</select>
				</div>
				<div class="col-md-3 col-sm-12 col-xs-12">
					<label class="help-block">(Mês)</label>
					<select name="meses" class="selectpicker form-control">
						<option value="1">01 mês</option>
						<?php for($i = 2; $i < 18; $i++): ?>
							<option value="<?=$i;?>"><?=$i < 10 ? "0{$i}":$i;?> meses</option>
						<?php endfor; ?>
					</select>
				</div>
				<div class="col-md-5 col-sm-12 col-xs-12">
					<label class="help-block">(Dia)</label>
					<select name="dias" class="selectpicker form-control">
						<option value="0">para vencimento (sem considerar dias)</option>
						<?php for($i = 1; $i < 31; $i++): ?>
							<option value="<?=$i;?>">e <?=$i < 10 ? "0{$i}":$i;?> dias para vencimento</option>
						<?php endfor; ?>
					</select>
				</div>
			</div>
			<label class="help-block">Na transação, os estabelecimentos tem as seguintes ações:</label>
			<div class="form-group row">
				<div class="col-md-6 col-sm-12">
					<label class="help-block col-md-12 col-sm-12 col-xs-12">Quem disponibilizou:</label>
					<div class="col-md-8 col-xs-12 col-sm-12">
						<label class="help-block text-center">Os pontos são:</label>
						<select name="reacao1" class="selectpicker form-control">
							<option value="1" selected>Acrescentados</option>
							<option value="0">Subtraidos</option>
						</select>
					</div>
					<div class="col-md-4 col-xs-12 col-sm-12">
						<label class="help-block text-center">No valor de:</label>
						<input class="form-control m-input" type="text" name="pontos1" value="" placeholder="Ex: 280">
					</div>
				</div>
				<div class="col-md-6 col-sm-12">
					<label class="help-block col-md-12 col-sm-12 col-xs-12">Quem adiquiriu:</label>
					<div class="col-md-8 col-xs-12 col-sm-12">
						<label class="help-block text-center">Os pontos são:</label>
						<select name="reacao2" class="selectpicker form-control">
							<option value="1">Acrescentados</option>
							<option value="0" selected>Subtraidos</option>
						</select>
					</div>
					<div class="col-md-4 col-xs-12 col-sm-12">
						<label class="help-block text-center">No valor de:</label>
						<input class="form-control m-input" type="text" name="pontos2" value="" placeholder="Ex: 150">
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
					"javascript:go('gestaopontos');",
					-1,
					"javascript:go('adicionar_regra');",
					"javascript:go('gestaopontos');"
				);
			?>
		</div>
	</div>
<?php } ?>
