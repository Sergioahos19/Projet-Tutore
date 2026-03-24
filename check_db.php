<?php
include 'database.php';

echo "Connected to database successfully.<br>";

// Check tables
$tables = ['users', 'jobs', 'applications', 'categories'];
foreach ($tables as $table) {
    $result = $conn->query("SHOW TABLES LIKE '$table'");
    if ($result->num_rows > 0) {
        echo "Table '$table' exists.<br>";
    } else {
        echo "Table '$table' does not exist.<br>";
    }
}

// Check jobs
$result = $conn->query("SELECT COUNT(*) as count FROM jobs WHERE status='active'");
if ($result) {
    $row = $result->fetch_assoc();
    echo "Number of active jobs: " . $row['count'] . "<br>";
} else {
    echo "Error checking jobs: " . $conn->error . "<br>";
}

$conn->close();
?>