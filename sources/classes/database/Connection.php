<?php

/**
 * data base connection class
 * @author Gabriel Texeira
 */

namespace Sources\Classes\Database;

abstract class Connection
{	
 
 private static $Dns 	= "mysql:host=localhost;dbname=teste";
 private static $User 	= "root";
 private static $Pass 	= "";
 protected static $Conn   = null;

 protected static function Start(){
		
 		try{
 			if(self::$Conn == null){
 				self::$Conn =  new \PDO(self::$Dns , self::$User , self::$Pass , [\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8']);
			}
 		}catch(\PDOException $e){
 			echo "Error :".$e->getMessage();
 		}

 }

}
?>
