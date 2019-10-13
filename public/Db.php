<?php


class Db
{
    protected static $instance;
    public static function getInstance(){
        if(self::$instance===null) {
            self::$instance = new PDO('mysql:host=localhost;dbname=abbyss1', 'root', 'secret');
        }
        return self::$instance;
    }
}