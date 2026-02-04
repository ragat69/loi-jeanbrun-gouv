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

<main class="container py-5">
    <div class="row">
        <div class="col-lg-10 mx-auto">

            <h1 class="mb-3">Loi Jeanbrun par ville</h1>
            <p class="lead mb-4">
                Retrouvez les informations sur la <a href="/">loi Jeanbrun</a> pour <?= fmt($total_villes) ?> villes françaises.
                Plafonds de loyer, prix du marché, simulations d'investissement.
            </p>

            <!-- Barre de recherche avec autocomplete -->
            <section class="card mb-4">
                <div class="card-body">
                    <label for="search-ville" class="form-label fw-bold">Rechercher une ville</label>
                    <div class="position-relative">
                        <input
                            type="text"
                            id="search-ville"
                            class="form-control form-control-lg"
                            placeholder="Tapez le nom d'une ville..."
                            autocomplete="off"
                        >
                        <div id="autocomplete-results" class="list-group position-absolute w-100 shadow" style="z-index: 1000; display: none; max-height: 300px; overflow-y: auto;"></div>
                    </div>
                </div>
            </section>

            <!-- Liste des villes avec pagination -->
            <section class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2 class="h5 mb-0">Toutes les villes</h2>
                    <span class="badge bg-primary"><?= fmt($total_villes) ?> villes</span>
                </div>
                <div class="card-body">
                    <!-- Indication de la page -->
                    <p class="text-muted mb-3">
                        Affichage <?= fmt($offset + 1) ?> - <?= fmt(min($offset + $per_page, $total_villes)) ?> sur <?= fmt($total_villes) ?> villes
                        (triées par population)
                    </p>

                    <!-- Liste des villes -->
                    <div class="row">
                        <?php foreach ($villes_page as $ville_data): ?>
                        <div class="col-md-6 col-lg-4 mb-3">
                            <a href="/ville/<?= htmlspecialchars($ville_data['filename']) ?>" class="text-decoration-none">
                                <div class="card h-100 border-0 shadow-sm hover-shadow">
                                    <div class="card-body py-3">
                                        <h3 class="h6 mb-1"><?= htmlspecialchars($ville_data['nom_ville']) ?></h3>
                                        <small class="text-muted">
                                            <?= fmt($ville_data['population'] ?? 0) ?> hab.
                                            • Zone <?= htmlspecialchars($ville_data['zone'] ?? 'C') ?>
                                            • <?= htmlspecialchars(substr($ville_data['departement'] ?? '', 0, 2)) ?>
                                        </small>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Pagination -->
                    <?php if ($total_pages > 1): ?>
                    <nav aria-label="Pagination des villes" class="mt-4">
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
            </section>

            <!-- Tableau récapitulatif des zones -->
            <section class="card mb-4">
                <div class="card-header">
                    <h2 class="h5 mb-0">Plafonds de loyer par zone</h2>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>Zone</th>
                                    <th>Loyer intermédiaire</th>
                                    <th>Loyer social</th>
                                    <th>Loyer très social</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr><td><strong>Abis</strong></td><td>18,25 €/m²</td><td>14,00 €/m²</td><td>10,50 €/m²</td></tr>
                                <tr><td><strong>A</strong></td><td>14,49 €/m²</td><td>11,11 €/m²</td><td>8,33 €/m²</td></tr>
                                <tr><td><strong>B1</strong></td><td>10,93 €/m²</td><td>8,38 €/m²</td><td>6,29 €/m²</td></tr>
                                <tr><td><strong>B2</strong></td><td>9,50 €/m²</td><td>7,28 €/m²</td><td>5,46 €/m²</td></tr>
                                <tr><td><strong>C</strong></td><td>9,17 €/m²</td><td>7,03 €/m²</td><td>5,27 €/m²</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

        </div>
    </div>
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
.hover-shadow:hover {
    box-shadow: 0 .5rem 1rem rgba(0,0,0,.15) !important;
    transform: translateY(-2px);
    transition: all 0.2s ease;
}
.card {
    transition: all 0.2s ease;
}
#autocomplete-results .list-group-item.active {
    background-color: #0d6efd;
    border-color: #0d6efd;
    color: white;
}
#autocomplete-results .list-group-item.active .text-muted {
    color: rgba(255,255,255,0.8) !important;
}
</style>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'); ?>
