<?php

namespace Sources\Classes\Validations;

trait ValidationMethods{
   
    protected $Message;

    public function checkEmail($Value){
      if(!preg_match('/^[a-z0-9\.\_\-]+(\@){1}[a-z]{3,}+(\.){1}[a-z]{3,4}+[\.a-z]{0}/', $Value)){
        $this->Message = "Invalid email";
        return false;
      }
    }

    public function max_length($Value,$Title,$Data){
      if(($length = strlen($Value)) > $Data){
        $this->Message = "Maximum number of characters reached on $Title, allowed: $Data, string length: $length";
        return false;
      }
    }

    public function min_length($Value,$Title,$Data){
      if(($length = strlen($Value)) < $Data){
        $this->Message = "Minimum number of characters not reached on $Title, required: $Data, string length: $length";
        return false;
      }
    }

    public function required($Value,$Title){
      if(empty($Value)){
        $this->Message = "Please fill in the field $Title";
        return false;
      }
    }

    public function regex($Value,$Title,$Data){
      if(!preg_match($Data, $Value)){
        $this->Message = "Invalid $Title";
        return false;
      }
    }

    public function confirmPassword($Value,$Title,$Data){
      if($Value !== $this->Values[$Data]){
        $this->Message = "Password don't match";
        return false;
      }
    }

    public function checkHtmlTags($Value,$Title){
      if(strip_tags($Value) !== $Value){
        $this->Message = "Invalid characters tags on $Title";
        return false;
      }
    }

}

?>