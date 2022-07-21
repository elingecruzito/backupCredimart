<?php
use yii\helpers\Html;
use common\models\Notificaciones;

/* @var $this \yii\web\View */
/* @var $content string */
?>

<header class="main-header">

    <?= Html::a('<span class="logo-mini"></span><span class="logo-lg">' . Html::img(Yii::$app->homeUrl . 'img/logo.png',['width' => '140px']) . '</span>', Yii::$app->homeUrl, ['class' => 'logo']) ?>

    <nav class="navbar navbar-static-top" role="navigation">

        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">

            <ul class="nav navbar-nav">
                <li class="dropdown notifications-menu">
                    <a href="#" class="dropdown-toggle" onclick="desactivarNotificaciones(<?= Yii::$app->user->id ?>)" data-toggle="dropdown">
                        <i class="fa fa-bell-o"></i>
                        <span class="label label-primary" id="numeroNotificaciones"><?= Notificaciones::getTotalNuevas(Yii::$app->user->id) ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header">Tienes <?= Notificaciones::getTotalNuevas(Yii::$app->user->id) ?> notificaciones nuevas</li>
                        <li>
                            <!-- inner menu: contains the actual data -->
                            <ul class="menu">

                                <?php 
                                foreach (Notificaciones::getNuevasNotificaciones(Yii::$app->user->id) as $value) {
                                    ?>
                                    <li>
                                        <a href="#">
                                            <i class="<?= Notificaciones::getIconNotificacion($value->g06_tipo) ?>"></i> <?= $value->g06_message ?>
                                        </a>
                                    </li>
                                    <?php
                                }
                                ?>
                            </ul>
                        </li>
                        <li class="footer"><?= Html::a('Ver todas', ['/notificaciones']) ?></li>
                    </ul>
                </li>
                <!-- User Account: style can be found in dropdown.less -->

                <li class="dropdown user user-menu">
                     <?= Html::a(
                        '<i class="fa fa-sign-out" aria-hidden="true"></i>',
                        ['/site/logout'],
                        ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']
                    ) ?>
                </li>
            </ul>
        </div>
    </nav>
</header>
