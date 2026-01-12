<?php
/**
 * Database Connection Class - Pure PHP Version
 */

class Database {
    private static $instance = null;
    private $connection;
    private $config;

    private function __construct() {
        $this->config = require __DIR__ . '/../config/database.php';
        $this->connect();
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function connect() {
        try {
            $dsn = "mysql:host={$this->config['host']};port={$this->config['port']};dbname={$this->config['database']};charset={$this->config['charset']}";
            $this->connection = new PDO($dsn, $this->config['username'], $this->config['password'], $this->config['options']);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    public function getConnection() {
        return $this->connection;
    }

    public function query($sql, $params = []) {
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            die("Query failed: " . $e->getMessage());
        }
    }

    public function fetch($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt->fetch();
    }

    public function fetchAll($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt->fetchAll();
    }

    public function insert($table, $data) {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        $values = array_values($data);
        
        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        $this->query($sql, $values);
        return $this->connection->lastInsertId();
    }

    public function update($table, $data, $where, $whereParams = []) {
        $setParts = [];
        $values = [];
        
        foreach ($data as $column => $value) {
            $setParts[] = "$column = ?";
            $values[] = $value;
        }
        
        $setClause = implode(', ', $setParts);
        $values = array_merge($values, $whereParams);
        
        $sql = "UPDATE $table SET $setClause WHERE $where";
        $stmt = $this->query($sql, $values);
        return $stmt->rowCount();
    }

    public function delete($table, $where, $params = []) {
        $sql = "DELETE FROM $table WHERE $where";
        $stmt = $this->query($sql, $params);
        return $stmt->rowCount();
    }

    public function beginTransaction() {
        return $this->connection->beginTransaction();
    }

    public function commit() {
        return $this->connection->commit();
    }

    public function rollback() {
        return $this->connection->rollBack();
    }
}
