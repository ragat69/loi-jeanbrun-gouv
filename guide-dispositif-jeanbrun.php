<?php
$current_page = 'guide';
$page_title_full = 'Guide PDF Complet - Dispositif Jeanbrun 2026 | Tout comprendre en 40 pages';
$page_description = 'Téléchargez gratuitement notre guide PDF de 40 pages sur le dispositif Jeanbrun : fonctionnement, avantages fiscaux, exemples chiffrés et FAQ complète.';
include 'includes/header.php';
?>

<!-- Page Header -->
<section class="page-header" style="background: linear-gradient(135deg, #000091 0%, #1212FF 100%);">
    <div class="container">
        <h1><i class="fas fa-file-pdf me-3"></i>Guide Complet du Dispositif Jeanbrun</h1>
        <p class="lead">40 pages pour maîtriser tous les aspects du dispositif Relance Logement 2026</p>
    </div>
</section>

<!-- Breadcrumb -->
<nav class="breadcrumb-gouv">
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Accueil</a></li>
            <li class="breadcrumb-item active">Guide PDF</li>
        </ol>
    </div>
</nav>

<!-- Introduction -->
<section class="section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h2 class="section-title">Tout savoir sur le dispositif Jeanbrun</h2>
                <p class="lead">
                    Un guide complet et gratuit pour comprendre le nouveau cadre fiscal de l'investissement
                    locatif en France.
                </p>
                <p>
                    Face à la crise du logement sans précédent, le gouvernement a lancé en 2026 le
                    <strong>dispositif Jeanbrun</strong>, un nouveau mécanisme fiscal qui remplace le Pinel
                    et introduit des avantages significatifs pour les bailleurs privés.
                </p>
                <p>
                    Notre guide PDF de 40 pages vous explique de A à Z comment bénéficier de ce dispositif,
                    avec des exemples concrets et des calculs détaillés.
                </p>

                <div class="d-flex gap-3 mt-4">
                    <a href="/docs/guide-loi-jeanbrun-2026.pdf"
                       class="btn-gouv btn-lg"
                       download="Guide-Loi-Jeanbrun-2026.pdf"
                       onclick="trackGuideDownload()">
                        <i class="fas fa-download me-2"></i>Télécharger le guide PDF
                    </a>
                    <a href="#sommaire" class="btn-gouv-outline btn-lg">
                        <i class="fas fa-list me-2"></i>Voir le sommaire
                    </a>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <img src="/images/guide-loi-jeanbrun-pdf.png"
                     alt="Aperçu du guide PDF Loi Jeanbrun 2026"
                     class="img-fluid shadow-lg"
                     style="max-width: 400px; border-radius: 12px;">
            </div>
        </div>
    </div>
</section>

<!-- Caractéristiques du guide -->
<section class="section section-alt">
    <div class="container">
        <h2 class="section-title text-center">Ce que vous trouverez dans ce guide</h2>
        <div class="row g-4 mt-4">
            <div class="col-md-6 col-lg-3">
                <div class="card-gouv h-100 text-center">
                    <div class="card-body">
                        <div class="card-icon mb-3">
                            <i class="fas fa-book-open text-bleu-france" style="font-size: 3rem;"></i>
                        </div>
                        <h5>40 Pages</h5>
                        <p class="small mb-0">
                            Guide complet et structuré pour une compréhension approfondie du dispositif
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card-gouv h-100 text-center">
                    <div class="card-body">
                        <div class="card-icon mb-3">
                            <i class="fas fa-calculator text-bleu-france" style="font-size: 3rem;"></i>
                        </div>
                        <h5>Exemples Chiffrés</h5>
                        <p class="small mb-0">
                            2 cas pratiques détaillés avec calculs d'amortissement et cash-flow
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card-gouv h-100 text-center">
                    <div class="card-body">
                        <div class="card-icon mb-3">
                            <i class="fas fa-table text-bleu-france" style="font-size: 3rem;"></i>
                        </div>
                        <h5>Tableaux & Graphiques</h5>
                        <p class="small mb-0">
                            Données visuelles pour comparer et comprendre rapidement
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card-gouv h-100 text-center">
                    <div class="card-body">
                        <div class="card-icon mb-3">
                            <i class="fas fa-question-circle text-bleu-france" style="font-size: 3rem;"></i>
                        </div>
                        <h5>FAQ Complète</h5>
                        <p class="small mb-0">
                            15 questions fréquentes avec réponses détaillées
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Sommaire -->
<section class="section" id="sommaire">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <h2 class="section-title text-center">Sommaire du Guide</h2>
                <p class="text-center text-muted mb-5">6 chapitres pour tout comprendre</p>

                <div class="row g-4">
                    <!-- Chapitre 1 -->
                    <div class="col-lg-6">
                        <div class="card-gouv h-100">
                            <div class="card-header d-flex align-items-center">
                                <span class="badge bg-bleu-france me-3" style="font-size: 1.2rem;">1</span>
                                <strong>Comprendre le dispositif Jeanbrun</strong>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled small">
                                    <li class="mb-2"><i class="fas fa-chevron-right text-bleu-france me-2"></i>Le contexte de la crise du logement</li>
                                    <li class="mb-2"><i class="fas fa-chevron-right text-bleu-france me-2"></i>Qu'est-ce que la loi Jeanbrun ?</li>
                                    <li class="mb-2"><i class="fas fa-chevron-right text-bleu-france me-2"></i>Pinel vs Jeanbrun : les différences clés</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Chapitre 2 -->
                    <div class="col-lg-6">
                        <div class="card-gouv h-100">
                            <div class="card-header d-flex align-items-center">
                                <span class="badge bg-bleu-france me-3" style="font-size: 1.2rem;">2</span>
                                <strong>Fonctionnement du mécanisme</strong>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled small">
                                    <li class="mb-2"><i class="fas fa-chevron-right text-bleu-france me-2"></i>Le principe de l'amortissement fiscal</li>
                                    <li class="mb-2"><i class="fas fa-chevron-right text-bleu-france me-2"></i>Les taux d'amortissement (3,5% à 5,5%)</li>
                                    <li class="mb-2"><i class="fas fa-chevron-right text-bleu-france me-2"></i>Les plafonds et limitations</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Chapitre 3 -->
                    <div class="col-lg-6">
                        <div class="card-gouv h-100">
                            <div class="card-header d-flex align-items-center">
                                <span class="badge bg-bleu-france me-3" style="font-size: 1.2rem;">3</span>
                                <strong>Avantages fiscaux</strong>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled small">
                                    <li class="mb-2"><i class="fas fa-chevron-right text-bleu-france me-2"></i>L'amortissement du bien (12 000€/an max)</li>
                                    <li class="mb-2"><i class="fas fa-chevron-right text-bleu-france me-2"></i>Le déficit foncier amplifié (21 400€/an)</li>
                                    <li class="mb-2"><i class="fas fa-chevron-right text-bleu-france me-2"></i>La déduction des charges à 100%</li>
                                    <li class="mb-2"><i class="fas fa-chevron-right text-bleu-france me-2"></i>Les intérêts d'emprunt déductibles</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Chapitre 4 -->
                    <div class="col-lg-6">
                        <div class="card-gouv h-100">
                            <div class="card-header d-flex align-items-center">
                                <span class="badge bg-bleu-france me-3" style="font-size: 1.2rem;">4</span>
                                <strong>Conditions d'éligibilité</strong>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled small">
                                    <li class="mb-2"><i class="fas fa-chevron-right text-bleu-france me-2"></i>Conditions sur le bien immobilier</li>
                                    <li class="mb-2"><i class="fas fa-chevron-right text-bleu-france me-2"></i>Conditions sur le locataire</li>
                                    <li class="mb-2"><i class="fas fa-chevron-right text-bleu-france me-2"></i>Plafonds de loyers et ressources</li>
                                    <li class="mb-2"><i class="fas fa-chevron-right text-bleu-france me-2"></i>Engagement de location (9 ans)</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Chapitre 5 -->
                    <div class="col-lg-6">
                        <div class="card-gouv h-100">
                            <div class="card-header d-flex align-items-center">
                                <span class="badge bg-bleu-france me-3" style="font-size: 1.2rem;">5</span>
                                <strong>Mise en pratique</strong>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled small">
                                    <li class="mb-2"><i class="fas fa-chevron-right text-bleu-france me-2"></i>Les étapes pour bénéficier du dispositif</li>
                                    <li class="mb-2"><i class="fas fa-chevron-right text-bleu-france me-2"></i>2 exemples de calculs détaillés</li>
                                    <li class="mb-2"><i class="fas fa-chevron-right text-bleu-france me-2"></i>Erreurs à éviter (8 pièges courants)</li>
                                    <li class="mb-2"><i class="fas fa-chevron-right text-bleu-france me-2"></i>Conseils d'optimisation</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Chapitre 6 -->
                    <div class="col-lg-6">
                        <div class="card-gouv h-100">
                            <div class="card-header d-flex align-items-center">
                                <span class="badge bg-bleu-france me-3" style="font-size: 1.2rem;">6</span>
                                <strong>FAQ - Questions fréquentes</strong>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled small">
                                    <li class="mb-2"><i class="fas fa-chevron-right text-bleu-france me-2"></i>Cumul de plusieurs investissements</li>
                                    <li class="mb-2"><i class="fas fa-chevron-right text-bleu-france me-2"></i>Compatibilité avec SCPI</li>
                                    <li class="mb-2"><i class="fas fa-chevron-right text-bleu-france me-2"></i>Vente anticipée du bien</li>
                                    <li class="mb-2"><i class="fas fa-chevron-right text-bleu-france me-2"></i>Et 12 autres questions...</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Exemples chiffrés -->
<section class="section section-alt">
    <div class="container">
        <h2 class="section-title text-center">Exemples chiffrés détaillés</h2>
        <p class="text-center text-muted mb-5">2 cas pratiques pour comprendre la rentabilité</p>

        <div class="row g-4">
            <div class="col-lg-6">
                <div class="card-gouv h-100">
                    <div class="card-header">
                        <i class="fas fa-user me-2"></i>Cas n°1 : Investisseur primo-accédant
                    </div>
                    <div class="card-body">
                        <p><strong>Profil :</strong> Cadre, TMI 30%, célibataire</p>
                        <hr>
                        <p><strong>Investissement :</strong></p>
                        <ul class="small">
                            <li>Appartement T2 neuf à Lyon : 220 000€</li>
                            <li>Emprunt : 200 000€ à 3,5% sur 20 ans</li>
                            <li>Loyer intermédiaire : 950€/mois</li>
                        </ul>
                        <hr>
                        <p class="mb-0"><strong>Résultat année 1 :</strong></p>
                        <ul class="small mb-0">
                            <li>Amortissement : 7 700€</li>
                            <li>Économie fiscale : <span class="text-success fw-bold">2 643€</span></li>
                            <li>Effort mensuel : <span class="text-info fw-bold">-188€</span></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card-gouv h-100">
                    <div class="card-header">
                        <i class="fas fa-briefcase me-2"></i>Cas n°2 : Investisseur expérimenté
                    </div>
                    <div class="card-body">
                        <p><strong>Profil :</strong> Chef d'entreprise, TMI 41%, couple</p>
                        <hr>
                        <p><strong>Investissement :</strong></p>
                        <ul class="small">
                            <li>Appartement T3 ancien à Bordeaux : 180 000€</li>
                            <li>Travaux : 80 000€ (rénovation complète)</li>
                            <li>Loyer social : 1 150€/mois</li>
                        </ul>
                        <hr>
                        <p class="mb-0"><strong>Résultat année 1 :</strong></p>
                        <ul class="small mb-0">
                            <li>Amortissement : 9 100€</li>
                            <li>Économie fiscale : <span class="text-success fw-bold">9 312€</span></li>
                            <li>Cash-flow positif : <span class="text-success fw-bold">+211€/mois</span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-4">
            <p class="text-muted small">
                <i class="fas fa-info-circle me-1"></i>
                Calculs détaillés avec toutes les étapes dans le guide PDF
            </p>
        </div>
    </div>
</section>

<!-- Téléchargement CTA -->
<section class="section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <div class="card-gouv" style="background: linear-gradient(135deg, #000091 0%, #1212FF 100%); color: white;">
                    <div class="card-body p-5">
                        <i class="fas fa-file-pdf" style="font-size: 4rem; opacity: 0.9; margin-bottom: 1.5rem;"></i>
                        <h3 style="color: white; margin-bottom: 1rem;">Téléchargez le guide complet</h3>
                        <p style="opacity: 0.95; margin-bottom: 2rem;">
                            40 pages pour maîtriser le dispositif Jeanbrun de A à Z.<br>
                            <strong>Gratuit • Sans inscription • Format PDF</strong>
                        </p>
                        <a href="/docs/guide-loi-jeanbrun-2026.pdf"
                           class="btn btn-light btn-lg"
                           download="Guide-Loi-Jeanbrun-2026.pdf"
                           onclick="trackGuideDownload()"
                           style="background: white; color: #000091; font-weight: 700;">
                            <i class="fas fa-download me-2"></i>Télécharger le guide PDF
                        </a>
                        <p style="margin-top: 1rem; font-size: 0.9rem; opacity: 0.8; margin-bottom: 0;">
                            <i class="fas fa-file-alt me-1"></i>457 Ko • PDF optimisé pour impression
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Aller plus loin -->
<section class="section section-alt">
    <div class="container">
        <h2 class="section-title text-center">Aller plus loin</h2>
        <div class="row g-4 mt-4">
            <div class="col-md-4">
                <div class="card-gouv h-100 text-center">
                    <div class="card-body">
                        <div class="card-icon mb-3">
                            <i class="fas fa-calculator text-bleu-france" style="font-size: 2.5rem;"></i>
                        </div>
                        <h5>Simulateur</h5>
                        <p class="small">
                            Estimez vos avantages fiscaux et votre effort d'épargne mensuel
                        </p>
                        <a href="/simulation" class="btn-gouv-outline btn-sm mt-2">
                            Simuler mon projet
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card-gouv h-100 text-center">
                    <div class="card-body">
                        <div class="card-icon mb-3">
                            <i class="fas fa-check-circle text-bleu-france" style="font-size: 2.5rem;"></i>
                        </div>
                        <h5>Test d'éligibilité</h5>
                        <p class="small">
                            Vérifiez si votre projet respecte toutes les conditions en 10 secondes
                        </p>
                        <a href="/eligibilite" class="btn-gouv-outline btn-sm mt-2">
                            Tester mon éligibilité
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card-gouv h-100 text-center">
                    <div class="card-body">
                        <div class="card-icon mb-3">
                            <i class="fas fa-cogs text-bleu-france" style="font-size: 2.5rem;"></i>
                        </div>
                        <h5>Fonctionnement</h5>
                        <p class="small">
                            Comprendre en détail le mécanisme d'amortissement fiscal
                        </p>
                        <a href="/fonctionnement" class="btn-gouv-outline btn-sm mt-2">
                            Voir le fonctionnement
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
function trackGuideDownload() {
    // Analytics tracking
    if (typeof gtag !== 'undefined') {
        gtag('event', 'download', {
            'event_category': 'Guide',
            'event_label': 'Guide Loi Jeanbrun 2026 - Page Dédiée',
            'value': 1
        });
    }
    console.log('Guide téléchargé depuis la page dédiée');
}
</script>

<?php include 'includes/footer.php'; ?>
