<?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/header.php'); ?>

<main class="container py-5">
    <div class="row">
        <div class="col-lg-10 mx-auto">

            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Accueil</a></li>
                    <li class="breadcrumb-item"><a href="/ville/">Loi Jeanbrun par ville</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ville}}</li>
                </ol>
            </nav>

            <h1 class="mb-3">Loi Jeanbrun à {{ville}}</h1>
            <p class="lead mb-4">Guide complet du dispositif Relance Logement 2026 pour investir à {{ville}}</p>

            <!-- Informations clés -->
            <section class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h2 class="h5 mb-0">Informations clés pour {{ville}}</h2>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2"><strong>Département :</strong> {{departement}}</li>
                                <li class="mb-2"><strong>Population :</strong> {{population_formatted}} habitants</li>
                                <li class="mb-2"><strong>Zone de classement :</strong> <span class="badge bg-info">{{zone}}</span></li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2"><strong>Taux de vacance :</strong> {{taux_vacance}}%</li>
                                <li class="mb-2"><strong>Projets construction :</strong> {{projets_construction_formatted}} logements</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Prix du marché -->
            <section class="card mb-4">
                <div class="card-header">
                    <h2 class="h5 mb-0">Prix du marché immobilier à {{ville}}</h2>
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
                                    <td><strong>{{prix_m2_neuf_formatted}} €</strong></td>
                                    <td>{{prix_t2_neuf_formatted}} €</td>
                                    <td>{{prix_t3_neuf_formatted}} €</td>
                                    <td>{{prix_t4_neuf_formatted}} €</td>
                                </tr>
                                <tr>
                                    <td>Logement ancien</td>
                                    <td><strong>{{prix_m2_ancien_formatted}} €</strong></td>
                                    <td>{{prix_t2_ancien_formatted}} €</td>
                                    <td>{{prix_t3_ancien_formatted}} €</td>
                                    <td>{{prix_t4_ancien_formatted}} €</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <p class="text-muted small mb-0">
                        <strong>Loyer de marché moyen :</strong> {{loyer_marche_m2}} €/m²
                    </p>
                </div>
            </section>

            <!-- Plafonds de loyer -->
            <section class="card mb-4">
                <div class="card-header">
                    <h2 class="h5 mb-0">Plafonds de loyer Loi Jeanbrun à {{ville}} (Zone {{zone}})</h2>
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
                                    <td>{{plafond_intermediaire}} €</td>
                                    <td>{{loyer_intermediaire_t2}} €/mois</td>
                                    <td>{{loyer_intermediaire_t3}} €/mois</td>
                                    <td>{{loyer_intermediaire_t4}} €/mois</td>
                                    <td>3,5%</td>
                                    <td>8 000 €</td>
                                </tr>
                                <tr>
                                    <td><strong>Loyer social</strong></td>
                                    <td>{{plafond_social}} €</td>
                                    <td>{{loyer_social_t2}} €/mois</td>
                                    <td>{{loyer_social_t3}} €/mois</td>
                                    <td>{{loyer_social_t4}} €/mois</td>
                                    <td>4,5%</td>
                                    <td>10 000 €</td>
                                </tr>
                                <tr>
                                    <td><strong>Loyer très social</strong></td>
                                    <td>{{plafond_tres_social}} €</td>
                                    <td>{{loyer_tres_social_t2}} €/mois</td>
                                    <td>{{loyer_tres_social_t3}} €/mois</td>
                                    <td>{{loyer_tres_social_t4}} €/mois</td>
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
                    <h2 class="h5 mb-0">Simulation d'investissement à {{ville}}</h2>
                </div>
                <div class="card-body">
                    <h3 class="h6">Exemple : T3 neuf de 65m² en loyer intermédiaire</h3>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <h4 class="h6 text-muted">Caractéristiques</h4>
                            <ul class="list-unstyled">
                                <li><strong>Prix d'acquisition :</strong> {{prix_t3_neuf_formatted}} €</li>
                                <li><strong>Apport (20%) :</strong> {{apport_formatted}} €</li>
                                <li><strong>Emprunt :</strong> {{emprunt_formatted}} €</li>
                                <li><strong>Loyer mensuel :</strong> {{loyer_mensuel}} €</li>
                                <li><strong>Loyer annuel :</strong> {{loyer_annuel}} €</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h4 class="h6 text-muted">Avantage fiscal</h4>
                            <ul class="list-unstyled">
                                <li><strong>Assiette amortissable (80%) :</strong> {{assiette_formatted}} €</li>
                                <li><strong>Amortissement annuel :</strong> {{amortissement_annuel_formatted}} €</li>
                                <li><strong>Gain fiscal/an (TMI 30%) :</strong> {{gain_fiscal_annuel_formatted}} €</li>
                                <li class="mt-2 text-success"><strong>Gain fiscal sur 9 ans :</strong> {{gain_fiscal_9ans_formatted}} €</li>
                            </ul>
                        </div>
                    </div>

                    <div class="alert alert-info mt-3 mb-0">
                        <strong>Rendement brut :</strong> {{rendement_brut}}%
                    </div>
                </div>
            </section>

            <!-- Conditions d'éligibilité -->
            <section class="card mb-4">
                <div class="card-header">
                    <h2 class="h5 mb-0">Conditions d'éligibilité à {{ville}}</h2>
                </div>
                <div class="card-body">
                    <ul class="mb-0">
                        <li>Logement situé dans un <strong>immeuble collectif</strong> (appartement uniquement)</li>
                        <li>Bien neuf ou ancien avec travaux ≥ 30% du prix d'achat</li>
                        <li>Pour l'ancien : travaux permettant d'atteindre la <strong>classe A ou B du DPE</strong></li>
                        <li>Engagement de location de <strong>9 ans minimum</strong></li>
                        <li>Location à titre de résidence principale (8 mois/an minimum)</li>
                        <li>Respect des plafonds de loyer de la zone <strong>{{zone}}</strong></li>
                        <li>Respect des plafonds de ressources des locataires</li>
                        <li>Interdiction de louer à un membre du foyer fiscal</li>
                    </ul>
                </div>
            </section>

            <!-- Comparaison -->
            <section class="card mb-4">
                <div class="card-header">
                    <h2 class="h5 mb-0">Comparaison Loi Jeanbrun vs marché libre à {{ville}}</h2>
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
                                    <td>{{plafond_intermediaire}} € (plafonné)</td>
                                    <td>{{loyer_marche_m2}} € (marché)</td>
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
                    <h2 class="h5 mb-0">Questions fréquentes sur la Loi Jeanbrun à {{ville}}</h2>
                </div>
                <div class="card-body">
                    <div class="accordion" id="faqAccordion">
                        <div class="accordion-item">
                            <h3 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                    Puis-je investir dans n'importe quel quartier de {{ville}} ?
                                </button>
                            </h3>
                            <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Oui, contrairement au dispositif Pinel, la loi Jeanbrun ne comporte pas de zonage restrictif.
                                    Tous les quartiers de {{ville}} sont éligibles, à condition de respecter les plafonds de loyer de la zone {{zone}}.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h3 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                    Quel est le loyer maximum pour un T3 à {{ville}} ?
                                </button>
                            </h3>
                            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Pour un T3 de 65m² en loyer intermédiaire à {{ville}} (zone {{zone}}),
                                    le loyer maximum est de <strong>{{loyer_intermediaire_t3}} €</strong> par mois, soit {{plafond_intermediaire}} €/m².
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

        </div>
    </div>
</main>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'); ?>
