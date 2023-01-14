<?php

namespace Sources\Application\Routes;

trait RouteMethods{

    protected static function importController($controller,$parameters){
        if(class_exists($controller = "\\Sources\\Application\\Controllers\\$controller")){
           $controller::execute($parameters);
        }
    }

    protected static function returnView($view){
        
    }

}