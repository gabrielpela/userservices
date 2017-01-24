<?php
return [
  'settings' => [
    'displayErrorDetails' => true,
    'logger' => [
      'name' => 'slim-app',
      'level' => 100,
      'path' => '/tmp/app.log',
    ],
    'db' => [
      'driver'    => 'mysql',
      'host'      => '192.168.33.11',
      'database'  => 'user',
      'username'  => 'test',
      'password'  => 'test',
      'charset'   => 'utf8',
      'collation' => 'utf8_general_ci',
      'prefix'    => '',
    ],
    "pathRoot"     => $_SERVER["DOCUMENT_ROOT"],
    "pathUpload"   => '/resources/user/'
  ],
];