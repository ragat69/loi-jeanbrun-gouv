<?php
$current_page = 'eligibilite';
$page_title_full = 'Loi Jeanbrun 2026 - Éligibilité | Dispositif Relance Logement';
$page_description = 'Vérifiez votre éligibilité au dispositif Jeanbrun : conditions sur le bien, le locataire, les plafonds de loyers et de ressources.';
include 'includes/header.php';
?>

<!-- Page Header -->
<section class="page-header page-header-eligibilite">
    <div class="container">
        <h1><i class="fas fa-check-circle me-3"></i>Conditions d'éligibilité</h1>
        <p class="lead">Tout savoir sur les critères pour bénéficier du dispositif Jeanbrun</p>
    </div>
</section>

<!-- Breadcrumb -->
<nav class="breadcrumb-gouv">
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Accueil</a></li>
            <li class="breadcrumb-item active">Éligibilité</li>
        </ol>
    </div>
</nav>

<!-- Introduction -->
<section class="section">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <h2 class="section-title">Qui peut bénéficier du dispositif ?</h2>
                <p class="lead">
                    Le dispositif Jeanbrun s'adresse à <strong>tous les contribuables français</strong>
                    souhaitant investir dans l'immobilier locatif, qu'ils soient déjà propriétaires
                    ou primo-investisseurs.
                </p>
                <p>
                    Pour bénéficier des avantages fiscaux, plusieurs conditions doivent être remplies
                    concernant le bien immobilier, le locataire et les plafonds applicables.
                </p>
            </div>
            <div class="col-lg-4">
                <div class="info-box success">
                    <h5><i class="fas fa-user-check me-2"></i>Public cible</h5>
                    <p class="mb-0 small">
                        Le dispositif est particulièrement adapté aux investisseurs avec des revenus
                        importants, cherchant une stratégie patrimoniale sur le long terme.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Conditions sur le bien -->
<section class="section section-alt">
    <div class="container">
        <h2 class="section-title">
            <i class="fas fa-home me-2 text-bleu-france"></i>
            Conditions relatives au bien immobilier
        </h2>

        <div class="row g-4">
            <div class="col-lg-6">
                <div class="card-gouv h-100">
                    <div class="card-header">
                        <i class="fas fa-building me-2"></i>Logement neuf
                    </div>
                    <div class="card-body">
                        <ul class="list-gouv">
                            <li>Logement acquis neuf ou en VEFA</li>
                            <li>Situé dans un immeuble collectif</li>
                            <li>Respectant les normes énergétiques en vigueur (RE2020)</li>
                            <li>Achevé dans les 30 mois suivant la déclaration d'ouverture de chantier</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card-gouv h-100">
                    <div class="card-header">
                        <i class="fas fa-tools me-2"></i>Logement ancien rénové
                    </div>
                    <div class="card-body">
                        <ul class="list-gouv">
                            <li>Travaux représentant minimum <strong>30%</strong> du prix d'acquisition</li>
                            <li>Travaux permettant d'atteindre une performance énergétique minimale</li>
                            <li>Logement situé dans un immeuble collectif</li>
                            <li>Travaux réalisés par des professionnels certifiés</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="info-box mt-4">
            <h5><i class="fas fa-map-marker-alt me-2"></i>Localisation</h5>
            <p class="mb-0">
                Contrairement au dispositif Pinel, le Jeanbrun s'applique <strong>sur tout le territoire
                français</strong>, y compris l'outre-mer. Il n'y a plus de zonage géographique restrictif.
            </p>
        </div>
    </div>
</section>

<!-- Conditions de location -->
<section class="section">
    <div class="container">
        <h2 class="section-title">
            <i class="fas fa-key me-2 text-bleu-france"></i>
            Conditions de mise en location
        </h2>

        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="card-gouv h-100">
                    <div class="card-body text-center">
                        <div class="card-icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <h5>Durée d'engagement</h5>
                        <p class="small text-muted">
                            Location obligatoire pendant <strong>9 ans minimum</strong>
                            à compter de la mise en location.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card-gouv h-100">
                    <div class="card-body text-center">
                        <div class="card-icon">
                            <i class="fas fa-home"></i>
                        </div>
                        <h5>Résidence principale</h5>
                        <p class="small text-muted">
                            Le logement doit être la <strong>résidence principale</strong>
                            du locataire.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card-gouv h-100">
                    <div class="card-body text-center">
                        <div class="card-icon">
                            <i class="fas fa-couch"></i>
                        </div>
                        <h5>Location nue</h5>
                        <p class="small text-muted">
                            Le bien doit être loué <strong>non meublé</strong>
                            (location nue uniquement).
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card-gouv h-100">
                    <div class="card-body text-center">
                        <div class="card-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <h5>Délai de location</h5>
                        <p class="small text-muted">
                            Mise en location dans les <strong>12 mois</strong>
                            suivant l'acquisition ou l'achèvement.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Conditions sur le locataire -->
<section class="section section-alt">
    <div class="container">
        <h2 class="section-title">
            <i class="fas fa-users me-2 text-bleu-france"></i>
            Conditions relatives au locataire
        </h2>

        <div class="row align-items-center">
            <div class="col-lg-6">
                <h4>Le locataire ne doit pas être :</h4>
                <ul class="list-group list-group-flush mb-4">
                    <li class="list-group-item">
                        <i class="fas fa-times text-danger me-2"></i>
                        Un membre du foyer fiscal du propriétaire
                    </li>
                    <li class="list-group-item">
                        <i class="fas fa-times text-danger me-2"></i>
                        Un ascendant (parent, grand-parent...)
                    </li>
                    <li class="list-group-item">
                        <i class="fas fa-times text-danger me-2"></i>
                        Un descendant (enfant, petit-enfant...)
                    </li>
                </ul>

                <h4>Le locataire doit :</h4>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <i class="fas fa-check text-success me-2"></i>
                        Respecter les plafonds de ressources (si applicable)
                    </li>
                    <li class="list-group-item">
                        <i class="fas fa-check text-success me-2"></i>
                        Utiliser le logement comme résidence principale
                    </li>
                    <li class="list-group-item">
                        <i class="fas fa-check text-success me-2"></i>
                        Fournir un avis d'imposition pour vérification
                    </li>
                </ul>
            </div>
            <div class="col-lg-6">
                <div class="info-box warning">
                    <h5><i class="fas fa-exclamation-triangle me-2"></i>Attention</h5>
                    <p>
                        L'interdiction de louer à un membre de la famille s'applique pendant
                        toute la durée de l'engagement de location (9 ans).
                    </p>
                    <p class="mb-0">
                        En cas de non-respect de cette condition, les avantages fiscaux
                        obtenus seront remis en cause avec application de pénalités.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Plafonds de loyers -->
<section class="section">
    <div class="container">
        <h2 class="section-title">
            <i class="fas fa-euro-sign me-2 text-bleu-france"></i>
            Plafonds de loyers
        </h2>
        <p class="mb-4">
            Les loyers sont plafonnés selon le type de location choisi. Ces plafonds sont exprimés
            en euros par mètre carré de surface habitable, hors charges.
        </p>

        <div class="table-responsive">
            <table class="table table-gouv">
                <thead>
                    <tr>
                        <th>Type de loyer</th>
                        <th>Plafond indicatif</th>
                        <th>Taux d'amortissement</th>
                        <th>Public cible</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Loyer intermédiaire</strong></td>
                        <td>Environ -15% du marché</td>
                        <td class="text-center"><span class="badge bg-primary">3,5%</span></td>
                        <td>Classes moyennes</td>
                    </tr>
                    <tr>
                        <td><strong>Loyer social</strong></td>
                        <td>Plafonds HLM (PLUS)</td>
                        <td class="text-center"><span class="badge bg-primary">4,5%</span></td>
                        <td>Ménages modestes</td>
                    </tr>
                    <tr>
                        <td><strong>Loyer très social</strong></td>
                        <td>Plafonds HLM (PLAI)</td>
                        <td class="text-center"><span class="badge bg-primary">5,5%</span></td>
                        <td>Ménages très modestes</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="info-box mt-4">
            <h5><i class="fas fa-info-circle me-2"></i>Coefficient de surface</h5>
            <p class="mb-0">
                Un coefficient multiplicateur s'applique selon la surface du logement :
                <strong>0,7 + (19 / surface habitable)</strong>. Ce coefficient est plafonné à 1,2.
                Ainsi, les petites surfaces bénéficient d'un plafond de loyer proportionnellement plus élevé.
            </p>
        </div>
    </div>
</section>

<!-- Plafonds de ressources -->
<section class="section section-alt">
    <div class="container">
        <h2 class="section-title">
            <i class="fas fa-wallet me-2 text-bleu-france"></i>
            Plafonds de ressources des locataires
        </h2>
        <p class="mb-4">
            Avec la suppression du zonage géographique, le dispositif Jeanbrun instaure des
            <strong>plafonds de ressources nationaux uniques</strong>. Les ressources du locataire
            (revenu fiscal de référence N-2) ne doivent pas dépasser les plafonds suivants selon
            la composition du foyer.
        </p>

        <div class="row">
            <div class="col-lg-8">
                <div class="table-responsive">
                    <table class="table table-gouv">
                        <thead>
                            <tr>
                                <th>Composition du foyer</th>
                                <th>Plafond loyer intermédiaire</th>
                                <th>Plafond loyer social</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Personne seule</td>
                                <td>35 000 €</td>
                                <td>25 000 €</td>
                            </tr>
                            <tr>
                                <td>Couple</td>
                                <td>50 000 €</td>
                                <td>35 000 €</td>
                            </tr>
                            <tr>
                                <td>+ 1 personne à charge</td>
                                <td>60 000 €</td>
                                <td>42 000 €</td>
                            </tr>
                            <tr>
                                <td>+ 2 personnes à charge</td>
                                <td>72 000 €</td>
                                <td>50 000 €</td>
                            </tr>
                            <tr>
                                <td>+ 3 personnes à charge</td>
                                <td>85 000 €</td>
                                <td>60 000 €</td>
                            </tr>
                            <tr>
                                <td>+ 4 personnes à charge</td>
                                <td>96 000 €</td>
                                <td>68 000 €</td>
                            </tr>
                            <tr>
                                <td>Majoration par personne supplémentaire</td>
                                <td>+10 000 €</td>
                                <td>+7 500 €</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="info-box success">
                    <h5><i class="fas fa-map-marked-alt me-2"></i>Fin du zonage</h5>
                    <p class="mb-0 small">
                        Contrairement au dispositif Pinel, il n'y a plus de distinction entre
                        zones A, B ou C. Les mêmes plafonds s'appliquent sur tout le territoire
                        français, simplifiant ainsi les démarches des investisseurs.
                    </p>
                </div>
            </div>
        </div>

        <p class="text-muted small mt-3">
            <i class="fas fa-info-circle me-1"></i>
            Ces plafonds sont donnés à titre indicatif et peuvent être actualisés annuellement.
            Les plafonds pour les loyers très sociaux sont inférieurs à ceux du loyer social.
        </p>
    </div>
</section>

<!-- Resume -->
<section class="section">
    <div class="container">
        <h2 class="section-title text-center">Checklist d'éligibilité</h2>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card-gouv">
                    <div class="card-header">
                        <i class="fas fa-clipboard-check me-2"></i>
                        Vérifiez ces points avant d'investir
                    </div>
                    <div class="card-body">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="check1">
                            <label class="form-check-label" for="check1">
                                Le bien est situé dans un immeuble collectif en France
                            </label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="check2">
                            <label class="form-check-label" for="check2">
                                Le bien est neuf OU ancien avec 30% de travaux minimum
                            </label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="check3">
                            <label class="form-check-label" for="check3">
                                Je m'engage à louer pendant au moins 9 ans
                            </label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="check4">
                            <label class="form-check-label" for="check4">
                                Le logement sera loué nu (non meublé)
                            </label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="check5">
                            <label class="form-check-label" for="check5">
                                Le logement sera la résidence principale du locataire
                            </label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="check6">
                            <label class="form-check-label" for="check6">
                                Je ne louerai pas à un membre de ma famille
                            </label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="check7">
                            <label class="form-check-label" for="check7">
                                Je respecterai les plafonds de loyers applicables
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="check8">
                            <label class="form-check-label" for="check8">
                                Le locataire respectera les plafonds de ressources
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="section section-alt">
    <div class="container text-center">
        <h2 class="section-title text-center">Vous êtes éligible ?</h2>
        <p class="lead mb-4">
            Estimez maintenant les avantages fiscaux de votre projet d'investissement.
        </p>
        <a href="/simulation" class="btn-gouv btn-lg">
            <i class="fas fa-calculator me-2"></i>Accéder au simulateur
        </a>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
