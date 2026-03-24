<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ams_portail";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

echo "✅ Connexion réussie<br><br>";

// Réinitialiser les tables
echo "🔄 Nettoyage des tables...<br>";
$conn->query("DROP TABLE IF EXISTS applications");
$conn->query("DROP TABLE IF EXISTS jobs");
$conn->query("DROP TABLE IF EXISTS categories");
$conn->query("DROP TABLE IF EXISTS users");

// Recréer la table users
$sql = "CREATE TABLE users (
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
$conn->query($sql) ? print("✅ Table users créée<br>") : print("❌ Erreur users<br>");

// Ajouter admin
$admin_password = password_hash("admin123", PASSWORD_DEFAULT);
$conn->query("INSERT INTO users (type, name, email, password, status) VALUES ('admin', 'Administrator', 'admin@ams.com', '$admin_password', 'approved')");
echo "✅ Admin créé<br>";

// Ajouter une entreprise test
$company_password = password_hash("test123", PASSWORD_DEFAULT);
$conn->query("INSERT INTO users (type, name, email, password, location, status, image) VALUES ('company', 'Tech Solutions SARL', 'tech@example.com', '$company_password', 'Dakar', 'approved', 'images/logo.png')");
echo "✅ Entreprise test créée<br>";

// Recréer la table jobs
$sql = "CREATE TABLE jobs (
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
$conn->query($sql) ? print("✅ Table jobs créée<br>") : print("❌ Erreur jobs<br>");

// Ajouter des offres test
$conn->query("INSERT INTO jobs (company_id, title, location, salary, type, category, description, status) 
VALUES (2, 'Développeur PHP Senior', 'Dakar', '800k-1.2M', 'full-time', 'Développement', 'Nous cherchons un développeur PHP expérimenté', 'active')");

$conn->query("INSERT INTO jobs (company_id, title, location, salary, type, category, description, status) 
VALUES (2, 'Manager Commercial', 'Abidjan', '600k-900k', 'full-time', 'Ventes', 'Pilotage d\'équipe commerciale', 'active')");

$conn->query("INSERT INTO jobs (company_id, title, location, salary, type, category, description, status) 
VALUES (2, 'Designer Graphique', 'Dakar', '400k-600k', 'part-time', 'Design', 'Création de visuels marketing', 'active')");

echo "✅ 3 offres test créées<br>";

// Recréer la table applications
$sql = "CREATE TABLE applications (
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
$conn->query($sql) ? print("✅ Table applications créée<br>") : print("❌ Erreur applications<br>");

// Recréer la table categories
$sql = "CREATE TABLE categories (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT
)";
$conn->query($sql) ? print("✅ Table categories créée<br>") : print("❌ Erreur categories<br>");

echo "<br>🎉 <strong>Base de données réinitialisée avec succès !</strong><br>";
echo "Credentials:<br>
- Admin: admin@ams.com / admin123<br>
- Entreprise: tech@example.com / test123<br><br>";
echo "<a href='index.php'>← Retour à l'accueil</a>";

$conn->close();
?>
