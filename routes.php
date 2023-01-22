<?php

use Sources\Application\Routes\Route;

Route::setRoute('home',['returnView','home']);
Route::setRoute('usuarios',['importController','Users','pages'=>['adicionar'=>'add','empty'=>'listUsers','ver'=>'listUsers']]);
Route::setRoute('error',['returnView','Error']);

Route::getRoute();
