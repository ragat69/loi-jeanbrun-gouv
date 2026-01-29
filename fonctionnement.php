<?php
$current_page = 'fonctionnement';
$page_title = 'Fonctionnement';
$page_description = 'Comment fonctionne le dispositif Jeanbrun ? Decouvrez le mecanisme d\'amortissement fiscal et les conditions de la loi Relance Logement.';
include 'includes/header.php';
?>

<!-- Page Header -->
<section class="page-header page-header-fonctionnement">
    <div class="container">
        <h1><i class="fas fa-cogs me-3"></i>Fonctionnement du dispositif</h1>
        <p class="lead">Comprendre le mecanisme d'amortissement fiscal de la loi Jeanbrun</p>
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
                    Le coeur du dispositif Jeanbrun repose sur un mecanisme d'<strong>amortissement fiscal</strong>
                    qui permet aux bailleurs de deduire annuellement une fraction de la valeur du bien
                    de leurs revenus fonciers.
                </p>
                <p>
                    Contrairement au dispositif Pinel qui offrait une <em>reduction d'impot directe</em>,
                    la loi Jeanbrun agit sur votre <strong>base imposable</strong>. Concretement, vous
                    ne payez pas moins d'impots directement, mais vous reduisez le montant des revenus
                    sur lesquels vous etes impose.
                </p>

                <div class="info-box">
                    <h5><i class="fas fa-lightbulb me-2"></i>En pratique</h5>
                    <p class="mb-0">
                        Si vous achetez un bien a 200 000EUR et que vous beneficiez d'un taux d'amortissement
                        de 3,5%, vous pouvez deduire 7 000EUR par an de vos revenus fonciers. Sur 9 ans,
                        cela represente 63 000EUR de revenus non imposes.
                    </p>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card-gouv">
                    <div class="card-header">
                        <i class="fas fa-exchange-alt me-2"></i>Pinel vs Jeanbrun
                    </div>
                    <div class="card-body">
                        <p><strong>Pinel (expire) :</strong></p>
                        <ul class="small">
                            <li>Reduction d'impot directe</li>
                            <li>Zonage geographique strict</li>
                            <li>Uniquement le neuf</li>
                        </ul>
                        <hr>
                        <p><strong>Jeanbrun (2026) :</strong></p>
                        <ul class="small mb-0">
                            <li>Amortissement fiscal</li>
                            <li>Pas de zonage</li>
                            <li>Neuf et ancien renove</li>
                        </ul>
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
            Le taux d'amortissement varie selon le type de bien et le niveau de loyer pratique.
            Plus vous vous engagez a pratiquer des loyers accessibles, plus le taux est avantageux.
        </p>

        <div class="table-responsive">
            <table class="table table-gouv">
                <thead>
                    <tr>
                        <th>Type de loyer</th>
                        <th>Logement neuf</th>
                        <th>Ancien renove</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Loyer intermediaire</strong></td>
                        <td class="text-center"><span class="badge bg-primary fs-6">3,5%</span></td>
                        <td class="text-center"><span class="badge bg-secondary fs-6">3,0%</span></td>
                        <td>Loyer legerement inferieur au marche (environ -15%)</td>
                    </tr>
                    <tr>
                        <td><strong>Loyer social</strong></td>
                        <td class="text-center"><span class="badge bg-primary fs-6">4,5%</span></td>
                        <td class="text-center"><span class="badge bg-secondary fs-6">3,5%</span></td>
                        <td>Loyer aligne sur les plafonds du logement social</td>
                    </tr>
                    <tr>
                        <td><strong>Loyer tres social</strong></td>
                        <td class="text-center"><span class="badge bg-primary fs-6">5,5%</span></td>
                        <td class="text-center"><span class="badge bg-secondary fs-6">4,0%</span></td>
                        <td>Loyer tres accessible pour les menages modestes</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="info-box warning mt-4">
            <h5><i class="fas fa-exclamation-triangle me-2"></i>Plafond annuel</h5>
            <p class="mb-0">
                L'amortissement est plafonne a <strong>12 000EUR par an</strong> pour un logement.
                Cela signifie que pour un bien de 400 000EUR avec un taux de 3,5%, le calcul theorique
                donne 14 000EUR, mais vous ne pourrez deduire que 12 000EUR.
            </p>
        </div>
    </div>
</section>

<!-- Etapes -->
<section class="section">
    <div class="container">
        <h2 class="section-title">Les etapes pour beneficier du dispositif</h2>
        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="card-gouv h-100">
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <span class="badge bg-primary rounded-circle p-3 fs-4">1</span>
                        </div>
                        <h5 class="text-center">Achat du bien</h5>
                        <p class="small text-muted">
                            Acquerir un logement neuf ou ancien (avec 30% minimum de travaux)
                            situe dans un immeuble collectif en France.
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
                            Louer le bien nu (non meuble) en tant que residence principale
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
                            Appliquer les plafonds de loyers definis selon la zone
                            et verifier les ressources du locataire.
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
                        <h5 class="text-center">Declaration fiscale</h5>
                        <p class="small text-muted">
                            Declarer l'amortissement chaque annee dans votre declaration
                            de revenus fonciers (formulaire 2044).
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Deficit foncier -->
<section class="section section-alt">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h2 class="section-title">Le deficit foncier renforce</h2>
                <p>
                    L'une des nouveautes majeures du dispositif Jeanbrun est le <strong>doublement
                    du plafond de deficit foncier</strong> imputable sur le revenu global.
                </p>
                <p>
                    Jusqu'alors limite a 10 700EUR par an, ce plafond passe a <strong>21 400EUR</strong>
                    jusqu'a fin 2027. Cela permet d'absorber davantage de charges et de reduire
                    significativement votre impot sur le revenu.
                </p>

                <h5 class="mt-4">Charges deductibles :</h5>
                <ul class="list-gouv">
                    <li>Interets d'emprunt (100%)</li>
                    <li>Frais de gestion et d'administration</li>
                    <li>Primes d'assurance</li>
                    <li>Travaux d'entretien et de reparation</li>
                    <li>Taxe fonciere</li>
                    <li>Charges de copropriete non recuperables</li>
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
                            <li>Prix d'achat : 250 000EUR</li>
                            <li>Loyer annuel : 9 600EUR</li>
                            <li>Charges deductibles : 8 000EUR</li>
                            <li>Amortissement (3,5%) : 8 750EUR</li>
                        </ul>
                        <hr>
                        <p><strong>Calcul du deficit :</strong></p>
                        <ul class="small">
                            <li>Revenus fonciers : 9 600EUR</li>
                            <li>- Charges : 8 000EUR</li>
                            <li>- Amortissement : 8 750EUR</li>
                            <li class="text-danger fw-bold">= Deficit : -7 150EUR</li>
                        </ul>
                        <div class="alert alert-success small mb-0">
                            Ce deficit de 7 150EUR est imputable sur votre revenu global,
                            reduisant directement votre base imposable.
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
        <h2 class="section-title text-center">Questions frequentes</h2>
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
                                sur le meme bien (Denormandie, Malraux, etc.). Cependant, vous pouvez detenir
                                plusieurs biens beneficiant chacun du dispositif Jeanbrun.
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
                                obtenus sont remis en cause. Vous devrez rembourser les amortissements deduits
                                majores d'interets de retard, sauf cas de force majeure (deces, invalidite,
                                licenciement, etc.).
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                Le locataire peut-il etre un membre de ma famille ?
                            </button>
                        </h2>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#accordionFAQ">
                            <div class="accordion-body">
                                Non, vous ne pouvez pas louer le bien a un ascendant (parents, grands-parents)
                                ou a un descendant (enfants, petits-enfants). Cette interdiction concerne
                                egalement le foyer fiscal du proprietaire.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                                Puis-je beneficier du dispositif pour un bien deja detenu ?
                            </button>
                        </h2>
                        <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#accordionFAQ">
                            <div class="accordion-body">
                                Pour un bien ancien, il faut realiser des travaux representant au minimum 30%
                                du prix d'acquisition. Si vous possedez deja un bien et souhaitez le faire
                                entrer dans le dispositif, vous devrez donc engager des travaux significatifs
                                et respecter toutes les conditions d'eligibilite.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
