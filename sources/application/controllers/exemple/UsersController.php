<?php

namespace Sources\Application\Controllers\Exemple;

use Sources\Core\Mvc\View;
use Sources\Core\Database\Pager;
use Sources\Application\Models\Exemple\UsersModel;

class UsersController{
    
    use \Sources\Core\Mvc\Controller;

    static function index($parameters){

        $listUsersParams = [
            'Parameters' => ['include' => ['key'=>'thumb','tag'=>'style="display:none"','offset'=>'<img','value'=>[NULL]]]
        ];
        
       Pager::List("users",['max_links'=>1,'link'=>BASE.'/users/','page'=>@$parameters[0]]);
       View::setView('users/ListUsers',['users'=>array_merge(Pager::getResult(),$listUsersParams),'styles'=>['file'=>'listusers']]);

    }

    static function add(){

        UsersModel::addUser();
        View::setView('users/Form',UsersModel::getData()); 

    }
    
}

?>