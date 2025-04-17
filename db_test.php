<?php
// Database connection test script

// Get database configuration from .env file
$envFile = file_get_contents('.env');
preg_match('/DB_HOST=(.*)/', $envFile, $hostMatches);
preg_match('/DB_PORT=(.*)/', $envFile, $portMatches);
preg_match('/DB_DATABASE=(.*)/', $envFile, $databaseMatches);
preg_match('/DB_USERNAME=(.*)/', $envFile, $usernameMatches);
preg_match('/DB_PASSWORD=(.*)/', $envFile, $passwordMatches);

$host = trim($hostMatches[1] ?? '127.0.0.1');
$port = trim($portMatches[1] ?? '3306');
$database = trim($databaseMatches[1] ?? 'osusa2');
$username = trim($usernameMatches[1] ?? 'root');
$password = trim($passwordMatches[1] ?? '');

echo "Testing database connection to: $host:$port/$database as $username\n";

// Test connection
$startTime = microtime(true);
try {
    $conn = new mysqli($host, $username, $password, $database, $port);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    echo "Connected successfully to the database.\n";

    // Test a simple query
    $sql = "SELECT 1";
    $result = $conn->query($sql);

    if ($result) {
        echo "Query executed successfully.\n";
    } else {
        echo "Error executing query: " . $conn->error . "\n";
    }

    // Close connection
    $conn->close();

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

$endTime = microtime(true);
$executionTime = ($endTime - $startTime) * 1000; // Convert to milliseconds

echo "Database connection test completed in " . number_format($executionTime, 2) . " ms.\n";
?>
