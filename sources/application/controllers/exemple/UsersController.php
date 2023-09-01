<?php

namespace Sources\Application\Controllers\Exemple;

use Sources\Core\Mvc\View;
use Sources\Core\Database\Pager;
use Sources\Application\Models\Exemple\UsersModel;

class UsersController{
    
    use \Sources\Core\Mvc\Controller;

    static function index($parameters){

        $listUsersParams = [
          'Parameters' => 
            [ 
              'include' => 
              [
                ['key'=>'thumb' ,'tag'=>'sources/application/views/themes/'.THEME.'/styles/images/person.png','offset'=>'src="http://localhost/microframework/','value'=>[NULL]],
                ['key'=>'active','tag'=>' bg-danger"','offset'=>'class="circle','value'=>[0]]
              ],
              'change' => ['active'=>['0'=>'Inactive','1'=>'Active']],
            ]
        ];
        
       Pager::List("users",['max_links'=>1,'link'=>BASE.'/users/','page'=>@$parameters[0]]);
       View::setView('users/ListUsers',['users'=>array_merge(Pager::getResult(),$listUsersParams),'styles'=>['file'=>'listusers']]);

    }

    static function add(){

        UsersModel::addUser();
        View::setView('users/Form', UsersModel::getData()); 

    }
    
}

?>