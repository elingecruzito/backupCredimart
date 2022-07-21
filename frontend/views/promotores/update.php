<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Promotores */

$this->title = Yii::$app->params['txtTitleModificarPromotores'];
$this->params['breadcrumbs'][] = ['label' => 'Promotores', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->g01_nombre . " " . $model->g01_paterno . " " . $model->g01_materno , 'url' => ['view', 'id' => $model->g01_id]];
$this->params['breadcrumbs'][] = Yii::$app->params['txtTitleModificarPromotores'];
?>
<div class="promotores-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
