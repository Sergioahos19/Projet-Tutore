<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'admin') {
    header("Location: admin_login.php");
    exit();
}
include 'database.php';

// Handle actions
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $id = $_GET['id'];

    if ($action == 'approve_company') {
        $conn->query("UPDATE users SET status='approved' WHERE id=$id AND type='company'");
    } elseif ($action == 'reject_company') {
        $conn->query("UPDATE users SET status='rejected' WHERE id=$id AND type='company'");
    } elseif ($action == 'delete_company') {
        $conn->query("DELETE FROM users WHERE id=$id AND type='company'");
    } elseif ($action == 'delete_job') {
        $conn->query("DELETE FROM jobs WHERE id=$id");
    } elseif ($action == 'accept_application') {
        $conn->query("UPDATE applications SET status='accepted' WHERE id=$id");
    } elseif ($action == 'reject_application') {
        $conn->query("UPDATE applications SET status='rejected' WHERE id=$id");
    } elseif ($action == 'archive_application') {
        $conn->query("UPDATE applications SET status='archived' WHERE id=$id");
    } elseif ($action == 'delete_category') {
        $conn->query("DELETE FROM categories WHERE id=$id");
    } elseif ($action == 'delete_admin') {
        // Prevent deleting self
        if ($id != $_SESSION['user_id']) {
            $conn->query("DELETE FROM users WHERE id=$id AND type='admin'");
        }
    }
    header("Location: admin_dashboard.php");
    exit();
}

// Fetch data
$companies = $conn->query("SELECT * FROM users WHERE type='company'");
$jobs = $conn->query("SELECT jobs.*, users.name as company_name FROM jobs JOIN users ON jobs.company_id = users.id");
$applications = $conn->query("SELECT applications.*, jobs.title as job_title FROM applications JOIN jobs ON applications.job_id = jobs.id");
$categories = $conn->query("SELECT * FROM categories");
$admins = $conn->query("SELECT * FROM users WHERE type='admin'");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord Admin - AMS</title>
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
                <span>Admin: Sergiahos  <?php echo $_SESSION['user_name']; ?></span>
                <a href="logout.php" class="btn btn-sm btn-outline-danger ms-2">Déconnexion</a>
            </div>
        </div>
    </nav>

    <main class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Tableau de Bord Administrateur</h2>
            <a href="index.php" class="btn btn-primary">Retour à l'accueil</a>
        </div>

        <!-- Admins -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Gestion des Administrateurs</h5>
                <a href="add_admin.php" class="btn btn-success btn-sm">Ajouter un admin</a>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($admin = $admins->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $admin['name']; ?></td>
                            <td><?php echo $admin['email']; ?></td>
                            <td>
                                <?php if ($admin['id'] != $_SESSION['user_id']): ?>
                                    <a href="?action=delete_admin&id=<?php echo $admin['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer?')">Supprimer</a>
                                <?php else: ?>
                                    <span class="text-muted">Vous-même</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        </div>

        <!-- Companies -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Gestion des Entreprises</h5>
                <a href="company_register.php?admin=1" class="btn btn-success btn-sm">Ajouter une entreprise</a>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Logo</th>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($company = $companies->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <?php if (!empty($company['image']) && file_exists($company['image'])): ?>
                                    <img src="<?php echo htmlspecialchars($company['image']); ?>" alt="Logo" style="width:50px;height:50px;object-fit:cover;border-radius:50%;" />
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td><?php echo $company['name']; ?></td>
                            <td><?php echo $company['email']; ?></td>
                            <td>
                                <?php 
                                $status_class = $company['status'] == 'approved' ? 'success' : ($company['status'] == 'pending' ? 'warning' : 'danger');
                                $status_text = $company['status'] == 'approved' ? 'Approuvé' : ($company['status'] == 'pending' ? 'En attente' : 'Rejeté');
                                ?>
                                <span class="badge bg-<?php echo $status_class; ?>" style="color: white;"><?php echo $status_text; ?></span>
                            </td>
                            <td>
                                <?php if ($company['status'] == 'pending'): ?>
                                    <a href="?action=approve_company&id=<?php echo $company['id']; ?>" class="btn btn-sm btn-success">Approuver</a>
                                    <a href="?action=reject_company&id=<?php echo $company['id']; ?>" class="btn btn-sm btn-warning">Rejeter</a>
                                <?php endif; ?>
                                <a href="edit_company.php?id=<?php echo $company['id']; ?>" class="btn btn-sm btn-info">Modifier</a>
                                <a href="?action=delete_company&id=<?php echo $company['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer?')">Supprimer</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Jobs -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Gestion des Offres</h5>
                <a href="add_job.php" class="btn btn-success btn-sm">Ajouter une offre</a>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Titre</th>
                            <th>Entreprise</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($job = $jobs->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $job['title']; ?></td>
                            <td><?php echo $job['company_name']; ?></td>
                            <td>
                                <?php 
                                $status_class = $job['status'] == 'active' ? 'success' : 'danger';
                                $status_text = $job['status'] == 'active' ? 'Actif' : 'Inactif';
                                ?>
                                <span class="badge bg-<?php echo $status_class; ?>" style="color: white;"><?php echo $status_text; ?></span>
                            </td>
                            <td>
                                <a href="edit_job.php?id=<?php echo $job['id']; ?>" class="btn btn-sm btn-warning">Modifier</a>
                                <a href="?action=delete_job&id=<?php echo $job['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer?')">Supprimer</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Categories -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Gestion des Catégories</h5>
                <a href="add_category.php" class="btn btn-success btn-sm">Ajouter une catégorie</a>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($cat = $categories->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $cat['name']; ?></td>
                            <td><?php echo $cat['description']; ?></td>
                            <td>
                                <a href="edit_category.php?id=<?php echo $cat['id']; ?>" class="btn btn-sm btn-warning">Modifier</a>
                                <a href="?action=delete_category&id=<?php echo $cat['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer?')">Supprimer</a>
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
                <h5>Candidatures</h5>
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
                            <td><?php echo $app['job_title']; ?></td>
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
                                <a href="?action=accept_application&id=<?php echo $app['id']; ?>" class="btn btn-sm btn-success">Accepter</a>
                                <a href="?action=reject_application&id=<?php echo $app['id']; ?>" class="btn btn-sm btn-danger">Rejeter</a>
                                <a href="?action=archive_application&id=<?php echo $app['id']; ?>" class="btn btn-sm btn-secondary">Archiver</a>
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