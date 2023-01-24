<?php

namespace Sources\Application\Controllers\Exemple;

use Sources\Classes\Mvc\View;
use Sources\Classes\Database\Pager;

class Users{
    
    use \Sources\Classes\Mvc\Controller;

    static function listUsers($parameters){

       Pager::List("users",['max_links'=>1,'Options'=>['Limit'=>2],'link'=>BASE.'usuarios/','page'=>@$parameters[0]]);
       View::setView('users/ListUsers',['users'=>Pager::getResult()]);
       
    }

    static function add(){
        
    }
    
}

?>