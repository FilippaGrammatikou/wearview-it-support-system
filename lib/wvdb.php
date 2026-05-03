<?php

$configPath = __DIR__ . '/../config/webconfig.local.php';
if (!file_exists($configPath)) {
    $configPath = __DIR__ . '/../config/webconfig.example.php';
}
require_once $configPath;

class Database {
    private static ?PDO $instance = null;

    public static function getConnection(): PDO {
        if (self::$instance === null) {
            try {
                $options = [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ];
                self::$instance = new PDO(
                    DB_DSN,
                    DB_USER,
                    DB_PASS,
                    $options
                );
            } catch (PDOException $e) {
                error_log('[DB ERROR] ' . $e->getMessage());
                die('Please try again after a minute.');
            }
        }
        return self::$instance;
    }
}
