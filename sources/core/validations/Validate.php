<?php 

/**
 * Auxiate forms validation
 * @author Gabriel Teixeira
 */

namespace Sources\Core\Validations;

class Validate{

  use ValidationMethods;

  //VALUES
  private static $Values;

  //PARAMETERS VALIDATION
  private static $Params;
  
  //ERROR
  private static $Error;

  static function ValidateValues(array $Values = null , array $Params = null){
      
    self::$Values = $Values;
    self::$Params = $Params;
    self::$Error  = false;
     
    if(self::checkTrigger()){
      return self::checkValues();
    }  

  }

  private static function checkValues(){

    foreach(array_keys(self::$Values) as $Field){
      if(!self::checkParams($Field)){
        return false;
      }
    }

    if(self::$Error){
      return false;
    }

    return true;

  }
  
  private static function checkTrigger(){
    return (!empty(self::$Values) ? true : false);
  }

  //CHECK PARAMETERS OF EACH FIELD
  private static function checkParams($Field){

    if(!isset(self::$Params[$Field])){
      self::$Error = 'Invalid field: '.$Field;
      return false;
    }

    if(!self::validateConditions(self::$Params[$Field][0],$Field)){
      return false;
    }
    
    return true;
  }
  //VALIDATE CONDITIONS
  private static function validateConditions($conditions,$Field){
    
    if(is_array($conditions)){

      foreach($conditions as $key => $value){
        
        if(!is_int($key)){
          $condition = $key;
          $Data = $value;
        }else{
          $condition = $value;
          $Data = null;
        }

        if(!method_exists(self::class,$condition)){
          self::$Error = 'Internal error: method '.$condition.' not exists!';
          return false;
        }
      
        if(self::{$condition}(['value'=> self::$Values[$Field],'title'=> @self::$Params[$Field]['title'],'data'=>$Data,'key'=>$Field]) === false){
          self::$Error = self::$Message;
          return false;
        }
        
      }

    }
    return true;
  }

  static function getError(){
    return self::$Error;
  }

  static function getValues(){
    return self::$Values;   
  }
  

}