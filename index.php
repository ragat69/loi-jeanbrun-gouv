<?php
$current_page = 'accueil';
$page_title = 'Accueil';
$page_description = 'Découvrez le dispositif Jeanbrun (Relance Logement) : le nouveau cadre fiscal pour investir dans l\'immobilier locatif en France depuis 2026.';
include 'includes/header.php';
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1>Dispositif Jeanbrun<br><small class="opacity-75">Relance Logement 2026</small></h1>
                <p class="lead mb-4">
                    Le nouveau statut du <strong>bailleur privé</strong> pour investir dans l'immobilier locatif en France.
                    Amortissement, déduction des charges, déficit foncier : découvrez tous les avantages fiscaux
                    du successeur du dispositif Pinel.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="/fonctionnement" class="btn-gouv">
                        <i class="fas fa-info-circle me-2"></i>Comprendre le dispositif
                    </a>
                    <a href="/simulation" class="btn-gouv-outline" style="background: rgba(255,255,255,0.1); border-color: #fff; color: #fff;">
                        <i class="fas fa-calculator me-2"></i>Simuler mon investissement
                    </a>
                </div>
            </div>
            <div class="col-lg-4 d-none d-lg-block">
                <img src="https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?w=500&h=400&fit=crop"
                     alt="Immeuble residentiel moderne en France"
                     class="img-fluid rounded shadow-lg"
                     style="opacity: 0.9;">
            </div>
        </div>
    </div>
</section>

<!-- Chiffres cles -->
<section class="section">
    <div class="container">
        <h2 class="section-title text-center">Les objectifs du plan Relance Logement</h2>
        <div class="row g-4 mt-4">
            <div class="col-md-3 col-6">
                <div class="chiffre-cle">
                    <div class="nombre">2<span class="unite">M</span></div>
                    <div class="label">logements d'ici 2030</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="chiffre-cle">
                    <div class="nombre">400<span class="unite">k</span></div>
                    <div class="label">constructions/an visées</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="chiffre-cle">
                    <div class="nombre">50<span class="unite">k</span></div>
                    <div class="label">logements locatifs privés/an</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="chiffre-cle">
                    <div class="nombre">125<span class="unite">k</span></div>
                    <div class="label">logements sociaux/an</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Presentation -->
<section class="section section-alt">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <h2 class="section-title">Qu'est-ce que la loi Jeanbrun ?</h2>
                <p>
                    Le <strong>dispositif Jeanbrun</strong>, officiellement nommé "Relance Logement", est le nouveau
                    cadre de soutien à l'investissement locatif entré en vigueur en <strong>février 2026</strong>.
                    Il remplace le dispositif Pinel qui a expiré fin 2024.
                </p>
                <p>
                    Porté par le ministre <strong><a href="/vincent-jeanbrun">Vincent Jeanbrun</a></strong>, cette réforme redéfinit l'investissement
                    locatif en France en privilégiant la <strong>rentabilité réelle</strong> plutôt que l'avantage
                    fiscal pur.
                </p>
                <p>
                    Contrairement au Pinel qui offrait une réduction d'impôt directe, le dispositif Jeanbrun
                    repose sur un système d'<strong>amortissement fiscal</strong> permettant de réduire
                    votre base imposable.
                </p>
            </div>
            <div class="col-lg-6">
                <div class="card-gouv">
                    <div class="card-header">
                        <i class="fas fa-calendar-alt me-2"></i>Dates clés
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            <div class="timeline-item">
                                <h4>23 janvier 2026</h4>
                                <p>Lancement officiel du plan "Relance Logement" par le gouvernement</p>
                            </div>
                            <div class="timeline-item">
                                <h4>Février 2026</h4>
                                <p>Entrée en vigueur du dispositif fiscal Jeanbrun</p>
                            </div>
                            <div class="timeline-item">
                                <h4>31 décembre 2028</h4>
                                <p>Date limite prévue pour bénéficier du dispositif</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Points cles -->
<section class="section">
    <div class="container">
        <h2 class="section-title text-center">Les points clés du dispositif</h2>
        <div class="row g-4 mt-4">
            <div class="col-lg-4 col-md-6">
                <div class="card-gouv h-100">
                    <div class="card-body text-center">
                        <div class="card-icon">
                            <i class="fas fa-percentage"></i>
                        </div>
                        <h4>Amortissement fiscal</h4>
                        <p>
                            Déduisez chaque année une fraction de la valeur du bien de vos revenus fonciers.
                            Taux de 3,5% à 5,5% selon le type de loyer pratiqué.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card-gouv h-100">
                    <div class="card-body text-center">
                        <div class="card-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <h4>Fin du zonage</h4>
                        <p>
                            Plus de restrictions géographiques ! Investissez partout en France,
                            que ce soit dans les grandes métropoles ou les villes moyennes.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card-gouv h-100">
                    <div class="card-body text-center">
                        <div class="card-icon">
                            <i class="fas fa-building"></i>
                        </div>
                        <h4>Neuf et ancien</h4>
                        <p>
                            Le dispositif s'applique aux logements neufs mais aussi à l'ancien
                            rénové (minimum 30% de travaux par rapport au prix d'acquisition).
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card-gouv h-100">
                    <div class="card-body text-center">
                        <div class="card-icon">
                            <i class="fas fa-file-invoice-dollar"></i>
                        </div>
                        <h4>Déficit foncier double</h4>
                        <p>
                            Le plafond du déficit foncier imputable passe de 10 700EUR a
                            <strong>21 400EUR</strong> par an jusqu'a fin 2027.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card-gouv h-100">
                    <div class="card-body text-center">
                        <div class="card-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <h4>Engagement de 9 ans</h4>
                        <p>
                            Vous vous engagez a louer le bien pendant 9 ans minimum
                            en tant que résidence principale du locataire.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card-gouv h-100">
                    <div class="card-body text-center">
                        <div class="card-icon">
                            <i class="fas fa-hand-holding-usd"></i>
                        </div>
                        <h4>Intérêts déductibles</h4>
                        <p>
                            100% des intérêts d'emprunt sont déductibles de vos revenus
                            fonciers, optimisant la rentabilité de votre investissement.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="section section-alt">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <h2 class="section-title text-center">Prêt à investir ?</h2>
                <p class="lead mb-4">
                    Utilisez notre simulateur pour estimer les avantages fiscaux de votre projet
                    d'investissement locatif avec le dispositif Jeanbrun.
                </p>
                <div class="d-flex justify-content-center gap-3 flex-wrap">
                    <a href="/simulation" class="btn-gouv btn-lg">
                        <i class="fas fa-calculator me-2"></i>Simuler mon projet
                    </a>
                    <a href="/eligibilite" class="btn-gouv-outline btn-lg">
                        <i class="fas fa-check-circle me-2"></i>Verifier mon éligibilité
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Info box -->
<section class="section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="info-box">
                    <h5><i class="fas fa-info-circle me-2"></i>Information importante</h5>
                    <p class="mb-0">
                        Ce site a vocation informative et ne constitue pas un conseil fiscal ou juridique.
                        Pour toute décision d'investissement, nous vous recommandons de consulter un
                        professionnel qualifié (conseiller en gestion de patrimoine, notaire, expert-comptable).
                        Les informations présentées sont basées sur les textes officiels disponibles à la date
                        de publication et peuvent être amenées à évoluer.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
