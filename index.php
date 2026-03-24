<?php
include 'database.php';

// Fetch categories from categories table
$categories_result = $conn->query("SELECT * FROM categories");
$categories = [];
while ($row = $categories_result->fetch_assoc()) {
    $categories[] = $row['name'];
}

// fallback categories if none in categories table
if (empty($categories)) {
    $categories_result = $conn->query("SELECT DISTINCT category FROM jobs WHERE status='active' AND category IS NOT NULL AND category != ''");
    while ($row = $categories_result->fetch_assoc()) {
        $categories[] = $row['category'];
    }
}

// Fetch all active jobs
$featured_jobs = $conn->query("SELECT jobs.*, users.name as company_name, users.image as company_image FROM jobs JOIN users ON jobs.company_id = users.id WHERE jobs.status='active' ORDER BY jobs.created_at DESC");
$recent_jobs = $conn->query("SELECT jobs.*, users.name as company_name, users.image as company_image FROM jobs JOIN users ON jobs.company_id = users.id WHERE jobs.status='active' ORDER BY jobs.created_at DESC");

?>

<!doctype html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta name="description" content="">
        <meta name="author" content="">

        <title>AMS Portail d'emploi</title>

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
                    <ul class="navbar-nav align-items-center ms-auto">
                        <li class="nav-item">
                            <a class="nav-link active" href="index.php">Accueil</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="about.php">A Propos</a>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarLightDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">Pages</a>

                            <ul class="dropdown-menu dropdown-menu-light" aria-labelledby="navbarLightDropdownMenuLink">
                                <li><a class="dropdown-item" href="job-listings.php">Liste des offres</a></li>

                                <li><a class="dropdown-item" href="job-listings.php">Détails de l'offre</a></li>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="contact.php">Contact</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="admin_login.php">Admin</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="company_register.php">S'inscrire</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link custom-btn btn" href="company_login.php">Se connecter</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <main>
            <br><br>
            <section class="about-section">
                <div class="container">
                    <div class="row">

                        <div class="col-lg-3 col-12">
                            <div class="about-image-wrap custom-border-radius-start">
                                <img src="images/sergiahos.jpeg" class="about-image custom-border-radius-start img-fluid" alt="">

                                <div class="about-info">
                                    <h4 class="text-white mb-0 me-2">Sergiahos</h4>
                                    <center>
                                    <p class="text-white mb-0">Creator</p>
                                </center>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 col-12">
                            <div class="custom-text-block">
                                <h2 class="text-white mb-2">Bienvenue sur AMS, votre portail d'emploi en ligne</h2>

                                <p class="text-white">Dans un monde en constante évolution, trouver le bon emploi ou le bon talent peut être un véritable défi. AMS se positionne comme la solution idéale en mettant en relation les chercheurs d'emploi et les recruteurs à travers une plateforme simple, rapide et efficace.

Que vous soyez à la recherche de nouvelles opportunités professionnelles ou que vous souhaitiez recruter les meilleurs profils, AMS vous accompagne à chaque étape. Grâce à une interface intuitive et des outils performants, accédez à des offres variées, postulez facilement et donnez un nouvel élan à votre carrière.

Avec AMS, l'emploi devient accessible, transparent et adapté aux réalités du marché.</p>

                                <div class="custom-border-btn-wrap d-flex align-items-center mt-5">
                                    <a href="about.php" class="custom-btn custom-border-btn btn me-4">Apprenez à nous connaître</a>

                                    <a href="#job-section" class="custom-link smoothscroll">Explorer les emplois</a>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-12">
                            <div class="instagram-block">
                                <img src="images/ams.jpeg" class="about-image custom-border-radius-end img-fluid" alt="">

                                <div class="instagram-block-text">
                                    <a href="https://instagram.com/" class="custom-btn btn">
                                        <i class="bi-instagram"></i>
                                        @AMS
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </section>

            <br><br><br><br>
            <section class="hero-section d-flex justify-content-center align-items-center">
                <div class="section-overlay"></div>

                <div class="container">
                    <div class="row">

                        <div class="col-lg-6 col-12 mb-5 mb-lg-0">
                            <div class="hero-section-text mt-5">
                                <h6 class="text-white">Cherchez-vous le travail de vos rêves ?</h6>

                                <h1 class="hero-title text-white mt-4 mb-4">Platforme en ligne. <br> Meilleure portail d'emploi.</h1>

                                <a href="#categories-section" class="custom-btn custom-border-btn btn">Pacourir les categories</a>
                            </div>
                        </div>

                        <div class="col-lg-6 col-12">
                            <form class="custom-form hero-form" action="#" method="get" role="form">
                                <h3 class="text-white mb-3">Cherche le métier des tes rêves</h3>

                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-12">
                                        <div class="input-group">
                                            <span class="input-group-text" id="basic-addon1"><i class="bi-person custom-icon"></i></span>

                                            <input type="text" name="job-title" id="job-title" class="form-control" placeholder="Titre du poste" required>
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-12">
                                        <div class="input-group">
                                            <span class="input-group-text" id="basic-addon2"><i class="bi-geo-alt custom-icon"></i></span>

                                            <input type="text" name="job-location" id="job-location" class="form-control" placeholder="Lieu" required>
                                        </div>
                                    </div>

                                    <div class="col-lg-12 col-12">
                                        <button type="submit" class="form-control">
                                            Trouver un emploi
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

                    </div>
                </div>
            </section>


            <section class="categories-section section-padding" id="categories-section">
                <div class="container">
                    <div class="row justify-content-center align-items-center">

                        <div class="col-lg-12 col-12 text-center">
                            <h2 class="mb-5">Parcourir par <span>catégories</span></h2>
                            <?php if (count($categories) > 0): ?>
                                <div class="row">
                                    <?php foreach ($categories as $category): ?>
                                        <div class="col-lg-4 col-md-6 col-12 mb-4">
                                            <div class="categories-block">
                                                <h4><?php echo htmlspecialchars($category); ?></h4>
                                                <a href="job-listings.php?category=<?php echo urlencode($category); ?>" class="custom-btn btn">Voir les offres</a>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <p>Aucune catégorie n'est encore disponible. Les catégories s'affichent uniquement après publication d'une offre par une entreprise.</p>
                            <?php endif; ?>
                        </div>

                    </div>
                </div>
            </section>


            <section class="about-section">
                <div class="container">
                    <div class="row">

                        


                     

                        


            <section class="job-section job-featured-section section-padding" id="job-section">
                <div class="container">
                    <div class="row">

                        <div class="col-lg-12 col-12 text-center mb-4">
                            <h2>Emplois en vedette</h2>
                        </div>

                        <?php if ($featured_jobs->num_rows > 0): ?>
                            <?php while ($job = $featured_jobs->fetch_assoc()): ?>
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
                            <?php endwhile; ?>
                        <?php else: ?>
                            <div class="col-lg-12 col-12 text-center mb-4">
                                <p>Aucune offre en vedette n'est disponible pour l'instant. Les offres apparaîtront ici une fois qu'une entreprise aura publié une offre.</p>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>
            </section>

            <section class="job-section recent-jobs-section section-padding">
                <div class="container">
                    <div class="row align-items-center">

                        <div class="col-lg-12 col-12 text-center mb-4">
                            <h2>Emplois récents</h2>
                        </div>

                        <?php if ($recent_jobs->num_rows > 0): ?>
                            <?php while ($job = $recent_jobs->fetch_assoc()): ?>
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
                            <?php endwhile; ?>
                        <?php else: ?>
                            <div class="col-lg-12 col-12 text-center mb-4">
                                <p>Aucun emploi récent n'est affiché pour le moment. Les trois dernières offres publiées par les entreprises apparaîtront ici.</p>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>
            </section>

            <section class="cta-section">

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

            <section class="contact-section section-padding" style="padding-bottom: 20px;">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-12 col-12 text-center mb-5">
                            <h2>Nous Contacter</h2>
                        </div>
                    </div>
                    <div class="row g-2">
                        <div class="col-lg-4 col-md-6 col-12 mb-2 text-center">
                            <div class="contact-item">
                                <h5 class="mb-3"><i class="bi-globe me-2"></i>Site Web</h5>
                                <p><a href="#" class="site-footer-link">www.ams.com</a></p>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-12 mb-2 text-center">
                            <div class="contact-item">
                                <h5 class="mb-3"><i class="bi-telephone me-2"></i>Téléphone</h5>
                                <p><a href="tel: +229 0156036800" class="site-footer-link">+229 0156036800</a></p>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-12 mb-2 text-center">
                            <div class="contact-item">
                                <h5 class="mb-3"><i class="bi-envelope me-2"></i>Email</h5>
                                <p><a href="mailto:visionfuture1919@gmail.com" class="site-footer-link">visionfuture1919@gmail.com</a></p>
                            </div>
                        </div>
                    </div>
                </div>
                <style>
                    .contact-item {
                        transition: all 0.3s ease;
                        padding: 15px;
                        border-radius: 8px;
                        margin: 0 5px;
                    }
                    
                    .contact-item:hover {
                        transform: translateY(-5px);
                        background-color: rgba(255, 255, 255, 0.05);
                        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
                    }
                    
                    .contact-item h5 {
                        transition: color 0.3s ease;
                    }
                    
                    .contact-item:hover h5 {
                        color: #007bff;
                    }
                    
                    .contact-item a {
                        transition: all 0.3s ease;
                    }
                    
                    .contact-item a:hover {
                        text-decoration: underline;
                        font-weight: 600;
                    }
                    
                    .newsletter-item {
                        transition: all 0.3s ease;
                        padding: 15px;
                        border-radius: 8px;
                    }
                    
                    .newsletter-item:hover {
                        transform: translateY(-5px);
                        background-color: rgba(255, 255, 255, 0.05);
                        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
                    }
                    
                    .newsletter-item h6 {
                        transition: color 0.3s ease;
                    }
                    
                    .newsletter-item:hover h6 {
                        color: #007bff;
                    }
                    
                    .newsletter-item .form-control,
                    .newsletter-item .input-group-text {
                        transition: all 0.3s ease;
                    }
                    
                    .newsletter-item:hover .form-control,
                    .newsletter-item:hover .input-group-text {
                        border-color: #007bff;
                    }
                </style>
            </section>

        </main>

        <footer class="site-footer" style="margin-top: 0; padding-top: 20px;">
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
