<?php
include 'database.php';

echo "<h2>Test de la Base de Données</h2>";

// Vérifier la connexion
echo "<p><strong>Connexion :</strong> " . ($conn->ping() ? "✅ OK" : "❌ ERREUR") . "</p>";

// Compter les offres actives
$result = $conn->query("SELECT COUNT(*) as count FROM jobs WHERE status='active'");
$row = $result->fetch_assoc();
echo "<p><strong>Offres actives :</strong> " . $row['count'] . "</p>";

// Lister les offres
echo "<h3>Liste des offres (top 5) :</h3>";
$jobs = $conn->query("SELECT jobs.id, jobs.title, jobs.status, users.name as company_name FROM jobs JOIN users ON jobs.company_id = users.id LIMIT 5");

if ($jobs->num_rows > 0) {
    echo "<table border='1' cellpadding='10'>";
    echo "<tr><th>ID</th><th>Titre</th><th>Entreprise</th><th>Statut</th></tr>";
    while ($job = $jobs->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $job['id'] . "</td>";
        echo "<td>" . $job['title'] . "</td>";
        echo "<td>" . $job['company_name'] . "</td>";
        echo "<td>" . $job['status'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>❌ Aucune offre trouvée</p>";
}

$conn->close();
?>
