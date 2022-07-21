<?php

use yii\helpers\Html;

use common\models\Clientes;

/* @var $this yii\web\View */
/* @var $model app\models\Prestamos */

$this->title = Yii::$app->params['txtTitleModificarPrestamos'];
$this->params['breadcrumbs'][] = ['label' => 'Prestamos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => Clientes::getCliente($model->g02_id) , 'url' => ['view', 'id' => $model->g03_id]];
$this->params['breadcrumbs'][] = Yii::$app->params['txtTitleModificarPrestamos'];
?>
<div class="prestamos-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
