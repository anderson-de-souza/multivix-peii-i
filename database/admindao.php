<?php

require_once __DIR__ . '/database.php';
require_once __DIR__ . '/schema/admin.php';

class AdminDAO {

    private static ?PDO $pdo = null;

    public static function getPDO(): ?PDO {

        if (!self::$pdo) {

            self::$pdo = Database::getPDO();

            self::$pdo->exec("
                CREATE TABLE IF NOT EXISTS admin (
                    admin_id INT AUTO_INCREMENT PRIMARY KEY,
                    admin_email VARCHAR(128) NOT NULL UNIQUE,
                    admin_token VARCHAR(255) NOT NULL
                );
            ");

        }

        return self::$pdo;

    }

    public static function insert(Admin $admin) {

        $stmt = self::getPDO()->query("SELECT COUNT(*) as total FROM Admin");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row['total'] > 0) {
            throw new Exception("There is already a registered Admin. It is not possible to create another one.");
        }

        $stmt = self::getPDO()->prepare("
            INSERT INTO admin (admin_name, admin_email, admin_token)
            VALUES (:name, :email, :token)
        ");

        $stmt->execute([
            ':name' => $admin->getName(),
            ':email' => $admin->getEmail(),
            ':token' => password_hash($admin->getToken(), token_BCRYPT)
        ]);

        return self::getPDO()->lastInsertId();

    }

    public static function update(Admin $admin) {

        $stmt = self::getPDO()->prepare("
            UPDATE admin
            SET admin_name = :name, admin_email = :email, admin_token = :token
            WHERE admin_id = :id
        ");

        return $stmt->execute([
            ':name' => $admin->getName(),
            ':email' => $admin->getEmail(),
            ':token' => password_hash($admin->getToken(), PASSWORD_BCRYPT),
            ':id' => $admin->getId()
        ]);

    }

    public static function getAdmin(): ?Admin {

        $stmt = self::getPDO()->query("SELECT * FROM admin LIMIT 1");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        $admin = new Admin(
            $row['admin_name'],
            $row['admin_email'],
            $row['admin_token']
        );

        $admin->setId((int) $row['admin_id']);
        return $admin;

    }

    public static function delete() {
        return self::getPDO()->exec("DELETE FROM admin");
    }

}
