<?php
// Database configuration
$host = 'localhost';
$dbname = 'purple_hotel';
$username = 'root';
$password = '';  // Default XAMPP password is blank
$charset = 'utf8mb4';

// Data Source Name
$dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";

// PDO options
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

// Create PDO instance
try {
    $pdo = new PDO($dsn, $username, $password, $options);
    
    // Test connection
    $pdo->query("SELECT 1");
} catch (PDOException $e) {
    // For debugging, you might want to show error during development
    die('Database Connection Failed: ' . $e->getMessage());
}
?>