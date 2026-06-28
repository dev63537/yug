<?php
/**
 * EventPro – Database Singleton (PDO)
 */
class Database {
    private static ?Database $instance = null;
    private ?PDO $connection = null;

    private function __construct() {
        try {
            $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci",
            ];
            $this->connection = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            if (DEBUG_MODE) {
                die('<div style="font-family:monospace;background:#fee2e2;color:#991b1b;padding:20px;border-radius:8px;margin:20px;">
                    <strong>Database Connection Failed</strong><br>
                    Error: ' . htmlspecialchars($e->getMessage()) . '<br>
                    <small>Please ensure XAMPP MySQL is running and the database "eventpro" exists.</small>
                </div>');
            } else {
                die('Database connection error. Please try again later.');
            }
        }
    }

    public static function getInstance(): self {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection(): PDO {
        return $this->connection;
    }

    // Prevent cloning/unserialization
    private function __clone() {}
    public function __wakeup() { throw new \Exception("Cannot unserialize singleton"); }
}
