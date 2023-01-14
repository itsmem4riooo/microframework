<?php 

/**
 * Auxiate forms validation
 * @author Gabriel Teixeira
 */

namespace Sources\Classes\Validations;

class Validate{

  use ValidationMethods;

  //VALUES
  private $Values;

  //PARAMETERS VALIDATION
  private $Params;
  
  //ERROR
  private $Error;

  function ValidateValues(array $Values = null , array $Params = null){
      
    $this->Values = $Values;
    $this->Params = $Params;
    $this->Error  = false;
     
    if($this->checkTrigger()){
      return $this->checkValues();
    }  

  }

  private function checkValues(){

    foreach(array_keys($this->Values) as $Field){
      if(!$this->checkParams($Field)){
        return false;
      }
    }

    if($this->Error){
      return false;
    }

    return true;

  }
  
  private function checkTrigger(){
    return (!empty($this->Values) ? true : false);
  }

  //CHECK PARAMETERS OF EACH FIELD
  private function checkParams($Field){

    if(!isset($this->Params[$Field])){
      $this->Error = 'Invalid field: '.$Field;
      return false;
    }

    if(!$this->validateConditions($this->Params[$Field][0],$Field)){
      return false;
    }
    
    return true;
  }
  //VALIDATE CONDITIONS
  private function validateConditions($conditions,$Field){
    
    if(is_array($conditions)){

      foreach($conditions as $key => $value){
        
        if(!is_int($key)){
          $condition = $key;
          $Data = $value;
        }else{
          $condition = $value;
          $Data = null;
        }

        if(!method_exists($this,$condition)){
          $this->Error = 'Internal error: method '.$condition.' not exists!';
          return false;
        }
      
        if($this->{$condition}($this->Values[$Field],$this->Params[$Field]['title'],$Data) === false){
          $this->Error = $this->Message;
          return false;
        }
        
      }

    }
    return true;
  }

  function getError(){
    return $this->Error;
  }

  function getValues(){
    return $this->Values;   
  }
  

}