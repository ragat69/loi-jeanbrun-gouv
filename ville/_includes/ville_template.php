<?php
/**
 * Template commun pour les pages Loi Jeanbrun
 * Les données sont chargées depuis _data/villes_data.php
 *
 * IMPORTANT: La variable $ville_key doit être définie avant d'inclure ce template.
 */

// Charger les données
include_once($_SERVER['DOCUMENT_ROOT'] . '/ville/_data/villes_data.php');

// Vérifier que $ville_key est défini (par la page appelante)
if (!isset($ville_key) || !isset($villes_data[$ville_key])) {
    header('HTTP/1.0 404 Not Found');
    die('Ville non trouvée');
}

$data = $villes_data[$ville_key];
$ville = $data['nom'];

// Calculs pour la simulation (T3 de 65m²)
$surface_t3 = 65;
$prix_t3_neuf = $data['prix_m2_neuf'] * $surface_t3;
$apport = $prix_t3_neuf * 0.20;
$emprunt = $prix_t3_neuf * 0.80;
$loyer_mensuel = $data['plafonds_loyer']['intermediaire'] * $surface_t3;
$loyer_annuel = $loyer_mensuel * 12;
$assiette = $prix_t3_neuf * 0.80;
$amortissement_annuel = min($assiette * 0.035, 8000);
$gain_fiscal_annuel = $amortissement_annuel * 0.30;
$gain_fiscal_9ans = $gain_fiscal_annuel * 9;
$rendement_brut = ($prix_t3_neuf > 0) ? ($loyer_annuel / $prix_t3_neuf * 100) : 0;

// Fonction de formatage
function fmt($n, $decimals = 0) {
    return number_format($n, $decimals, ',', ' ');
}

// Définir le title de la page
$page_title_full = "Loi Jeanbrun à " . htmlspecialchars($ville) . " (" . htmlspecialchars($data['code_postal']) . ") - Dispositif Relance Logement";
?>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/header.php'); ?>

<!-- Page Header -->
<section class="page-header page-header-ville">
    <div class="container">
        <h1>Loi Jeanbrun à <?= htmlspecialchars($ville) ?></h1>
        <p class="lead">Guide complet du dispositif Relance Logement 2026 pour investir à <?= htmlspecialchars($ville) ?></p>
    </div>
</section>

<!-- Breadcrumb -->
<nav class="breadcrumb-gouv" aria-label="breadcrumb">
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Accueil</a></li>
            <li class="breadcrumb-item"><a href="/ville/">Villes</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($ville) ?></li>
        </ol>
    </div>
</nav>

<main>
    <?php if (!empty($data['intro_text'])): ?>
    <!-- Texte introductif -->
    <section class="section">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 mx-auto">
                    <div class="card-gouv" style="border-left: 4px solid var(--bleu-france);">
                        <div class="card-body">
                            <?php
                            // Préparer les variables pour le remplacement
                            $replacements = [
                                '{{population}}' => fmt($data['population']),
                                '{{prix_m2_neuf}}' => fmt($data['prix_m2_neuf']),
                                '{{prix_m2_ancien}}' => fmt($data['prix_m2_ancien']),
                                '{{loyer_marche_m2}}' => number_format($data['loyer_marche_m2'], 2, ',', ' '),
                                '{{plafond_intermediaire}}' => number_format($data['plafonds_loyer']['intermediaire'], 2, ',', ' '),
                                '{{plafond_social}}' => number_format($data['plafonds_loyer']['social'], 2, ',', ' '),
                                '{{plafond_tres_social}}' => number_format($data['plafonds_loyer']['tres_social'], 2, ',', ' '),
                                '{{taux_vacance}}' => number_format($data['taux_vacance'], 1, ',', ' '),
                                '{{projets_construction}}' => fmt($data['projets_construction']),
                                '{{zone}}' => htmlspecialchars($data['zone']),
                            ];

                            // Remplacer les placeholders par les valeurs
                            $intro = $data['intro_text'];
                            foreach ($replacements as $placeholder => $value) {
                                $intro = str_replace($placeholder, $value, $intro);
                            }

                            // Extraire le numéro de département
                            $dept_numero = '';
                            if (preg_match('/^(\d+)/', $data['departement'], $matches)) {
                                $dept_numero = $matches[1];
                            }

                            // Remplacer la première occurrence du nom de la ville par version formatée avec département
                            $ville_formatted = '<strong style="font-weight: 700;">' . $ville . ' (' . $dept_numero . ')</strong>';
                            $intro = preg_replace(
                                '/\b' . preg_quote($ville, '/') . '\b/u',
                                $ville_formatted,
                                $intro,
                                1  // Remplacer seulement la première occurrence
                            );

                            // Convertir "loi Jeanbrun" en lien vers la homepage (sensible à la casse)
                            $intro = preg_replace(
                                '/\bloi Jeanbrun\b/',
                                '<a href="/" style="color: var(--bleu-france); font-weight: 500;">loi Jeanbrun</a>',
                                $intro
                            );

                            // Diviser le texte en paragraphes (séparés par <br><br>)
                            $paragraphes = explode('<br><br>', $intro);
                            $nb_paragraphes = count($paragraphes);

                            // Fusionner les 2 derniers paragraphes avec un simple <br>
                            if ($nb_paragraphes >= 2) {
                                $derniers_deux = array_splice($paragraphes, -2);
                                $paragraphes[] = implode('<br>', $derniers_deux);
                                $nb_paragraphes = count($paragraphes);
                            }

                            foreach ($paragraphes as $index => $paragraphe) {
                                $is_last = ($index === $nb_paragraphes - 1);
                                $mb_class = $is_last ? 'mb-0' : 'mb-3';
                                echo '<p class="lead ' . $mb_class . '">' . $paragraphe . '</p>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Informations clés -->
    <section class="section">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 mx-auto">
                    <h2 class="section-title">Informations clés pour <?= htmlspecialchars($ville) ?></h2>

                    <div class="card-gouv">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <ul class="list-unstyled mb-3 mb-md-0">
                                        <li class="mb-2"><strong>Département :</strong> <?= htmlspecialchars($data['departement']) ?></li>
                                        <li class="mb-2"><strong>Population :</strong> <?= fmt($data['population']) ?> habitants</li>
                                        <li class="mb-2"><strong>Zone de classement :</strong> <span class="badge bg-primary"><?= htmlspecialchars($data['zone']) ?></span></li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <ul class="list-unstyled mb-0">
                                        <li class="mb-2"><strong>Taux de vacance :</strong> <?= $data['taux_vacance'] ?>%</li>
                                        <li class="mb-2"><strong>Projets construction :</strong> <?= fmt($data['projets_construction']) ?> logements</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="info-box mt-3" style="font-size: 0.9rem;">
                                <i class="fas fa-info-circle"></i> <strong>Liberté géographique totale :</strong> Contrairement au dispositif Pinel qui était limité aux zones tendues, la loi Jeanbrun s'applique sur tout le territoire français. La zone détermine uniquement les plafonds de loyer applicables.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Prix du marché -->
    <section class="section section-alt">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 mx-auto">
                    <h2 class="section-title">Prix du marché immobilier à <?= htmlspecialchars($ville) ?></h2>

                    <div class="card-gouv">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table-gouv">
                                    <thead>
                                        <tr>
                                            <th>Type de bien</th>
                                            <th>Prix moyen au m²</th>
                                            <th>Prix T2 (45m²)</th>
                                            <th>Prix T3 (65m²)</th>
                                            <th>Prix T4 (85m²)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Logement neuf</td>
                                            <td><strong><?= fmt($data['prix_m2_neuf']) ?> €</strong></td>
                                            <td><?= fmt($data['prix_m2_neuf'] * 45) ?> €</td>
                                            <td><?= fmt($data['prix_m2_neuf'] * 65) ?> €</td>
                                            <td><?= fmt($data['prix_m2_neuf'] * 85) ?> €</td>
                                        </tr>
                                        <tr>
                                            <td>Logement ancien</td>
                                            <td><strong><?= fmt($data['prix_m2_ancien']) ?> €</strong></td>
                                            <td><?= fmt($data['prix_m2_ancien'] * 45) ?> €</td>
                                            <td><?= fmt($data['prix_m2_ancien'] * 65) ?> €</td>
                                            <td><?= fmt($data['prix_m2_ancien'] * 85) ?> €</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="info-box mt-3">
                                <strong>Loyer de marché moyen :</strong> <?= $data['loyer_marche_m2'] ?> €/m²
                            </div>

                            <?php if (!empty($data['dvf_transactions_count'])): ?>
                            <div class="alert alert-info mt-3" style="font-size: 0.9em; background-color: #f5f5fe; border-left: 4px solid #6a6af4;">
                                <p class="mb-2"><strong>📊 Source des données de prix</strong></p>
                                <p class="mb-1">
                                    Les prix au m² sont calculés à partir de <strong><?= fmt($data['dvf_transactions_count']) ?> transactions immobilières réelles</strong>
                                    effectuées entre <strong><?= $data['dvf_period'] ?></strong>.
                                </p>
                                <?php if (!empty($data['dvf_arrondissements'])): ?>
                                <details class="mt-2">
                                    <summary style="cursor: pointer; color: #6a6af4; font-weight: 500;">
                                        Voir le détail par arrondissement (<?= count($data['dvf_arrondissements']) ?> arrondissements)
                                    </summary>
                                    <div class="mt-2" style="font-size: 0.85em;">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Arrondissement</th>
                                                    <th>Transactions</th>
                                                    <th>Prix ancien</th>
                                                    <th>Prix neuf</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($data['dvf_arrondissements'] as $arr):
                                                    // Extraire le numéro d'arrondissement selon la ville
                                                    // Lyon: 69381-69389 → 1er-9e (dernier chiffre uniquement)
                                                    // Paris: 75101-75120 → 1er-20e (2 derniers chiffres)
                                                    // Marseille: 13201-13216 → 1er-16e (2 derniers chiffres)
                                                    $code = $arr['code_insee'];
                                                    if (substr($code, 0, 3) === '693') {
                                                        // Lyon: prendre le dernier chiffre seulement
                                                        $arr_num = substr($code, -1);
                                                    } else {
                                                        // Paris et Marseille: prendre les 2 derniers chiffres
                                                        $arr_num = substr($code, -2);
                                                    }
                                                    // Supprimer le zéro initial si présent
                                                    $arr_num = ltrim($arr_num, '0') ?: '0';
                                                ?>
                                                <tr>
                                                    <td><?= $arr_num ?>e</td>
                                                    <td><?= fmt($arr['count']) ?></td>
                                                    <td><?= $arr['prix_m2_ancien'] ? fmt($arr['prix_m2_ancien']) . ' €/m²' : '-' ?></td>
                                                    <td><?= $arr['prix_m2_neuf'] ? fmt($arr['prix_m2_neuf']) . ' €/m²' : '-' ?></td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                        <p class="text-muted mb-0" style="font-size: 0.9em;">
                                            <em>Les prix affichés en haut de page sont la moyenne pondérée de tous les arrondissements.</em>
                                        </p>
                                    </div>
                                </details>
                                <?php endif; ?>
                                <p class="text-muted mb-0 mt-2" style="font-size: 0.85em;">
                                    <em>Données : Demandes de Valeurs Foncières (DVF) - data.gouv.fr</em>
                                </p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Plafonds de loyer -->
    <section class="section">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 mx-auto">
                    <h2 class="section-title">Plafonds de loyer Loi Jeanbrun à <?= htmlspecialchars($ville) ?> (Zone <?= $data['zone'] ?>)</h2>

                    <div class="card-gouv">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table-gouv">
                                    <thead>
                                        <tr>
                                            <th>Catégorie</th>
                                            <th>Plafond/m²</th>
                                            <th>Loyer T2 (45m²)</th>
                                            <th>Loyer T3 (65m²)</th>
                                            <th>Loyer T4 (85m²)</th>
                                            <th>Amortissement</th>
                                            <th>Plafond annuel</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><strong>Loyer intermédiaire</strong></td>
                                            <td><?= $data['plafonds_loyer']['intermediaire'] ?> €</td>
                                            <td><?= fmt($data['plafonds_loyer']['intermediaire'] * 45, 2) ?> €/mois</td>
                                            <td><?= fmt($data['plafonds_loyer']['intermediaire'] * 65, 2) ?> €/mois</td>
                                            <td><?= fmt($data['plafonds_loyer']['intermediaire'] * 85, 2) ?> €/mois</td>
                                            <td>3,5%</td>
                                            <td>8 000 €</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Loyer social</strong></td>
                                            <td><?= $data['plafonds_loyer']['social'] ?> €</td>
                                            <td><?= fmt($data['plafonds_loyer']['social'] * 45, 2) ?> €/mois</td>
                                            <td><?= fmt($data['plafonds_loyer']['social'] * 65, 2) ?> €/mois</td>
                                            <td><?= fmt($data['plafonds_loyer']['social'] * 85, 2) ?> €/mois</td>
                                            <td>4,5%</td>
                                            <td>10 000 €</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Loyer très social</strong></td>
                                            <td><?= $data['plafonds_loyer']['tres_social'] ?> €</td>
                                            <td><?= fmt($data['plafonds_loyer']['tres_social'] * 45, 2) ?> €/mois</td>
                                            <td><?= fmt($data['plafonds_loyer']['tres_social'] * 65, 2) ?> €/mois</td>
                                            <td><?= fmt($data['plafonds_loyer']['tres_social'] * 85, 2) ?> €/mois</td>
                                            <td>5,5%</td>
                                            <td>12 000 €</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Simulation -->
    <section class="section section-alt">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 mx-auto">
                    <h2 class="section-title">Simulation d'investissement Jeanbrun à <?= htmlspecialchars($ville) ?></h2>

                    <div class="info-box success">
                        <h3 class="h5 mb-3">Exemple : T3 neuf de 65m² en loyer intermédiaire</h3>

                        <div class="row mt-3">
                            <div class="col-md-6">
                                <h4 class="h6 text-muted mb-3">Caractéristiques du bien</h4>
                                <ul class="list-unstyled">
                                    <li class="mb-2"><strong>Prix d'acquisition :</strong> <?= fmt($prix_t3_neuf) ?> €</li>
                                    <li class="mb-2"><strong>Apport (20%) :</strong> <?= fmt($apport) ?> €</li>
                                    <li class="mb-2"><strong>Emprunt :</strong> <?= fmt($emprunt) ?> €</li>
                                    <li class="mb-2"><strong>Loyer mensuel :</strong> <?= fmt($loyer_mensuel, 2) ?> €</li>
                                    <li class="mb-2"><strong>Loyer annuel :</strong> <?= fmt($loyer_annuel, 2) ?> €</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h4 class="h6 text-muted mb-3">Avantage fiscal</h4>
                                <ul class="list-unstyled">
                                    <li class="mb-2"><strong>Assiette amortissable (80%) :</strong> <?= fmt($assiette) ?> €</li>
                                    <li class="mb-2"><strong>Amortissement annuel :</strong> <?= fmt($amortissement_annuel) ?> €</li>
                                    <li class="mb-2"><strong>Gain fiscal/an (TMI 30%) :</strong> <?= fmt($gain_fiscal_annuel) ?> €</li>
                                    <li class="mt-3"><strong style="color: var(--bleu-france); font-size: 1.25rem;">Gain fiscal sur 9 ans :</strong> <span style="color: var(--bleu-france); font-size: 1.25rem;"><?= fmt($gain_fiscal_9ans) ?> €</span></li>
                                </ul>
                            </div>
                        </div>

                        <div class="info-box mt-4" style="background-color: rgba(0, 0, 145, 0.1); border-left-color: var(--bleu-france);">
                            <strong>Rendement brut :</strong> <?= fmt($rendement_brut, 2) ?>%
                        </div>

                        <div class="text-center mt-4">
                            <a href="/simulation/" class="btn-gouv">Réaliser une simulation personnalisée</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Conditions d'éligibilité -->
    <section class="section">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 mx-auto">
                    <h2 class="section-title">Conditions d'éligibilité au dispositif Relance Logement à <?= htmlspecialchars($ville) ?></h2>

                    <div class="card-gouv">
                        <div class="card-body">
                            <ul class="list-gouv">
                                <li>Logement situé dans un <strong>immeuble collectif</strong> (appartement uniquement)</li>
                                <li>Bien neuf ou ancien avec travaux ≥ 30% du prix d'achat</li>
                                <li>Pour l'ancien : travaux permettant d'atteindre la <strong>classe A ou B du DPE</strong></li>
                                <li>Engagement de location de <strong>9 ans minimum</strong></li>
                                <li>Location à titre de résidence principale (8 mois/an minimum)</li>
                                <li>Respect des plafonds de loyer de la zone <strong><?= $data['zone'] ?></strong></li>
                                <li>Respect des plafonds de ressources des locataires</li>
                                <li>Interdiction de louer à un membre du foyer fiscal</li>
                            </ul>

                            <div class="text-center mt-4">
                                <a href="/eligibilite.php" class="btn-gouv">Tester votre éligibilité au dispositif</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Comparaison -->
    <section class="section section-alt">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 mx-auto">
                    <h2 class="section-title">Loi Jeanbrun vs marché libre</h2>

                    <div class="card-gouv">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table-gouv">
                                    <thead>
                                        <tr>
                                            <th>Critère</th>
                                            <th>Loi Jeanbrun</th>
                                            <th>Marché libre</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Loyer au m²</td>
                                            <td><?= $data['plafonds_loyer']['intermediaire'] ?> € (plafonné)</td>
                                            <td><?= $data['loyer_marche_m2'] ?> € (marché)</td>
                                        </tr>
                                        <tr>
                                            <td>Amortissement fiscal</td>
                                            <td><strong style="color: var(--bleu-france);">Jusqu'à 8 000 €/an</strong></td>
                                            <td class="text-muted">Non applicable</td>
                                        </tr>
                                        <tr>
                                            <td>Déficit foncier</td>
                                            <td><strong style="color: var(--bleu-france);">Imputable sur revenu global (21 400 €)</strong></td>
                                            <td>Revenus fonciers uniquement</td>
                                        </tr>
                                        <tr>
                                            <td>Engagement</td>
                                            <td>9 ans minimum</td>
                                            <td>Libre</td>
                                        </tr>
                                    </tbody>
                                </table>
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
            <div class="row">
                <div class="col-lg-10 mx-auto">
                    <h2 class="section-title">Questions fréquentes</h2>

                    <div class="accordion accordion-gouv" id="faqAccordion">
                        <div class="accordion-item">
                            <h3 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                    Puis-je investir dans n'importe quel quartier de <?= htmlspecialchars($ville) ?> ?
                                </button>
                            </h3>
                            <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Oui, contrairement au dispositif Pinel, la loi Jeanbrun ne comporte pas de zonage restrictif.
                                    Tous les quartiers de <?= htmlspecialchars($ville) ?> sont éligibles, à condition de respecter les plafonds de loyer de la zone <?= $data['zone'] ?>.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h3 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                    Quel est le loyer maximum pour un T3 à <?= htmlspecialchars($ville) ?> ?
                                </button>
                            </h3>
                            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Pour un T3 de 65m² en loyer intermédiaire à <?= htmlspecialchars($ville) ?> (zone <?= $data['zone'] ?>),
                                    le loyer maximum est de <strong><?= fmt($data['plafonds_loyer']['intermediaire'] * 65, 2) ?> €</strong> par mois, soit <?= $data['plafonds_loyer']['intermediaire'] ?> €/m².
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h3 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                    Les maisons sont-elles éligibles ?
                                </button>
                            </h3>
                            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Non, seuls les appartements situés dans des immeubles collectifs sont éligibles au dispositif Jeanbrun.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Villes du même département -->
    <?php
    // Extraire le numéro de département
    $dept_numero = substr($data['code_insee'], 0, 2);
    if ($dept_numero === '97') {
        // DOM-TOM : 3 chiffres
        $dept_numero = substr($data['code_insee'], 0, 3);
    }

    // Trouver les autres villes du même département
    $villes_meme_dept = [];
    foreach ($villes_data as $autre_ville => $autre_data) {
        if ($autre_ville === $ville_key) continue; // Exclure la ville actuelle

        $autre_dept = substr($autre_data['code_insee'], 0, 2);
        if ($autre_dept === '97') {
            $autre_dept = substr($autre_data['code_insee'], 0, 3);
        }

        if ($autre_dept === $dept_numero) {
            $villes_meme_dept[$autre_ville] = $autre_data;
        }
    }

    // Trier par population décroissante
    uasort($villes_meme_dept, function($a, $b) {
        return ($b['population'] ?? 0) - ($a['population'] ?? 0);
    });
    ?>

    <?php if (!empty($villes_meme_dept)): ?>
    <section class="section section-alt">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 mx-auto">
                    <h2 class="section-title">Loi Jeanbrun dans le département <?= htmlspecialchars($dept_numero) ?></h2>

                    <div class="card-gouv">
                        <div class="card-body">
                            <p class="text-muted mb-3">Découvrez également les opportunités d'investissement dans les autres villes du département :</p>
                            <div class="row g-3">
                                <?php foreach ($villes_meme_dept as $autre_ville => $autre_data): ?>
                                <div class="col-6 col-md-4 col-lg-3">
                                    <a href="/ville/<?= htmlspecialchars($autre_data['filename'] ?? 'loi-jeanbrun-' . ($autre_data['slug'] ?? strtolower($autre_ville)) . '.php') ?>" class="d-block p-2 border rounded text-decoration-none hover-shadow">
                                        <strong><?= htmlspecialchars($autre_ville) ?></strong>
                                        <br><small class="text-muted"><?= fmt($autre_data['population'] ?? 0) ?> hab.</small>
                                    </a>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- CTA -->
    <section class="section">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 mx-auto text-center">
                    <a href="/ville/" class="btn-gouv">Voir toutes les villes</a>
                </div>
            </div>
        </div>
    </section>
</main>

<style>
.hover-shadow:hover {
    box-shadow: 0 .5rem 1rem rgba(0,0,0,.15) !important;
    transform: translateY(-2px);
    transition: all 0.2s ease;
}

/* Fix pour éviter que les cards ne passent derrière les sections suivantes */
.card-gouv {
    position: relative;
    z-index: 1;
    background-color: white;
    height: auto !important; /* Enlever le height: 100% sur les pages villes */
}

/* Badge zone plus grand */
.badge.bg-primary {
    font-size: 1.1rem;
    padding: 0.5rem 0.75rem;
}

/* Padding uniforme de la card-gouv */
.card-gouv .card-body {
    padding: 1.5rem !important;
}
</style>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'); ?>
