<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'admin') {
    header("Location: admin_login.php");
    exit();
}
include 'database.php';

$id = $_GET['id'];
$category = $conn->query("SELECT * FROM categories WHERE id=$id")->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];

    $stmt = $conn->prepare("UPDATE categories SET name=?, description=? WHERE id=?");
    $stmt->bind_param("ssi", $name, $description, $id);
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
    <title>Modifier une Catégorie - AMS</title>
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
        <h2>Modifier une Catégorie</h2>
        <form method="post">
            <div class="mb-3">
                <label for="name" class="form-label">Nom de la Catégorie</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo $category['name']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3"><?php echo $category['description']; ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Modifier</button>
            <a href="admin_dashboard.php" class="btn btn-secondary">Annuler</a>
        </form>
    </main>

    <script src="js/bootstrap.min.js"></script>
</body>
</html>