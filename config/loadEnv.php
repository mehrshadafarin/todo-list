<?php
require 'vendor/autoload.php';


$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$env = $dotenv->load();

return [
    'host'=>$env['DB_HOST'],
    'dbname'=>$env['DB_NAME'],
    'user'=>$env['DB_USER'],
    'pass'=>$env['DB_PASS']
]

?>