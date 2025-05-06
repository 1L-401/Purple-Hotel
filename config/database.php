<?php
class Database {
    private $host = 'localhost';
    private $dbname = 'purple_hotel';
    private $username = 'root';
    private $password = '';
    private $charset = 'utf8mb4';
    private $pdo;

    public function __construct() {
        try {
            // First try to connect without specifying database
            $this->pdo = new PDO("mysql:host={$this->host}", $this->username, $this->password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);
            
            // Check if database exists
            $stmt = $this->pdo->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '{$this->dbname}'");
            $dbExists = $stmt->fetchColumn();
            
            if (!$dbExists) {
                // Create database
                $this->pdo->exec("CREATE DATABASE `{$this->dbname}` DEFAULT CHARACTER SET {$this->charset} COLLATE {$this->charset}_unicode_ci");
            }
            
            // Now connect to the database with all options
            $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset={$this->charset}";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            
            $this->pdo = new PDO($dsn, $this->username, $this->password, $options);
            
        } catch (PDOException $e) {
            die('Database Connection Failed: ' . $e->getMessage());
        }
    }

    public function getConnection() {
        return $this->pdo;
    }
}