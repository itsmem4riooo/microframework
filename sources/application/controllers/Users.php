<?php

namespace Sources\Application\Controllers;
use Sources\Application\Views\View;

class Users implements ControllerInterface{

    use ControllerMethods;

    static function listUsers($Data){
        $Parameters = ['include'=>['tag'=>'selected','key'=>'id','value'=>[1],'offset'=>'<option'] ];
        View::setView('users/ListUsers',['name'=>'Gabriel','users'=> [['id'=>'0','name'=>'Gabriel'],['id'=>'1','name'=>'Bruno'],
        'Parameters'=>$Parameters]]); 
    }

    static function add(){
   
    }
    
}

?>