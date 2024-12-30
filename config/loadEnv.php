<?php
require __DIR__.'/../vendor/autoload.php';


$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../');
$dotenv->load();
return [
    'host'=>$_ENV['DB_HOST'],
    'dbname'=>$_ENV['DB_NAME'],
    'user'=>$_ENV['DB_USER'],
    'pass'=>$_ENV['DB_PASS']
]

?>