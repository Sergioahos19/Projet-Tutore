<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'company') {
    header("Location: company_login.php");
    exit();
}
include 'database.php';
$user_id = $_SESSION['user_id'];

// Get company info
$company = $conn->query("SELECT * FROM users WHERE id=$user_id AND type='company'")->fetch_assoc();

// Handle delete job
if (isset($_GET['delete_job'])) {
    $job_id = $_GET['delete_job'];
    $conn->query("DELETE FROM jobs WHERE id=$job_id AND company_id=$user_id");
    header("Location: company_dashboard.php");
    exit();
}

// Handle application decisions
if (isset($_GET['application_action'])) {
    $app_id = $_GET['app_id'];
    $action = $_GET['application_action'];
    $status = ($action == 'accept') ? 'accepted' : (($action == 'reject') ? 'rejected' : 'archived');

    // Update application status
    $conn->query("UPDATE applications SET status='$status' WHERE id=$app_id");

    // Get application details for email
    $app_result = $conn->query("SELECT applications.*, jobs.title, users.email as company_email FROM applications JOIN jobs ON applications.job_id = jobs.id JOIN users ON jobs.company_id = users.id WHERE applications.id=$app_id");
    $app = $app_result->fetch_assoc();

    // Send email notification only for accept/reject
    if ($action != 'archive') {
        $to = $app['applicant_email'];
        $subject = "Réponse à votre candidature pour " . $app['title'];
        $message = "Cher " . $app['applicant_name'] . ",\n\nVotre candidature pour le poste de " . $app['title'] . " a été " . ($status == 'accepted' ? 'acceptée' : 'rejetée') . ".\n\nCordialement,\n" . $app['company_email'];
        $headers = "From: " . $app['company_email'];

        mail($to, $subject, $message, $headers);
    }

    header("Location: company_dashboard.php");
    exit();
}

// Fetch jobs
$jobs = $conn->query("SELECT * FROM jobs WHERE company_id=$user_id");

// Fetch applications
$applications = $conn->query("SELECT applications.*, jobs.title FROM applications JOIN jobs ON applications.job_id = jobs.id WHERE jobs.company_id=$user_id");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord Entreprise - AMS</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/tooplate-gotto-job.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <img src="images/logo.png" class="img-fluid logo-image">
                <div class="d-flex flex-column">
                    <strong class="logo-text">AMS</strong>
                    <small class="logo-slogan">Portail d'emploi en ligne</small>
                </div>
            </a>
            <div class="ms-auto">
                <span><?php echo $_SESSION['user_name']; ?></span>
                <a href="logout.php" class="btn btn-sm btn-outline-danger ms-2">Déconnexion</a>
            </div>
        </div>
    </nav>

    <main class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Tableau de Bord Entreprise</h2>
            <a href="index.php" class="btn btn-primary">Retour à l'accueil</a>
        </div>

        <div class="mb-4 d-flex align-items-center gap-3">
            <?php if (!empty($company['image']) && file_exists($company['image'])): ?>
                <img src="<?php echo htmlspecialchars($company['image']); ?>" alt="Logo entreprise" class="rounded-circle" style="width:70px;height:70px;object-fit:cover;" />
            <?php endif; ?>
            <div>
                <h4><?php echo htmlspecialchars($company['name']); ?></h4>
                <p><?php echo htmlspecialchars($company['email']); ?> | <?php echo htmlspecialchars($company['location']); ?></p>
            </div>
        </div>

        <div class="mb-4">
            <a href="add_job.php" class="btn btn-primary">Ajouter une offre</a>
        </div>

        <!-- Jobs -->
        <div class="card mb-4">
            <div class="card-header">
                <h5>Mes Offres d'emploi</h5>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Titre</th>
                            <th>Type</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($job = $jobs->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $job['title']; ?></td>
                            <td>
                                <?php 
                                $type_fr = $job['type'] == 'full-time' ? 'Temps plein' : ($job['type'] == 'part-time' ? 'Temps partiel' : ($job['type'] == 'contract' ? 'Contrat' : 'Stage'));
                                echo $type_fr;
                                ?>
                            </td>
                            <td>
                                <?php 
                                $status_class = $job['status'] == 'active' ? 'success' : 'danger';
                                $status_text = $job['status'] == 'active' ? 'Actif' : 'Inactif';
                                ?>
                                <span class="badge bg-<?php echo $status_class; ?>" style="color: white;"><?php echo $status_text; ?></span>
                            </td>
                            <td>
                                <a href="edit_job.php?id=<?php echo $job['id']; ?>" class="btn btn-sm btn-warning">Modifier</a>
                                <a href="?delete_job=<?php echo $job['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer?')">Supprimer</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Applications -->
        <div class="card mb-4">
            <div class="card-header">
                <h5>Candidatures reçues</h5>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Poste</th>
                            <th>Candidat</th>
                            <th>Email</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($app = $applications->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $app['title']; ?></td>
                            <td><?php echo $app['applicant_name']; ?></td>
                            <td><?php echo $app['applicant_email']; ?></td>
                            <td>
                                <?php 
                                $status_class = $app['status'] == 'accepted' ? 'success' : ($app['status'] == 'pending' ? 'warning' : ($app['status'] == 'rejected' ? 'danger' : 'secondary'));
                                $status_text = $app['status'] == 'accepted' ? 'Accepté' : ($app['status'] == 'pending' ? 'En attente' : ($app['status'] == 'rejected' ? 'Rejeté' : 'Archivé'));
                                ?>
                                <span class="badge bg-<?php echo $status_class; ?>" style="color: white;"><?php echo $status_text; ?></span>
                            </td>
                            <td>
                                <a href="?application_action=accept&app_id=<?php echo $app['id']; ?>" class="btn btn-sm btn-success">Accepter</a>
                                <a href="?application_action=reject&app_id=<?php echo $app['id']; ?>" class="btn btn-sm btn-danger">Rejeter</a>
                                <a href="?application_action=archive&app_id=<?php echo $app['id']; ?>" class="btn btn-sm btn-secondary">Archiver</a>
                                <a href="uploads/<?php echo basename($app['cv']); ?>" target="_blank" class="btn btn-sm btn-info">Voir CV</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <script src="js/bootstrap.min.js"></script>
</body>
</html>