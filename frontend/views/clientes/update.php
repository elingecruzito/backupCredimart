<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Clientes */

$this->title = Yii::$app->params['txtTitleModificarCliente'];
$this->params['breadcrumbs'][] = ['label' => 'Clientes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->g02_nombre . " " . $model->g02_paterno . " " . $model->g02_materno, 'url' => ['view', 'id' => $model->g02_id]];
$this->params['breadcrumbs'][] = Yii::$app->params['txtTitleModificarCliente'];
?>
<div class="clientes-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
