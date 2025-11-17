<?php
/**
 * Database connection class - PDO based with prepared statements
 */
class Database {
    private $host = "127.0.0.1";
    private $username = "root";
    private $password = "";
    private $database = "ecommerce_catalog";
    private $conn;

    public function __construct() {
        $this->connect();
    }

    private function connect() {
        try {
            $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->database . ";charset=utf8mb4";
            $this->conn = new PDO(
                $dsn,
                $this->username,
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );
        } catch(PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    public function getConnection() {
        return $this->conn;
    }

    public function query($sql, $params = []) {
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch(PDOException $e) {
            throw new Exception("Query failed: " . $e->getMessage());
        }
    }

    public function fetchAll($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt->fetchAll();
    }

    public function fetchOne($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt->fetch();
    }

    public function insert($table, $data) {
        $columns = array_keys($data);
        $placeholders = array_fill(0, count($columns), '?');
        $sql = "INSERT INTO $table (" . implode(',', $columns) . ") VALUES (" . implode(',', $placeholders) . ")";
        $stmt = $this->query($sql, array_values($data));
        return $this->conn->lastInsertId();
    }

    public function update($table, $data, $where_col, $where_val) {
        $set_clause = implode(' = ?, ', array_keys($data)) . ' = ?';
        $sql = "UPDATE $table SET $set_clause WHERE $where_col = ?";
        $values = array_merge(array_values($data), [$where_val]);
        return $this->query($sql, $values);
    }

    public function delete($table, $where_col, $where_val) {
        $sql = "DELETE FROM $table WHERE $where_col = ?";
        return $this->query($sql, [$where_val]);
    }
}
?>
