<?php

namespace Sources\Classes\Database;
use Sources\Classes\Validations\Requests;

class Pager extends Crud{

    private static $Data;
    private static $Page;
    private static $Table;
    private static $Parameters;
   
    static function List(string $Table, array $Parameters){

        self::$Table = (string) $Table;
        self::$Parameters = $Parameters;

        self::checkParameters();
        self::getPagesAndOffset();
        self::checkPage();
        self::Execute();

    }

    private static function checkParameters(){
      
        self::$Page = (!empty(self::$Parameters['page']) ? (int) self::$Parameters['page'] : (int) Requests::Get('page'));

        //SEARCH FILTERS AND CUSTOM CONDITIONS
        self::$Parameters['Values'] = (!empty(self::$Parameters['Values']) ? self::$Parameters['Values'] : []);

        //ITENS DISPLAY LIMIT
        self::$Parameters['Options']['Limit'] = (!empty(self::$Parameters['Options']['Limit']) ? (int) self::$Parameters['Options']['Limit']  : 10);

        //PAGES DISPLAY LIMIT AND ACCESS LINK
        self::$Parameters['link'] = (!empty(self::$Parameters['link'])     ? self::$Parameters['link'] : '&page=');
        self::$Parameters['max_links']  = (!empty($Parameters['maxLinks']) ? self::$Parameters['maxLinks'] : 5);

    }

    private static function getPagesAndOffset(){

        self::Query(self::getSyntax(),self::$Parameters['Values']);
        self::$Data['Rows']   = (self::getResult() ? self::getResult()[0]['rows_count'] : 0);
        self::$Data['num_pages'] = ceil(self::$Data['Rows'] / self::$Parameters['Options']['Limit']);

        if(self::$Page > 1){        
          self::$Parameters['Options']['Offset'] = (int)((self::$Page-1)*self::$Parameters['Options']['Limit']);
        }

    }

    private static function checkPage(){
      if(self::$Page > self::$Data['num_pages']){
        header('location: error');
      }
    }

    private static function getSyntax($Filters = null){

        if(!empty(self::$Parameters['Values']) && is_array(self::$Parameters['Values'])){
          foreach( array_keys(self::$Parameters['Values']) as $key){
            $Filters[] = "$key = :$key";
          }
          $Filters = ' WHERE '.implode(' AND ',$Filters);
        } 

        self::$Parameters['Options']['Condition'] = (!empty(self::$Parameters['Options']['Condition']) ? self::$Parameters['Options']['Condition'] : $Filters);
        return "SELECT COUNT(*) rows_count FROM users ".self::$Parameters['Options']['Condition'];

    }

    private static function Execute(){
        
        self::Read(self::$Table,self::$Parameters['Values'],self::$Parameters['Options']);
        var_dump(self::getResult());
    }

}
