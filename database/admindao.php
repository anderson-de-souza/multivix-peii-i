<?php

require_once __DIR__ . '/pdoconnection.php';
require_once __DIR__ . '/schema/admin.php';

class AdminDAO {

    public static function getPDO(): PDO {

        $pdo = PDOConnection::getPDO();

        $pdo->exec("
            CREATE TABLE IF NOT EXISTS Admin (
                admin_id INT AUTO_INCREMENT PRIMARY KEY,
                admin_email VARCHAR(128) NOT NULL UNIQUE,
                admin_token VARCHAR(255) NOT NULL
            );
        ");

        return $pdo;

    }

    public static function insert(Admin $admin) {

        $stmt = self::getPDO()->query("SELECT COUNT(*) as total FROM Admin");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row['total'] > 0) {
            throw new Exception("There is already a registered Admin. It is not possible to create another one.");
        }

        $stmt = self::getPDO()->prepare("INSERT INTO Admin (admin_email, admin_token) VALUES (:email, :token)");

        $stmt->execute([
            ':email' => $admin->getEmail(),
            ':token' => password_hash($admin->getToken(), PASSWORD_BCRYPT)
        ]);

        return self::getPDO()->lastInsertId();

    }

    public static function update(Admin $admin) {

        $stmt = self::getPDO()->prepare("
            UPDATE Admin
            SET admin_email = :email, admin_token = :token
            WHERE admin_id = :id
        ");

        return $stmt->execute([
            ':email' => $admin->getEmail(),
            ':token' => password_hash($admin->getToken(), PASSWORD_BCRYPT),
            ':id' => $admin->getId()
        ]);

    }

    public static function getAdmin(): ?Admin {

        $stmt = self::getPDO()->query("SELECT * FROM Admin LIMIT 1");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        $admin = new Admin(
            $row['admin_email'],
            $row['admin_token']
        );

        $admin->setId((int) $row['admin_id']);
        return $admin;

    }

    public static function delete() {
        return self::getPDO()->exec("DELETE FROM Admin");
    }

}
