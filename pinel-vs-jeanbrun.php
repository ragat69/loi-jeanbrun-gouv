<?php
$current_page = '';
$page_title_full = 'Comparatif Pinel vs Jeanbrun Bailleur Privé | Dispositif Relance Logement';
$page_description = 'Comparaison détaillée entre le dispositif Pinel (expiré fin 2024) et la loi Jeanbrun (2026) : avantages fiscaux, conditions, zonage et rentabilité.';
include 'includes/header.php';
?>

<!-- Page Header -->
<section class="page-header page-header-avantages">
    <div class="container">
        <h1><i class="fas fa-balance-scale me-3"></i>Pinel vs Jeanbrun</h1>
        <p class="lead">Comparatif complet des deux dispositifs d'investissement locatif</p>
    </div>
</section>

<!-- Breadcrumb -->
<nav class="breadcrumb-gouv">
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Accueil</a></li>
            <li class="breadcrumb-item"><a href="/avantages">Avantages</a></li>
            <li class="breadcrumb-item active">Pinel vs Jeanbrun</li>
        </ol>
    </div>
</nav>

<!-- Introduction -->
<section class="section">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <h2 class="section-title">Deux approches fiscales différentes</h2>
                <p class="lead">
                    Le dispositif Pinel, qui a pris fin le 31 décembre 2024, et la loi Jeanbrun, entrée en vigueur
                    en février 2026, reposent sur des mécanismes fiscaux fondamentalement différents.
                </p>
                <p>
                    Alors que le <strong>Pinel offrait une réduction d'impôt directe</strong> calculée sur le prix d'achat
                    du bien, le <strong>dispositif Jeanbrun privilégie l'amortissement</strong>, permettant de déduire
                    chaque année une fraction de la valeur du bien de vos revenus fonciers.
                </p>
                <p>
                    Cette différence fondamentale change la stratégie d'investissement : le Pinel favorisait les
                    contribuables fortement imposés cherchant une réduction immédiate, tandis que le Jeanbrun
                    avantage les investisseurs recherchant une rentabilité réelle sur le long terme.
                </p>
            </div>
            <div class="col-lg-4">
                <div class="card-gouv">
                    <div class="card-header">
                        <i class="fas fa-calendar-alt me-2"></i>Chronologie
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            <div class="timeline-item">
                                <h4>2014</h4>
                                <p>Lancement du dispositif Pinel</p>
                            </div>
                            <div class="timeline-item">
                                <h4>31/12/2024</h4>
                                <p>Fin du dispositif Pinel</p>
                            </div>
                            <div class="timeline-item">
                                <h4>Février 2026</h4>
                                <p>Entrée en vigueur du dispositif Jeanbrun</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Tableau comparatif principal -->
<section class="section section-alt">
    <div class="container">
        <h2 class="section-title text-center">Tableau comparatif complet</h2>
        <div class="table-responsive mt-4">
            <table class="table table-gouv">
                <thead>
                    <tr>
                        <th style="width: 25%">Critère</th>
                        <th style="width: 37.5%">Dispositif Pinel (2014-2024)</th>
                        <th style="width: 37.5%">Dispositif Jeanbrun (2026)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Mécanisme fiscal</strong></td>
                        <td>
                            <i class="fas fa-minus-circle text-muted me-2"></i>
                            Réduction d'impôt directe calculée sur le prix d'achat
                        </td>
                        <td>
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Amortissement annuel + déduction des charges
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Avantage fiscal</strong></td>
                        <td>
                            9% sur 6 ans<br>
                            12% sur 9 ans<br>
                            14% sur 12 ans<br>
                            <small class="text-muted">(taux 2024, dégressifs depuis 2023)</small>
                        </td>
                        <td>
                            3,5% à 5,5% par an d'amortissement<br>
                            + déficit foncier 21 400€/an<br>
                            + 100% charges déductibles
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Zonage géographique</strong></td>
                        <td>
                            <i class="fas fa-times-circle text-danger me-2"></i>
                            Zones A bis, A et B1 uniquement<br>
                            <small class="text-muted">Zones B2/C exclues</small>
                        </td>
                        <td>
                            <i class="fas fa-check-circle text-success me-2"></i>
                            <strong>Toute la France</strong><br>
                            <small class="text-muted">Métropole + Outre-mer</small>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Type de bien</strong></td>
                        <td>
                            <i class="fas fa-minus-circle text-muted me-2"></i>
                            Logement neuf uniquement<br>
                            <small class="text-muted">(ou VEFA, réhabilité assimilé neuf)</small>
                        </td>
                        <td>
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Neuf <strong>ET</strong> ancien rénové<br>
                            <small class="text-muted">(30% min. de travaux)</small>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Plafond d'investissement</strong></td>
                        <td>
                            300 000€ par an<br>
                            5 500€/m² maximum
                        </td>
                        <td>
                            <i class="fas fa-check-circle text-success me-2"></i>
                            <strong>Pas de plafond global</strong><br>
                            <small class="text-muted">Amortissement plafonné à 12 000€/an</small>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Durée d'engagement</strong></td>
                        <td>
                            6, 9 ou 12 ans<br>
                            <small class="text-muted">Au choix de l'investisseur</small>
                        </td>
                        <td>
                            <strong>9 ans minimum</strong>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Plafonds de loyers</strong></td>
                        <td>
                            Oui, selon zone géographique<br>
                            <small class="text-muted">A bis: 18,25€/m² - B1: 10,55€/m²</small>
                        </td>
                        <td>
                            Oui, selon type de location<br>
                            <small class="text-muted">Intermédiaire / Social / Très social</small>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Plafonds de ressources</strong></td>
                        <td>
                            Oui, selon zone et composition du foyer
                        </td>
                        <td>
                            Oui, selon type de location choisi
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Déficit foncier</strong></td>
                        <td>
                            10 700€/an<br>
                            <small class="text-muted">(droit commun)</small>
                        </td>
                        <td>
                            <i class="fas fa-check-circle text-success me-2"></i>
                            <strong>21 400€/an</strong><br>
                            <small class="text-muted">(jusqu'au 31/12/2027)</small>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Location à un ascendant/descendant</strong></td>
                        <td>
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Autorisée (hors foyer fiscal)
                        </td>
                        <td>
                            <i class="fas fa-times-circle text-danger me-2"></i>
                            Interdite
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Cumul avec d'autres dispositifs</strong></td>
                        <td>
                            Non cumulable sur le même bien
                        </td>
                        <td>
                            Non cumulable sur le même bien
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>

<!-- Comparaison des avantages -->
<section class="section">
    <div class="container">
        <h2 class="section-title text-center">Avantages et inconvénients</h2>
        <div class="row g-4 mt-4">
            <div class="col-lg-6">
                <div class="card-gouv h-100">
                    <div class="card-header bg-secondary text-white">
                        <i class="fas fa-home me-2"></i>Dispositif Pinel (expiré)
                    </div>
                    <div class="card-body">
                        <h5 class="text-success"><i class="fas fa-plus-circle me-2"></i>Points forts</h5>
                        <ul class="list-gouv">
                            <li>Réduction d'impôt immédiate et prévisible</li>
                            <li>Choix de la durée d'engagement (6, 9 ou 12 ans)</li>
                            <li>Location aux ascendants/descendants possible</li>
                            <li>Bien connu des investisseurs et conseillers</li>
                        </ul>

                        <h5 class="text-danger mt-4"><i class="fas fa-minus-circle me-2"></i>Points faibles</h5>
                        <ul class="list-gouv">
                            <li>Zonage restrictif (grandes villes uniquement)</li>
                            <li>Limité au neuf (prix au m² élevés)</li>
                            <li>Plafond d'investissement contraignant</li>
                            <li>Taux dégressifs depuis 2023</li>
                            <li>Rentabilité locative souvent faible</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card-gouv h-100">
                    <div class="card-header" style="background: var(--bleu-france); color: white;">
                        <i class="fas fa-building me-2"></i>Dispositif Jeanbrun (2026)
                    </div>
                    <div class="card-body">
                        <h5 class="text-success"><i class="fas fa-plus-circle me-2"></i>Points forts</h5>
                        <ul class="list-gouv">
                            <li>Liberté géographique totale</li>
                            <li>Ouvert au neuf ET à l'ancien rénové</li>
                            <li>Déficit foncier doublé (21 400€/an)</li>
                            <li>Pas de plafond d'investissement global</li>
                            <li>Meilleure rentabilité potentielle</li>
                            <li>Charges 100% déductibles</li>
                        </ul>

                        <h5 class="text-danger mt-4"><i class="fas fa-minus-circle me-2"></i>Points faibles</h5>
                        <ul class="list-gouv">
                            <li>Engagement minimum de 9 ans (non modulable)</li>
                            <li>Location familiale interdite</li>
                            <li>Mécanisme plus complexe à appréhender</li>
                            <li>Avantage fiscal moins "visible" immédiatement</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Simulation comparative -->
<section class="section section-alt">
    <div class="container">
        <h2 class="section-title text-center">Exemple chiffré comparatif</h2>
        <p class="text-center mb-4">
            Simulation pour un investissement de <strong>250 000€</strong> avec un loyer intermédiaire
        </p>

        <div class="row g-4">
            <div class="col-lg-6">
                <div class="card-gouv">
                    <div class="card-header bg-secondary text-white">
                        Pinel (taux 2024) - 9 ans
                    </div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <tr>
                                <td>Prix d'achat</td>
                                <td class="text-end"><strong>250 000€</strong></td>
                            </tr>
                            <tr>
                                <td>Réduction d'impôt (12% sur 9 ans)</td>
                                <td class="text-end"><strong>30 000€</strong></td>
                            </tr>
                            <tr>
                                <td>Soit par an</td>
                                <td class="text-end">3 333€/an</td>
                            </tr>
                            <tr class="table-secondary">
                                <td><strong>Avantage fiscal total</strong></td>
                                <td class="text-end"><strong>30 000€</strong></td>
                            </tr>
                        </table>
                        <p class="small text-muted mb-0">
                            <i class="fas fa-info-circle me-1"></i>
                            Réduction d'impôt directe, indépendante du niveau de revenus fonciers.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card-gouv">
                    <div class="card-header" style="background: var(--bleu-france); color: white;">
                        Jeanbrun (loyer intermédiaire) - 9 ans
                    </div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <tr>
                                <td>Prix d'achat</td>
                                <td class="text-end"><strong>250 000€</strong></td>
                            </tr>
                            <tr>
                                <td>Amortissement (3,5% × 9 ans)</td>
                                <td class="text-end"><strong>78 750€</strong></td>
                            </tr>
                            <tr>
                                <td>Économie d'impôt (TMI 30%)</td>
                                <td class="text-end">23 625€</td>
                            </tr>
                            <tr>
                                <td>Économie d'impôt (TMI 41%)</td>
                                <td class="text-end">32 288€</td>
                            </tr>
                            <tr class="table-secondary">
                                <td><strong>+ Déficit foncier potentiel</strong></td>
                                <td class="text-end"><strong>Selon charges</strong></td>
                            </tr>
                        </table>
                        <p class="small text-muted mb-0">
                            <i class="fas fa-info-circle me-1"></i>
                            L'économie réelle dépend de votre TMI. Ajouter les déductions de charges.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="info-box mt-4">
            <h5><i class="fas fa-lightbulb me-2"></i>À retenir</h5>
            <p class="mb-0">
                Le dispositif Jeanbrun devient plus avantageux que le Pinel pour les contribuables avec une
                <strong>tranche marginale d'imposition (TMI) élevée</strong> (30% et plus) et des
                <strong>charges déductibles significatives</strong> (intérêts d'emprunt, travaux, gestion...).
                De plus, la liberté géographique permet d'investir dans des marchés à meilleur rendement locatif.
            </p>
        </div>
    </div>
</section>

<!-- Pour qui ? -->
<section class="section">
    <div class="container">
        <h2 class="section-title text-center">Quel dispositif pour quel profil ?</h2>
        <div class="row g-4 mt-4">
            <div class="col-lg-6">
                <div class="card-gouv h-100">
                    <div class="card-header">
                        <i class="fas fa-user me-2"></i>Le Pinel était adapté à...
                    </div>
                    <div class="card-body">
                        <ul class="list-gouv">
                            <li>Investisseurs cherchant une réduction d'impôt immédiate et prévisible</li>
                            <li>Ceux souhaitant loger un membre de leur famille</li>
                            <li>Investisseurs privilégiant la simplicité de calcul</li>
                            <li>Profils avec une forte imposition mais peu de charges à déduire</li>
                            <li>Investissement dans les grandes métropoles uniquement</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card-gouv h-100">
                    <div class="card-header">
                        <i class="fas fa-user-check me-2"></i>Le Jeanbrun est adapté à...
                    </div>
                    <div class="card-body">
                        <ul class="list-gouv">
                            <li>Investisseurs cherchant une rentabilité locative optimisée</li>
                            <li>Ceux ayant un emprunt avec des intérêts à déduire</li>
                            <li>Investisseurs intéressés par l'ancien à rénover</li>
                            <li>Profils souhaitant investir hors des grandes métropoles</li>
                            <li>Contribuables avec TMI de 30% ou plus</li>
                            <li>Stratégie patrimoniale long terme (9 ans minimum)</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Conclusion -->
<section class="section section-alt">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <h2 class="section-title text-center">Conclusion</h2>
                <p class="lead text-center">
                    Le passage du Pinel au Jeanbrun marque un changement de philosophie dans l'aide à l'investissement
                    locatif en France.
                </p>
                <p>
                    Le <strong>dispositif Pinel</strong>, avec sa réduction d'impôt directe, avait l'avantage de la
                    simplicité et de la prévisibilité. Cependant, ses contraintes géographiques et sa limitation au
                    neuf ont souvent conduit à des investissements peu rentables, dans des programmes où le prix au
                    m² était gonflé par l'effet d'aubaine fiscal.
                </p>
                <p>
                    Le <strong>dispositif Jeanbrun</strong> adopte une approche différente : en supprimant le zonage
                    et en ouvrant à l'ancien rénové, il permet aux investisseurs de choisir des biens sur des critères
                    de rentabilité réelle plutôt que d'éligibilité géographique. Le mécanisme d'amortissement, combiné
                    au déficit foncier doublé, offre une optimisation fiscale potentiellement supérieure pour les
                    contribuables fortement imposés.
                </p>
                <div class="text-center mt-4">
                    <a href="/simulation" class="btn-gouv btn-lg me-3">
                        <i class="fas fa-calculator me-2"></i>Simuler mon projet Jeanbrun
                    </a>
                    <a href="/fonctionnement" class="btn-gouv-outline btn-lg">
                        <i class="fas fa-cogs me-2"></i>Comprendre le fonctionnement
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
