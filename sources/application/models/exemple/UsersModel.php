<?php 

namespace Sources\Application\Models\Exemple;

use Sources\Core\Database\Crud;
use Sources\Core\Helpers\Upload;
use Sources\Core\Validations\Validate;

class UsersModel{

    use \Sources\Core\Validations\Requests;

    private static $Data = ['export' => null];
    private static $Values;
   
    private static $Parameters = [
        'first_name'    => [ 'title'=>'First Name', ['required','max_length'=>25,'checkHtmlTags'] ],
        'last_name'     => [ 'title'=>'Last Name', ['max_length'=>25,'checkHtmlTags'] ],
        'nickname'      => [ 'title'=>'Nickname', ['required']],
        'active'        => [ 'title'=>'Status',['accept'=>['0','1']]],
        'login'         => [ 'title'=>'Login', ['required','regex'=>'/^[A-Za-z0-9_\.]*$/','dbcheck'=>['users','login']]],
        'email'         => [ 'title'=>'Email', ['required','checkEmail','dbcheck'=>['users','email']]],
        'pass'          => [ 'title'=>'Password', ['required','confirmPassword'=>'confirm_pass','format_password']],
        'confirm_pass'  => [ 'title'=>'Confirm Password', ['required','delete_field']]
    ];

    static function addUser(){

        if(self::Validate()){
            Crud::Create('users',self::$Values);
            self::$Data['export']['validate_message'] = ['class' => 'success','msg'=>'User registered successfully!'];
        }

        self::getStatus([self::Post('active')]);

    }

    static function editUser($User = ['user'=>'0']){

      self::checkUser($User);

      self::$Data['user']   = Validate::getValues()['user'][0];
      self::$Data['export'] = self::$Data['user'];

      unset(self::$Parameters['pass'][0][0]);
      unset(self::$Parameters['confirm_pass'][0][0]);

      self::$Parameters['login'][0]['dbcheck']['exception'] = self::$Data['user']['login'];
      self::$Parameters['email'][0]['dbcheck']['exception'] = self::$Data['user']['email'];

      if(self::Validate()){
        self::UpdateUser(); 
      } 

      self::getStatus([self::$Data['user']['active'],self::Post('active')]);

    }

    private static function Validate(){

        if(Validate::ValidateValues(self::Post(),self::$Parameters) === true){
        
          self::$Values = Validate::getValues();  
          return (!self::checkThumb() ? false : true);

        }

        if(Validate::getError()){
           self::$Data['export'] = self::Post();
           self::$Data['export']['validate_message'] = ['msg'=>Validate::getError(),'class'=>'danger'];
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
      self::$Data['export']['status'] = 
      [ 
        ['id'=>'1','title'=>'Active'],
        ['id'=>'0','title'=>'Inactive'],
        'Parameters'=>[ 'include'=> ['key'=>'id','value'=>$Status,'tag'=>' selected','offset'=>'<option'] ]
      ];
    }

    private static function updateUser(){

      if(!empty(self::$Values['thumb']) && !empty(self::$Data['user']['thumb'])){
        unlink(self::$Data['user']['thumb']);
      }

      Crud::Update('users',self::$Values,['id'=>self::$Data['user']['id']]);

      self::$Data['export'] = self::$Values;
      self::$Data['export']['validate_message'] = ['class' => 'success','msg'=>'User updated successfully!'];

    }

    private static function checkUser($User){
      if(Validate::ValidateValues($User,['user'=>[['exist'=>['users','id','save']]]]) === false){
        header('location: '.BASE.'/error');
      }
    }

    static function deleteUser($User){

      self::checkUser($User);
      self::$Data['export'] = Validate::getValues()['user'][0];

      if(self::Post('delete')){
        Crud::Delete('users',['id'=>self::$Data['export']['id']]);
        header('location: '.BASE.'/users');
      }

    }

    static function getData($key){
      return (!empty(self::$Data[$key]) ? self::$Data[$key] : null);  
    }

}