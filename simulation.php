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

<!-- Simulateur -->
<section class="section">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <h2 class="section-title">Votre projet d'investissement</h2>

                <form id="simulateur-form" class="simulation-form">
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
                        <label for="prixAchat" class="form-label">Prix d'acquisition (EUR)</label>
                        <input type="number" class="form-control" id="prixAchat" value="250000" min="50000" max="1000000" step="1000">
                        <div class="form-text">Prix du bien immobilier hors frais de notaire</div>
                    </div>

                    <!-- Montant des travaux (si ancien) -->
                    <div class="mb-4" id="blocTravaux" style="display: none;">
                        <label for="montantTravaux" class="form-label">Montant des travaux (EUR)</label>
                        <input type="number" class="form-control" id="montantTravaux" value="75000" min="0" max="500000" step="1000">
                        <div class="form-text">Minimum 30% du prix d'acquisition pour etre éligible</div>
                    </div>

                    <!-- Type de loyer -->
                    <div class="mb-4">
                        <label for="typeLoyer" class="form-label">Type de loyer pratique</label>
                        <select class="form-select" id="typeLoyer">
                            <option value="intermédiaire">Loyer intermédiaire (3,5% neuf / 3,0% ancien)</option>
                            <option value="social">Loyer social (4,5% neuf / 3,5% ancien)</option>
                            <option value="tressocial">Loyer très social (5,5% neuf / 4,0% ancien)</option>
                        </select>
                    </div>

                    <!-- Loyer mensuel -->
                    <div class="mb-4">
                        <label for="loyerMensuel" class="form-label">Loyer mensuel estime (EUR)</label>
                        <input type="number" class="form-control" id="loyerMensuel" value="800" min="200" max="5000" step="50">
                        <div class="form-text">Loyer mensuel hors charges</div>
                    </div>

                    <!-- Charges annuelles -->
                    <div class="mb-4">
                        <label for="chargesAnnuelles" class="form-label">Charges annuelles estimees (EUR)</label>
                        <input type="number" class="form-control" id="chargesAnnuelles" value="3000" min="0" max="50000" step="100">
                        <div class="form-text">Taxe foncière, assurance, copropriété, gestion...</div>
                    </div>

                    <!-- Intérêts d'emprunt -->
                    <div class="mb-4">
                        <label for="intérêtsAnnuels" class="form-label">Intérêts d'emprunt annuels (EUR)</label>
                        <input type="number" class="form-control" id="intérêtsAnnuels" value="5000" min="0" max="50000" step="100">
                        <div class="form-text">Intérêts payes la première année (decroissants ensuite)</div>
                    </div>

                    <!-- TMI -->
                    <div class="mb-4">
                        <label for="tmi" class="form-label">Tranche marginale d'imposition (TMI)</label>
                        <select class="form-select" id="tmi">
                            <option value="0">0% (non imposable)</option>
                            <option value="11">11%</option>
                            <option value="30" selected>30%</option>
                            <option value="41">41%</option>
                            <option value="45">45%</option>
                        </select>
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
                                    <div class="small text-muted">Économie IR annuelle</div>
                                    <div class="fs-5 fw-bold text-success" id="économieAnnuelle">-- EUR</div>
                                </div>
                                <div class="col-6">
                                    <div class="small text-muted">Économie PS (17,2%)</div>
                                    <div class="fs-5 fw-bold text-success" id="économiePS">-- EUR</div>
                                </div>
                                <div class="col-6">
                                    <div class="small text-muted">Économie totale/an</div>
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

<script>
// Afficher/masquer le bloc travaux selon le type de bien
document.querySelectorAll('input[name="typeBien"]').forEach(function(radio) {
    radio.addEventListener('change', function() {
        document.getElementById('blocTravaux').style.display =
            this.value === 'ancien' ? 'block' : 'none';
    });
});

function calculerSimulation() {
    // Recuperer les valeurs
    const typeBien = document.querySelector('input[name="typeBien"]:checked').value;
    const prixAchat = parseFloat(document.getElementById('prixAchat').value) || 0;
    const montantTravaux = typeBien === 'ancien' ? (parseFloat(document.getElementById('montantTravaux').value) || 0) : 0;
    const typeLoyer = document.getElementById('typeLoyer').value;
    const loyerMensuel = parseFloat(document.getElementById('loyerMensuel').value) || 0;
    const chargesAnnuelles = parseFloat(document.getElementById('chargesAnnuelles').value) || 0;
    const intérêtsAnnuels = parseFloat(document.getElementById('intérêtsAnnuels').value) || 0;
    const tmi = parseFloat(document.getElementById('tmi').value) || 0;

    // Determiner le taux d'amortissement
    let tauxAmortissement = 0;
    if (typeBien === 'neuf') {
        if (typeLoyer === 'intermédiaire') tauxAmortissement = 3.5;
        else if (typeLoyer === 'social') tauxAmortissement = 4.5;
        else tauxAmortissement = 5.5;
    } else {
        if (typeLoyer === 'intermédiaire') tauxAmortissement = 3.0;
        else if (typeLoyer === 'social') tauxAmortissement = 3.5;
        else tauxAmortissement = 4.0;
    }

    // Base amortissable
    const baseAmortissable = prixAchat + montantTravaux;

    // Calcul amortissement (plafonne a 12 000)
    let amortissement = baseAmortissable * (tauxAmortissement / 100);
    amortissement = Math.min(amortissement, 12000);

    // Revenus fonciers annuels
    const revenusFonciers = loyerMensuel * 12;

    // Charges déductibles totales
    const chargesDéductibles = chargesAnnuelles + intérêtsAnnuels;

    // Resultat foncier
    const resultatFoncier = revenusFonciers - chargesDéductibles - amortissement;

    // Déficit imputable (plafonne a 21 400)
    let déficitImputable = 0;
    if (resultatFoncier < 0) {
        déficitImputable = Math.min(Math.abs(resultatFoncier), 21400);
    }

    // Économies d'impot
    const économieIR = déficitImputable * (tmi / 100);
    const économiePS = resultatFoncier < 0 ? 0 : 0; // Pas de PS si déficit
    const économieTotaleAn = économieIR;

    // Économie sur 9 ans (simplifiee)
    const économieTotal = économieTotaleAn * 9;

    // Afficher les resultats
    document.getElementById('économieTotal').textContent = formatMontant(économieTotal);
    document.getElementById('revenusFonciers').textContent = formatMontant(revenusFonciers);
    document.getElementById('amortissement').textContent = formatMontant(amortissement);
    document.getElementById('chargesDéductibles').textContent = formatMontant(chargesDéductibles);
    document.getElementById('resultatFoncier').textContent = formatMontant(resultatFoncier);
    document.getElementById('déficitImputable').textContent = formatMontant(déficitImputable);
    document.getElementById('économieAnnuelle').textContent = formatMontant(économieIR);
    document.getElementById('économiePS').textContent = formatMontant(économiePS);
    document.getElementById('économieTotaleAn').textContent = formatMontant(économieTotaleAn);
    document.getElementById('baseAmortissable').textContent = formatMontant(baseAmortissable);
    document.getElementById('tauxApplique').textContent = tauxAmortissement + '%';

    // Style du resultat foncier
    const resultatEl = document.getElementById('resultatFoncier');
    if (resultatFoncier < 0) {
        resultatEl.classList.add('text-danger');
        resultatEl.classList.remove('text-success');
    } else {
        resultatEl.classList.add('text-success');
        resultatEl.classList.remove('text-danger');
    }
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
