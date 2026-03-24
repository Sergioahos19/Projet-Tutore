<?php
$servername = "localhost";
$username = "root";
$password = "";

// Connect without database
$conn = new mysqli($servername, $username, $password);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if not exists
$sql = "CREATE DATABASE IF NOT EXISTS ams_portail";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully<br>";
} else {
    echo "Error creating database: " . $conn->error . "<br>";
}

// Select database
$conn->select_db("ams_portail");

// Create users table
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    type ENUM('admin', 'company') NOT NULL,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    ifu VARCHAR(50),
    location VARCHAR(255),
    field VARCHAR(255),
    image VARCHAR(255),
    commitment TEXT,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Table users created successfully<br>";
} else {
    echo "Error creating table users: " . $conn->error . "<br>";
}

// Insert admin user
$admin_password = password_hash("admin123", PASSWORD_DEFAULT);
$sql = "INSERT IGNORE INTO users (type, name, email, password, status) VALUES ('admin', 'Administrator', 'admin@ams.com', '$admin_password', 'approved')";
if ($conn->query($sql) === TRUE) {
    echo "Admin user inserted<br>";
} else {
    echo "Error inserting admin: " . $conn->error . "<br>";
}

// Create jobs table
$sql = "CREATE TABLE IF NOT EXISTS jobs (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    company_id INT(6) UNSIGNED,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    location VARCHAR(255),
    salary VARCHAR(100),
    type ENUM('full-time', 'part-time', 'contract', 'internship') DEFAULT 'full-time',
    category VARCHAR(100),
    requirements TEXT,
    benefits TEXT,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (company_id) REFERENCES users(id) ON DELETE CASCADE
)";

if ($conn->query($sql) === TRUE) {
    echo "Table jobs created successfully<br>";
} else {
    echo "Error creating table jobs: " . $conn->error . "<br>";
}

// Create applications table
$sql = "CREATE TABLE IF NOT EXISTS applications (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    job_id INT(6) UNSIGNED,
    applicant_name VARCHAR(255) NOT NULL,
    applicant_email VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    cv VARCHAR(255),
    cover_letter TEXT,
    status ENUM('pending', 'accepted', 'rejected') DEFAULT 'pending',
    applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE
)";

if ($conn->query($sql) === TRUE) {
    echo "Table applications created successfully<br>";
} else {
    echo "Error creating table applications: " . $conn->error . "<br>";
}

// Create categories table (for future use)
$sql = "CREATE TABLE IF NOT EXISTS categories (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT
)";

if ($conn->query($sql) === TRUE) {
    echo "Table categories created successfully<br>";
} else {
    echo "Error creating table categories: " . $conn->error . "<br>";
}

$conn->close();
echo "Setup completed!";
?>