<?php

use Sources\Application\Routes\Route;

Route::setRoute('home',['returnView','home']);
Route::setRoute('usuarios',['importController','Users','pages'=>['adicionar'=>'add','empty'=>'listUsers','int'=>'listUsers']]);
Route::setRoute('error',['returnView','Error']);

Route::getRoute();
