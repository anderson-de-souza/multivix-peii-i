    
}<?php

require_once __DIR__ . '/pdoconnection.php';
require_once __DIR__ . '/schema/poster.php';

class PosterDAO {
    
    public static function getPDO(): PDO {
        $pdo = PDOConnection::getPDO();
        
        if ($pdo) {
            $pdo->exec("
                CREATE TABLE IF NOT EXISTS poster (
                    poster_id INT PRIMARY KEY AUTO_INCREMENT,
                    poster_title VARCHAR(64) NOT NULL,
                    poster_headline VARCHAR(128) NOT NULL,
                    poster_description TEXT NOT NULL,
                    poster_cover_img_name VARCHAR(64),
                    poster_created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                    poster_updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                );
            ");
        }
        
        return $pdo;
    }
    
    public static function insert(Poster $poster): int {
        $stmt = self::getPDO()->prepare("
            INSERT INTO poster (poster_title, poster_headline, poster_description, poster_cover_img_name)
            VALUES (:title, :headline, :description, :coverImgName)
        ");
        
        $stmt->execute([
            ":title" => $poster->getTitle(),
            ":headline" => $poster->getHeadline(),
            ":description" => $poster->getDescription(),
            ":coverImgName" => $poster->getCoverImgName()
        ]);
        
        return self::getPDO()->lastInsertId();
    }
    
    public static function update(Poster $poster): void {
        $stmt = self::getPDO()->prepare("
            UPDATE poster
            SET 
                poster_title = :title, 
                poster_headline = :headline, 
                poster_description = :description, 
                poster_cover_img_name = :coverImgName
            WHERE poster_id = :id
        ");
        
        $stmt->execute([
            ":title" => $poster->getTitle(),
            ":headline" => $poster->getHeadline(),
            ":description" => $poster->getDescription(),
            ":coverImgName" => $poster->getCoverImgName(),
            ":id" => $poster->getId()
        ]);
    }
    
    public static function delete(int $id): void {
        $stmt = self::getPDO()->prepare("DELETE FROM poster WHERE poster_id = :id");
        $stmt->execute([":id" => $id]);
    }
    
    public static function select(int $id): ?Poster {
        
        $stmt = self::getPDO()->prepare("SELECT * FROM poster WHERE poster_id = :id");
        
        $stmt->execute([":id" => $id]);
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$row) {
            return null;
        }
        
        $poster = new Poster(
            $row['poster_title'],
            $row['poster_headline'],
            $row['poster_description'],
            $row['poster_cover_img_name'],
            new DateTime($row['poster_created_at']),
            new DateTime($row['poster_updated_at'])
        );
        
        $poster->setId((int) $row['poster_id']);
        
        return $poster;
        
    }
    
    public static function getAllPosters(): array {
        
        $stmt = self::getPDO()->query("SELECT * FROM poster ORDER BY poster_created_at DESC");
        
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $posters = [];
        
        foreach ($rows as $row) {
            
            $poster = new Poster(
                $row['poster_title'],
                $row['poster_headline'],
                $row['poster_description'],
                $row['poster_cover_img_name'],
                new DateTime($row['poster_created_at']),
                new DateTime($row['poster_updated_at'])
                
            );
            
            $poster->setId((int) $row['poster_id']);
            
            $posters[] = $poster;
            
        }
        
        return $posters;
        
    }
    
}