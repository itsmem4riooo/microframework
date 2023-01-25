<?php

namespace Sources\Core\Mvc;

class View{

    private static $Data = [
      'base' => BASE,
      'b_theme'  => BASE.'/sources/application/views/themes/'.THEME.'/'
    ];
    private static $Content = null;

    static function setView(string $Page,array $Data){
        self::setData($Data);
        self::$Content .= self::fileGetContent($Page); 
    }

    static function Render($layout = 'mainLayout'){

      self::$Content  = str_replace('@content', self::$Content ,self::fileGetContent('mainLayout','layout_contents'));
        
      foreach(self::$Data as $Key => $Value){
        self::$Content = self::{(is_array($Value) ?  'replaceArray' : 'replaceSingleValue')}(self::$Content,$Key,$Value);
      }
      
      self::removeEmptyValues();
      echo self::$Content;

    }


    static function replaceArray($mainContent,$Key,$Value){

        $Parameters = null;
        $replacedContent = null;
        $Count  = substr_count(self::$Content,'{{'.$Key.'-');

        if(!empty($Value['Parameters'])){ 
            $Parameters = $Value['Parameters'];
            unset($Value['Parameters']); 
        }
        
        for($i = 0; $i < $Count; $i++){
            $listContent      = self::getContent($mainContent,'{{'.$Key.'-','}}');
            $replacedContent  = self::setContent($listContent,$Value,$Parameters);
            $mainContent = str_replace('{{'.$Key.'-'.$listContent.'}}',$replacedContent,$mainContent);
        }
        
        return $mainContent;
        
    }

    private static function replaceSingleValue($Content,$Key,$Value){
        return str_replace('{{'.$Key.'}}',$Value,$Content);
    }

    private static function getContent($Content,$Key,$Concat = null){
        $Concat = ($Concat ? $Concat : $Key);
        $cutX   =  strpos($Content,$Key)+strlen($Key);  
        $cutY   =  strpos($Content,$Concat,$cutX);  
        return substr($Content,$cutX,$cutY-$cutX);
    }

    static function setContent($Content,$Data,$Parameters = null,$replacedContent = []){
        
        $Data = (!isset($Data[0]) ? [$Data] : $Data);
        
        for($i = 0;$i < count($Data);$i++){
          foreach($Data[$i] as $search => $replace){ 
           $Filter = self::filterData(($Parameters ? $Parameters :[]),[$search,$replace],
           (!empty($replacedContent[$i]) ? $replacedContent[$i] : $Content)); 
           $replacedContent[$i] = str_replace('#'.$search.'#',$Filter['Value'],$Filter['Content']);
          } 
        }
        return implode('',$replacedContent);
    }

    static function fileGetContent($File,$Dir = 'pages_contents',$Extension = '.view.html'){
       return (file_exists($Content = APP_DIR.'/views/themes/'.THEME.'/'.$Dir.'/'.$File.$Extension) ?  file_get_contents($Content) : null); 
    }

    private static function filterData($Parameters,$Data,$Content){
      
        foreach($Parameters as $parameter => $values){

           switch($parameter){

             case 'change':
                
                if(isset($values[$Data[0]]) && array_key_exists($Data[1],$values[$Data[0]])){
                    $Data[1] = $values[$Data[0]][$Data[1]];
                }
           
             break;

             case 'include':

                $values = (!empty($values[0]) ? $values : [$values]);

                foreach($values as $value){
                  if(((string)$Data[0] == (string)$value['key']) && in_array($Data[1],$value['value'])){
                      $offset = strpos($Content,$value['offset'])+strlen($value['offset']);
                      $Content = substr_replace($Content, ' '.$value['tag'] , $offset , 0 ); 
                  }
                }

             break;   

           }

        }
        
        return ['Value' => $Data[1], 'Content' => $Content];

    }

    private static function removeEmptyValues(){

        $Count = substr_count(self::$Content,'{{'); 
        
        for($i = 0; $i < $Count ; $i++){
          $key = self::getContent(self::$Content,'{{','}}');
          self::$Content   = str_replace('{{'.$key.'}}','',self::$Content);
        }

    }

    static function setData($Data){
      foreach($Data as $key => $val){
        self::$Data[$key] = $val;
      }
    }

}