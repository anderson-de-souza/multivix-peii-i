<?php

class PDOConnection {
    
    public static string $host = 'localhost';
    public static string $dbname = 'peiii';
    public static string $username ='root';
    public static string $password = '@devFullstack2026';
    
    private static ?PDO $pdo = null;
    
    private function __construct() {
        
    }
    
    public static function getPDO(): PDO {
        
        if (!self::$pdo) {
            
            try {
                
                self::$pdo = new PDO('mysql:host=' . self::$host .';charset=utf8', self::$username, self::$password, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_EMULATE_PREPARES => true
                ]);

                error_log("PDO CREATED");
                
                self::$pdo->exec('CREATE DATABASE IF NOT EXISTS ' . self::$dbname);
                self::$pdo->exec('USE ' . self::$dbname);
                
            } catch (PDOException $e) {
            }
            
        }
        
        return self::$pdo;
        
    }
    
}