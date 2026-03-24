<?php
session_start();
include 'database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $ifu = $_POST['ifu'];
    $location = $_POST['location'];
    $field = $_POST['field'];
    $commitment = $_POST['commitment'];

    // Handle file upload
    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        $image = $target_dir . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $image);
    }

    $sql = "INSERT INTO users (type, name, email, password, ifu, location, field, image, commitment) VALUES ('company', ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssss", $name, $email, $password, $ifu, $location, $field, $image, $commitment);

    if ($stmt->execute()) {
        if (isset($_GET['admin']) && $_GET['admin'] == '1') {
            // Si l'admin crée l'entreprise depuis son dashboard, on l'approuve directement
            $lastId = $conn->insert_id;
            $conn->query("UPDATE users SET status='approved' WHERE id=$lastId");
            $success = "Entreprise ajoutée et approuvée avec succès.";
        } else {
            $success = "Inscription soumise. En attente d'approbation par l'administrateur.";
        }
    } else {
        $error = "Erreur lors de l'inscription.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription Entreprise - AMS</title>
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
        </div>
    </nav>

    <main>
        <section class="section-padding">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header text-center">
                                <h3>Inscription Entreprise</h3>
                            </div>
                            <div class="card-body">
                                <?php if (isset($success)) echo "<div class='alert alert-success'>$success</div>"; ?>
                                <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
                                <form method="POST" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="name" class="form-label">Nom de l'entreprise</label>
                                            <input type="text" class="form-control" id="name" name="name" pattern="[A-Za-zÀ-ÿ\s]+" title="Seules les lettres et espaces sont autorisés" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="email" class="form-label">Email de l'entreprise</label>
                                            <input type="email" class="form-control" id="email" name="email" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="password" class="form-label">Mot de passe</label>
                                            <input type="password" class="form-control" id="password" name="password" minlength="6" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="ifu" class="form-label">Numéro IFU</label>
                                            <input type="text" class="form-control" id="ifu" name="ifu" pattern="\d+" title="Seuls les chiffres sont autorisés" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="location" class="form-label">Localisation</label>
                                            <input type="text" class="form-control" id="location" name="location" pattern="[A-Za-zÀ-ÿ\s]+" title="Seules les lettres et espaces sont autorisés" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="field" class="form-label">Domaine d'activité</label>
                                            <input type="text" class="form-control" id="field" name="field" pattern="[A-Za-zÀ-ÿ\s]+" title="Seules les lettres et espaces sont autorisés" required>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label for="image" class="form-label">Image de l'entreprise</label>
                                            <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label for="commitment" class="form-label">Engagement</label>
                                            <textarea class="form-control" id="commitment" name="commitment" rows="3" required></textarea>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100">S'inscrire</button>
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