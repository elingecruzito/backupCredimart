<?php

namespace common\models;

use Yii;
use common\models\User;

/**
 * This is the model class for table "g05_perfiles".
 *
 * @property int $g05_id ID
 * @property int $user_id Usuario
 * @property int $g05_tipo Tipo
 */
class Perfiles extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'g05_perfiles';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'g05_tipo'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'g05_id' => 'ID',
            'user_id' => 'Usuario',
            'g05_tipo' => 'Tipo',
        ];
    }

    public static function getTypeUser($id){
        $model = Perfiles::findOne(['user_id' => $id]);
        return User::getListTipoUsuarios()[$model->g05_tipo];
    }

    public static function findModel($user_id){
        return Perfiles::findOne(['user_id' => $user_id]);
    }    

    public static function getAccess($route, $user_id){
        $model = Perfiles::findModel($user_id);
        $routes = [];

        switch ($model->g05_tipo) {
            case User::TIPO_ADMINISTRADOR: return true; break;
            case User::TIPO_SOLO_CLIENTES: $routes = Yii::$app->params['routes_solo_clientes']; break;
            case User::TIPO_SOLO_PAGOS: $routes = Yii::$app->params['routes_solo_pagos']; break;
        }

        return in_array($route, $routes);
    }

    public static function getMenuOptions(){
        $model = Perfiles::findModel(Yii::$app->user->id);

        $clientes = [
                        'label' => 'Clientes',
                        'icon' => 'address-book-o',
                        'url' => '#',
                        'items' => [
                            ['label' => 'Lista', 'icon' => 'list-alt', 'url' => ['/clientes'],],
                            ['label' => 'Agregar', 'icon' => 'user-plus', 'url' => ['/clientes/create'],],
                        ]
                    ];
        $pagos = [
                        'label' => 'Pagos',
                        'icon' => 'money',
                        'url' => '#',
                        'items' => [
                            ['label' => 'Lista', 'icon' => 'list-alt', 'url' => ['/pagos'],],
                            ['label' => 'Agregar', 'icon' => 'plus', 'url' => ['/pagos/create'],],
                        ]
                    ];

        $contraseña = ['label' => 'Cambiar contraseña', 'icon' => 'key', 'url' => ['/site/change-password']];
            
        $menu = [
                    ['label' => 'Menu', 'options' => ['class' => 'header']],
                    [
                        'label' => 'Promotores',
                        'icon' => 'users',
                        'url' => '#',
                        'items' => [
                            ['label' => 'Lista', 'icon' => 'list-alt', 'url' => ['/promotores'],],
                            ['label' => 'Agregar', 'icon' => 'user-plus', 'url' => ['/promotores/create'],],
                        ]
                    ],
                    $clientes,
                    [
                        'label' => 'Prestamos',
                        'icon' => 'credit-card',
                        'url' => '#',
                        'items' => [
                            ['label' => 'Lista', 'icon' => 'list-alt', 'url' => ['/prestamos'],],
                            ['label' => 'Agregar', 'icon' => 'plus', 'url' => ['/prestamos/create'],],
                        ]
                    ],
                    $pagos,
                    ['label' => 'Movimientos', 'icon' => 'gears', 'url' => ['/movimientos'],],
                    [
                        'label' => 'Usuarios',
                        'icon' => 'users',
                        'url' => '#',
                        'items' => [
                            ['label' => 'Lista', 'icon' => 'list-alt', 'url' => ['/user'],],
                            ['label' => 'Agregar', 'icon' => 'user-plus', 'url' => ['/user/create'],],
                        ]
                    ],
                    $contraseña,
                    ['label' => 'Reporte test', 'icon' => '', 'url' => ['/test/reportev2'],],
                    //['label' => 'Email test', 'icon' => '', 'url' => ['/test/send-email'],],
                    //['label' => 'Generate query', 'icon' => '', 'url' => ['/test/generate-query-bitacora'],],
                    //['label' => 'Cerrar prestamos', 'icon' => '', 'url' => ['/test/close-prestamos'],],
                    //['label' => 'Notificaciones', 'icon' => '', 'url' => ['/test/notificaciones'],],
                ];

        if(Yii::$app->user->isGuest)
            return $menu;

        switch ($model->g05_tipo) {
            case User::TIPO_SOLO_CLIENTES: 
                $menu = [
                    ['label' => 'Menu', 'options' => ['class' => 'header']],
                    $clientes,
                    $contraseña
                ];
            break;
            case User::TIPO_SOLO_PAGOS:
                $menu = [
                    ['label' => 'Menu', 'options' => ['class' => 'header']],
                    $pagos,
                    $contraseña,
                ];
            break;
        }

        return $menu;
    }
}
