<?php

use Sources\Core\Mvc\Routes;

Routes::setRoute('home',['returnView','Home','user'=>'Gabriel']);
Routes::setRoute('users',['importController','UsersController','pages'=>['int'=>'index']]);
Routes::setRoute('error',['returnView','Error']);

Routes::getRoute();
