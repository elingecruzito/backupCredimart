<?php

use yii\helpers\Html;
use yii\grid\GridView;

use common\models\Notificaciones;
use common\models\Utilerias;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\NotificacionesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Notificaciones';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="notificaciones-index">

    <div class="col-md-12">
	  <!-- The time line -->
		<ul class="timeline">
			<?php 
			foreach ($model as $key => $value) {
				?><li class="time-label"><span class="bg-blue"><?= Utilerias::invertirFecha($value['g06_date']) ?></span></li><?php
				$notificaciones = Notificaciones::getNotificacionesOfTheDay(Yii::$app->user->id, $value['g06_date']);
				foreach ($notificaciones as $keyN => $valueN) {
					?>
					<li>
				      	<i class="<?= Notificaciones::getIconForListNotificaciones($valueN->g06_tipo) ?> bg-yellow"></i>

				      	<div class="timeline-item" style="border: 1px solid #dff0d8;">
				        	<span class="time"><i class="fa fa-clock-o"></i> <?= Utilerias::getTimeFromTimestamp($valueN->g06_date) ?></span>

				        	<h3 class="timeline-header"><a href="#"><?= Notificaciones::getTitleNotificacion($valueN->g06_tipo) ?></a></h3>

				        	<div class="timeline-body"><?= $valueN->g06_message ?></div>
				      	</div>
				    </li>
					<?php
				}
			}
			?>
			<li>
              <i class="fa fa-clock-o bg-gray"></i>
            </li>
		    
	  	</ul>
	</div>


</div>
