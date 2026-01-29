<?php
$current_page = 'eligibilite';
$page_title = 'Eligibilite';
$page_description = 'Verifiez votre eligibilite au dispositif Jeanbrun : conditions sur le bien, le locataire, les plafonds de loyers et de ressources.';
include 'includes/header.php';
?>

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <h1><i class="fas fa-check-circle me-3"></i>Conditions d'eligibilite</h1>
        <p class="lead">Tout savoir sur les criteres pour beneficier du dispositif Jeanbrun</p>
    </div>
</section>

<!-- Breadcrumb -->
<nav class="breadcrumb-gouv">
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Accueil</a></li>
            <li class="breadcrumb-item active">Eligibilite</li>
        </ol>
    </div>
</nav>

<!-- Introduction -->
<section class="section">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <h2 class="section-title">Qui peut beneficier du dispositif ?</h2>
                <p class="lead">
                    Le dispositif Jeanbrun s'adresse a <strong>tous les contribuables francais</strong>
                    souhaitant investir dans l'immobilier locatif, qu'ils soient deja proprietaires
                    ou primo-investisseurs.
                </p>
                <p>
                    Pour beneficier des avantages fiscaux, plusieurs conditions doivent etre remplies
                    concernant le bien immobilier, le locataire et les plafonds applicables.
                </p>
            </div>
            <div class="col-lg-4">
                <div class="info-box success">
                    <h5><i class="fas fa-user-check me-2"></i>Public cible</h5>
                    <p class="mb-0 small">
                        Le dispositif est particulierement adapte aux investisseurs avec des revenus
                        importants, cherchant une strategie patrimoniale sur le long terme.
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
                            <li>Situe dans un immeuble collectif</li>
                            <li>Respectant les normes energetiques en vigueur (RE2020)</li>
                            <li>Acheve dans les 30 mois suivant la declaration d'ouverture de chantier</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card-gouv h-100">
                    <div class="card-header">
                        <i class="fas fa-tools me-2"></i>Logement ancien renove
                    </div>
                    <div class="card-body">
                        <ul class="list-gouv">
                            <li>Travaux representant minimum <strong>30%</strong> du prix d'acquisition</li>
                            <li>Travaux permettant d'atteindre une performance energetique minimale</li>
                            <li>Logement situe dans un immeuble collectif</li>
                            <li>Travaux realises par des professionnels certifies</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="info-box mt-4">
            <h5><i class="fas fa-map-marker-alt me-2"></i>Localisation</h5>
            <p class="mb-0">
                Contrairement au dispositif Pinel, le Jeanbrun s'applique <strong>sur tout le territoire
                francais</strong>, y compris l'outre-mer. Il n'y a plus de zonage geographique restrictif.
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
                        <h5>Duree d'engagement</h5>
                        <p class="small text-muted">
                            Location obligatoire pendant <strong>9 ans minimum</strong>
                            a compter de la mise en location.
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
                        <h5>Residence principale</h5>
                        <p class="small text-muted">
                            Le logement doit etre la <strong>residence principale</strong>
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
                            Le bien doit etre loue <strong>non meuble</strong>
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
                        <h5>Delai de location</h5>
                        <p class="small text-muted">
                            Mise en location dans les <strong>12 mois</strong>
                            suivant l'acquisition ou l'achevement.
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
                <h4>Le locataire ne doit pas etre :</h4>
                <ul class="list-group list-group-flush mb-4">
                    <li class="list-group-item">
                        <i class="fas fa-times text-danger me-2"></i>
                        Un membre du foyer fiscal du proprietaire
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
                        Utiliser le logement comme residence principale
                    </li>
                    <li class="list-group-item">
                        <i class="fas fa-check text-success me-2"></i>
                        Fournir un avis d'imposition pour verification
                    </li>
                </ul>
            </div>
            <div class="col-lg-6">
                <div class="info-box warning">
                    <h5><i class="fas fa-exclamation-triangle me-2"></i>Attention</h5>
                    <p>
                        L'interdiction de louer a un membre de la famille s'applique pendant
                        toute la duree de l'engagement de location (9 ans).
                    </p>
                    <p class="mb-0">
                        En cas de non-respect de cette condition, les avantages fiscaux
                        obtenus seront remis en cause avec application de penalites.
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
            Les loyers sont plafonnes selon le type de location choisi. Ces plafonds sont exprimes
            en euros par metre carre de surface habitable, hors charges.
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
                        <td><strong>Loyer intermediaire</strong></td>
                        <td>Environ -15% du marche</td>
                        <td class="text-center"><span class="badge bg-primary">3,5%</span></td>
                        <td>Classes moyennes</td>
                    </tr>
                    <tr>
                        <td><strong>Loyer social</strong></td>
                        <td>Plafonds HLM (PLUS)</td>
                        <td class="text-center"><span class="badge bg-primary">4,5%</span></td>
                        <td>Menages modestes</td>
                    </tr>
                    <tr>
                        <td><strong>Loyer tres social</strong></td>
                        <td>Plafonds HLM (PLAI)</td>
                        <td class="text-center"><span class="badge bg-primary">5,5%</span></td>
                        <td>Menages tres modestes</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="info-box mt-4">
            <h5><i class="fas fa-info-circle me-2"></i>Coefficient de surface</h5>
            <p class="mb-0">
                Un coefficient multiplicateur s'applique selon la surface du logement :
                <strong>0,7 + (19 / surface habitable)</strong>. Ce coefficient est plafonne a 1,2.
                Ainsi, les petites surfaces beneficient d'un plafond de loyer proportionnellement plus eleve.
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
            Les ressources du locataire (revenu fiscal de reference N-2) ne doivent pas depasser
            certains plafonds selon la composition du foyer et la zone geographique.
        </p>

        <div class="table-responsive">
            <table class="table table-gouv">
                <thead>
                    <tr>
                        <th>Composition du foyer</th>
                        <th>Zone A bis/A</th>
                        <th>Zone B1</th>
                        <th>Zone B2/C</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Personne seule</td>
                        <td>43 475 EUR</td>
                        <td>35 435 EUR</td>
                        <td>32 084 EUR</td>
                    </tr>
                    <tr>
                        <td>Couple</td>
                        <td>64 976 EUR</td>
                        <td>47 321 EUR</td>
                        <td>42 846 EUR</td>
                    </tr>
                    <tr>
                        <td>+ 1 personne a charge</td>
                        <td>85 175 EUR</td>
                        <td>56 905 EUR</td>
                        <td>51 524 EUR</td>
                    </tr>
                    <tr>
                        <td>+ 2 personnes a charge</td>
                        <td>101 693 EUR</td>
                        <td>68 699 EUR</td>
                        <td>62 202 EUR</td>
                    </tr>
                    <tr>
                        <td>+ 3 personnes a charge</td>
                        <td>120 995 EUR</td>
                        <td>80 816 EUR</td>
                        <td>73 173 EUR</td>
                    </tr>
                    <tr>
                        <td>+ 4 personnes a charge</td>
                        <td>136 151 EUR</td>
                        <td>91 078 EUR</td>
                        <td>82 465 EUR</td>
                    </tr>
                    <tr>
                        <td>Majoration par personne</td>
                        <td>+15 168 EUR</td>
                        <td>+10 161 EUR</td>
                        <td>+9 200 EUR</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <p class="text-muted small mt-3">
            <i class="fas fa-info-circle me-1"></i>
            Ces plafonds sont donnes a titre indicatif pour le loyer intermediaire et peuvent varier
            selon les textes en vigueur. Les plafonds pour les loyers sociaux et tres sociaux sont plus bas.
        </p>
    </div>
</section>

<!-- Resume -->
<section class="section">
    <div class="container">
        <h2 class="section-title text-center">Checklist d'eligibilite</h2>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card-gouv">
                    <div class="card-header">
                        <i class="fas fa-clipboard-check me-2"></i>
                        Verifiez ces points avant d'investir
                    </div>
                    <div class="card-body">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="check1">
                            <label class="form-check-label" for="check1">
                                Le bien est situe dans un immeuble collectif en France
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
                                Je m'engage a louer pendant au moins 9 ans
                            </label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="check4">
                            <label class="form-check-label" for="check4">
                                Le logement sera loue nu (non meuble)
                            </label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="check5">
                            <label class="form-check-label" for="check5">
                                Le logement sera la residence principale du locataire
                            </label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="check6">
                            <label class="form-check-label" for="check6">
                                Je ne louerai pas a un membre de ma famille
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
        <h2 class="section-title text-center">Vous etes eligible ?</h2>
        <p class="lead mb-4">
            Estimez maintenant les avantages fiscaux de votre projet d'investissement.
        </p>
        <a href="/simulation.php" class="btn-gouv btn-lg">
            <i class="fas fa-calculator me-2"></i>Acceder au simulateur
        </a>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
