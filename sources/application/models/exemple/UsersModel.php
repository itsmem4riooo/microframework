<?php 

namespace Sources\Application\Models\Exemple;

use Sources\Core\Database\Crud;
use Sources\Core\Helpers\Upload;
use Sources\Core\Validations\Validate;

class UsersModel{

    use \Sources\Core\Validations\Requests;

    private static $Data;
    private static $Values;
   
    private static $Parameters = [
        'first_name'    => [ 'title'=>'First Name', ['required','max_length'=>25,'checkHtmlTags'] ],
        'last_name'     => [ 'title'=>'Last Name', ['max_length'=>25,'checkHtmlTags'] ],
        'nickname'      => [ 'title'=>'Nickname', ['required']],
        'active'        => [ 'title'=>'Status',['required','accept'=>['0','1']]],
        'login'         => [ 'title'=>'Login', ['required','regex'=>'/^[A-Za-z0-9]*$/',]],
        'email'         => [ 'title'=>'Email', ['required','checkEmail']],
        'pass'          => [ 'title'=>'Password', ['required','confirmPassword'=>'confirm_pass','min_length'=>10,'format_password']],
        'confirm_pass'  => [ 'title'=>'Confirm Password', ['required','min_length'=>10,'delete_field']]
    ];

    static function addUser(){
        
        if(self::ValidateValues()){
            Crud::Create('users',self::$Values);
            self::$Data['validate_message'] = ['class' => 'success','msg'=>'User registered successfully!'];
        }

        self::getStatus([self::Post('active')]);

    }

    private static function ValidateValues(){

        if(Validate::ValidateValues(self::$Data = self::Post(),self::$Parameters) === true){
        
          self::$Values = Validate::getValues();  

          if(!self::checkThumb()){
            return false;
          }

          return true;  

        }

        if(Validate::getError()){
           self::$Data['validate_message'] = ['msg'=>Validate::getError(),'class'=>'danger'];
        }

        return false;  

    }

    private static function checkThumb(){

      if(self::File('thumb')){

        $Upload = new Upload();
        $Upload->image(self::File('thumb'),1000,['image/png','image/jpeg','image/webp'],200);

        if($Upload->getError()){
          self::$Data['validate_message'] = ['msg'=>$Upload->getError(),'class'=>'danger'];
          return false;
        }

        self::$Values['thumb'] = $Upload->getFile()['name'];
        
      }
      return true;

    }

    private static function getStatus(array $Status){
      self::$Data['status'] = 
      [ 
        ['id'=>'1','title'=>'Active'],
        ['id'=>'0','title'=>'Inactive'],
        'Parameters'=>[ 'include'=> ['key'=>'id','value'=>$Status,'tag'=>'selected','offset'=>'<option'] ]
      ];
    }

    static function getData(){
      return self::$Data;  
    }

}