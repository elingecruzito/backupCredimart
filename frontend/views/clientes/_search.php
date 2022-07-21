<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\search\ClientesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="clientes-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'g02_id') ?>

    <?= $form->field($model, 'g02_nombre') ?>

    <?= $form->field($model, 'g02_paterno') ?>

    <?= $form->field($model, 'g02_materno') ?>

    <?= $form->field($model, 'g02_domicilio') ?>

    <?php // echo $form->field($model, 'g02_telefono') ?>

    <?php // echo $form->field($model, 'g01_id') ?>

    <?php // echo $form->field($model, 'g02_nombre_aval') ?>

    <?php // echo $form->field($model, 'g02_paterno_aval') ?>

    <?php // echo $form->field($model, 'g02_materno_aval') ?>

    <?php // echo $form->field($model, 'g02_domicilio_aval') ?>

    <?php // echo $form->field($model, 'g02_telefono_aval') ?>

    <?php // echo $form->field($model, 'g02_fecha_solicitud') ?>

    <?php // echo $form->field($model, 'g02_tipo') ?>

    <?php // echo $form->field($model, 'g02_created') ?>

    <?php // echo $form->field($model, 'user_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
