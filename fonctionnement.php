<?php
$current_page = 'fonctionnement';
$page_title_full = 'Loi Jeanbrun 2026 - Fonctionnement | Dispositif Relance Logement';
$page_description = 'Comment fonctionne le dispositif Jeanbrun ? Découvrez le mécanisme d\'amortissement fiscal et les conditions de la loi Relance Logement.';
include 'includes/header.php';
?>

<!-- Page Header -->
<section class="page-header page-header-fonctionnement">
    <div class="container">
        <h1><i class="fas fa-cogs me-3"></i>Fonctionnement du dispositif</h1>
        <p class="lead">Comprendre le mécanisme d'amortissement fiscal de la loi Jeanbrun</p>
    </div>
</section>

<!-- Breadcrumb -->
<nav class="breadcrumb-gouv">
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Accueil</a></li>
            <li class="breadcrumb-item active">Fonctionnement</li>
        </ol>
    </div>
</nav>

<!-- Introduction -->
<section class="section">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <h2 class="section-title">Le principe de l'amortissement</h2>
                <p class="lead">
                    Le coeur du dispositif Jeanbrun repose sur un mécanisme d'<strong>amortissement fiscal</strong>
                    qui permet aux bailleurs de déduire annuellement une fraction de la valeur du bien
                    de leurs revenus fonciers.
                </p>
                <p>
                    Contrairement au dispositif Pinel qui offrait une <em>réduction d'impôt directe</em>,
                    la loi Jeanbrun agit sur votre <strong>base imposable</strong>. Concrètement, vous
                    ne payez pas moins d'impôts directement, mais vous réduisez le montant des revenus
                    sur lesquels vous êtes imposé. Ce dispositif, porté par <a href="/vincent-jeanbrun">Vincent Jeanbrun</a>,
                    ministre délégué au Logement, marque un tournant dans la politique de soutien à l'investissement locatif.
                </p>

                <div class="info-box">
                    <h5><i class="fas fa-lightbulb me-2"></i>En pratique</h5>
                    <p class="mb-0">
                        Si vous achetez un bien à 200 000€ et que vous bénéficiez d'un taux d'amortissement
                        de 3,5%, vous pouvez déduire 7 000€ par an de vos revenus fonciers. Sur 9 ans,
                        cela représente 63 000€ de revenus non imposés.
                    </p>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card-gouv">
                    <div class="card-header">
                        <i class="fas fa-exchange-alt me-2"></i>Pinel vs Jeanbrun
                    </div>
                    <div class="card-body">
                        <p><strong>Pinel (expiré) :</strong></p>
                        <ul class="small">
                            <li>Réduction d'impôt directe</li>
                            <li>Zonage géographique strict</li>
                            <li>Uniquement le neuf</li>
                        </ul>
                        <hr>
                        <p><strong>Jeanbrun (2026) :</strong></p>
                        <ul class="small">
                            <li>Amortissement fiscal</li>
                            <li>Pas de zonage</li>
                            <li>Neuf et ancien rénové</li>
                        </ul>
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

<!-- Taux d'amortissement -->
<section class="section section-alt">
    <div class="container">
        <h2 class="section-title">Les taux d'amortissement</h2>
        <p class="mb-4">
            Le taux d'amortissement varie selon le type de bien et le niveau de loyer pratiqué.
            Plus vous vous engagez à pratiquer des loyers accessibles, plus le taux est avantageux.
        </p>

        <div class="table-responsive">
            <table class="table table-gouv">
                <thead>
                    <tr>
                        <th>Type de loyer</th>
                        <th>Logement neuf</th>
                        <th>Ancien rénové</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Loyer intermédiaire</strong></td>
                        <td class="text-center"><span class="badge bg-primary fs-6">3,5%</span></td>
                        <td class="text-center"><span class="badge bg-secondary fs-6">3,0%</span></td>
                        <td>Loyer légèrement inférieur au marché (environ -15%)</td>
                    </tr>
                    <tr>
                        <td><strong>Loyer social</strong></td>
                        <td class="text-center"><span class="badge bg-primary fs-6">4,5%</span></td>
                        <td class="text-center"><span class="badge bg-secondary fs-6">3,5%</span></td>
                                <td>Loyer aligné sur les plafonds du logement social</td>
                    </tr>
                    <tr>
                        <td><strong>Loyer très social</strong></td>
                        <td class="text-center"><span class="badge bg-primary fs-6">5,5%</span></td>
                        <td class="text-center"><span class="badge bg-secondary fs-6">4,0%</span></td>
                        <td>Loyer très accessible pour les ménages modestes</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="info-box warning mt-4">
            <h5><i class="fas fa-exclamation-triangle me-2"></i>Plafond annuel</h5>
            <p class="mb-0">
                L'amortissement est plafonné à <strong>12 000€ par an</strong> pour un logement.
                Cela signifie que pour un bien de 400 000€ avec un taux de 3,5%, le calcul théorique
                donne 14 000€, mais vous ne pourrez déduire que 12 000€.
            </p>
        </div>
    </div>
</section>

<!-- Étapes -->
<section class="section">
    <div class="container">
        <h2 class="section-title">Les étapes pour bénéficier du dispositif</h2>
        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="card-gouv h-100">
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <span class="badge bg-primary rounded-circle p-3 fs-4">1</span>
                        </div>
                        <h5 class="text-center">Achat du bien</h5>
                        <p class="small text-muted">
                            Acquérir un logement neuf ou ancien (avec 30% minimum de travaux)
                            situé dans un immeuble collectif en France.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card-gouv h-100">
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <span class="badge bg-primary rounded-circle p-3 fs-4">2</span>
                        </div>
                        <h5 class="text-center">Mise en location</h5>
                        <p class="small text-muted">
                            Louer le bien nu (non meublé) en tant que résidence principale
                            du locataire, dans les 12 mois suivant l'acquisition.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card-gouv h-100">
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <span class="badge bg-primary rounded-circle p-3 fs-4">3</span>
                        </div>
                        <h5 class="text-center">Respect des plafonds</h5>
                        <p class="small text-muted">
                            Appliquer les plafonds de loyers définis selon le type de location
                            et vérifier les ressources du locataire.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card-gouv h-100">
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <span class="badge bg-primary rounded-circle p-3 fs-4">4</span>
                        </div>
                        <h5 class="text-center">Déclaration fiscale</h5>
                        <p class="small text-muted">
                            Déclarer l'amortissement chaque année dans votre déclaration
                            de revenus fonciers (formulaire 2044).
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Déficit foncier -->
<section class="section section-alt">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h2 class="section-title">Le déficit foncier renforcé</h2>
                <p>
                    L'une des nouveautés majeures du dispositif Jeanbrun est le <strong>doublement
                    du plafond de déficit foncier</strong> imputable sur le revenu global.
                </p>
                <p>
                    Jusqu'alors limité à 10 700€ par an, ce plafond passe à <strong>21 400€</strong>
                    jusqu'à fin 2027. Cela permet d'absorber davantage de charges et de réduire
                    significativement votre impôt sur le revenu.
                </p>

                <h5 class="mt-4">Charges déductibles :</h5>
                <ul class="list-gouv">
                    <li>Intérêts d'emprunt (100%)</li>
                    <li>Frais de gestion et d'administration</li>
                    <li>Primes d'assurance</li>
                    <li>Travaux d'entretien et de réparation</li>
                    <li>Taxe foncière</li>
                    <li>Charges de copropriété non récupérables</li>
                </ul>
            </div>
            <div class="col-lg-6">
                <div class="card-gouv">
                    <div class="card-header">
                        <i class="fas fa-calculator me-2"></i>Exemple de calcul
                    </div>
                    <div class="card-body">
                        <p><strong>Situation :</strong></p>
                        <ul class="small">
                            <li>Prix d'achat : 250 000€</li>
                            <li>Loyer annuel : 9 600€</li>
                            <li>Charges déductibles : 8 000€</li>
                            <li>Amortissement (3,5%) : 8 750€</li>
                        </ul>
                        <hr>
                        <p><strong>Calcul du déficit :</strong></p>
                        <ul class="small">
                            <li>Revenus fonciers : 9 600€</li>
                            <li>- Charges : 8 000€</li>
                            <li>- Amortissement : 8 750€</li>
                            <li class="text-danger fw-bold">= Déficit : -7 150€</li>
                        </ul>
                        <div class="alert alert-success small mb-0">
                            Ce déficit de 7 150€ est imputable sur votre revenu global,
                            réduisant directement votre base imposable.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ -->
<section class="section">
    <div class="container">
        <h2 class="section-title text-center">Questions fréquentes</h2>
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="accordion accordion-gouv" id="accordionFAQ">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                Peut-on cumuler le Jeanbrun avec d'autres dispositifs ?
                            </button>
                        </h2>
                        <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#accordionFAQ">
                            <div class="accordion-body">
                                Non, le dispositif Jeanbrun n'est pas cumulable avec d'autres avantages fiscaux
                                sur le même bien (Denormandie, Malraux, etc.). Cependant, vous pouvez détenir
                                plusieurs biens bénéficiant chacun du dispositif Jeanbrun.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                Que se passe-t-il si je vends avant 9 ans ?
                            </button>
                        </h2>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#accordionFAQ">
                            <div class="accordion-body">
                                En cas de non-respect de l'engagement de location de 9 ans, les avantages fiscaux
                                obtenus sont remis en cause. Vous devrez rembourser les amortissements déduits
                                majorés d'intérêts de retard, sauf cas de force majeure (décès, invalidité,
                                licenciement, etc.).
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                Le locataire peut-il être un membre de ma famille ?
                            </button>
                        </h2>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#accordionFAQ">
                            <div class="accordion-body">
                                Non, vous ne pouvez pas louer le bien à un ascendant (parents, grands-parents)
                                ou à un descendant (enfants, petits-enfants). Cette interdiction concerne
                                également le foyer fiscal du propriétaire.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                                Puis-je bénéficier du dispositif pour un bien déjà détenu ?
                            </button>
                        </h2>
                        <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#accordionFAQ">
                            <div class="accordion-body">
                                Pour un bien ancien, il faut réaliser des travaux représentant au minimum 30%
                                du prix d'acquisition. Si vous possédez déjà un bien et souhaitez le faire
                                entrer dans le dispositif, vous devrez donc engager des travaux significatifs
                                et respecter toutes les conditions d'éligibilité.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
