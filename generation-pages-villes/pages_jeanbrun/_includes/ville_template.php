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
?>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/header.php'); ?>

<main class="container py-5">
    <div class="row">
        <div class="col-lg-10 mx-auto">

            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Accueil</a></li>
                    <li class="breadcrumb-item"><a href="/ville/">Loi Jeanbrun par ville</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($ville) ?></li>
                </ol>
            </nav>

            <h1 class="mb-3">Loi Jeanbrun à <?= htmlspecialchars($ville) ?></h1>
            <p class="lead mb-4">Guide complet de la <a href="/">loi Jeanbrun</a> (dispositif Relance Logement 2026) pour investir à <?= htmlspecialchars($ville) ?>.</p>

            <!-- Informations clés -->
            <section class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h2 class="h5 mb-0">Informations clés pour <?= htmlspecialchars($ville) ?></h2>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2"><strong>Département :</strong> <?= htmlspecialchars($data['departement']) ?></li>
                                <li class="mb-2"><strong>Population :</strong> <?= fmt($data['population']) ?> habitants</li>
                                <li class="mb-2"><strong>Zone de classement :</strong> <span class="badge bg-info"><?= htmlspecialchars($data['zone']) ?></span></li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2"><strong>Taux de vacance :</strong> <?= $data['taux_vacance'] ?>%</li>
                                <li class="mb-2"><strong>Projets construction :</strong> <?= fmt($data['projets_construction']) ?> logements</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Prix du marché -->
            <section class="card mb-4">
                <div class="card-header">
                    <h2 class="h5 mb-0">Prix du marché immobilier à <?= htmlspecialchars($ville) ?></h2>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead class="table-light">
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
                    <p class="text-muted small mb-0">
                        <strong>Loyer de marché moyen :</strong> <?= $data['loyer_marche_m2'] ?> €/m²
                    </p>
                </div>
            </section>

            <!-- Plafonds de loyer -->
            <section class="card mb-4">
                <div class="card-header">
                    <h2 class="h5 mb-0">Plafonds de loyer Loi Jeanbrun à <?= htmlspecialchars($ville) ?> (Zone <?= $data['zone'] ?>)</h2>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead class="table-light">
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
            </section>

            <!-- Simulation -->
            <section class="card mb-4 border-success">
                <div class="card-header bg-success text-white">
                    <h2 class="h5 mb-0">Simulation d'investissement à <?= htmlspecialchars($ville) ?></h2>
                </div>
                <div class="card-body">
                    <h3 class="h6">Exemple : T3 neuf de 65m² en loyer intermédiaire</h3>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <h4 class="h6 text-muted">Caractéristiques</h4>
                            <ul class="list-unstyled">
                                <li><strong>Prix d'acquisition :</strong> <?= fmt($prix_t3_neuf) ?> €</li>
                                <li><strong>Apport (20%) :</strong> <?= fmt($apport) ?> €</li>
                                <li><strong>Emprunt :</strong> <?= fmt($emprunt) ?> €</li>
                                <li><strong>Loyer mensuel :</strong> <?= fmt($loyer_mensuel, 2) ?> €</li>
                                <li><strong>Loyer annuel :</strong> <?= fmt($loyer_annuel, 2) ?> €</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h4 class="h6 text-muted">Avantage fiscal</h4>
                            <ul class="list-unstyled">
                                <li><strong>Assiette amortissable (80%) :</strong> <?= fmt($assiette) ?> €</li>
                                <li><strong>Amortissement annuel :</strong> <?= fmt($amortissement_annuel) ?> €</li>
                                <li><strong>Gain fiscal/an (TMI 30%) :</strong> <?= fmt($gain_fiscal_annuel) ?> €</li>
                                <li class="mt-2 text-success"><strong>Gain fiscal sur 9 ans :</strong> <?= fmt($gain_fiscal_9ans) ?> €</li>
                            </ul>
                        </div>
                    </div>

                    <div class="alert alert-info mt-3 mb-0">
                        <strong>Rendement brut :</strong> <?= fmt($rendement_brut, 2) ?>%
                    </div>
                </div>
            </section>

            <!-- Conditions d'éligibilité -->
            <section class="card mb-4">
                <div class="card-header">
                    <h2 class="h5 mb-0">Conditions d'éligibilité à <?= htmlspecialchars($ville) ?></h2>
                </div>
                <div class="card-body">
                    <ul class="mb-0">
                        <li>Logement situé dans un <strong>immeuble collectif</strong> (appartement uniquement)</li>
                        <li>Bien neuf ou ancien avec travaux ≥ 30% du prix d'achat</li>
                        <li>Pour l'ancien : travaux permettant d'atteindre la <strong>classe A ou B du DPE</strong></li>
                        <li>Engagement de location de <strong>9 ans minimum</strong></li>
                        <li>Location à titre de résidence principale (8 mois/an minimum)</li>
                        <li>Respect des plafonds de loyer de la zone <strong><?= $data['zone'] ?></strong></li>
                        <li>Respect des plafonds de ressources des locataires</li>
                        <li>Interdiction de louer à un membre du foyer fiscal</li>
                    </ul>
                </div>
            </section>

            <!-- Comparaison -->
            <section class="card mb-4">
                <div class="card-header">
                    <h2 class="h5 mb-0">Comparaison Loi Jeanbrun vs marché libre à <?= htmlspecialchars($ville) ?></h2>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
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
                                    <td class="text-success">Jusqu'à 8 000 €/an</td>
                                    <td class="text-muted">Non applicable</td>
                                </tr>
                                <tr>
                                    <td>Déficit foncier</td>
                                    <td class="text-success">Imputable sur revenu global (21 400 €)</td>
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
            </section>

            <!-- FAQ -->
            <section class="card mb-4">
                <div class="card-header">
                    <h2 class="h5 mb-0">Questions fréquentes sur la Loi Jeanbrun à <?= htmlspecialchars($ville) ?></h2>
                </div>
                <div class="card-body">
                    <div class="accordion" id="faqAccordion">
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
            </section>

            <!-- CTA -->
            <section class="text-center py-4">
                <a href="/ville/" class="btn btn-outline-primary me-2">Voir toutes les villes</a>
                <a href="/contact/" class="btn btn-primary">Être accompagné</a>
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
            <section class="card mb-4">
                <div class="card-header">
                    <h2 class="h5 mb-0">Loi Jeanbrun dans le département <?= htmlspecialchars($dept_numero) ?></h2>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">Découvrez également les opportunités d'investissement dans les autres villes du département :</p>
                    <div class="row">
                        <?php foreach ($villes_meme_dept as $autre_ville => $autre_data): ?>
                        <div class="col-6 col-md-4 col-lg-3 mb-2">
                            <a href="/ville/<?= htmlspecialchars($autre_data['filename'] ?? 'loi-jeanbrun-' . ($autre_data['slug'] ?? strtolower($autre_ville)) . '.php') ?>">
                                loi Jeanbrun <?= htmlspecialchars($autre_ville) ?> (<?= htmlspecialchars($dept_numero) ?>)
                            </a>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>
            <?php endif; ?>

        </div>
    </div>
</main>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'); ?>
