<?php

return [
    'components' => [
        'db' => [
            'class' => \yii\db\Connection::class,
            'dsn' => 'mysql:host=localhost;dbname=yii2advanced',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
        ],
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@common/mail',
            // send all mails to a file by default.
            'useFileTransport' => true,
            // You have to set
            //
            // 'useFileTransport' => false,
            //
            // and configure a transport for the mailer to send real emails.
            //
            // SMTP server example:
               'transport' => [
                   'scheme' => 'smtps',
                   'host' => 'smtp.titan.email',
                   'username' => 'sales@businessonly.nl',
                   'password' => 'Geldzien10!',
                   'port' => 465,
                   'dsn' => 'native://default',
               ],
            //
            // DSN example:
            //    'transport' => [
            //        'dsn' => 'smtp://sales@businessonly.nl:Geldzien10!@smtp.titan.email:25',
            //    ],
            //
            // See: https://symfony.com/doc/current/mailer.html#using-built-in-transports
            // Or if you use a 3rd party service, see:
            // https://symfony.com/doc/current/mailer.html#using-a-3rd-party-transport
        ],
    ],
];
