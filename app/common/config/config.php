<?php
return array(

    'AUTO_SESSION'=>false,//是否全局打开session

    'DB_LINK'=>[
        'default'=>[
            'DB_TYPE' => 'MysqlPdo',
            'DB_HOST' => '127.0.0.1',
            'DB_USER' => 'root',
            'DB_PWD' => '',
            'DB_PORT' => 3306,
            'DB_NAME' => 'test',
            'DB_CHARSET' => 'utf8',
            'DB_PREFIX' => 'wx_',
            'DB_CACHE' => 'db',
            'DB_SLAVE' => array(),
        ]
    ],

);