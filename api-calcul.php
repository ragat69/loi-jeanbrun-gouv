<?php
// API de calcul sécurisée - Ne contient QUE la logique de calcul
// Nettoyer tout output buffer existant
while (ob_get_level()) {
    ob_end_clean();
}

// Désactiver l'affichage des erreurs pour ne pas polluer le JSON
ini_set('display_errors', '0');
error_reporting(0);

header('Content-Type: application/json');

// Récupérer et valider les données
$typeBien = $_POST['typeBien'] ?? 'neuf';
$prixAchat = floatval($_POST['prixAchat'] ?? 0);
$montantTravaux = $typeBien === 'ancien' ? floatval($_POST['montantTravaux'] ?? 0) : 0;
$typeLoyer = $_POST['typeLoyer'] ?? 'intermédiaire';
$tmi = floatval($_POST['tmi'] ?? 0);
$modeAvance = ($_POST['modeAvance'] ?? 'false') === 'true';

// Déterminer le taux d'amortissement
$tauxAmortissement = 0;
if ($typeBien === 'neuf') {
    if ($typeLoyer === 'intermédiaire') $tauxAmortissement = 3.5;
    elseif ($typeLoyer === 'social') $tauxAmortissement = 4.5;
    else $tauxAmortissement = 5.5;
} else {
    if ($typeLoyer === 'intermédiaire') $tauxAmortissement = 3.0;
    elseif ($typeLoyer === 'social') $tauxAmortissement = 3.5;
    else $tauxAmortissement = 4.0;
}

// Base amortissable = 80% du prix total (terrain non amortissable)
$baseAmortissable = ($prixAchat + $montantTravaux) * 0.8;

// Plafonds variables selon le type de loyer
$plafondAmortissement = 12000;
if ($typeLoyer === 'intermédiaire') $plafondAmortissement = 8000;
elseif ($typeLoyer === 'social') $plafondAmortissement = 10000;
else $plafondAmortissement = 12000; // très social

// Calcul amortissement (plafonné selon le type de loyer)
$amortissement = $baseAmortissable * ($tauxAmortissement / 100);
$amortissement = min($amortissement, $plafondAmortissement);

if ($modeAvance) {
    // MODE DÉTAILLÉ : Calcul avec revenus/charges réels
    $loyerMensuel = floatval($_POST['loyerMensuel'] ?? 0);
    $chargesAnnuelles = floatval($_POST['chargesAnnuelles'] ?? 0);
    $intérêtsAnnuels = floatval($_POST['intérêtsAnnuels'] ?? 0);

    // Revenus fonciers annuels
    $revenusFonciers = $loyerMensuel * 12;

    // Charges déductibles totales
    $chargesDéductibles = $chargesAnnuelles + $intérêtsAnnuels;

    // Résultat foncier
    $resultatFoncier = $revenusFonciers - $chargesDéductibles - $amortissement;

    // Déficit imputable (plafonné à 21 400)
    $déficitImputable = 0;
    if ($resultatFoncier < 0) {
        $déficitImputable = min(abs($resultatFoncier), 21400);
    }

    // Économies d'impôt
    $économieIR = $déficitImputable * ($tmi / 100);
    $économiePS = $déficitImputable * 0.172; // Économie sur les Prélèvements Sociaux (17,2%)
    $économieTotaleAn = $économieIR + $économiePS;

    // Économie sur 9 ans
    $économieTotal = $économieTotaleAn * 9;

    echo json_encode([
        'success' => true,
        'mode' => 'avance',
        'économieTotal' => $économieTotal,
        'revenusFonciers' => $revenusFonciers,
        'amortissement' => $amortissement,
        'chargesDéductibles' => $chargesDéductibles,
        'resultatFoncier' => $resultatFoncier,
        'déficitImputable' => $déficitImputable,
        'économieIR' => $économieIR,
        'économiePS' => $économiePS,
        'économieTotaleAn' => $économieTotaleAn,
        'baseAmortissable' => $baseAmortissable,
        'tauxAmortissement' => $tauxAmortissement
    ]);
} else {
    // MODE SIMPLE : Calcul théorique basé uniquement sur l'amortissement
    $économieIRannuelle = $amortissement * ($tmi / 100);
    $économieTotal = $économieIRannuelle * 9;

    echo json_encode([
        'success' => true,
        'mode' => 'simple',
        'économieTotal' => $économieTotal,
        'amortissement' => $amortissement,
        'économieIRannuelle' => $économieIRannuelle,
        'tmi' => $tmi,
        'baseAmortissable' => $baseAmortissable,
        'tauxAmortissement' => $tauxAmortissement,
        'plafondAmortissement' => $plafondAmortissement
    ]);
}
