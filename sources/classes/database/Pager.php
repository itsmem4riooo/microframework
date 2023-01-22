<?php

namespace Sources\Classes\Database;

use Sources\Application\Views\View;
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
        
        if(self::$Data['rows'] > 0){
          self::Read(self::$Table,self::$Parameters['Values'],self::$Parameters['Options']);
          self::pagesView();
        }

    }

    private static function checkParameters(){
      
        self::$Page = (!empty(self::$Parameters['page']) ? (int) self::$Parameters['page'] : (int) Requests::Get('page'));
        self::$Page = (self::$Page > 0 ? self::$Page : 1);

        //SEARCH FILTERS AND CUSTOM CONDITIONS
        self::$Parameters['Values'] = (!empty(self::$Parameters['Values']) ? self::$Parameters['Values'] : []);

        //ITENS DISPLAY LIMIT
        self::$Parameters['Options']['Limit'] = (!empty(self::$Parameters['Options']['Limit']) ? (int) self::$Parameters['Options']['Limit']  : 10);

        //PAGES DISPLAY LIMIT AND ACCESS LINK
        self::$Parameters['link'] = (!empty(self::$Parameters['link'])     ? self::$Parameters['link'] : '&page=');
        self::$Parameters['max_links']  = (!empty(self::$Parameters['max_links']) ? self::$Parameters['max_links'] : 5);

    }

    private static function getPagesAndOffset(){

        self::Query(self::getSyntax(),self::$Parameters['Values']);
        self::$Data['rows']   = (self::getResult() ? self::getResult()[0]['rows_count'] : 0);
        self::$Data['num_pages'] = ceil(self::$Data['rows'] / self::$Parameters['Options']['Limit']);

        if(self::$Page > 1){        
          self::$Parameters['Options']['Offset'] = (int)((self::$Page-1)*self::$Parameters['Options']['Limit']);
        }

    }

    private static function checkPage(){
      if(self::$Page > self::$Data['num_pages']){
        header('location: error');
      }
    }

    static function pagesView(){

      self::$Data['pagination']['before'] = [];
      self::$Data['pagination']['after']  = [];

      for($i = 0 ; $i < self::$Data['num_pages']+1 ; $i++ ){
        if($i > 0 && $i < self::$Page){ 
          if($i >= self::$Page-self::$Parameters['max_links']){
            self::$Data['pagination']['before'][] = [ 'link' => self::$Parameters['link'].$i , 'page'=> $i];
          }
        }
      }
      
      self::$Data['pagination']['currentPage'][] = ['page' => (self::$Page > 1 ? self::$Page : null)];

      if(self::$Data['num_pages'] > self::$Page){
        for($i = 0; $i < self::$Data['num_pages']+1 ; $i++){
          if($i > self::$Page && $i <= (self::$Page+self::$Parameters['max_links']) ){
            self::$Data['pagination']['after'][] = [ 'link' => self::$Parameters['link'].$i , 'page'=> $i];  
          }
        }
      }
      
      $before  = View::setContent(View::fileGetContent('pagination/Before'),self::$Data['pagination']['before']);
      $current = View::setContent(View::fileGetContent('pagination/Current'),self::$Data['pagination']['currentPage']);
      $after   = View::setContent(View::fileGetContent('pagination/After'),self::$Data['pagination']['after']);
      
      View::setData(['pagination' => $before.$current.$after]);

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

}
