<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;

use kartik\select2\Select2;
use kartik\date\DatePicker;
use common\models\Clientes;

/* @var $this yii\web\View */
/* @var $model app\models\Prestamos */
/* @var $form yii\widgets\ActiveForm */


$this->registerJsFile(Yii::$app->request->baseUrl.'/js/prestamos.js', ['depends'=>[\yii\web\JqueryAsset::className()]]);
?>

<div class="prestamos-form">

    <?php $form = ActiveForm::begin([
        'type' => ActiveForm::TYPE_HORIZONTAL,
        'fullSpan' => 10,
        'formConfig' => ['labelSpan' => 3, 'deviceSize' => ActiveForm::SIZE_SMALL]
    ]); ?>

        <?= $form->field($model, 'g02_id')->widget(Select2::classname(), [
                'data' => Clientes::getListClientes(),
                'options' => ['placeholder' => ''],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>

        <?= $form->field($model, 'g03_monto')->textInput() ?>

        <?= $form->field($model, 'g03_abono')->textInput() ?>

        <?= $form->field($model, 'g03_total')->textInput(['readonly' => true]) ?>

        <?= $form->field($model, 'g03_fecha')->widget(DatePicker::classname(), [
                    'language' => 'es',
                   'pluginOptions' => [
                        'format' => 'dd/mm/yyyy',
                    ]
            ]) ?>

        <div class="pull-right">
            <?= Html::a('Cancelar', ['/prestamos'], ['class' => 'btn btn-default']) ?>
            <?= Html::submitButton(Yii::$app->params['btnGuardarPromotor'] , ['class' => 'btn btn-primary']) ?>
        </div>

    <?php ActiveForm::end(); ?>

</div>
