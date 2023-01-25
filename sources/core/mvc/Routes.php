<?php

namespace Sources\Core\Mvc;

class Routes{

use RoutesMethods;
use \Sources\Core\Validations\Requests;

private static $Routes;
private static $currentRoute;
private static $Parameters;

static function getRoute(){
  self::formatUri();
  self::checkRoute();
  self::Execute();
}

//Format the URI
private static function formatUri(){
  $Uri  = explode('/', self::Get('uri'));
  self::$currentRoute = (!empty($Uri[0]) ? $Uri[0] : 'home');
  self::$Parameters = $Uri; array_shift(self::$Parameters);
}

//Check routes
private static function checkRoute(){
 
    if(!array_key_exists(self::$currentRoute, self::$Routes)){
      header('location: '.BASE.'/error');
    }   
}

//Ex: Route::setRoute('users',['importController','UsersController','pages'=>['add'=>'methodName]]);
static function setRoute($Route, $Parameters){
    self::$Routes[$Route] = $Parameters;
}

private static function Execute(){
  if(!empty(self::$Routes[self::$currentRoute][0]) && method_exists(self::class,self::$Routes[self::$currentRoute][0])){
    $Settings =  self::$Routes[self::$currentRoute]; array_shift($Settings); array_shift($Settings);
    self::{self::$Routes[self::$currentRoute][0]}(self::$Routes[self::$currentRoute][1],array_merge($Settings,['uri'=>self::$Parameters]));
  }
}

}