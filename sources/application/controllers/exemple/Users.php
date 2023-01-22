<?php

namespace Sources\Application\Controllers\Exemple;
use Sources\Application\Controllers\ControllerMethods;
use Sources\Classes\Database\Pager;

class Users{
    
    use ControllerMethods;

    static function listUsers(){
        
       Pager::List("users",['Options'=>['Limit'=>'1']]);

       
    }

    static function add(){
        
    }
    
}

?>