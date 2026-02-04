<?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/header.php'); ?>

<main class="container py-5">
    <div class="row">
        <div class="col-lg-10 mx-auto">

            <h1 class="mb-3">Loi Jeanbrun - Guide par ville</h1>
            <p class="lead mb-4">Dispositif Relance Logement 2026 : trouvez les informations spécifiques à votre ville</p>

            <!-- Résumé du dispositif -->
            <section class="card mb-4 bg-light">
                <div class="card-body">
                    <h2 class="h5">Le dispositif Jeanbrun en résumé</h2>
                    <p>
                        Le dispositif Jeanbrun (officiellement "Relance Logement") est un mécanisme fiscal
                        destiné à relancer l'investissement locatif privé en France. Il repose sur un système
                        d'amortissement fiscal permettant de réduire significativement l'imposition sur les revenus locatifs.
                    </p>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <h3 class="h6">Avantages</h3>
                            <ul class="small mb-0">
                                <li>Amortissement fiscal de 3,5% à 5,5%</li>
                                <li>Applicable sur tout le territoire</li>
                                <li>Déficit foncier imputable sur revenu global</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h3 class="h6">Conditions</h3>
                            <ul class="small mb-0">
                                <li>Engagement de 9 ans</li>
                                <li>Appartement en immeuble collectif</li>
                                <li>Respect des plafonds de loyer</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Recherche -->
            <section class="mb-4">
                <div class="input-group">
                    <input type="text" class="form-control" id="searchCity" placeholder="Rechercher une ville...">
                    <button class="btn btn-primary" type="button">Rechercher</button>
                </div>
            </section>

            <!-- Liste par zone -->
            {{zones_content}}

            <!-- Tableau comparatif -->
            <section class="card mb-4">
                <div class="card-header">
                    <h2 class="h5 mb-0">Comparatif des principales villes</h2>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="villesTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Ville</th>
                                    <th>Zone</th>
                                    <th>Population</th>
                                    <th>Prix neuf/m²</th>
                                    <th>Plafond loyer</th>
                                    <th>Loyer marché</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{table_rows}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- Plafonds par zone -->
            <section class="card mb-4">
                <div class="card-header">
                    <h2 class="h5 mb-0">Plafonds de loyer par zone</h2>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Zone</th>
                                    <th>Loyer intermédiaire</th>
                                    <th>Loyer social</th>
                                    <th>Loyer très social</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>Abis</strong> (Paris)</td>
                                    <td>18,25 €/m²</td>
                                    <td>14,00 €/m²</td>
                                    <td>10,50 €/m²</td>
                                </tr>
                                <tr>
                                    <td><strong>A</strong> (Grandes agglomérations)</td>
                                    <td>14,49 €/m²</td>
                                    <td>11,11 €/m²</td>
                                    <td>8,33 €/m²</td>
                                </tr>
                                <tr>
                                    <td><strong>B1</strong> (Agglomérations moyennes)</td>
                                    <td>10,93 €/m²</td>
                                    <td>8,38 €/m²</td>
                                    <td>6,29 €/m²</td>
                                </tr>
                                <tr>
                                    <td><strong>B2</strong> (Autres communes)</td>
                                    <td>9,50 €/m²</td>
                                    <td>7,28 €/m²</td>
                                    <td>5,46 €/m²</td>
                                </tr>
                                <tr>
                                    <td><strong>C</strong> (Reste du territoire)</td>
                                    <td>9,17 €/m²</td>
                                    <td>7,03 €/m²</td>
                                    <td>5,27 €/m²</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

        </div>
    </div>
</main>

<script>
// Recherche simple
document.getElementById('searchCity').addEventListener('input', function(e) {
    const search = e.target.value.toLowerCase();
    const rows = document.querySelectorAll('#villesTable tbody tr');
    rows.forEach(row => {
        const ville = row.querySelector('td:first-child').textContent.toLowerCase();
        row.style.display = ville.includes(search) ? '' : 'none';
    });
});
</script>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'); ?>
