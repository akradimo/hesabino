<?php
require_once 'config.php';

class Database {
    private $conn;

    public function __construct() {
        $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
        $this->conn->set_charset("utf8mb4");
    }

    public function getConnection() {
        return $this->conn;
    }

    public function query($sql) {
        return $this->conn->query($sql);
    }

    public function escape($value) {
        return $this->conn->real_escape_string($value);
    }

    public function close() {
        $this->conn->close();
    }

    public function deleteCategory($id) {
        $this->conn->begin_transaction();
        try {
            // حذف تمام زیرمجموعه‌های دسته‌بندی
            $sql = "DELETE FROM categories WHERE path LIKE (SELECT CONCAT(path, '/%') FROM (SELECT path FROM categories WHERE id = '$id') AS temp)";
            $this->query($sql);

            // حذف خود دسته‌بندی
            $sql = "DELETE FROM categories WHERE id = '$id'";
            $this->query($sql);

            $this->conn->commit();
            return true;
        } catch (mysqli_sql_exception $exception) {
            $this->conn->rollback();
            throw $exception;
        }
    }
}

$db = new Database();
?>