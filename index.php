<?php
$current_page = 'accueil';
$page_title_full = 'Nouvelle Loi Jeanbrun 2026 ⇒ Dispositif Relance Logement';
$page_description = 'Découvrez le dispositif Jeanbrun (Relance Logement) : le nouveau cadre fiscal pour investir dans l\'immobilier locatif en France depuis 2026.';
include 'includes/header.php';
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1>Dispositif Jeanbrun <br><small class="opacity-75">Relance Logement 2026</small></h1>
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

<!-- Contexte : La crise du logement -->
<section class="section section-alt">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <h2 class="section-title text-center">Pourquoi le plan Relance Logement ?</h2>
                <p class="text-center lead mb-5">
                    Face à une crise du logement sans précédent, le gouvernement mobilise des moyens inédits depuis 10 ans
                </p>

                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="card-gouv h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-start">
                                    <div class="card-icon me-3" style="font-size: 2rem;">
                                        <i class="fas fa-chart-line text-danger"></i>
                                    </div>
                                    <div>
                                        <h5 class="mb-2">Une situation critique</h5>
                                        <ul class="list-unstyled mb-0">
                                            <li class="mb-2"><i class="fas fa-exclamation-triangle text-warning me-2"></i>Les Français consacrent en moyenne <strong>28% de leurs revenus</strong> au logement</li>
                                            <li class="mb-2"><i class="fas fa-arrow-down text-danger me-2"></i>L'offre locative a chuté de <strong>15% en 5 ans</strong></li>
                                            <li><i class="fas fa-hammer text-danger me-2"></i>Les mises en chantier ont baissé de <strong>22%</strong> (déc. 2024 - nov. 2025)</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card-gouv h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-start">
                                    <div class="card-icon me-3" style="font-size: 2rem;">
                                        <i class="fas fa-users text-bleu-france"></i>
                                    </div>
                                    <div>
                                        <h5 class="mb-2">Des Français en difficulté</h5>
                                        <ul class="list-unstyled mb-0">
                                            <li class="mb-2"><i class="fas fa-home me-2 text-bleu-france"></i><strong>2,9 millions</strong> de dossiers en attente de logement social</li>
                                            <li class="mb-2"><i class="fas fa-user-graduate me-2 text-bleu-france"></i>Étudiants, apprentis et jeunes actifs particulièrement touchés</li>
                                            <li><i class="fas fa-exclamation-circle me-2 text-bleu-france"></i>Bailleurs sociaux en difficulté pour répondre à la demande</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <a href="/docs/Relance-Logement-communique-presse-gouvernement-23-janvier-2026.pdf"
                       class="btn-gouv-outline"
                       target="_blank"
                       download>
                        <i class="fas fa-download me-2"></i>Télécharger le communiqué de presse officiel
                    </a>
                </div>
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
                    cadre de soutien à l'investissement locatif lancé le <strong>23 janvier 2026</strong> par le Premier ministre
                    Sébastien Lecornu et entré en vigueur en <strong>février 2026</strong>.
                    Il remplace le dispositif Pinel qui a expiré fin 2024.
                </p>
                <p>
                    Porté par le ministre de la Ville et du Logement <strong><a href="/vincent-jeanbrun">Vincent Jeanbrun</a></strong>,
                    cette réforme s'inscrit dans un plan ambitieux visant à <strong>construire 2 millions de logements d'ici 2030</strong>.
                    Le gouvernement mise sur un "grand acte de confiance" envers les acteurs du logement, privés comme publics.
                </p>
                <p>
                    Contrairement au Pinel qui offrait une réduction d'impôt directe et géographiquement limitée,
                    le dispositif Jeanbrun repose sur un système d'<strong>amortissement fiscal</strong> permettant de réduire
                    votre base imposable, <strong>accessible à tous les ménages sans condition de zonage</strong>.
                </p>
                <p class="mb-0">
                    <i class="fas fa-info-circle text-bleu-france me-2"></i>
                    <small>Le dispositif mobilise l'épargne des Français pour créer des logements abordables,
                    avec des loyers plafonnés garantissant l'accès au logement pour les classes moyennes et modestes.</small>
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

<!-- Mesures d'accompagnement -->
<section class="section section-alt">
    <div class="container">
        <h2 class="section-title text-center">Un plan global d'action</h2>
        <p class="text-center lead mb-5">
            Au-delà de l'avantage fiscal, le gouvernement mobilise des moyens inédits pour relancer la construction
        </p>

        <div class="row g-4">
            <div class="col-lg-6">
                <div class="card-gouv h-100">
                    <div class="card-header bg-bleu-france text-white">
                        <i class="fas fa-landmark me-2"></i>Moyens pour le logement social
                    </div>
                    <div class="card-body">
                        <p class="mb-3">
                            <strong class="text-bleu-france" style="font-size: 1.5rem;">500 millions d'euros</strong> supplémentaires
                            pour les 700 bailleurs sociaux, afin qu'ils construisent plus et rénovent davantage.
                        </p>
                        <p class="small text-muted mb-0">
                            Une augmentation inédite pour répondre aux 2,9 millions de dossiers en attente.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card-gouv h-100">
                    <div class="card-header bg-bleu-france text-white">
                        <i class="fas fa-bolt me-2"></i>Zones à bâtir d'urgence
                    </div>
                    <div class="card-body">
                        <p class="mb-3">
                            Dans certaines zones à forte demande, des <strong>dérogations aux normes</strong> seront mises en place
                            pour faciliter et accélérer la construction.
                        </p>
                        <p class="small text-muted mb-0">
                            <i class="fas fa-lightbulb me-1"></i>
                            Inspiré de l'approche utilisée pour Notre-Dame et les Jeux Olympiques.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card-gouv h-100">
                    <div class="card-body text-center">
                        <div class="card-icon">
                            <i class="fas fa-file-signature"></i>
                        </div>
                        <h5>Simplification administrative</h5>
                        <p class="small mb-0">
                            Rapprochement de la décision du terrain et simplification du droit de la construction
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card-gouv h-100">
                    <div class="card-body text-center">
                        <div class="card-icon">
                            <i class="fas fa-recycle"></i>
                        </div>
                        <h5>Transformation du tertiaire</h5>
                        <p class="small mb-0">
                            Accélération de la conversion des bureaux et commerces en logements
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card-gouv h-100">
                    <div class="card-body text-center">
                        <div class="card-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h5>Protection des bailleurs</h5>
                        <p class="small mb-0">
                            Sécurisation des propriétaires contre les impayés de loyer
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="info-box mt-4">
            <h5><i class="fas fa-balance-scale me-2"></i>Un projet de loi à venir</h5>
            <p class="mb-0">
                Le gouvernement portera devant le Parlement un projet de loi de décentralisation comportant un volet
                pour redonner aux élus locaux les capacités d'agir sur les enjeux de logement. Le seuil de travaux
                pour l'ancien sera également abaissé de <strong>30% à 20%</strong> dans un texte législatif à venir.
            </p>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="section">
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

<!-- Infographie Dispositif Relance Logement -->
<section class="section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-8">
                <div class="text-center mb-4">
                    <h2 class="section-title">Le dispositif Relance Logement en un coup d'œil</h2>
                    <p class="text-muted">Comprendre les mécanismes du dispositif Jeanbrun</p>
                </div>
                <img src="/images/dispositif-relance-logement-jeanbrun.webp"
                     alt="Infographie explicative du dispositif Relance Logement Jeanbrun : logements concernés (neufs et anciens avec travaux), ce que vous pouvez déduire (amortissement et charges), vos avantages (jusqu'à 12 000€ d'amortissement par an et 10 700€ de déduction des autres revenus), et les conditions à respecter (location 9 ans, plafonds de loyers, pas de location familiale)"
                     class="img-fluid w-100 rounded shadow-sm"
                     style="border: 1px solid #E5E5E5;">
            </div>
        </div>
    </div>
</section>

<!-- Guide Download Card -->
<?php include 'includes/guide-download-card.php'; ?>

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
