<?php

use Sources\Classes\Mvc\Routes;

Routes::setRoute('home',['returnView','Home','user'=>'Gabriel']);
Routes::setRoute('usuarios',['importController','Users','pages'=>['adicionar'=>'add','empty'=>'listUsers','int'=>'listUsers']]);
Routes::setRoute('error',['returnView','Error']);

Routes::getRoute();
