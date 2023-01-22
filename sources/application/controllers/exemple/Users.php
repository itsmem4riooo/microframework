<?php

namespace Sources\Application\Controllers\Exemple;

use Sources\Classes\Database\Pager;
use Sources\Application\Controllers\ControllerMethods;
use Sources\Application\Views\View;

class Users{
    
    use ControllerMethods;

    static function listUsers(){
        
       Pager::List("users",['max_links'=>2,'link'=>'usuarios&page=','Options'=> ['Limit'=>1]]);
       View::setView('users/ListUsers',['users'=>Pager::getResult()]);
       
    }

    static function add(){
        
    }
    
}

?>