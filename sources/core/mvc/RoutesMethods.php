<?php

namespace Sources\Core\Mvc;

trait RoutesMethods{

    protected static function importController($controller,$parameters){
        if(class_exists($controller = "\\Sources\\Application\\Controllers\\".ucfirst(CONTROLLER_DIR)."\\$controller")){
           $controller::execute($parameters);
        }
    }

    protected static function returnView($view,$data = array()){
        View::setView($view,$data);
    }

}