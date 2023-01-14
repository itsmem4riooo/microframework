<?php

use Sources\Application\Routes\Route;

Route::setRoute('home',['returnView','home']);
Route::setRoute('contact',['returnView','contact']);
Route::setRoute('users',['importController','Users']);

Route::getRoute();
