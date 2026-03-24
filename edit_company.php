<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'admin') {
    header("Location: admin_login.php");
    exit();
}
include 'database.php';

$id = $_GET['id'];
$company = $conn->query("SELECT * FROM users WHERE id=$id AND type='company'")->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $ifu = $_POST['ifu'];
    $location = $_POST['location'];
    $field = $_POST['field'];
    $commitment = $_POST['commitment'];

    // Handle file upload
    $image = $company['image'];
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        $image = $target_dir . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $image);
    }

    $stmt = $conn->prepare("UPDATE users SET name=?, email=?, ifu=?, location=?, field=?, image=?, commitment=? WHERE id=?");
    $stmt->bind_param("sssssssi", $name, $email, $ifu, $location, $field, $image, $commitment, $id);
    $stmt->execute();
    $stmt->close();

    header("Location: admin_dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier une Entreprise - AMS</title>
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
                <span>Admin: <?php echo $_SESSION['user_name']; ?></span>
                <a href="logout.php" class="btn btn-sm btn-outline-danger ms-2">Déconnexion</a>
            </div>
        </div>
    </nav>

    <main class="container mt-4">
        <h2>Modifier une Entreprise</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">Nom de l'entreprise</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?php echo $company['name']; ?>" pattern="[A-Za-zÀ-ÿ\s]+" title="Seules les lettres et espaces sont autorisés" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">Email de l'entreprise</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo $company['email']; ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="ifu" class="form-label">Numéro IFU</label>
                    <input type="text" class="form-control" id="ifu" name="ifu" value="<?php echo $company['ifu']; ?>" pattern="\d+" title="Seuls les chiffres sont autorisés" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="location" class="form-label">Localisation</label>
                    <input type="text" class="form-control" id="location" name="location" value="<?php echo $company['location']; ?>" pattern="[A-Za-zÀ-ÿ\s]+" title="Seules les lettres et espaces sont autorisés" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="field" class="form-label">Domaine d'activité</label>
                    <input type="text" class="form-control" id="field" name="field" value="<?php echo $company['field']; ?>" pattern="[A-Za-zÀ-ÿ\s]+" title="Seules les lettres et espaces sont autorisés" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="image" class="form-label">Logo</label>
                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                </div>
                <div class="col-md-12 mb-3">
                    <label for="commitment" class="form-label">Engagement</label>
                    <textarea class="form-control" id="commitment" name="commitment" rows="3"><?php echo $company['commitment']; ?></textarea>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Modifier</button>
            <a href="admin_dashboard.php" class="btn btn-secondary">Annuler</a>
        </form>
    </main>

    <script src="js/bootstrap.min.js"></script>
</body>
</html>