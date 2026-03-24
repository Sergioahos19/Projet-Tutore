<?php
include 'database.php';
$job_id = $_GET['id'] ?? 0;
$job_result = $conn->query("SELECT jobs.*, users.name as company_name, users.email as company_email, users.image as company_image FROM jobs JOIN users ON jobs.company_id = users.id WHERE jobs.id=$job_id AND jobs.status='active'");
if ($job_result->num_rows == 0) {
    header("Location: job-listings.php");
    exit();
}
$job = $job_result->fetch_assoc();

// Handle application
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $cover_letter = $_POST['cover_letter'];

    // Handle CV upload
    $cv = '';
    if (isset($_FILES['cv']) && $_FILES['cv']['error'] == 0) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        $cv = $target_dir . basename($_FILES["cv"]["name"]);
        move_uploaded_file($_FILES["cv"]["tmp_name"], $cv);
    }

    $sql = "INSERT INTO applications (job_id, applicant_name, applicant_email, phone, cv, cover_letter) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssss", $job_id, $name, $email, $phone, $cv, $cover_letter);
    $stmt->execute();

    // Send email notification to company
    $subject = "Nouvelle candidature pour: " . $job['title'];
    $message = "Une nouvelle candidature a été reçue pour l'offre: " . $job['title'] . "\n\n";
    $message .= "Candidat: $name\n";
    $message .= "Email: $email\n";
    $message .= "Téléphone: $phone\n";
    $message .= "CV: $cv\n\n";
    $message .= "Lettre de motivation:\n$cover_letter";

    $headers = "From: noreply@ams.com\r\n";
    @mail($job['company_email'], $subject, $message, $headers);

    $success = "Candidature soumise avec succès! L'entreprise a été notifiée.";
}
?>

<!doctype html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta name="description" content="">
        <meta name="author" content="">

        <title>Détails de l'offre AMS</title>

        <!-- CSS FILES -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

        <link href="https://fonts.googleapis.com/css2?family=League+Spartan:wght@100;300;400;600;700&display=swap" rel="stylesheet">

        <link href="css/bootstrap.min.css" rel="stylesheet">

        <link href="css/bootstrap-icons.css" rel="stylesheet">

        <link href="css/owl.carousel.min.css" rel="stylesheet">

        <link href="css/owl.theme.default.min.css" rel="stylesheet">

        <link href="css/tooplate-gotto-job.css" rel="stylesheet">
        
<!--

Tooplate 2134 Gotto Job

https://www.tooplate.com/view/2134-gotto-job

Bootstrap 5 HTML CSS Template

-->
    </head>
    
    <body id="top">

        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center" href="index.php">
                    <img src="images/logo.png" class="img-fluid logo-image">

                    <div class="d-flex flex-column">
                        <strong class="logo-text">AMS</strong>
                        <small class="logo-slogan">Portail d'emploi en ligne</small>
                    </div>
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav align-items-center ms-lg-5">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php">Page d'accueil</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="about.php">À propos d'AMS</a>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarLightDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">Pages</a>

                            <ul class="dropdown-menu dropdown-menu-light" aria-labelledby="navbarLightDropdownMenuLink">
                                <li><a class="dropdown-item" href="job-listings.php">Liste des emplois</a></li>

                                <li><a class="dropdown-item" href="job-listings.php">Détails de l'emploi</a></li>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link active" href="contact.php">Contact</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="admin_login.php">Admin</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <main>

            <header class="site-header">
                <div class="section-overlay"></div>

                <div class="container">
                    <div class="row">
                        
                        <div class="col-lg-12 col-12 text-center">
                            <h1 class="text-white">Détails de l'emploi</h1>

                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb justify-content-center">
                                    <li class="breadcrumb-item"><a href="index.php">Accueil</a></li>

                                    <li class="breadcrumb-item"><a href="job-listings.php">Offres d'emploi</a></li>

                                    <li class="breadcrumb-item active" aria-current="page">Détails de l'emploi</li>
                                </ol>
                            </nav>
                        </div>

                    </div>
                </div>
            </header>


            <section class="job-section section-padding pb-0">
                <div class="container">
                    <div class="row">

                        <div class="col-lg-8 col-12">
                            <div class="job-thumb d-flex">
                                <div class="job-image-wrap bg-white shadow-lg">
                                    <img src="<?php echo $job['company_image'] ?: 'images/logo.png'; ?>" class="job-image img-fluid" alt="">
                                </div>

                                <div class="job-body d-flex flex-wrap flex-auto align-items-center ms-4">
                                    <div class="mb-3">
                                        <h4 class="job-title mb-lg-3 mb-0"><?php echo $job['title']; ?></h4>

                                        <div class="d-flex flex-wrap align-items-center">
                                            <p class="job-location mb-0">
                                                <i class="custom-icon bi-geo-alt me-1"></i>
                                                <?php echo $job['location']; ?>
                                            </p>

                                            <p class="job-date mb-0">
                                                <i class="custom-icon bi-clock me-1"></i>
                                                <?php echo date('d/m/Y', strtotime($job['created_at'])); ?>
                                            </p>

                                            <p class="job-price mb-0">
                                                <i class="custom-icon bi-cash me-1"></i>
                                                <?php echo $job['salary']; ?> €
                                            </p>
                                        </div>
                                    </div>

                                    <div class="job-section-btn-wrap">
                                        <a href="#apply" class="custom-btn btn">Postuler maintenant</a>
                                    </div>
                                </div>
                            </div>

                            <div class="job-description mt-5">
                                <h3>Description de l'emploi</h3>
                                <p><?php echo nl2br($job['description']); ?></p>
                            </div>

                            <div class="job-description mt-4">
                                <h3>Exigences</h3>
                                <p><?php echo nl2br($job['requirements']); ?></p>
                            </div>
                        </div>

                        <div class="col-lg-4 col-12 mt-5 mt-lg-0">
                            <div class="job-summary-wrap">
                                <h4>Résumé de l'emploi</h4>

                                <p><?php echo $job['company_name']; ?></p>

                                <div class="job-summary-list">
                                    <ul class="list-unstyled">
                                        <li>Publiée le: <span><?php echo date('d/m/Y', strtotime($job['created_at'])); ?></span></li>
                                        <li>Salaire: <span><?php echo $job['salary']; ?> €</span></li>
                                        <li>Localisation: <span><?php echo $job['location']; ?></span></li>
                                        <li>Type: <span><?php echo $job['type']; ?></span></li>
                                        <li>Catégorie: <span><?php echo $job['category']; ?></span></li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </section>

            <section class="job-section section-padding" id="apply">
                <div class="container">
                    <div class="row">

                        <div class="col-lg-8 col-12">
                            <div class="job-application">
                                <h3>Postuler pour cet emploi</h3>
                                <?php if (isset($success)) echo "<p class='text-success'>$success</p>"; ?>
                                <form class="custom-form job-application-form" action="" method="post" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-lg-6 col-12">
                                            <input type="text" name="name" id="name" class="form-control" placeholder="Nom complet" pattern="[A-Za-zÀ-ÿ\s]+" title="Seules les lettres et espaces sont autorisés" required>
                                        </div>

                                        <div class="col-lg-6 col-12">
                                            <input type="email" name="email" id="email" class="form-control" placeholder="Email" required>
                                        </div>

                                        <div class="col-lg-6 col-12">
                                            <input type="tel" name="phone" id="phone" class="form-control" placeholder="Téléphone" pattern="[\d\s\-\+\(\)]+" title="Format de téléphone invalide" required>
                                        </div>

                                        <div class="col-lg-6 col-12">
                                            <input type="file" name="cv" id="cv" class="form-control" accept=".pdf,.doc,.docx" required>
                                        </div>

                                        <div class="col-12">
                                            <textarea name="cover_letter" rows="4" class="form-control" id="cover_letter" placeholder="Lettre de motivation" required></textarea>
                                        </div>

                                        <div class="col-12">
                                            <button type="submit" class="custom-btn btn">Soumettre la candidature</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </section>





            <section class="cta-section">
                <div class="section-overlay"></div>

                <div class="container">
                    <div class="row">

                        <div class="col-lg-6 col-10">
                            <h2 class="text-white mb-2">AMS vous aide à trouver un nouvel emploi plus facilement</h2>

                            <p class="text-white">Les entreprises doivent se connecter et ajouter des offres pour les rendre visibles sur la plateforme.</p>
                        </div>

                        <div class="col-lg-4 col-12 ms-auto">
                            <div class="custom-border-btn-wrap d-flex align-items-center mt-lg-4 mt-2">
                                <a href="#" class="custom-btn custom-border-btn btn me-4">Créer un compte</a>

                                <a href="#" class="custom-link">Publier une offre</a>
                            </div>
                        </div>

                    </div>
                </div>
            </section>
        </main>

        <footer class="site-footer">
            <div class="container">
                <div class="row">
                    
                    <center> 
                    <div class="col-lg-4 col-md-6 col-12">
                        <div class="newsletter-item">
                            <h6 class="site-footer-title">Newsletter</h6>

                            <form class="custom-form newsletter-form" action="#" method="post" role="form">
                                <h6 class="site-footer-title">Recevoir les nouvelles des emplois</h6>

                                <div class="input-group">
                                    <span class="input-group-text" id="basic-addon1"><i class="bi-person"></i></span>

                                    <input type="text" name="newsletter-name" id="newsletter-name" class="form-control" placeholder="tonnom@gmail.com" required>

                                    <button type="submit" class="form-control">
                                        <i class="bi-send"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                   
                </div>
            </div>
            </center>

            <div class="site-footer-bottom">
                <div class="container">
                    <div class="row justify-content-center align-items-center">

                        <div class="col-lg-12 col-12 text-center">
                            <div class="d-flex justify-content-center align-items-center flex-wrap gap-3">
                                <p class="copyright-text mb-0">Copyright © AMS 2026</p>

                                <ul class="footer-menu d-flex mb-0">
                                    <li class="footer-menu-item"><a href="#" class="footer-menu-link">Politique de confidentialité</a></li>

                                    <li class="footer-menu-item"><a href="#" class="footer-menu-link">Conditions</a></li>
                                </ul>
                            </div>

                            <ul class="social-icon justify-content-center mt-3">
                        
                                <li class="social-icon-item">
                                    <a href="#" class="social-icon-link bi-facebook"></a>
                                </li>

                                <li class="social-icon-item">
                                    <a href="#" class="social-icon-link bi-linkedin"></a>
                                </li>

                                <li class="social-icon-item">
                                    <a href="#" class="social-icon-link bi-instagram"></a>
                                </li>

                                <li class="social-icon-item">
                                    <a href="#" class="social-icon-link bi-youtube"></a>
                                </li>
                            </ul>
                        </div>

                    </div>

                    <a class="back-top-icon bi-arrow-up smoothscroll d-flex justify-content-center align-items-center" href="#top"></a>

                </div>
            </div>
        </footer>

        <!-- JAVASCRIPT FILES -->
        <script src="js/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/owl.carousel.min.js"></script>
        <script src="js/counter.js"></script>
        <script src="js/custom.js"></script>

    </body>
</html>