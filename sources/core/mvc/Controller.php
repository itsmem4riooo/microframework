<?php

namespace Sources\Core\Mvc;

trait Controller{

   static function execute($parameters){
      if($method = self::checkMethod($parameters)){
        self::{$method}($parameters['uri']);
      }
    }

    protected static function checkMethod($Method){
        
        $Method['pages'] = (!empty($Method['pages']) ? $Method['pages'] : []);

        if(empty($Method['uri'][0]) && method_exists(self::class,'index')){
          return 'index';
        }
        if(is_numeric($Method['uri'][0]) && isset($Method['pages']['int']) && method_exists(self::class,$Method['pages']['int'])){
          return $Method['pages']['int'];
        }
        if((!empty($Method['pages'][$Method['uri'][0]]) && method_exists(self::class,$Method['pages'][$Method['uri'][0]])) || (!in_array($Method['uri'][0],array_values($Method['pages'])) && method_exists(self::class,$Method['pages'][$Method['uri'][0]] = $Method['uri'][0])) ){
          return $Method['pages'][$Method['uri'][0]];
        }

    }


}