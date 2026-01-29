<?php
$current_page = 'avantages';
$page_title = 'Avantages fiscaux';
$page_description = 'Découvrez tous les avantages fiscaux du dispositif Jeanbrun : amortissement, déficit foncier, déduction des charges et intérêts d\'emprunt.';
include 'includes/header.php';
?>

<!-- Page Header -->
<section class="page-header page-header-avantages">
    <div class="container">
        <h1><i class="fas fa-gift me-3"></i>Avantages du dispositif</h1>
        <p class="lead">Tous les bénéfices fiscaux de la loi Jeanbrun pour votre investissement</p>
    </div>
</section>

<!-- Breadcrumb -->
<nav class="breadcrumb-gouv">
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Accueil</a></li>
            <li class="breadcrumb-item active">Avantages</li>
        </ol>
    </div>
</nav>

<!-- Vue d'ensemble -->
<section class="section">
    <div class="container">
        <h2 class="section-title">Pourquoi choisir le dispositif Jeanbrun ?</h2>
        <p class="lead">
            Le dispositif Jeanbrun offre un ensemble d'avantages fiscaux significatifs pour les
            investisseurs immobiliers, avec une approche plus souple et potentiellement plus rentable
            que son prédécesseur le Pinel.
        </p>

        <div class="row g-4 mt-4">
            <div class="col-lg-6">
                <div class="card-gouv h-100">
                    <div class="card-header">
                        <i class="fas fa-chart-line me-2"></i>Avantages fiscaux directs
                    </div>
                    <div class="card-body">
                        <ul class="list-gouv">
                            <li>Amortissement jusqu'à 12 000€/an</li>
                            <li>Déficit foncier jusqu'à 21 400€/an</li>
                            <li>Déduction de 100% des intérêts d'emprunt</li>
                            <li>Charges locatives entièrement déductibles</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card-gouv h-100">
                    <div class="card-header">
                        <i class="fas fa-unlock me-2"></i>Souplesse du dispositif
                    </div>
                    <div class="card-body">
                        <ul class="list-gouv">
                            <li>Applicable sur tout le territoire français</li>
                            <li>Éligible au neuf et à l'ancien rénové</li>
                            <li>Pas de plafond d'investissement global</li>
                            <li>Combinable avec plusieurs biens</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Avantage 1 : Amortissement -->
<section class="section section-alt">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <h2 class="section-title">
                    <span class="badge bg-primary me-2">1</span>
                    L'amortissement du bien
                </h2>
                <p>
                    L'amortissement est le mécanisme central du dispositif. Il permet de déduire chaque
                    année une fraction de la valeur du bien immobilier de vos revenus fonciers.
                </p>

                <div class="info-box success">
                    <h5><i class="fas fa-euro-sign me-2"></i>Économies potentielles</h5>
                    <p class="mb-0">
                        Pour un bien de 300 000€ en loyer social (4,5%), l'amortissement annuel est de
                        <strong>13 500€</strong> (plafonné à 12 000€). Sur 9 ans, cela représente jusqu'à
                        <strong>108 000€</strong> de revenus non imposés.
                    </p>
                </div>

                <h5 class="mt-4">Taux selon le type de location :</h5>
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="text-center p-3 bg-white border">
                            <div class="fs-2 fw-bold text-bleu-france">3,5%</div>
                            <small>Loyer intermédiaire</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center p-3 bg-white border">
                            <div class="fs-2 fw-bold text-bleu-france">4,5%</div>
                            <small>Loyer social</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center p-3 bg-white border">
                            <div class="fs-2 fw-bold text-bleu-france">5,5%</div>
                            <small>Loyer très social</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="card-gouv">
                    <div class="card-header">Comparatif sur 9 ans</div>
                    <div class="card-body">
                        <canvas id="chartAmortissement" height="250"></canvas>
                        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                        <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            new Chart(document.getElementById('chartAmortissement'), {
                                type: 'bar',
                                data: {
                                    labels: ['Intermédiaire', 'Social', 'Très social'],
                                    datasets: [{
                                        label: 'Amortissement total sur 9 ans (bien 250k)',
                                        data: [78750, 101250, 108000],
                                        backgroundColor: ['#000091', '#1212FF', '#E1000F']
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    plugins: {
                                        legend: { display: false }
                                    },
                                    scales: {
                                        y: {
                                            beginAtZero: true,
                                            ticks: {
                                                callback: function(value) {
                                                    return value.toLocaleString() + ' EUR';
                                                }
                                            }
                                        }
                                    }
                                }
                            });
                        });
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Avantage 2 : Déficit foncier -->
<section class="section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-5 order-lg-2">
                <h2 class="section-title">
                    <span class="badge bg-primary me-2">2</span>
                    Le déficit foncier double
                </h2>
                <p>
                    Le dispositif Jeanbrun double temporairement le plafond du déficit foncier
                    imputable sur le revenu global, le faisant passer de 10 700€ à
                    <strong>21 400€ par an</strong> jusqu'à fin 2027.
                </p>
                <p>
                    Ce déficit se crée lorsque vos charges déductibles (intérêts, travaux, gestion...)
                    dépassent vos revenus locatifs. Il vient directement réduire votre revenu imposable
                    global, générant une économie d'impôt immédiate.
                </p>
            </div>
            <div class="col-lg-7 order-lg-1">
                <div class="table-responsive">
                    <table class="table table-gouv">
                        <thead>
                            <tr>
                                <th>Élément</th>
                                <th>Avant Jeanbrun</th>
                                <th>Avec Jeanbrun</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Plafond déficit foncier</td>
                                <td>10 700€/an</td>
                                <td class="text-success fw-bold">21 400€/an</td>
                            </tr>
                            <tr>
                                <td>Report déficit excédentaire</td>
                                <td>10 ans</td>
                                <td>10 ans</td>
                            </tr>
                            <tr>
                                <td>Imputation sur revenus fonciers</td>
                                <td>Illimitée</td>
                                <td>Illimitée</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="info-box">
                    <h5><i class="fas fa-info-circle me-2"></i>Le saviez-vous ?</h5>
                    <p class="mb-0">
                        Le déficit qui dépasse le plafond de 21 400€ n'est pas perdu : il est reportable
                        sur vos revenus fonciers des 10 années suivantes.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Avantage 3 : Déductions -->
<section class="section section-alt">
    <div class="container">
        <h2 class="section-title">
            <span class="badge bg-primary me-2">3</span>
            Les charges déductibles
        </h2>
        <p class="lead mb-4">
            En plus de l'amortissement, de nombreuses charges sont déductibles de vos revenus fonciers,
            optimisant la rentabilité de votre investissement.
        </p>

        <div class="row g-4">
            <div class="col-md-6 col-lg-4">
                <div class="card-gouv h-100">
                    <div class="card-body">
                        <div class="card-icon">
                            <i class="fas fa-percent"></i>
                        </div>
                        <h5>Intérêts d'emprunt</h5>
                        <p class="small text-muted mb-0">
                            100% des intérêts de votre crédit immobilier sont déductibles,
                            ainsi que les frais de dossier et l'assurance emprunteur.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="card-gouv h-100">
                    <div class="card-body">
                        <div class="card-icon">
                            <i class="fas fa-tools"></i>
                        </div>
                        <h5>Travaux d'entretien</h5>
                        <p class="small text-muted mb-0">
                            Réparations, entretien, remise en état : tous les travaux nécessaires
                            au maintien du bien en bon état sont déductibles.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="card-gouv h-100">
                    <div class="card-body">
                        <div class="card-icon">
                            <i class="fas fa-building"></i>
                        </div>
                        <h5>Charges de copropriété</h5>
                        <p class="small text-muted mb-0">
                            Les charges non récupérables auprès du locataire
                            (provisions pour travaux, frais de syndic...) sont déductibles.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="card-gouv h-100">
                    <div class="card-body">
                        <div class="card-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h5>Assurances</h5>
                        <p class="small text-muted mb-0">
                            Assurance PNO (propriétaire non occupant), assurance loyers impayés,
                            garantie des risques locatifs...
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="card-gouv h-100">
                    <div class="card-body">
                        <div class="card-icon">
                            <i class="fas fa-home"></i>
                        </div>
                        <h5>Taxe foncière</h5>
                        <p class="small text-muted mb-0">
                            La taxe foncière (hors ordures ménagères récupérables)
                            est intégralement déductible de vos revenus fonciers.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="card-gouv h-100">
                    <div class="card-body">
                        <div class="card-icon">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <h5>Frais de gestion</h5>
                        <p class="small text-muted mb-0">
                            Honoraires d'agence immobilière, frais de comptabilité,
                            frais de procédure en cas de litige locatif.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Avantage 4 : Pas de zonage -->
<section class="section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h2 class="section-title">
                    <span class="badge bg-primary me-2">4</span>
                    Liberté géographique totale
                </h2>
                <p>
                    Contrairement au dispositif Pinel qui était limité aux zones tendues (A, A bis, B1),
                    le dispositif Jeanbrun s'applique <strong>sur tout le territoire français</strong>.
                </p>
                <p>
                    Cette liberté vous permet d'investir là où les opportunités sont les meilleures,
                    que ce soit dans une grande métropole ou dans une ville moyenne à fort potentiel.
                </p>

                <div class="info-box success">
                    <h5><i class="fas fa-map-marked-alt me-2"></i>Opportunités</h5>
                    <p class="mb-0">
                        Les villes moyennes offrent souvent un meilleur rendement locatif grâce à des
                        prix d'achat plus accessibles et une demande locative soutenue.
                    </p>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card-gouv">
                    <div class="card-header">
                        <i class="fas fa-map me-2"></i>Avant / Après
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6 border-end">
                                <h6 class="text-muted">Pinel (expiré)</h6>
                                <ul class="small">
                                    <li>Zone A bis : Paris</li>
                                    <li>Zone A : Grandes agglos</li>
                                    <li>Zone B1 : Agglos > 250k</li>
                                    <li class="text-danger"><i class="fas fa-times me-1"></i>Zones B2/C exclues</li>
                                </ul>
                            </div>
                            <div class="col-6">
                                <h6 class="text-success">Jeanbrun (2026)</h6>
                                <ul class="small">
                                    <li><i class="fas fa-check text-success me-1"></i>Grandes métropoles</li>
                                    <li><i class="fas fa-check text-success me-1"></i>Villes moyennes</li>
                                    <li><i class="fas fa-check text-success me-1"></i>Zones rurales</li>
                                    <li><i class="fas fa-check text-success me-1"></i>Outre-mer</li>
                                </ul>
                            </div>
                        </div>
                        <div class="text-center mt-3 pt-3 border-top">
                            <a href="/pinel-vs-jeanbrun" class="btn-gouv-outline btn-sm">
                                <i class="fas fa-balance-scale me-1"></i>Voir le comparatif complet Pinel vs Jeanbrun
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Recapitulatif -->
<section class="section section-alt">
    <div class="container">
        <h2 class="section-title text-center">Récapitulatif des avantages</h2>
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="table-responsive">
                    <table class="table table-gouv">
                        <thead>
                            <tr>
                                <th>Avantage</th>
                                <th>Montant / Taux</th>
                                <th>Conditions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>Amortissement annuel</strong></td>
                                <td>3,5% à 5,5% (max 12 000€/an)</td>
                                <td>Selon type de loyer pratiqué</td>
                            </tr>
                            <tr>
                                <td><strong>Déficit foncier</strong></td>
                                <td>21 400€/an sur revenu global</td>
                                <td>Jusqu'au 31/12/2027</td>
                            </tr>
                            <tr>
                                <td><strong>Intérêts d'emprunt</strong></td>
                                <td>100% déductibles</td>
                                <td>Crédit pour acquisition/travaux</td>
                            </tr>
                            <tr>
                                <td><strong>Charges locatives</strong></td>
                                <td>100% déductibles</td>
                                <td>Charges non récupérables</td>
                            </tr>
                            <tr>
                                <td><strong>Zone géographique</strong></td>
                                <td>France entière</td>
                                <td>Aucune restriction</td>
                            </tr>
                            <tr>
                                <td><strong>Type de bien</strong></td>
                                <td>Neuf ou ancien rénové</td>
                                <td>30% travaux pour l'ancien</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="section">
    <div class="container text-center">
        <h2 class="section-title text-center">Estimez vos avantages</h2>
        <p class="lead mb-4">
            Utilisez notre simulateur pour calculer les économies fiscales de votre projet.
        </p>
        <a href="/simulation" class="btn-gouv btn-lg">
            <i class="fas fa-calculator me-2"></i>Accéder au simulateur
        </a>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
