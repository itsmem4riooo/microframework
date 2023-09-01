<?php 


require 'vendor/autoload.php';

Sources\Core\Database\Crud::ConnectDb();

define('THEME','agili');
define('CONTROLLER_DIR','exemple');
define('APP_DIR','./sources/application');
define('BASE','http://localhost/microframework');

?>