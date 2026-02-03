<?php
$current_page = 'simulation';
$page_title_full = 'Loi Jeanbrun 2026 - Simulation | Dispositif Relance Logement';
$page_description = 'Simulez votre investissement avec le dispositif Jeanbrun : calculez l\'amortissement, le déficit foncier et vos économies d\'impot.';
include 'includes/header.php';
?>

<!-- Page Header -->
<section class="page-header page-header-simulation">
    <div class="container">
        <h1><i class="fas fa-calculator me-3"></i>Simulateur Jeanbrun</h1>
        <p class="lead">Estimez les avantages fiscaux de votre investissement locatif</p>
    </div>
</section>

<!-- Breadcrumb -->
<nav class="breadcrumb-gouv">
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Accueil</a></li>
            <li class="breadcrumb-item active">Simulation</li>
        </ol>
    </div>
</nav>

<!-- Section pédagogique -->
<section class="section section-alt">
    <div class="container">
        <h2 class="section-title text-center mb-4">Comment fonctionne l'avantage fiscal Jeanbrun ?</h2>

        <div class="row justify-content-center mb-4">
            <div class="col-lg-10">
                <div class="info-box">
                    <h5 class="mb-3"><i class="fas fa-lightbulb me-2"></i>Le principe : l'amortissement plutôt que la réduction d'impôt</h5>
                    <div class="row">
                        <div class="col-md-4 mb-3 mb-md-0">
                            <div class="d-flex align-items-start">
                                <div class="text-bleu-france me-2"><i class="fas fa-chart-line fa-2x"></i></div>
                                <div>
                                    <strong>Amortissement comptable</strong>
                                    <p class="small mb-0">Déduisez chaque année de 3% à 5,5% de la valeur de votre bien de vos revenus locatifs.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3 mb-md-0">
                            <div class="d-flex align-items-start">
                                <div class="text-bleu-france me-2"><i class="fas fa-piggy-bank fa-2x"></i></div>
                                <div>
                                    <strong>Réduction d'impôt sur les loyers</strong>
                                    <p class="small mb-0">L'amortissement réduit, voire annule, l'imposition sur vos loyers perçus pendant de nombreuses années.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex align-items-start">
                                <div class="text-bleu-france me-2"><i class="fas fa-file-invoice-dollar fa-2x"></i></div>
                                <div>
                                    <strong>Déficit foncier</strong>
                                    <p class="small mb-0">Jusqu'à 21 400 € par an déductibles de votre revenu global (salaires, pensions...).</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card-gouv">
                    <div class="card-header text-center">
                        <i class="fas fa-calculator me-2"></i>Exemple concret
                    </div>
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <p class="mb-2"><strong>Investissement :</strong> 250 000 €</p>
                                <p class="mb-2"><strong>Base amortissable :</strong> 200 000 € (80% du prix)</p>
                                <p class="mb-2"><strong>Amortissement annuel :</strong> 7 000 € (3,5% en loyer intermédiaire)</p>
                                <p class="mb-2"><strong>TMI 45% :</strong> 3 150 € d'économie d'impôt par an</p>
                            </div>
                            <div class="col-md-4 text-center">
                                <div class="chiffre-cle">
                                    <div class="nombre text-bleu-france">28 350 €</div>
                                    <div class="label">économie sur 9 ans</div>
                                </div>
                            </div>
                        </div>
                        <div class="text-center mt-3">
                            <a href="fonctionnement.php" class="btn-gouv-outline btn-sm">
                                <i class="fas fa-info-circle me-2"></i>En savoir plus sur le dispositif
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Simulateur -->
<section class="section">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <h2 class="section-title">Votre projet d'investissement</h2>

                <form id="simulateur-form" class="simulation-form">
                    <!-- Champs mode simple (toujours visibles) -->
                    <div id="champs-simples">
                        <!-- Type de bien -->
                        <div class="mb-4">
                            <label class="form-label">Type de bien</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="typeBien" id="typeNeuf" value="neuf" checked>
                                <label class="form-check-label" for="typeNeuf">
                                    Logement neuf
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="typeBien" id="typeAncien" value="ancien">
                                <label class="form-check-label" for="typeAncien">
                                    Ancien avec travaux
                                </label>
                            </div>
                        </div>

                        <!-- Prix d'acquisition -->
                        <div class="mb-4">
                            <label for="prixAchat" class="form-label">Prix d'achat du bien</label>
                            <input type="number" class="form-control" id="prixAchat" value="250000" min="50000" max="1000000" step="1000">
                            <div class="form-text">Hors frais de notaire. La base amortissable sera calculée à 80% de ce montant (terrain non amortissable).</div>
                        </div>

                        <!-- Montant des travaux (si ancien) -->
                        <div class="mb-4" id="blocTravaux" style="display: none;">
                            <label for="montantTravaux" class="form-label">Montant des travaux</label>
                            <input type="number" class="form-control" id="montantTravaux" value="75000" min="0" max="500000" step="1000">
                            <div class="form-text">Doivent représenter au moins 30% du prix d'acquisition pour être éligible au dispositif.</div>
                        </div>

                        <!-- Type de loyer -->
                        <div class="mb-4">
                            <label for="typeLoyer" class="form-label">Niveau de loyer que vous pratiquerez</label>
                            <select class="form-select" id="typeLoyer">
                                <option value="intermédiaire">Loyer intermédiaire (3,5% neuf / 3,0% ancien) - Plafond 8 000 €/an</option>
                                <option value="social">Loyer social (4,5% neuf / 3,5% ancien) - Plafond 10 000 €/an</option>
                                <option value="tressocial">Loyer très social (5,5% neuf / 4,0% ancien) - Plafond 12 000 €/an</option>
                            </select>
                            <div class="form-text">Le taux d'amortissement et le plafond de déduction dépendent du niveau de loyer choisi.</div>
                        </div>

                        <!-- TMI -->
                        <div class="mb-4">
                            <label for="tmi" class="form-label">Votre tranche marginale d'imposition (TMI)</label>
                            <select class="form-select" id="tmi">
                                <option value="0">0% (non imposable)</option>
                                <option value="11">11%</option>
                                <option value="30" selected>30%</option>
                                <option value="41">41%</option>
                                <option value="45">45%</option>
                            </select>
                            <div class="form-text">Votre TMI détermine l'économie d'impôt réalisée grâce à l'amortissement.</div>
                        </div>
                    </div>

                    <!-- Toggle pour mode avancé -->
                    <div class="text-center my-4">
                        <button type="button" class="btn-gouv-outline" id="toggle-mode-avance" onclick="toggleModeAvance()">
                            <i class="fas fa-cog me-2"></i>Passer en mode avancé
                        </button>
                    </div>

                    <!-- Champs mode détaillé (masqués par défaut) -->
                    <div id="champs-avances" style="display: none;">
                        <div class="info-box mb-4">
                            <h6 class="mb-2"><i class="fas fa-info-circle me-2"></i>Mode avancé</h6>
                            <p class="small mb-0">Ces informations permettent un calcul plus précis de votre résultat foncier réel et de l'impact fiscal détaillé année par année.</p>
                        </div>

                        <!-- Loyer mensuel -->
                        <div class="mb-4">
                            <label for="loyerMensuel" class="form-label">Loyer mensuel estimé</label>
                            <input type="number" class="form-control" id="loyerMensuel" value="800" min="200" max="5000" step="50">
                            <div class="form-text">Loyer mensuel hors charges que vous prévoyez de percevoir.</div>
                        </div>

                        <!-- Charges annuelles -->
                        <div class="mb-4">
                            <label for="chargesAnnuelles" class="form-label">Charges annuelles estimées</label>
                            <input type="number" class="form-control" id="chargesAnnuelles" value="3000" min="0" max="50000" step="100">
                            <div class="form-text">Taxe foncière, assurance, charges de copropriété, frais de gestion locative...</div>
                        </div>

                        <!-- Intérêts d'emprunt -->
                        <div class="mb-4">
                            <label for="intérêtsAnnuels" class="form-label">Intérêts d'emprunt annuels</label>
                            <input type="number" class="form-control" id="intérêtsAnnuels" value="5000" min="0" max="50000" step="100">
                            <div class="form-text">Intérêts payés la première année (montant dégressif les années suivantes).</div>
                        </div>
                    </div>

                    <button type="button" class="btn-gouv w-100" onclick="calculerSimulation()">
                        <i class="fas fa-calculator me-2"></i>Calculer ma simulation
                    </button>
                </form>
            </div>

            <div class="col-lg-6">
                <h2 class="section-title">Resultats de la simulation</h2>

                <div id="resultats">
                    <div class="simulation-result mb-4">
                        <p class="mb-2">Économie d'impot estimee sur 9 ans</p>
                        <div class="montant" id="économieTotal">-- EUR</div>
                    </div>

                    <div class="card-gouv mb-3">
                        <div class="card-header">
                            <i class="fas fa-chart-pie me-2"></i>Detail annuel (année 1)
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-6">
                                    <div class="small text-muted">Revenus fonciers</div>
                                    <div class="fs-5 fw-bold" id="revenusFonciers">-- EUR</div>
                                </div>
                                <div class="col-6">
                                    <div class="small text-muted">Amortissement</div>
                                    <div class="fs-5 fw-bold text-bleu-france" id="amortissement">-- EUR</div>
                                </div>
                                <div class="col-6">
                                    <div class="small text-muted">Charges déductibles</div>
                                    <div class="fs-5 fw-bold" id="chargesDéductibles">-- EUR</div>
                                </div>
                                <div class="col-6">
                                    <div class="small text-muted">Resultat foncier</div>
                                    <div class="fs-5 fw-bold" id="resultatFoncier">-- EUR</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-gouv mb-3">
                        <div class="card-header">
                            <i class="fas fa-piggy-bank me-2"></i>Impact fiscal annuel
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-6">
                                    <div class="small text-muted">Déficit imputable</div>
                                    <div class="fs-5 fw-bold text-success" id="déficitImputable">-- EUR</div>
                                </div>
                                <div class="col-6">
                                    <div class="small text-muted">Économie Impôt sur le Revenu annuelle</div>
                                    <div class="fs-5 fw-bold text-success" id="économieAnnuelle">-- EUR</div>
                                </div>
                                <div class="col-6">
                                    <div class="small text-muted">Économie Prélèvements Sociaux (17,2%)</div>
                                    <div class="fs-5 fw-bold text-success" id="économiePS">-- EUR</div>
                                </div>
                                <div class="col-6">
                                    <div class="small text-muted">Économie totale/an (IR + PS)</div>
                                    <div class="fs-5 fw-bold text-success" id="économieTotaleAn">-- EUR</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-gouv">
                        <div class="card-header">
                            <i class="fas fa-info-circle me-2"></i>Parametres utilises
                        </div>
                        <div class="card-body">
                            <div class="row small">
                                <div class="col-6">
                                    <p class="mb-1"><strong>Base amortissable :</strong> <span id="baseAmortissable">--</span></p>
                                    <p class="mb-1"><strong>Taux applique :</strong> <span id="tauxApplique">--</span></p>
                                </div>
                                <div class="col-6">
                                    <p class="mb-1"><strong>Plafond amortissement :</strong> 12 000 EUR/an</p>
                                    <p class="mb-1"><strong>Plafond déficit :</strong> 21 400 EUR/an</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Avertissement -->
<section class="section section-alt">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="info-box warning">
                    <h5><i class="fas fa-exclamation-triangle me-2"></i>Avertissement</h5>
                    <p>
                        Cette simulation est fournie a titre indicatif et ne constitue en aucun cas
                        un conseil fiscal ou juridique. Les resultats sont bases sur les parametres
                        que vous avez renseignes et sur les regles fiscales connues a ce jour.
                    </p>
                    <p class="mb-0">
                        Pour une etude personnalisee de votre projet, nous vous recommandons de
                        consulter un conseiller en gestion de patrimoine ou un expert-comptable.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Exemples -->
<section class="section">
    <div class="container">
        <h2 class="section-title text-center">Exemples de simulations</h2>
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card-gouv h-100">
                    <div class="card-header">T2 a Lyon - 220 000 EUR</div>
                    <div class="card-body">
                        <ul class="small mb-3">
                            <li>Logement neuf</li>
                            <li>Loyer intermédiaire : 650 EUR/mois</li>
                            <li>Amortissement 3,5% : 7 700 EUR/an</li>
                        </ul>
                        <div class="bg-light p-3 text-center">
                            <div class="small text-muted">Économie estimee sur 9 ans</div>
                            <div class="fs-4 fw-bold text-bleu-france">~16 630 EUR</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card-gouv h-100">
                    <div class="card-header">T3 a Bordeaux - 300 000 EUR</div>
                    <div class="card-body">
                        <ul class="small mb-3">
                            <li>Logement neuf</li>
                            <li>Loyer social : 750 EUR/mois</li>
                            <li>Amortissement 4,5% : 12 000 EUR/an (plafonne)</li>
                        </ul>
                        <div class="bg-light p-3 text-center">
                            <div class="small text-muted">Économie estimee sur 9 ans</div>
                            <div class="fs-4 fw-bold text-bleu-france">~37 000 EUR</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card-gouv h-100">
                    <div class="card-header">T2 ancien rénové - 180 000 EUR</div>
                    <div class="card-body">
                        <ul class="small mb-3">
                            <li>Ancien + 60 000 EUR travaux</li>
                            <li>Loyer intermédiaire : 600 EUR/mois</li>
                            <li>Amortissement 3,0% : 7 200 EUR/an</li>
                        </ul>
                        <div class="bg-light p-3 text-center">
                            <div class="small text-muted">Économie estimee sur 9 ans</div>
                            <div class="fs-4 fw-bold text-bleu-france">~21 500 EUR</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Explications sur les calculs -->
<section class="section section-alt">
    <div class="container">
        <h2 class="section-title text-center mb-5">Simulation Jeanbrun : explications</h2>

        <div class="row justify-content-center">
            <div class="col-lg-10">
                <h3 class="h4 mb-4"><i class="fas fa-calculator me-2 text-bleu-france"></i>Comment sont calculés les avantages fiscaux ?</h3>

                <p class="lead">
                    Le dispositif "Relance Logement" Jeanbrun repose sur deux mécanismes complémentaires qui permettent de réduire significativement votre imposition :
                </p>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-bleu-france text-white">
                        <h4 class="mb-0"><i class="fas fa-home me-2"></i>1. L'amortissement du bien</h4>
                    </div>
                    <div class="card-body">
                        <p>Chaque année, vous pouvez déduire de vos revenus locatifs une fraction de la valeur de votre bien :</p>
                        <ul>
                            <li><strong>Base amortissable</strong> : 80% du prix d'acquisition (le terrain n'est pas amortissable)</li>
                            <li><strong>Taux d'amortissement</strong> : de 3% à 5,5% selon le type de bien et le niveau de loyer pratiqué</li>
                            <li><strong>Plafond annuel</strong> : de 8 000 € à 12 000 € selon le niveau de loyer</li>
                        </ul>

                        <div class="bg-light p-3 rounded mt-3">
                            <p class="mb-2"><strong>Exemple de calcul :</strong> Pour un bien neuf à 250 000 € en loyer intermédiaire (3,5%)</p>
                            <ul class="mb-0">
                                <li>Base amortissable = 250 000 € × 0,8 = 200 000 €</li>
                                <li>Amortissement annuel = 200 000 € × 0,035 = <strong class="text-bleu-france">7 000 €</strong></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-bleu-france text-white">
                        <h4 class="mb-0"><i class="fas fa-chart-line me-2"></i>2. Le déficit foncier</h4>
                    </div>
                    <div class="card-body">
                        <p>Si vos charges (intérêts d'emprunt, charges déductibles, amortissement) dépassent vos loyers, le déficit généré peut être déduit de votre revenu global :</p>
                        <ul>
                            <li><strong>Plafond de déduction</strong> : jusqu'à 21 400 € par an (si travaux de rénovation énergétique)</li>
                            <li><strong>Impact fiscal</strong> : économie d'Impôt sur le Revenu + économie de Prélèvements Sociaux (17,2%)</li>
                        </ul>

                        <div class="bg-light p-3 rounded mt-3">
                            <p class="mb-2"><strong>Calcul de l'économie fiscale totale :</strong></p>
                            <ul class="mb-0">
                                <li>Économie IR = Déficit imputable × Votre TMI</li>
                                <li>Économie PS = Déficit imputable × 17,2%</li>
                                <li><strong class="text-success">Économie totale = Économie IR + Économie PS</strong></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <h3 class="h4 mb-4 mt-5"><i class="fas fa-check-circle me-2 text-success"></i>Les avantages du dispositif Jeanbrun</h3>

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-check text-success me-2 mt-1"></i>
                            <div>
                                <strong>Réduction durable de la fiscalité</strong>
                                <p class="small mb-0">L'amortissement permet de réduire, voire d'annuler l'imposition sur les loyers pendant de nombreuses années</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-check text-success me-2 mt-1"></i>
                            <div>
                                <strong>Déficit foncier renforcé</strong>
                                <p class="small mb-0">Jusqu'à 21 400 € déductibles de votre revenu global par an, réduisant votre impôt total</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-check text-success me-2 mt-1"></i>
                            <div>
                                <strong>Applicable partout en France</strong>
                                <p class="small mb-0">Aucun zonage géographique contrairement au Pinel</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-check text-success me-2 mt-1"></i>
                            <div>
                                <strong>Éligible dans l'ancien avec travaux</strong>
                                <p class="small mb-0">Permet la rénovation de logements dégradés avec un minimum de 30% de travaux</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-check text-success me-2 mt-1"></i>
                            <div>
                                <strong>Pas de réintégration à la revente</strong>
                                <p class="small mb-0">Les amortissements déduits ne sont pas réintégrés dans le calcul de la plus-value (contrairement au LMNP)</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-check text-success me-2 mt-1"></i>
                            <div>
                                <strong>Cumulable</strong>
                                <p class="small mb-0">L'amortissement et le déficit foncier peuvent se cumuler pour maximiser l'avantage fiscal</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="info-box mt-5">
                    <h5><i class="fas fa-info-circle me-2"></i>En résumé</h5>
                    <p>Le dispositif Jeanbrun transforme votre investissement locatif en un véritable levier de défiscalisation :</p>
                    <ul class="mb-0">
                        <li><strong>Sur 9 ans minimum</strong> : engagement de location obligatoire</li>
                        <li><strong>Pour tous les contribuables</strong> : l'avantage fiscal est proportionnel à votre TMI</li>
                        <li><strong>Sans complexité excessive</strong> : calculs automatisés et gestion simplifiée</li>
                    </ul>
                    <p class="mb-0 mt-3">
                        <strong>L'économie fiscale peut représenter plusieurs milliers d'euros par an</strong> et se cumule avec une stratégie patrimoniale de long terme, tout en contribuant à l'offre de logements locatifs abordables.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
// Afficher/masquer le bloc travaux selon le type de bien
document.querySelectorAll('input[name="typeBien"]').forEach(function(radio) {
    radio.addEventListener('change', function() {
        document.getElementById('blocTravaux').style.display =
            this.value === 'ancien' ? 'block' : 'none';
        calculerSimulation();
    });
});

// Toggle mode avancé
function toggleModeAvance() {
    const champsAvances = document.getElementById('champs-avances');
    const bouton = document.getElementById('toggle-mode-avance');
    const isVisible = champsAvances.style.display !== 'none';

    if (isVisible) {
        champsAvances.style.display = 'none';
        bouton.innerHTML = '<i class="fas fa-cog me-2"></i>Passer en mode avancé';
    } else {
        champsAvances.style.display = 'block';
        bouton.innerHTML = '<i class="fas fa-cog me-2"></i>Revenir au mode simple';
        // Scroll smooth vers les champs avancés
        champsAvances.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    // Recalculer la simulation
    calculerSimulation();
}

async function calculerSimulation() {
    // Récupérer les valeurs
    const typeBien = document.querySelector('input[name="typeBien"]:checked').value;
    const prixAchat = parseFloat(document.getElementById('prixAchat').value) || 0;
    const montantTravaux = typeBien === 'ancien' ? (parseFloat(document.getElementById('montantTravaux').value) || 0) : 0;
    const typeLoyer = document.getElementById('typeLoyer').value;
    const tmi = parseFloat(document.getElementById('tmi').value) || 0;

    // Vérifier si on est en mode avancé
    const modeAvance = document.getElementById('champs-avances').style.display !== 'none';

    // Préparer les données à envoyer
    const formData = new FormData();
    formData.append('typeBien', typeBien);
    formData.append('prixAchat', prixAchat);
    formData.append('montantTravaux', montantTravaux);
    formData.append('typeLoyer', typeLoyer);
    formData.append('tmi', tmi);
    formData.append('modeAvance', modeAvance ? 'true' : 'false');

    if (modeAvance) {
        const loyerMensuel = parseFloat(document.getElementById('loyerMensuel').value) || 0;
        const chargesAnnuelles = parseFloat(document.getElementById('chargesAnnuelles').value) || 0;
        const intérêtsAnnuels = parseFloat(document.getElementById('intérêtsAnnuels').value) || 0;

        formData.append('loyerMensuel', loyerMensuel);
        formData.append('chargesAnnuelles', chargesAnnuelles);
        formData.append('intérêtsAnnuels', intérêtsAnnuels);
    }

    try {
        // Envoyer les données au serveur API (sans .php pour éviter la redirection)
        const response = await fetch('api-calcul', {
            method: 'POST',
            body: formData
        });

        // Vérifier si la réponse est OK
        if (!response.ok) {
            console.error('Erreur HTTP:', response.status);
            return;
        }

        const data = await response.json();

        if (data.success) {
            if (data.mode === 'avance') {
                // Afficher les résultats (mode détaillé)
                afficherResultatsDetailles(
                    data.économieTotal,
                    data.revenusFonciers,
                    data.amortissement,
                    data.chargesDéductibles,
                    data.resultatFoncier,
                    data.déficitImputable,
                    data.économieIR,
                    data.économiePS,
                    data.économieTotaleAn,
                    data.baseAmortissable,
                    data.tauxAmortissement
                );
            } else {
                // Afficher les résultats (mode simple)
                afficherResultatsSimples(
                    data.économieTotal,
                    data.amortissement,
                    data.économieIRannuelle,
                    data.tmi,
                    data.baseAmortissable,
                    data.tauxAmortissement,
                    data.plafondAmortissement
                );
            }
        } else {
            console.error('Erreur dans la réponse:', data);
        }
    } catch (error) {
        console.error('Erreur lors du calcul:', error);
    }
}

function afficherResultatsSimples(économieTotal, amortissement, économieIRannuelle,
                                  tmi, baseAmortissable, tauxAmortissement, plafondAmortissement) {
    // Afficher le résumé simple
    const resultatsDiv = document.getElementById('resultats');
    resultatsDiv.innerHTML = `
        <div class="simulation-result mb-4">
            <p class="mb-2">Économie d'impôt estimée sur 9 ans</p>
            <div class="montant">${formatMontant(économieTotal)}</div>
        </div>

        <div class="card-gouv mb-3">
            <div class="card-header">
                <i class="fas fa-chart-pie me-2"></i>Détail annuel
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12">
                        <div class="small text-muted">Amortissement annuel</div>
                        <div class="fs-5 fw-bold text-bleu-france">${formatMontant(amortissement)}</div>
                    </div>
                    <div class="col-12">
                        <div class="small text-muted">Économie d'impôt annuelle (TMI ${tmi}%)</div>
                        <div class="fs-5 fw-bold text-success">${formatMontant(économieIRannuelle)}</div>
                    </div>
                    <div class="col-12">
                        <div class="small text-muted">Économie totale sur 9 ans</div>
                        <div class="fs-4 fw-bold text-success">${formatMontant(économieTotal)}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-gouv mb-3">
            <div class="card-header">
                <i class="fas fa-info-circle me-2"></i>Paramètres utilisés
            </div>
            <div class="card-body">
                <div class="row small">
                    <div class="col-6">
                        <p class="mb-1"><strong>Base amortissable :</strong> ${formatMontant(baseAmortissable)}</p>
                        <p class="mb-1"><strong>Taux appliqué :</strong> ${tauxAmortissement}%</p>
                    </div>
                    <div class="col-6">
                        <p class="mb-1"><strong>Plafond amortissement :</strong> ${formatMontant(plafondAmortissement)}/an</p>
                        <p class="mb-1"><strong>Plafond déficit :</strong> 21 400 EUR/an</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="info-box mb-3">
            <p class="small mb-0">
                <i class="fas fa-lightbulb me-2"></i><strong>Ce calcul est une estimation théorique</strong>
                basée uniquement sur l'amortissement. Pour un calcul personnalisé tenant compte de vos revenus
                locatifs réels, de vos charges et intérêts d'emprunt, passez en mode avancé.
            </p>
        </div>
    `;
}

function afficherResultatsDetailles(économieTotal, revenusFonciers, amortissement, chargesDéductibles,
                                   resultatFoncier, déficitImputable, économieIR, économiePS,
                                   économieTotaleAn, baseAmortissable, tauxApplique) {
    const resultatsDiv = document.getElementById('resultats');
    resultatsDiv.innerHTML = `
        <div class="simulation-result mb-4">
            <p class="mb-2">Économie d'impôt estimée sur 9 ans</p>
            <div class="montant">${formatMontant(économieTotal)}</div>
        </div>

        <div class="card-gouv mb-3">
            <div class="card-header">
                <i class="fas fa-chart-pie me-2"></i>Détail annuel (année 1)
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="small text-muted">Revenus fonciers</div>
                        <div class="fs-5 fw-bold">${formatMontant(revenusFonciers)}</div>
                    </div>
                    <div class="col-6">
                        <div class="small text-muted">Amortissement</div>
                        <div class="fs-5 fw-bold text-bleu-france">${formatMontant(amortissement)}</div>
                    </div>
                    <div class="col-6">
                        <div class="small text-muted">Charges déductibles</div>
                        <div class="fs-5 fw-bold">${formatMontant(chargesDéductibles)}</div>
                    </div>
                    <div class="col-6">
                        <div class="small text-muted">Résultat foncier</div>
                        <div class="fs-5 fw-bold ${resultatFoncier < 0 ? 'text-danger' : 'text-success'}">${formatMontant(resultatFoncier)}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-gouv mb-3">
            <div class="card-header">
                <i class="fas fa-piggy-bank me-2"></i>Impact fiscal annuel
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="small text-muted">Déficit imputable</div>
                        <div class="fs-5 fw-bold text-success">${formatMontant(déficitImputable)}</div>
                    </div>
                    <div class="col-6">
                        <div class="small text-muted">Économie Impôt sur le Revenu annuelle</div>
                        <div class="fs-5 fw-bold text-success">${formatMontant(économieIR)}</div>
                    </div>
                    <div class="col-6">
                        <div class="small text-muted">Économie Prélèvements Sociaux (17,2%)</div>
                        <div class="fs-5 fw-bold text-success">${formatMontant(économiePS)}</div>
                    </div>
                    <div class="col-6">
                        <div class="small text-muted">Économie totale/an (IR + PS)</div>
                        <div class="fs-5 fw-bold text-success">${formatMontant(économieTotaleAn)}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-gouv">
            <div class="card-header">
                <i class="fas fa-info-circle me-2"></i>Paramètres utilisés
            </div>
            <div class="card-body">
                <div class="row small">
                    <div class="col-6">
                        <p class="mb-1"><strong>Base amortissable :</strong> ${formatMontant(baseAmortissable)}</p>
                        <p class="mb-1"><strong>Taux appliqué :</strong> ${tauxApplique}%</p>
                    </div>
                    <div class="col-6">
                        <p class="mb-1"><strong>Plafond amortissement :</strong> Variable selon loyer</p>
                        <p class="mb-1"><strong>Plafond déficit :</strong> 21 400 EUR/an</p>
                    </div>
                </div>
            </div>
        </div>
    `;
}

function formatMontant(montant) {
    return new Intl.NumberFormat('fr-FR', {
        style: 'currency',
        currency: 'EUR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(montant);
}

// Calculer au chargement
document.addEventListener('DOMContentLoaded', function() {
    calculerSimulation();
});
</script>

<?php include 'includes/footer.php'; ?>
