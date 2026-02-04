<?php
/**
 * Page d'index des villes - Loi Jeanbrun
 * Avec recherche autocomplete et pagination
 * Les données sont chargées depuis _data/villes_data.php
 */

// Charger les données
include_once($_SERVER['DOCUMENT_ROOT'] . '/ville/_data/villes_data.php');

// Paramètres de pagination
$per_page = 50;
$current_page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;

// Trier les villes par population décroissante
$villes_sorted = $villes_data;
uasort($villes_sorted, function($a, $b) {
    return ($b['population'] ?? 0) - ($a['population'] ?? 0);
});

// Convertir en tableau indexé pour la pagination
$villes_array = [];
foreach ($villes_sorted as $ville => $data) {
    $villes_array[] = array_merge(['nom_ville' => $ville], $data);
}

$total_villes = count($villes_array);
$total_pages = ceil($total_villes / $per_page);
$current_page = min($current_page, $total_pages);
$offset = ($current_page - 1) * $per_page;

// Villes pour la page courante
$villes_page = array_slice($villes_array, $offset, $per_page);

// Préparer les données JSON pour l'autocomplete
$autocomplete_data = [];
foreach ($villes_sorted as $ville => $data) {
    $autocomplete_data[] = [
        'nom' => $ville,
        'slug' => $data['slug'] ?? strtolower($ville),
        'filename' => $data['filename'] ?? 'loi-jeanbrun-' . ($data['slug'] ?? strtolower($ville)) . '.php',
        'departement' => substr($data['code_insee'] ?? '', 0, 2),
        'population' => $data['population'] ?? 0
    ];
}

// Fonction de formatage
function fmt($n, $decimals = 0) {
    return number_format($n, $decimals, ',', ' ');
}
?>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/header.php'); ?>

<!-- Page Header -->
<section class="page-header page-header-villes">
    <div class="container">
        <h1>Loi Jeanbrun par ville</h1>
        <p class="lead">
            Retrouvez les informations sur le dispositif Relance Logement 2026 pour <?= fmt($total_villes) ?> villes françaises.
            Plafonds de loyer, prix du marché, simulations d'investissement.
        </p>
    </div>
</section>

<!-- Breadcrumb -->
<nav class="breadcrumb-gouv" aria-label="breadcrumb">
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Accueil</a></li>
            <li class="breadcrumb-item active" aria-current="page">Villes</li>
        </ol>
    </div>
</nav>

<main>
    <!-- Barre de recherche -->
    <section class="section">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 mx-auto">
                    <h2 class="section-title text-center">Rechercher une ville</h2>

                    <div class="card-gouv">
                        <div class="card-body">
                            <div class="position-relative">
                                <input
                                    type="text"
                                    id="search-ville"
                                    class="form-control form-control-lg"
                                    placeholder="Tapez le nom d'une ville (ex: Paris, Lyon, Marseille)..."
                                    autocomplete="off"
                                >
                                <div id="autocomplete-results" class="list-group position-absolute w-100 shadow" style="z-index: 1000; display: none; max-height: 300px; overflow-y: auto;"></div>
                            </div>
                            <small class="text-muted d-block mt-2">
                                <i class="fas fa-info-circle"></i> Utilisez les flèches du clavier pour naviguer, Entrée pour valider
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Liste des villes -->
    <section class="section section-alt">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 mx-auto">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="section-title mb-0">Toutes les villes</h2>
                        <span class="badge bg-primary fs-6"><?= fmt($total_villes) ?> villes</span>
                    </div>

                    <!-- Indication de la page -->
                    <p class="text-muted mb-4">
                        <i class="fas fa-list"></i> Affichage <?= fmt($offset + 1) ?> - <?= fmt(min($offset + $per_page, $total_villes)) ?> sur <?= fmt($total_villes) ?> villes
                        (triées par population)
                    </p>

                    <!-- Cartes des villes -->
                    <div class="row g-3">
                        <?php foreach ($villes_page as $ville_data): ?>
                        <div class="col-md-6 col-lg-4">
                            <a href="/ville/<?= htmlspecialchars($ville_data['filename']) ?>" class="text-decoration-none">
                                <div class="card-gouv h-100 hover-card">
                                    <div class="card-body">
                                        <h3 class="h6 mb-2" style="color: var(--bleu-france);">
                                            <i class="fas fa-map-marker-alt me-1"></i>
                                            <?= htmlspecialchars($ville_data['nom_ville']) ?>
                                        </h3>
                                        <div class="d-flex flex-wrap gap-2 text-muted small">
                                            <span><i class="fas fa-users"></i> <?= fmt($ville_data['population'] ?? 0) ?> hab.</span>
                                            <span><i class="fas fa-tag"></i> Zone <?= htmlspecialchars($ville_data['zone'] ?? 'C') ?></span>
                                            <span><i class="fas fa-map"></i> <?= htmlspecialchars(substr($ville_data['departement'] ?? '', 0, 2)) ?></span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Pagination -->
                    <?php if ($total_pages > 1): ?>
                    <nav aria-label="Pagination des villes" class="mt-5">
                        <ul class="pagination justify-content-center flex-wrap">
                            <!-- Première page -->
                            <?php if ($current_page > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=1" aria-label="Première page">
                                    <span aria-hidden="true">&laquo;&laquo;</span>
                                </a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?= $current_page - 1 ?>" aria-label="Page précédente">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                            <?php endif; ?>

                            <!-- Pages numérotées -->
                            <?php
                            $start_page = max(1, $current_page - 2);
                            $end_page = min($total_pages, $current_page + 2);

                            if ($start_page > 1): ?>
                            <li class="page-item disabled"><span class="page-link">...</span></li>
                            <?php endif;

                            for ($i = $start_page; $i <= $end_page; $i++): ?>
                            <li class="page-item <?= $i === $current_page ? 'active' : '' ?>">
                                <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                            </li>
                            <?php endfor;

                            if ($end_page < $total_pages): ?>
                            <li class="page-item disabled"><span class="page-link">...</span></li>
                            <?php endif; ?>

                            <!-- Dernière page -->
                            <?php if ($current_page < $total_pages): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?= $current_page + 1 ?>" aria-label="Page suivante">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?= $total_pages ?>" aria-label="Dernière page">
                                    <span aria-hidden="true">&raquo;&raquo;</span>
                                </a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Tableau récapitulatif des zones -->
    <section class="section">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 mx-auto">
                    <h2 class="section-title">Plafonds de loyer par zone</h2>

                    <div class="card-gouv">
                        <div class="card-body">
                            <p class="text-muted mb-3">
                                Les plafonds de loyer varient selon la zone de classement de votre ville.
                                Voici un récapitulatif des plafonds en loyer intermédiaire (les plus courants) :
                            </p>
                            <div class="table-responsive">
                                <table class="table-gouv">
                                    <thead>
                                        <tr>
                                            <th>Zone</th>
                                            <th>Description</th>
                                            <th>Loyer intermédiaire</th>
                                            <th>Loyer social</th>
                                            <th>Loyer très social</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><strong>Abis</strong></td>
                                            <td>Paris et proche banlieue</td>
                                            <td>18,25 €/m²</td>
                                            <td>14,00 €/m²</td>
                                            <td>10,50 €/m²</td>
                                        </tr>
                                        <tr>
                                            <td><strong>A</strong></td>
                                            <td>Agglomération parisienne et Côte d'Azur</td>
                                            <td>14,49 €/m²</td>
                                            <td>11,11 €/m²</td>
                                            <td>8,33 €/m²</td>
                                        </tr>
                                        <tr>
                                            <td><strong>B1</strong></td>
                                            <td>Grandes agglomérations (Lyon, Marseille...)</td>
                                            <td>10,93 €/m²</td>
                                            <td>8,38 €/m²</td>
                                            <td>6,29 €/m²</td>
                                        </tr>
                                        <tr>
                                            <td><strong>B2</strong></td>
                                            <td>Agglomérations moyennes</td>
                                            <td>9,50 €/m²</td>
                                            <td>7,28 €/m²</td>
                                            <td>5,46 €/m²</td>
                                        </tr>
                                        <tr>
                                            <td><strong>C</strong></td>
                                            <td>Reste du territoire</td>
                                            <td>9,17 €/m²</td>
                                            <td>7,03 €/m²</td>
                                            <td>5,27 €/m²</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="info-box mt-3">
                                <strong>Bon à savoir :</strong> Ces plafonds sont cumulables avec l'amortissement fiscal jusqu'à 8 000 €/an sur 9 ans.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="section section-alt">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h2 class="section-title">Besoin d'accompagnement ?</h2>
                    <p class="lead mb-4">
                        Nos experts vous accompagnent dans votre projet d'investissement en Loi Jeanbrun.
                    </p>
                    <a href="/" class="btn-gouv-outline me-2">En savoir plus sur la loi Jeanbrun</a>
                    <a href="/contact/" class="btn-gouv">Prendre contact</a>
                </div>
            </div>
        </div>
    </section>
</main>

<!-- Données pour l'autocomplete -->
<script>
const villesData = <?= json_encode($autocomplete_data, JSON_UNESCAPED_UNICODE) ?>;
</script>

<!-- Script d'autocomplete -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-ville');
    const resultsContainer = document.getElementById('autocomplete-results');
    let selectedIndex = -1;

    // Normaliser le texte pour la recherche (enlever accents)
    function normalizeText(text) {
        return text.normalize('NFD').replace(/[\u0300-\u036f]/g, '').toLowerCase();
    }

    // Filtrer les villes
    function filterVilles(query) {
        if (!query || query.length < 2) return [];

        const normalizedQuery = normalizeText(query);

        return villesData
            .filter(ville => {
                const normalizedNom = normalizeText(ville.nom);
                return normalizedNom.includes(normalizedQuery);
            })
            .slice(0, 10); // Limiter à 10 résultats
    }

    // Afficher les résultats
    function showResults(results) {
        if (results.length === 0) {
            resultsContainer.style.display = 'none';
            return;
        }

        resultsContainer.innerHTML = results.map((ville, index) => `
            <a href="/ville/${ville.filename}"
               class="list-group-item list-group-item-action ${index === selectedIndex ? 'active' : ''}"
               data-index="${index}">
                <strong>${ville.nom}</strong>
                <small class="text-muted ms-2">${ville.departement} • ${Number(ville.population).toLocaleString('fr-FR')} hab.</small>
            </a>
        `).join('');

        resultsContainer.style.display = 'block';
    }

    // Événement de saisie
    searchInput.addEventListener('input', function() {
        const query = this.value.trim();
        selectedIndex = -1;
        const results = filterVilles(query);
        showResults(results);
    });

    // Navigation au clavier
    searchInput.addEventListener('keydown', function(e) {
        const items = resultsContainer.querySelectorAll('.list-group-item');

        if (e.key === 'ArrowDown') {
            e.preventDefault();
            selectedIndex = Math.min(selectedIndex + 1, items.length - 1);
            updateSelection(items);
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            selectedIndex = Math.max(selectedIndex - 1, -1);
            updateSelection(items);
        } else if (e.key === 'Enter') {
            e.preventDefault();
            if (selectedIndex >= 0 && items[selectedIndex]) {
                window.location.href = items[selectedIndex].href;
            }
        } else if (e.key === 'Escape') {
            resultsContainer.style.display = 'none';
            selectedIndex = -1;
        }
    });

    function updateSelection(items) {
        items.forEach((item, index) => {
            item.classList.toggle('active', index === selectedIndex);
        });
        if (selectedIndex >= 0 && items[selectedIndex]) {
            items[selectedIndex].scrollIntoView({ block: 'nearest' });
        }
    }

    // Cacher les résultats quand on clique ailleurs
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !resultsContainer.contains(e.target)) {
            resultsContainer.style.display = 'none';
            selectedIndex = -1;
        }
    });

    // Focus sur le champ de recherche
    searchInput.addEventListener('focus', function() {
        if (this.value.trim().length >= 2) {
            const results = filterVilles(this.value.trim());
            showResults(results);
        }
    });
});
</script>

<style>
.hover-card {
    transition: all 0.2s ease;
}
.hover-card:hover {
    box-shadow: 0 .5rem 1rem rgba(0,0,0,.15) !important;
    transform: translateY(-2px);
}
#autocomplete-results .list-group-item.active {
    background-color: var(--bleu-france);
    border-color: var(--bleu-france);
    color: white;
}
#autocomplete-results .list-group-item.active .text-muted {
    color: rgba(255,255,255,0.8) !important;
}
</style>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'); ?>
