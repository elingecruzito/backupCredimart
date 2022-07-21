<?php
use yii\helpers\Html;
use common\models\Utilerias;
use common\models\Clientes;
use common\models\Prestamos;
use common\models\Pagos;

$this->registerCssFile(Yii::$app->params['photo_view'] . "/css/report.css");

?>
<div align="right">
	<?= Html::img(Yii::$app->params['photo_view'] . 'img/logo.png', ['width' => '150px']) ?>
</div>


<div class="test-report">
	
	<table width="100%" style="padding: 20px">
		<tr style="margin-bottom: 3px #fff solid !important;">
			<th style="text-align: center;">Prestamo</th>
			<th style="text-align: center;">Promotor</th>
			<th style="text-align: center;">Cliente</th>
			<th style="text-align: center;">Fecha ultimo prestamo</th>
			<th style="text-align: center;">Total prestamo</th>
			<th style="text-align: center;">Total pagado</th>
			<th style="text-align: center;">Tipo de cliente</th>
		</tr>

		<?php 
			
			foreach ($model as $row) {	
				switch ($row->g03_estado) {
					case Prestamos::ESTADO_PENDIENTE: $colorRow = '#fcf8e3'; break;
					case Prestamos::ESTADO_LIQUIDADO: $colorRow = '#dff0d8'; break;
					case Prestamos::ESTADO_CERRADO: $colorRow = '#f2dede'; break;
				}

			?>
				<tr style="background: <?= $colorRow ?>;">
					<td style="text-align: center; margin-left: 1px solid #fff;"><?= $row->g03_id ?></td>
					<td style="text-align: center; margin-left: 1px solid #fff;"><?= $row->g01_nombre ?></td>
					<td style="text-align: center; margin-left: 1px solid #fff;"><?= $row->g02_nombre ?></td>
					<td style="text-align: center; margin-left: 1px solid #fff;"><?= Utilerias::invertirFecha($row->g03_fecha) ?></td>
					<td style="text-align: center; margin-left: 1px solid #fff;"><?= Utilerias::formatMoney($row->g03_total) ?></td>
					<td style="text-align: center; margin-left: 1px solid #fff;"><?= Utilerias::formatMoney($row->g04_cantidad) ?></td>
					<td style="text-align: center; margin-left: 1px solid #fff;"><?= Clientes::getTipoCliente($row->g02_tipo) ?></td>
				</tr>
			<?php
			}
		?>
	</table>	
	
</div>