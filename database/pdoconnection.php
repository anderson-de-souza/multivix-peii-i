<?php

class PDOConnection {
    
    private static string $host = 'localhost';
    private static string $dbname = 'peiii';
    private static string $username ='root';
    private static string $password = '@devFullstack2026';
    
    private static ?PDO $pdo = null;
    
    private function __construct() {
        
    }
    
    public static function getPDO(): PDO {
        
        if (!self::$pdo) {
            
            try {
                
                self::$pdo = new PDO('mysql:host=' . self::$host .';charset=utf8', self::$username, self::$password);
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                self::$pdo->exec('CREATE DATABASE IF NOT EXISTS ' . self::$dbname);
                self::$pdo->exec('USE ' . self::$dbname);
                
            } catch (PDOException $e) {
            }
            
        }
        
        return self::$pdo;
        
    }
    
}