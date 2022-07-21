<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

use common\models\User;
use common\models\Movimientos;
use common\models\Utilerias;

/* @var $this yii\web\View */
/* @var $model app\models\Movimientos */

$this->title = Yii::$app->params['txtTitleViewMovimientos'] . $model->c01_id;
$this->params['breadcrumbs'][] = ['label' => Yii::$app->params['txtTitleIndexMovimientos'], 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="movimientos-view">

    <div class="col-md-12"> 
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'c01_id',
                //'c01_tipo',
                [
                    'format' => 'raw',
                    'attribute' => 'c01_tipo',
                    'value' => function($model){
                        return Movimientos::getMovimiento($model->c01_tipo);
                    },
                ],
                //'c01_id_tabla',
                 [
                    'format' => 'raw',
                    'attribute' => 'c01_tabla',
                    'value' => function($model){
                        $array = explode("_", $model->c01_tabla);
                        return strtoupper($array[1]);
                    }
                ],
                //'c01_tabla:ntext',
                //'user_id',
                [
                    'format' => 'raw',
                    'attribute' => 'user_id',
                    'value' => function($model){
                        return User::getNameUser($model->user_id);
                    },
                ],
                //'c01_date',
                [
                    'format' => 'raw',
                    'attribute' => 'c01_date',
                    'value' => function($model){
                        return Utilerias::convertTimeStampToFecha($model->c01_date);
                    },
                ],
                //'c01_old_row',
                //'c01_new_row'
            ],
        ]) ?>
    </div>


    <div class="col-md-12">
        <div class="col-md-6">
            <div align="left">
                <h4 class="box-title">Valores anteriores</h4>
            </div>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Campo</th>
                        <th>Valor</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach (json_decode($model->c01_old_row) as $key => $value) {
                        ?>
                        <tr>
                            <td><?= Utilerias::getLabelModel($model->c01_tabla,$key) ?></td>
                            <td><?= $value ?></td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <div class="col-md-6">
            <div align="left">
                <h4 class="box-title">Valores nuevos</h4>
            </div>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Campo</th>
                        <th>Valor</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach (json_decode($model->c01_new_row) as $key => $value) {
                        ?>
                        <tr>
                            <td><?= Utilerias::getLabelModel($model->c01_tabla,$key) ?></td>
                            <td><?= $value ?></td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
