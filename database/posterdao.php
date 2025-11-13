    
<?php

require_once __DIR__ . '/pdoconnection.php';
require_once __DIR__ . '/schema/poster.php';

class PosterDAO {
    
    public static function getPDO(): PDO {

        $pdo = PDOConnection::getPDO();
        
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS Poster (
                poster_id INT PRIMARY KEY AUTO_INCREMENT,
                poster_title VARCHAR(64) NOT NULL,
                poster_headline VARCHAR(128) NOT NULL,
                poster_description TEXT NOT NULL,
                poster_cover_img_name VARCHAR(64),
                poster_created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                poster_updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            );
        ");
        
        return $pdo;
        
    }
    
    public static function insert(Poster $poster): int {
        $stmt = self::getPDO()->prepare("
            INSERT INTO Poster (poster_title, poster_headline, poster_description, poster_cover_img_name)
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
            UPDATE Poster
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
        $stmt = self::getPDO()->prepare("DELETE FROM Poster WHERE poster_id = :id");
        $stmt->execute([":id" => $id]);
    }
    
    public static function select(int $id): ?Poster {
        
        $stmt = self::getPDO()->prepare("SELECT * FROM Poster WHERE poster_id = :id");
        
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
        
        $stmt = self::getPDO()->query("SELECT * FROM Poster ORDER BY poster_created_at DESC");
        
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

    public static function getCount(): int {
        $stmt = self::getPDO()->query("SELECT COUNT(*) as total FROM Poster");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int) $row['total'];
    }

    public static function search(string $text, int $page = 1, int $resultsPerPage = 12): array {

        if ($page < 1) {
            $page = 1;
        }

        $offset = ($page - 1) * $resultsPerPage;

        $stmt = self::getPDO()->prepare("
            SELECT * FROM Poster
            WHERE 
                poster_title LIKE :text
                OR poster_headline LIKE :text
                OR poster_description LIKE :text
            ORDER BY poster_id DESC
            LIMIT :limitParam
            OFFSET :offsetParam
        ");

        $stmt->bindValue(':text', '%' . $text . '%', PDO::PARAM_STR);
        
        $stmt->bindValue(':limitParam', $resultsPerPage, PDO::PARAM_INT);
        $stmt->bindValue(':offsetParam', $offset, PDO::PARAM_INT);

        $stmt->execute();

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

    public static function getPage(int $page = 1, int $resultsPerPage = 12): array {

        if ($page < 1) {
            $page = 1;
        }

        $offset = ($page - 1) * $resultsPerPage;

        $stmt = self::getPDO()->prepare("
            SELECT * FROM Poster
            ORDER BY poster_id DESC
            LIMIT :limit OFFSET :offset
        ");

        $stmt->bindValue(':limit', $resultsPerPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

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