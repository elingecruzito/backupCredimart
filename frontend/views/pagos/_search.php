<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\search\PagosSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pagos-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'g04_id') ?>

    <?= $form->field($model, 'g01_id') ?>

    <?= $form->field($model, 'g02_id') ?>

    <?= $form->field($model, 'g03_id') ?>

    <?= $form->field($model, 'g04_cantidad') ?>

    <?php // echo $form->field($model, 'g04_fecha') ?>

    <?php // echo $form->field($model, 'g04_semana') ?>

    <?php // echo $form->field($model, 'g04_created') ?>

    <?php // echo $form->field($model, 'user_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
