<?php

namespace Sources\Application\Routes;

class Route{

use RouteMethods;
use \Sources\Classes\Validations\Requests;

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
  self::$Parameters = $Uri; unset(self::$Parameters[0]);
}

//Check routes
private static function checkRoute(){
 
    if(!array_key_exists(self::$currentRoute, self::$Routes)){
      http_response_code(404);
      return false;
    }   
}

//Ex: Route::setRoute('users',['importController','UsersController']);
static function setRoute($Route, $Parameters){
    self::$Routes[$Route] = $Parameters;
}

private static function Execute(){
  if(!empty(self::$Routes[self::$currentRoute][0]) && method_exists(self::class,self::$Routes[self::$currentRoute][0])){
    self::{self::$Routes[self::$currentRoute][0]}(self::$Routes[self::$currentRoute][1],self::$Parameters);
  }
}

}