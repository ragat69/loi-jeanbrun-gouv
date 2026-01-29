<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo isset($page_description) ? $page_description : 'Dispositif Jeanbrun - Relance Logement 2026 : Tout savoir sur le nouveau dispositif fiscal pour l\'investissement locatif en France.'; ?>">
    <title><?php echo isset($page_title) ? $page_title . ' | ' : ''; ?>Loi Jeanbrun - Dispositif Relance Logement</title>

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="/assets/favicon.svg">
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/favicon-32x32.png">
    <link rel="apple-touch-icon" href="/assets/apple-touch-icon.png">

    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts - Marianne alternative -->
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+3:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="/css/style.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Source Sans 3', sans-serif;
        }
    </style>
</head>
<body>
    <!-- Bandeau tricolore -->
    <div class="bandeau-tricolore"></div>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-gouv sticky-top">
        <div class="container">
            <a class="navbar-brand" href="/">
                <div class="brand-logo">
                    <span class="brand-rf">REPUBLIQUE<br>FRANCAISE</span>
                </div>
                <div class="brand-logo">
                    <span class="brand-title">Loi Jeanbrun</span>
                    <small class="text-muted">Dispositif Relance Logement</small>
                </div>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarMain">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page ?? '') === 'accueil' ? 'active' : ''; ?>" href="/">
                            <i class="fas fa-home me-1"></i> Accueil
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page ?? '') === 'fonctionnement' ? 'active' : ''; ?>" href="/fonctionnement">
                            <i class="fas fa-cogs me-1"></i> Fonctionnement
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page ?? '') === 'avantages' ? 'active' : ''; ?>" href="/avantages">
                            <i class="fas fa-gift me-1"></i> Avantages
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page ?? '') === 'eligibilite' ? 'active' : ''; ?>" href="/eligibilite">
                            <i class="fas fa-check-circle me-1"></i> Eligibilite
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page ?? '') === 'simulation' ? 'active' : ''; ?>" href="/simulation">
                            <i class="fas fa-calculator me-1"></i> Simulation
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
