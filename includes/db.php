<?php
class Database {
    private $connection;
    private static $instance = null;

    private function __construct() {
        try {
            $this->connection = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
                DB_USER,
                DB_PASS,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
                ]
            );
        } catch(PDOException $e) {
            die('خطا در اتصال به پایگاه داده: ' . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function query($query, $params = []) {
        try {
            $stmt = $this->connection->prepare($query);
            $stmt->execute($params);
            return $stmt;
        } catch(PDOException $e) {
            die('خطا در اجرای کوئری: ' . $e->getMessage());
        }
    }

    public function getAll($query, $params = []) {
        return $this->query($query, $params)->fetchAll();
    }

    public function getOne($query, $params = []) {
        return $this->query($query, $params)->fetch();
    }

    public function insert($query, $params = []) {
        $this->query($query, $params);
        return $this->connection->lastInsertId();
    }

    public function update($query, $params = []) {
        return $this->query($query, $params)->rowCount();
    }

    public function delete($query, $params = []) {
        return $this->query($query, $params)->rowCount();
    }
}

$db = Database::getInstance();