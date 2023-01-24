<?php

use Sources\Classes\Mvc\Routes;

Routes::setRoute('home',['returnView','Home','user'=>'Gabriel']);
Routes::setRoute('users',['importController','Users','pages'=>['int'=>'index']]);
Routes::setRoute('error',['returnView','Error']);

Routes::getRoute();
