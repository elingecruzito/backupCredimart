<?php
return [
    'components' => [
        
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=credimart',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
        ],
        /*        
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=credimar_credimart',
            'username' => 'credimar_andres',
            'password' => '59xNLVO0',
            'charset' => 'utf8',
        ],
        */
        
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => false,
        ],
    ],
];
