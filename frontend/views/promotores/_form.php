<?php

use yii\helpers\Html;
//use yii\widgets\ActiveForm;
use kartik\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Promotores */
/* @var $form yii\widgets\ActiveForm */

$this->registerJsFile(Yii::$app->request->baseUrl.'/js/promotores-form.js', ['depends'=>[\yii\web\JqueryAsset::className()]]);
?>

<div class="promotores-form">

    <?php $form = ActiveForm::begin([
        'type' => ActiveForm::TYPE_HORIZONTAL,
        'fullSpan' => 10,
        'formConfig' => ['labelSpan' => 3, 'deviceSize' => ActiveForm::SIZE_SMALL]
    ]); ?>

        <?= $form->field($model, 'g01_nombre')->textInput(['id' => 'g01_nombre']) ?> 
        <?= $form->field($model, 'g01_paterno')->textInput(['id' => 'g01_paterno']) ?>  
        <?= $form->field($model, 'g01_materno')->textInput(['id' => 'g01_materno']) ?>  


        <?= $form->field($model, 'g01_domicilio')->textInput(['id' => 'g01_domicilio']) ?>
        <?= $form->field($model, 'g01_telefono')->widget(\yii\widgets\MaskedInput::className(), ['mask' => '999-999-9999']) ?>

        <div class="pull-right">
            <?= Html::a('Cancelar', ['/promotores'], ['class' => 'btn btn-default']) ?>
            <?= Html::submitButton(Yii::$app->params['btnGuardarPromotor'] , ['class' => 'btn btn-primary']) ?>
        </div>

    <?php ActiveForm::end(); ?>

</div>
