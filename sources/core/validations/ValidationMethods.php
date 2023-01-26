<?php

namespace Sources\Core\Validations;

use Sources\Core\Database\Crud;

trait ValidationMethods{
   
    protected static $Message;

    static function checkEmail($Parameters){
      if(!preg_match('/^[a-z0-9\.\_\-]+(\@){1}[a-z]{3,}+(\.){1}[a-z]{3,4}+[\.a-z]{0}/', $Parameters['value'])){
        self::$Message = "Invalid email";
        return false;
      }
    }

    static function max_length($Parameters){
      if(($length = strlen($Parameters['value'])) > $Parameters['data']){
        self::$Message = "Maximum number of characters reached on $Parameters[title], allowed: $Parameters[data], string length: $length";
        return false;
      }
    }

    static function min_length($Parameters){
      if(($length = strlen($Parameters['value'])) < $Parameters['data']){
        self::$Message = "Minimum number of characters not reached on $Parameters[title], required: $Parameters[data], string length: $length";
        return false;
      }
    }

    static function required($Parameters){
      if(empty($Parameters['value'])){
        self::$Message = "Please fill in the field $Parameters[title]";
        return false;
      }
    }

    static function regex($Parameters){
      if(!preg_match($Parameters['data'], $Parameters['value'])){
        self::$Message = "Invalid $Parameters[title]";
        return false;
      }
    }

    static function confirmPassword($Parameters){
      if($Parameters['value'] !== self::$Values[$Parameters['data']]){
        self::$Message = "Password don't match";
        return false;
      }
    }

    static function checkHtmlTags($Parameters){
      if(strip_tags($Parameters['value']) !== $Parameters['value']){
        self::$Message = "Invalid characters tags on $Parameters[title]";
        return false;
      }
    }

    static function format_password($Parameters){
      self::$Values[$Parameters['key']] = password_hash(self::$Values[$Parameters['key']],PASSWORD_DEFAULT);
    }

    static function delete_field($Parameters){
      unset(self::$Values[$Parameters['key']]);
    }

    static function accept($Parameters){
      if(!in_array($Parameters['value'],$Parameters['data'])){
        self::$Message = "Value invalid on $Parameters[title]";
        return false;
      }
    }

    static function exist($Parameters){

      Crud::Read($Parameters['data'][0],[$Parameters['data'][1] => $Parameters['value']]);

      if(Crud::getRowCount() < 1){
        return false;
      }

      if(in_array('save',$Parameters['data'])){
        self::$Values[$Parameters['key']] = Crud::getResult();
      }

    }

    static function dbcheck($Parameters){

      Crud::Read($Parameters['data'][0],[$Parameters['data'][1] => $Parameters['value']]);

      if(Crud::getRowCount() > 0){
        self::$Message = "$Parameters[title] already exists!";
        return (!empty($Parameters['data']['exception']) && $Parameters['value'] == $Parameters['data']['exception'] ? true :  false);
      }

    }

}

?>