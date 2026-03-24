<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'company') {
    header("Location: company_login.php");
    exit();
}
include 'database.php';
$user_id = $_SESSION['user_id'];
$job_id = $_GET['id'];

// Fetch job
$job_result = $conn->query("SELECT * FROM jobs WHERE id=$job_id AND company_id=$user_id");
if ($job_result->num_rows == 0) {
    header("Location: company_dashboard.php");
    exit();
}
$job = $job_result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $location = $_POST['location'];
    $salary = $_POST['salary'];
    $type = $_POST['type'];
    $category = $_POST['category'];
    $requirements = $_POST['requirements'];
    $benefits = $_POST['benefits'];

    $sql = "UPDATE jobs SET title=?, description=?, location=?, salary=?, type=?, category=?, requirements=?, benefits=? WHERE id=? AND company_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssii", $title, $description, $location, $salary, $type, $category, $requirements, $benefits, $job_id, $user_id);

    if ($stmt->execute()) {
        header("Location: company_dashboard.php");
        exit();
    } else {
        $error = "Erreur lors de la modification.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier une Offre - AMS</title>
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
                                <h3>Modifier l'Offre d'emploi</h3>
                            </div>
                            <div class="card-body">
                                <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
                                <form method="POST">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="title" class="form-label">Titre du poste</label>
                                            <input type="text" class="form-control" id="title" name="title" value="<?php echo $job['title']; ?>" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="location" class="form-label">Lieu</label>
                                            <input type="text" class="form-control" id="location" name="location" value="<?php echo $job['location']; ?>" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="salary" class="form-label">Salaire</label>
                                            <input type="text" class="form-control" id="salary" name="salary" value="<?php echo $job['salary']; ?>">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="type" class="form-label">Type</label>
                                            <select class="form-control" id="type" name="type" required>
                                                <option value="full-time" <?php if ($job['type'] == 'full-time') echo 'selected'; ?>>Temps plein</option>
                                                <option value="part-time" <?php if ($job['type'] == 'part-time') echo 'selected'; ?>>Temps partiel</option>
                                                <option value="contract" <?php if ($job['type'] == 'contract') echo 'selected'; ?>>Contrat</option>
                                                <option value="internship" <?php if ($job['type'] == 'internship') echo 'selected'; ?>>Stage</option>
                                            </select>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label for="category" class="form-label">Catégorie</label>
                                            <input type="text" class="form-control" id="category" name="category" value="<?php echo $job['category']; ?>">
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label for="description" class="form-label">Description</label>
                                            <textarea class="form-control" id="description" name="description" rows="4" required><?php echo $job['description']; ?></textarea>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label for="requirements" class="form-label">Exigences</label>
                                            <textarea class="form-control" id="requirements" name="requirements" rows="3"><?php echo $job['requirements']; ?></textarea>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label for="benefits" class="form-label">Avantages</label>
                                            <textarea class="form-control" id="benefits" name="benefits" rows="3"><?php echo $job['benefits']; ?></textarea>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100">Modifier l'offre</button>
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