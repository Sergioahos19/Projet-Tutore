<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'company') {
    header("Location: company_login.php");
    exit();
}
include 'database.php';

// Fetch categories
$categories = $conn->query("SELECT * FROM categories");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $conn->real_escape_string($_POST['title']);
    $description = $conn->real_escape_string($_POST['description']);
    $location = $conn->real_escape_string($_POST['location']);
    $salary = $conn->real_escape_string($_POST['salary']);
    $type = $conn->real_escape_string($_POST['type']);
    $category = $conn->real_escape_string($_POST['category']);
    $requirements = $conn->real_escape_string($_POST['requirements']);
    $benefits = $conn->real_escape_string($_POST['benefits']);
    $company_id = $_SESSION['user_id'];

    $sql = "INSERT INTO jobs (company_id, title, description, location, salary, type, category, requirements, benefits, status) 
            VALUES ($company_id, '$title', '$description', '$location', '$salary', '$type', '$category', '$requirements', '$benefits', 'active')";
    
    if ($conn->query($sql)) {
        header("Location: company_dashboard.php");
        exit();
    } else {
        $error = "Erreur : " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une Offre - AMS</title>
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
                <a href="company_dashboard.php" class="btn btn-sm btn-outline-primary">Retour</a>
            </div>
        </div>
    </nav>

    <main>
        <section class="section-padding">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header text-center">
                                <h3>Ajouter une Offre d'emploi</h3>
                            </div>
                            <div class="card-body">
                                <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
                                <form method="POST">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="title" class="form-label">Titre du poste</label>
                                            <input type="text" class="form-control" id="title" name="title" pattern="[A-Za-zÀ-ÿ\s]+" title="Seules les lettres et espaces sont autorisés" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="location" class="form-label">Lieu</label>
                                            <input type="text" class="form-control" id="location" name="location" pattern="[A-Za-zÀ-ÿ\s]+" title="Seules les lettres et espaces sont autorisés" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="salary" class="form-label">Salaire</label>
                                            <input type="text" class="form-control" id="salary" name="salary" pattern="\d+" title="Seuls les chiffres sont autorisés">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="type" class="form-label">Type</label>
                                            <select class="form-control" id="type" name="type" required>
                                                <option value="full-time">Temps plein</option>
                                                <option value="part-time">Temps partiel</option>
                                                <option value="contract">Contrat</option>
                                                <option value="internship">Stage</option>
                                            </select>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label for="category" class="form-label">Catégorie</label>
                                            <select class="form-control" id="category" name="category" required>
                                                <option value="">Sélectionner une catégorie</option>
                                                <?php while ($cat = $categories->fetch_assoc()): ?>
                                                    <option value="<?php echo $cat['name']; ?>"><?php echo $cat['name']; ?></option>
                                                <?php endwhile; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label for="description" class="form-label">Description</label>
                                            <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label for="requirements" class="form-label">Exigences</label>
                                            <textarea class="form-control" id="requirements" name="requirements" rows="3"></textarea>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label for="benefits" class="form-label">Avantages</label>
                                            <textarea class="form-control" id="benefits" name="benefits" rows="3"></textarea>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100">Ajouter l'offre</button>
                                    <div class="d-flex gap-2 mt-3">
                                        <button type="reset" class="btn btn-secondary flex-fill">Annuler</button>
                                        <a href="index.php" class="btn btn-outline-primary flex-fill">Retour à l'accueil</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script src="js/bootstrap.min.js"></script>
</body>
</html>