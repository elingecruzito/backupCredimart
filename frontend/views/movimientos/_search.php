<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\search\MovimientosSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="movimientos-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'c01_id') ?>

    <?= $form->field($model, 'c01_tipo') ?>

    <?= $form->field($model, 'c01_id_tabla') ?>

    <?= $form->field($model, 'c01_tabla') ?>

    <?= $form->field($model, 'user_id') ?>

    <?php // echo $form->field($model, 'c01_date') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
