<?php 


require 'vendor/autoload.php';

Sources\Classes\Database\Crud::ConnectDb();

define('THEME','exemple');
define('CONTROLLER_DIR','exemple');
define('APP_DIR','./sources/application');
define('BASE','http://localhost/microframework/');

?>