<?php
include 'database.php';

$where_clauses = ["jobs.status='active'"];

if (isset($_GET['category']) && !empty($_GET['category'])) {
    $category = $conn->real_escape_string($_GET['category']);
    $where_clauses[] = "jobs.category = '$category'";
}

if (isset($_GET['job-title']) && !empty($_GET['job-title'])) {
    $title = $conn->real_escape_string($_GET['job-title']);
    $where_clauses[] = "jobs.title LIKE '%$title%'";
}

if (isset($_GET['job-location']) && !empty($_GET['job-location'])) {
    $location = $conn->real_escape_string($_GET['job-location']);
    $where_clauses[] = "jobs.location LIKE '%$location%'";
}

$where_sql = implode(' AND ', $where_clauses);
$jobs = $conn->query("SELECT jobs.*, users.name as company_name, users.image as company_image FROM jobs JOIN users ON jobs.company_id = users.id WHERE $where_sql ORDER BY jobs.created_at DESC");
$categories = $conn->query("SELECT * FROM categories");
?>

<!doctype html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta name="description" content="">
        <meta name="author" content="">

        <title>Liste des offres AMS</title>

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
    
    <body class="job-listings-page" id="top">

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
                            <h1 class="text-white">Liste des emplois</h1>

                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb justify-content-center">
                                    <li class="breadcrumb-item"><a href="index.php">Accueil</a></li>

                                    <li class="breadcrumb-item active" aria-current="page">Liste des emplois</li>
                                </ol>
                            </nav>
                        </div>

                    </div>
                </div>
            </header>

            <section class="section-padding pb-0 d-flex justify-content-center align-items-center">
                <div class="container">
                    <div class="row">

                        <div class="col-lg-12 col-12">
                            <form class="custom-form hero-form" action="#" method="get" role="form">
                                <h3 class="text-white mb-3">Recherchez votre emploi de rêve</h3>
                                
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-12">
                                        <div class="input-group">
                                            <span class="input-group-text" id="basic-addon1"><i class="bi-person custom-icon"></i></span>

                                            <input type="text" name="job-title" id="job-title" class="form-control" placeholder="Titre du poste" required>
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-12">
                                        <div class="input-group">
                                            <span class="input-group-text" id="basic-addon1"><i class="bi-geo-alt custom-icon"></i></span>

                                            <input type="text" name="job-location" id="job-location" class="form-control" placeholder="Lieu" required>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-md-4 col-12">
                                        <div class="input-group">
                                            <span class="input-group-text" id="basic-addon1"><i class="bi-cash custom-icon"></i></span>

                                            <select class="form-select form-control" name="job-salary" id="job-salary" aria-label="Default select example">
                                                <option selected>Fourchette salariale</option>
                                                <option value="1">300k - 500k $</option>
                                                <option value="2">10 000k - 45 000k $</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-md-4 col-12">
                                        <div class="input-group">
                                            <span class="input-group-text" id="basic-addon1"><i class="bi-laptop custom-icon"></i></span>

                                            <select class="form-select form-control" name="job-level" id="job-level" aria-label="Default select example">
                                                <option selected>Niveau</option>
                                                <option value="1">Stage</option>
                                                <option value="2">Junior</option>
                                                <option value="2">Senior</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-md-4 col-12">
                                        <div class="input-group">
                                            <span class="input-group-text" id="basic-addon1"><i class="bi-laptop custom-icon"></i></span>

                                            <select class="form-select form-control" name="job-remote" id="job-remote" aria-label="Default select example">
                                                <option selected>Télétravail</option>
                                                <option value="1">Temps plein</option>
                                                <option value="2">Contrat</option>
                                                <option value="2">Temps partiel</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-12 col-12">
                                        <button type="submit" class="form-control">
                                            Rechercher un emploi
                                        </button>
                                    </div>

                                    <div class="col-12">
                                        <div class="d-flex flex-wrap align-items-center mt-4 mt-lg-0">
                                            <span class="text-white mb-lg-0 mb-md-0 me-2">Mots-clés populaires :</span>

                                            <div>
                                                <a href="job-listings.php" class="badge">Web design</a>

                                                <a href="job-listings.php" class="badge">Marketing</a>

                                                <a href="job-listings.php" class="badge">Customer support</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="col-lg-6 col-12">
                            <img src="images/4557388.png" class="hero-image img-fluid" alt="">
                        </div>

                    </div>
                </div>
            </section>


            <section class="job-section section-padding">
                <div class="container">
                    <div class="row align-items-center">
                        <?php if ($jobs->num_rows > 0): ?>
                            <?php while ($job = $jobs->fetch_assoc()) { ?>
                        <div class="col-lg-4 col-md-6 col-12">
                            <div class="job-thumb job-thumb-box">
                                <div class="job-image-box-wrap">
                                    <a href="job-details.php?id=<?php echo $job['id']; ?>">
                                        <img src="<?php echo $job['company_image'] ?: 'images/logo.png'; ?>" class="job-image img-fluid" alt="">
                                    </a>
                                </div>

                                <div class="job-body">
                                    <h4 class="job-title">
                                        <a href="job-details.php?id=<?php echo $job['id']; ?>" class="job-title-link"><?php echo $job['title']; ?></a>
                                    </h4>

                                    <div class="d-flex align-items-center">
                                        <div class="job-image-wrap d-flex align-items-center bg-white shadow-lg mt-2 mb-4">
                                            <img src="<?php echo $job['company_image'] ?: 'images/logo.png'; ?>" class="job-image me-3 img-fluid" alt="">
                                            <p class="mb-0"><?php echo $job['company_name']; ?></p>
                                        </div>
                                    </div>

                                    <div class="d-flex align-items-center">
                                        <p class="job-location">
                                            <i class="custom-icon bi-geo-alt me-1"></i>
                                            <?php echo $job['location']; ?>
                                        </p>

                                        <p class="job-date">
                                            <i class="custom-icon bi-clock me-1"></i>
                                            <?php echo date('d/m/Y', strtotime($job['created_at'])); ?>
                                        </p>
                                    </div>

                                    <div class="d-flex align-items-center border-top pt-3">
                                        <p class="job-price mb-0">
                                            <i class="custom-icon bi-cash me-1"></i>
                                            <?php echo $job['salary'] ?: 'N/A'; ?>
                                        </p>

                                        <a href="job-details.php?id=<?php echo $job['id']; ?>" class="custom-btn btn ms-auto">Voir détails</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                            <?php } ?>
                        <?php else: ?>
                        <div class="col-lg-12 col-12 text-center mb-4">
                            <h3>Aucune offre n'est disponible</h3>
                            <p>Les entreprises doivent se connecter et publier des offres pour qu'elles s'affichent sur cette page.</p>
                        </div>
                        <?php endif; ?>
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