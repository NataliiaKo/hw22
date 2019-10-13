<?php
require_once 'Db.php';

abstract class Model
{
    protected static $identifierColumn='id';
    protected static $table;
    protected static $identifier;
    protected static $columns;

    public function __construct()
    {
        $pdo = null;
    }

    public static function pluralize($quantity, $singular, $plural=null) {
        if($quantity==1 || !strlen($singular)) return $singular;
        if($plural!==null) return $plural;

        $last_letter = strtolower($singular[strlen($singular)-1]);
        switch($last_letter) {
            case 'y':
                return substr($singular,0,-1).'ies';
            case 's':
                return $singular.'es';
            default:
                return $singular.'s';
        }
    }

    public static function find($id)
    {
        $pdo=Db::getInstance();
        $table = self::pluralize(2,strtolower(static::class));
        if(static::$table !== null) {
            $table=static::$table;
        }

        $sql = 'SELECT * FROM ' . $table
                . ' WHERE `' . self::$identifierColumn . '`= :id LIMIT 1;';
        $stmt=$pdo->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        $data=$stmt->fetch(\PDO::FETCH_ASSOC);

        self::$identifier=$id;

        $object=new static;

        if ($data) {
            foreach ($data as $key => $value)
            {
            $object->$key = $value;
            }
        }
        else $object=false;
        return $object;

    }

    public static function findAll()
    {
        $pdo=Db::getInstance();
        $table = self::pluralize(2,strtolower(static::class));
        if(static::$table !== null) {
            $table=static::$table;
        }
        $sql = 'SELECT * FROM ' . $table
            . ' WHERE 1';
        $stmt=$pdo->query($sql);
        $stmt->execute();

        $data=$stmt->fetchAll(\PDO::FETCH_ASSOC);
        $pdo=null;
        $stmt=null;
        return $data;
    }
    public function delete()
    {
        $pdo=Db::getInstance();
        $table = self::pluralize(2,strtolower(static::class));
        if(static::$table !== null) {
            $table=static::$table;
        }
        $sql = ('DELETE FROM ' . static::$table
            . ' WHERE `id`='.self::$identifier);
        var_dump($sql);
        $stmt=$pdo->query($sql);
        $pdo=null;
        $stmt=null;
    }

    public function save()
    {
        if (isset(self::$identifier)) {
            $this->update();
        } else {
            $this->insert();
        }
    }
    public function update(){

        $pdo=Db::getInstance();

        $table = self::pluralize(2, strtolower(static::class));
        if (static::$table !== null) {
            $table = static::$table;
        }

        $stmt = $pdo->prepare("DESCRIBE ".$table);
        $stmt->execute();
        $table_fields = $stmt->fetchAll(PDO::FETCH_COLUMN);
        //массив для записи названия столбцов таблицы;
        $columns = [];

        foreach ($table_fields as $column)
        { array_push($columns,$column);}
        $columnsField='';

        foreach ($columns as $column) {
            if(isset($this->$column)){
                if($column==='password'){
                    $columnsField.=" `".$column."`='".$this->password=password_hash($this->password,1)."',";}
                else{$columnsField.=" `".$column."`='".$this->$column."',";}
            }

        }

        $sql="UPDATE `".$table."` SET ".substr($columnsField, 0, -1)."WHERE id=".self::$identifier.";";
        var_dump($sql);
        $stmt=$pdo->prepare($sql);
        $stmt->execute();

        }

    public function insert(){
        $pdo=Db::getInstance();
        $table = self::pluralize(2, strtolower(static::class));
        if (static::$table !== null) {
            $table = static::$table;
        }

        $stmt = $pdo->prepare("DESCRIBE ".$table);
        $stmt->execute();
        $table_fields = $stmt->fetchAll(PDO::FETCH_COLUMN);
        //массив для записи названия столбцов таблицы;
        $columns = [];

        foreach ($table_fields as $column)
        { array_push($columns,$column);}
        $columnsField='';
        $this->password=password_hash($this->password,1);
        foreach ($columns as $column) {
            if(!strcmp($column,self::$identifierColumn)){
                if(!isset(self::$identifier)){
                $columnsField.=" `".$column."`= null,";}
            else{
                $columnsField.=" `".$column."`='".$this->$column."',";
            }}
            else{
                $columnsField.=" `".$column."`='".$this->$column."',";
            }

        }

       $sql="INSERT INTO `".$table."` SET ".substr($columnsField, 0, -1).";";
       $stmt=$pdo->prepare($sql);
       $stmt->execute();
    }
}