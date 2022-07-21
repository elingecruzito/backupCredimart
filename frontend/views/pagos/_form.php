<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;

use kartik\select2\Select2;
use kartik\date\DatePicker;

use common\models\Clientes;
use common\models\Promotores;
use common\models\Pagos;

$this->registerJsFile(Yii::$app->request->baseUrl.'/js/pagos.js', ['depends'=>[\yii\web\JqueryAsset::className()]]);

/* @var $this yii\web\View */
/* @var $model app\models\Pagos */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pagos-form">

    <?php $form = ActiveForm::begin([
        'type' => ActiveForm::TYPE_HORIZONTAL,
        'fullSpan' => 10,
        'id' => 'form-pagos',
        'formConfig' => ['labelSpan' => 3, 'deviceSize' => ActiveForm::SIZE_SMALL]
    ]); ?>

        <?= $form->field($model, 'g01_id')->widget(Select2::classname(), [
                'data' => Promotores::getListPromotores(),
                'options' => ['placeholder' => '', 'id' => 'g01_id'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>

        <?= $form->field($model, 'g02_id')->widget(Select2::classname(), [
                'options' => ['placeholder' => '', 'id' => 'g02_id'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>

        <?= $form->field($model, 'g04_cantidad')->textInput(['id' => 'g04_cantidad']) ?>

        <?= $form->field($model, 'g04_fecha')->widget(DatePicker::classname(), [
                    'language' => 'es',
                    'options' => ['placeholder' => '', 'id' => 'g04_fecha'],
                   'pluginOptions' => [
                        'format' => 'dd/mm/yyyy',
                    ]
            ]) ?>

        <?= $form->field($model, 'g04_semana')->widget(Select2::classname(), [
                'data' => Pagos::getListSemanas(),
                'options' => ['placeholder' => '',  'id' => 'g04_semana'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>

        <div class="pull-right">
            <?= Html::a('Cancelar', ['/pagos'], ['class' => 'btn btn-default']) ?>
            <?= Html::submitButton(Yii::$app->params['btnGuardarPagos'] , ['class' => 'btn btn-primary']) ?>
        </div>

    <?php ActiveForm::end(); ?>

</div>
