<?php

namespace Sources\Classes\Database;

class Crud extends Connection{

    //DATABASE CONNECTION
    protected static $Conn;

    //SQL SYNTAX
    private static $Syntax;

    //RESULT
    private static $Result;
    private static $RowCount;

    private static $Error;

    //START CONNECTION WITH DATABASE
    static function ConnectDb(){
        parent::Start(); 
        self::$Conn = parent::$Conn;
    }

    static function Create(string $Table,array $Values){
        
        $Fields = array_keys($Values);
        self::$Syntax =  "INSERT INTO $Table (".implode(',',$Fields).") VALUES (".':'.implode(',:', $Fields).")";
        self::bindAndExecute($Values);
      
    }

    static function Read(string $Table,array $Values = null,array $Options = null){

       $Filters = null;
       
       if(!empty($Values) && is_array($Values)){
        foreach( array_keys($Values) as $key){
          $Filters[] = "$key = :$key";
          $Values[":$key"] = $Values[$key];
          unset($Values[$key]);
        }
        $Filters = ' WHERE '.implode(' AND ',$Filters);
       } 

       $Options['Columns']    = (!empty($Options['Columns']) ? $Options['Columns'] : '*');
       $Options['Condition'] = (!empty($Options['Condition']) ? $Options['Condition'] : $Filters);

       self::$Syntax = "SELECT $Options[Columns] FROM $Table $Options[Condition]";

       if(!empty($Options['Limit'])){
        self::$Syntax .= ' LIMIT :limit';
        $Values[':limit'] = (int)$Options['Limit'];
       }

       if(!empty($Options['Offset'])){
        self::$Syntax .= ' OFFSET :offset';
        $Values[':offset'] = (int)$Options['Offset'];
       }

       self::$Syntax .= (!empty($Options['Order']) ? $Options['Order'] : null);
       self::bindAndExecute($Values,true);

    }

    static function Update(string $Table,array $Values, array $Filters){

        foreach( array_keys($Values) as $key){
            $Data[] = "$key = :$key";
        }

        foreach( array_keys($Filters) as $key){
            $Filters[] = "$key = :$key";
            $Values[$key] = $Filters[$key];
            unset($Filters[$key]);
        }

       $Filters = implode(' AND ',$Filters);
       $Data = implode(',',$Data);

       self::$Syntax = "UPDATE $Table SET $Data WHERE $Filters";
       self::bindAndExecute($Values);

    }

    static function Delete(string $Table,array $Values){

      foreach( array_keys($Values) as $key){
        $Filters[] = "$key = :$key";
      }

      $Filters = implode(',',$Filters);

      self::$Syntax = "DELETE FROM $Table WHERE $Filters";
      self::bindAndExecute($Values);

    }

    static function Query($Syntax,$Values){

      foreach( array_keys($Values) as $key){
        $Values[":$key"] = $Values[$key];
        unset($Values[$key]);
      }

      self::$Syntax = $Syntax;
      self::bindAndExecute($Values,true);

    }

    private static function bindAndExecute($Values,bool $Select = false){
        
        $Query = self::$Conn->prepare(self::$Syntax);

        if(!$Select){

          foreach($Values as $key => $value){
            $Values[':'.$key] = $value;
            unset($Values[$key]);
          }

          try{
            $Query->execute($Values);
            self::$Result = self::$Conn->lastInsertId();
          }catch (\Exception $e) {
            self::$Error = $e->getMessage();
            self::$Result = false;
          }
          
        }else{

            if(!empty($Values)){

                foreach($Values as $key => $value):
                  if($key == ':limit' || $key == ':offset'){
                    $Query->bindValue($key , $value , \PDO::PARAM_INT);
                  }else{
                    $Query->bindValue($key , $value , ((string)$value ? \PDO::PARAM_STR : \PDO::PARAM_INT));
                  }
                endforeach;

            }

            try{
                $Query->execute();
                self::$Result      = $Query->fetchAll(\PDO::FETCH_ASSOC);
                self::$RowCount    = $Query->rowCount(); 
            } catch (\Exception $e) {
                self::$Error = $e->getMessage();
                self::$Result = false;
            }

        }

    }
    
    static function getResult(){
      return self::$Result;
    }

    static function getError(){
      return self::$Error;
    }

    static function getRowCount(){
      return self::$RowCount;
    }

}


?>