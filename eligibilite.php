<?php
$current_page = 'eligibilite';
$page_title_full = 'Loi Jeanbrun 2026 - Éligibilité | Dispositif Relance Logement';
$page_description = 'Vérifiez votre éligibilité au dispositif Jeanbrun : conditions sur le bien, le locataire, les plafonds de loyers et de ressources.';
include 'includes/header.php';
?>

<!-- Page Header -->
<section class="page-header page-header-eligibilite">
    <div class="container">
        <h1><i class="fas fa-check-circle me-3"></i>Conditions d'éligibilité</h1>
        <p class="lead">Tout savoir sur les critères pour bénéficier du dispositif Jeanbrun</p>
    </div>
</section>

<!-- Breadcrumb -->
<nav class="breadcrumb-gouv">
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Accueil</a></li>
            <li class="breadcrumb-item active">Éligibilité</li>
        </ol>
    </div>
</nav>

<!-- Introduction -->
<section class="section">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <h2 class="section-title">Qui peut bénéficier du dispositif ?</h2>
                <p class="lead">
                    Le dispositif Jeanbrun s'adresse à <strong>tous les contribuables français</strong>
                    souhaitant investir dans l'immobilier locatif, qu'ils soient déjà propriétaires
                    ou primo-investisseurs.
                </p>
                <p>
                    Pour bénéficier des avantages fiscaux, plusieurs conditions doivent être remplies
                    concernant le bien immobilier, le locataire et les plafonds applicables.
                </p>
            </div>
            <div class="col-lg-4">
                <div class="info-box success">
                    <h5><i class="fas fa-user-check me-2"></i>Public cible</h5>
                    <p class="mb-0 small">
                        Le dispositif est particulièrement adapté aux investisseurs avec des revenus
                        importants, cherchant une stratégie patrimoniale sur le long terme.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Test d'éligibilité interactif -->
<section class="section section-alt">
    <div class="container">
        <h2 class="section-title text-center">Testez votre éligibilité au dispositif Jeanbrun en 10 secondes</h2>
        <p class="text-center lead mb-5">
            Répondez à quelques questions pour savoir si votre projet pourrait bénéficier du plan Relance Logement
        </p>

        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card-gouv" id="eligibility-test">
                    <!-- Barre de progression -->
                    <div class="progress mb-4" style="height: 8px;">
                        <div class="progress-bar bg-bleu-france" role="progressbar" id="progress-bar"
                             style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>

                    <!-- Conteneur des questions -->
                    <div id="question-container" class="card-body p-4">
                        <!-- Les questions seront injectées ici par JavaScript -->
                    </div>

                    <!-- Boutons de navigation -->
                    <div class="card-footer bg-white border-top d-flex justify-content-between">
                        <button class="btn-gouv" id="prev-btn" style="display: none;">
                            <i class="fas fa-arrow-left me-2"></i>Précédent
                        </button>
                        <div class="ms-auto">
                            <button class="btn-gouv" id="next-btn" disabled>
                                Suivant<i class="fas fa-arrow-right ms-2"></i>
                            </button>
                            <button class="btn-gouv" id="result-btn" style="display: none;">
                                Afficher les résultats<i class="fas fa-check-circle ms-2"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Résultat -->
                <div id="result-container" style="display: none;">
                    <!-- Le résultat sera affiché ici -->
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.question-slide {
    animation: slideIn 0.3s ease-out;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateX(20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.btn-choice {
    width: 100%;
    padding: 1rem;
    margin-bottom: 0.75rem;
    border: 2px solid #e5e5e5;
    background: white;
    border-radius: 8px;
    transition: all 0.2s;
    cursor: pointer;
    text-align: left;
    display: flex;
    align-items: center;
}

.btn-choice:hover {
    border-color: var(--bleu-france);
    background: #f6f6f6;
    transform: translateX(5px);
}

.btn-choice.selected {
    border-color: var(--bleu-france);
    background: rgba(0, 0, 145, 0.05);
}

.btn-choice i {
    margin-right: 12px;
    font-size: 1.2rem;
    color: var(--bleu-france);
}

.result-eligible {
    background: linear-gradient(135deg, #00a550 0%, #00c165 100%);
    color: white;
    padding: 2rem;
    border-radius: 12px;
    text-align: center;
}

.result-not-eligible {
    background: linear-gradient(135deg, #d64d00 0%, #ff5722 100%);
    color: white;
    padding: 2rem;
    border-radius: 12px;
    text-align: center;
}

.result-icon {
    font-size: 4rem;
    margin-bottom: 1rem;
}

.btn-gouv-secondary {
    background-color: #f5f5f5;
    border-color: #e5e5e5;
}

.btn-gouv-secondary:hover {
    background-color: #e5e5e5;
    border-color: #d5d5d5;
}

.btn-gouv:disabled,
.btn-gouv.disabled {
    opacity: 0.6;
    cursor: not-allowed !important;
    background-color: #e0e0e0 !important;
    border-color: #d0d0d0 !important;
    color: #999999 !important;
    pointer-events: none !important;
}

.btn-gouv:disabled:hover,
.btn-gouv.disabled:hover {
    background-color: #e0e0e0 !important;
    border-color: #d0d0d0 !important;
    transform: none !important;
}
</style>

<script>
// Configuration du test d'éligibilité
const eligibilityQuestions = [
    {
        id: 'periode_acquisition',
        question: 'Quand envisagez-vous d\'acquérir le bien ?',
        icon: 'fa-calendar-alt',
        type: 'choice',
        choices: [
            { value: '2026-2028', label: 'Entre 2026 et fin 2028', icon: 'fa-check', eligible: true },
            { value: 'apres_2028', label: 'Après 2028', icon: 'fa-question', eligible: false }
        ]
    },
    {
        id: 'type_bien',
        question: 'Quel type de bien souhaitez-vous acquérir ?',
        icon: 'fa-home',
        type: 'choice',
        choices: [
            { value: 'neuf', label: 'Logement neuf ou en VEFA', icon: 'fa-building', eligible: true },
            { value: 'ancien', label: 'Logement ancien avec travaux (min. 30% du prix)', icon: 'fa-tools', eligible: true },
            { value: 'ancien_sans', label: 'Logement ancien sans travaux', icon: 'fa-home', eligible: false },
            { value: 'maison', label: 'Maison individuelle', icon: 'fa-house-user', eligible: false }
        ]
    },
    {
        id: 'type_immeuble',
        question: 'Le bien est-il situé dans un immeuble collectif ?',
        icon: 'fa-building',
        type: 'yesno',
        yes_eligible: true,
        description: 'Le dispositif exclut les maisons individuelles'
    },
    {
        id: 'localisation',
        question: 'Le bien est-il situé en France (métropole ou outre-mer) ?',
        icon: 'fa-map-marked-alt',
        type: 'yesno',
        yes_eligible: true,
        description: 'Le dispositif est applicable sur tout le territoire français'
    },
    {
        id: 'engagement',
        question: 'Pouvez-vous vous engager à louer le bien pendant au moins 9 ans ?',
        icon: 'fa-calendar-check',
        type: 'yesno',
        yes_eligible: true,
        description: 'L\'engagement minimum de location est de 9 ans'
    },
    {
        id: 'type_location',
        question: 'Comment envisagez-vous de louer le bien ?',
        icon: 'fa-couch',
        type: 'choice',
        choices: [
            { value: 'nu', label: 'Location nue (non meublée)', icon: 'fa-home', eligible: true },
            { value: 'meuble', label: 'Location meublée', icon: 'fa-couch', eligible: false }
        ]
    },
    {
        id: 'residence_principale',
        question: 'Le bien sera-t-il la résidence principale du locataire ?',
        icon: 'fa-key',
        type: 'yesno',
        yes_eligible: true,
        description: 'Le logement doit être utilisé comme résidence principale'
    },
    {
        id: 'famille',
        question: 'Envisagez-vous de louer à un membre de votre famille ?',
        icon: 'fa-users',
        type: 'yesno',
        yes_eligible: false,
        description: 'Ascendants, descendants ou membres du foyer fiscal'
    },
    {
        id: 'delai',
        question: 'Pourrez-vous mettre le bien en location dans les 12 mois suivant son acquisition/achèvement ?',
        icon: 'fa-clock',
        type: 'yesno',
        yes_eligible: true,
        description: 'Délai maximum de mise en location'
    },
    {
        id: 'plafonds',
        question: 'Acceptez-vous de respecter les plafonds de loyers et de ressources du locataire ?',
        icon: 'fa-euro-sign',
        type: 'yesno',
        yes_eligible: true,
        description: 'Pour bénéficier des majorations de taux d\'amortissement'
    }
];

let currentQuestion = 0;
let answers = {};
let isEligible = true;
let ineligibilityReasons = [];

function initTest() {
    showQuestion(0);
    updateProgressBar();
}

function showQuestion(index) {
    const question = eligibilityQuestions[index];
    const container = document.getElementById('question-container');

    let html = `
        <div class="question-slide">
            <div class="mb-3">
                <span class="badge bg-bleu-france">Question ${index + 1}/${eligibilityQuestions.length}</span>
            </div>
            <h4 class="mb-2">
                <i class="fas ${question.icon} me-2 text-bleu-france"></i>
                ${question.question}
            </h4>
    `;

    // Ajouter la description si elle existe
    if (question.description) {
        html += `<p class="text-muted small mb-4"><i class="fas fa-info-circle me-1"></i>${question.description}</p>`;
    } else {
        html += `<div class="mb-4"></div>`;
    }

    if (question.type === 'yesno') {
        html += `
            <button class="btn-choice" onclick="selectAnswer('${question.id}', 'yes')">
                <i class="fas fa-check-circle"></i>
                <span>Oui</span>
            </button>
            <button class="btn-choice" onclick="selectAnswer('${question.id}', 'no')">
                <i class="fas fa-times-circle"></i>
                <span>Non</span>
            </button>
        `;
    } else if (question.type === 'choice') {
        question.choices.forEach(choice => {
            html += `
                <button class="btn-choice" onclick="selectAnswer('${question.id}', '${choice.value}', ${choice.eligible})">
                    <i class="fas ${choice.icon}"></i>
                    <span>${choice.label}</span>
                </button>
            `;
        });
    }

    html += '</div>';
    container.innerHTML = html;

    // Mettre à jour les boutons
    document.getElementById('prev-btn').style.display = index > 0 ? 'block' : 'none';
    const nextBtn = document.getElementById('next-btn');
    const resultBtn = document.getElementById('result-btn');

    nextBtn.style.display = 'block';
    resultBtn.style.display = 'none';

    // Toujours désactiver le bouton "Suivant" si aucune réponse n'a été donnée pour cette question
    if (!answers[question.id]) {
        nextBtn.disabled = true;
        nextBtn.classList.add('disabled');
    } else {
        nextBtn.disabled = false;
        nextBtn.classList.remove('disabled');
    }
}

function selectAnswer(questionId, value, eligible = null) {
    const question = eligibilityQuestions.find(q => q.id === questionId);

    // Enregistrer la réponse
    answers[questionId] = value;

    // Vérifier l'éligibilité
    if (question.type === 'yesno') {
        const expectedAnswer = question.yes_eligible ? 'yes' : 'no';
        if (value !== expectedAnswer) {
            isEligible = false;
            ineligibilityReasons.push(getIneligibilityReason(questionId, value));
        }
    } else if (question.type === 'choice') {
        if (eligible === false) {
            isEligible = false;
            ineligibilityReasons.push(getIneligibilityReason(questionId, value));
        }
    }

    // Marquer visuellement la sélection
    document.querySelectorAll('.btn-choice').forEach(btn => {
        btn.classList.remove('selected');
    });
    event.target.closest('.btn-choice').classList.add('selected');

    // Si c'est la dernière question, afficher directement le bouton de résultat
    const nextBtn = document.getElementById('next-btn');
    const resultBtn = document.getElementById('result-btn');

    if (currentQuestion === eligibilityQuestions.length - 1) {
        // Dernière question : afficher le bouton de résultat en vert
        nextBtn.style.display = 'none';
        resultBtn.style.display = 'block';
        resultBtn.style.backgroundColor = '#00a550';
        resultBtn.style.borderColor = '#00a550';
    } else {
        // Activer le bouton "Suivant"
        nextBtn.disabled = false;
        nextBtn.classList.remove('disabled');
    }
}

function getIneligibilityReason(questionId, value) {
    const reasons = {
        'periode_acquisition': 'Le dispositif s\'applique uniquement aux acquisitions entre 2026 et fin 2028',
        'type_bien': 'Le bien doit être neuf, en VEFA, ou ancien avec au moins 30% de travaux (les maisons individuelles sont exclues)',
        'type_immeuble': 'Le bien doit être situé dans un immeuble collectif (les maisons individuelles ne sont pas éligibles)',
        'localisation': 'Le bien doit être situé en France (métropole ou outre-mer)',
        'engagement': 'Un engagement de location de 9 ans minimum est requis',
        'type_location': 'Seule la location nue (non meublée) est éligible',
        'residence_principale': 'Le bien doit être la résidence principale du locataire',
        'famille': 'Il est strictement interdit de louer à un membre de votre famille (ascendants, descendants, ou membre du foyer fiscal)',
        'delai': 'Le bien doit être mis en location dans les 12 mois suivant son acquisition ou achèvement',
        'plafonds': 'Le respect des plafonds de loyers et de ressources est obligatoire pour bénéficier des avantages fiscaux'
    };
    return reasons[questionId] || 'Critère non respecté';
}

function nextQuestion() {
    if (currentQuestion < eligibilityQuestions.length - 1) {
        currentQuestion++;
        showQuestion(currentQuestion);
        updateProgressBar();
    } else {
        // Dernière question, afficher le bouton de résultat
        document.getElementById('next-btn').style.display = 'none';
        document.getElementById('result-btn').style.display = 'block';
    }
}

function prevQuestion() {
    if (currentQuestion > 0) {
        currentQuestion--;
        showQuestion(currentQuestion);
        updateProgressBar();
    }
}

function updateProgressBar() {
    const progress = ((currentQuestion + 1) / eligibilityQuestions.length) * 100;
    document.getElementById('progress-bar').style.width = progress + '%';
    document.getElementById('progress-bar').setAttribute('aria-valuenow', progress);
}

function showResult() {
    document.getElementById('eligibility-test').style.display = 'none';
    const resultContainer = document.getElementById('result-container');
    resultContainer.style.display = 'block';

    let html = '';

    if (isEligible) {
        html = `
            <div class="result-eligible">
                <div class="result-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h2 class="mb-3">Félicitations ! Votre projet est éligible</h2>
                <p class="lead mb-4">
                    Votre projet remplit toutes les conditions pour bénéficier du dispositif Jeanbrun.
                    Estimez maintenant les avantages fiscaux que vous pouvez obtenir.
                </p>
                <div class="d-flex gap-3 justify-content-center flex-wrap">
                    <a href="/simulation" class="btn btn-light btn-lg">
                        <i class="fas fa-calculator me-2"></i>Faire une simulation
                    </a>
                    <button class="btn btn-outline-light btn-lg" onclick="restartTest()">
                        <i class="fas fa-redo me-2"></i>Refaire le test
                    </button>
                </div>
            </div>
        `;
    } else {
        html = `
            <div class="result-not-eligible">
                <div class="result-icon">
                    <i class="fas fa-times-circle"></i>
                </div>
                <h2 class="mb-3">Votre projet n'est pas éligible</h2>
                <p class="lead mb-4">
                    Malheureusement, votre projet ne remplit pas toutes les conditions requises pour bénéficier du dispositif Jeanbrun.
                </p>
                <div class="bg-white text-dark p-4 rounded mb-4" style="text-align: left;">
                    <h5 class="mb-3"><i class="fas fa-exclamation-triangle text-warning me-2"></i>Points bloquants :</h5>
                    <ul class="mb-0">
                        ${ineligibilityReasons.map(reason => `<li>${reason}</li>`).join('')}
                    </ul>
                </div>
                <div class="d-flex gap-3 justify-content-center flex-wrap">
                    <button class="btn btn-light btn-lg" onclick="restartTest()">
                        <i class="fas fa-redo me-2"></i>Refaire le test
                    </button>
                </div>
            </div>
        `;
    }

    resultContainer.innerHTML = html;

    // Scroll vers le résultat
    resultContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
}

function restartTest() {
    currentQuestion = 0;
    answers = {};
    isEligible = true;
    ineligibilityReasons = [];

    document.getElementById('eligibility-test').style.display = 'block';
    document.getElementById('result-container').style.display = 'none';

    initTest();
}

// Événements
document.addEventListener('DOMContentLoaded', function() {
    initTest();

    document.getElementById('prev-btn').addEventListener('click', prevQuestion);
    document.getElementById('next-btn').addEventListener('click', nextQuestion);
    document.getElementById('result-btn').addEventListener('click', showResult);
});
</script>

<!-- Conditions sur le bien -->
<section class="section">
    <div class="container">
        <h2 class="section-title">
            <i class="fas fa-home me-2 text-bleu-france"></i>
            Conditions relatives au bien immobilier
        </h2>

        <div class="row g-4">
            <div class="col-lg-6">
                <div class="card-gouv h-100">
                    <div class="card-header">
                        <i class="fas fa-building me-2"></i>Logement neuf
                    </div>
                    <div class="card-body">
                        <ul class="list-gouv">
                            <li>Logement acquis neuf ou en VEFA</li>
                            <li>Situé dans un immeuble collectif</li>
                            <li>Respectant les normes énergétiques en vigueur (RE2020)</li>
                            <li>Achevé dans les 30 mois suivant la déclaration d'ouverture de chantier</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card-gouv h-100">
                    <div class="card-header">
                        <i class="fas fa-tools me-2"></i>Logement ancien rénové
                    </div>
                    <div class="card-body">
                        <ul class="list-gouv">
                            <li>Travaux représentant minimum <strong>30%</strong> du prix d'acquisition</li>
                            <li>Travaux permettant d'atteindre une performance énergétique minimale</li>
                            <li>Logement situé dans un immeuble collectif</li>
                            <li>Travaux réalisés par des professionnels certifiés</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3 mt-4">
            <div class="col-lg-6">
                <div class="info-box">
                    <h5><i class="fas fa-map-marker-alt me-2"></i>Localisation</h5>
                    <p class="mb-0">
                        Contrairement au dispositif Pinel, le Jeanbrun s'applique <strong>sur tout le territoire
                        français</strong>, y compris l'outre-mer. Il n'y a plus de zonage géographique restrictif.
                    </p>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="info-box">
                    <h5><i class="fas fa-calendar-alt me-2"></i>Période d'acquisition</h5>
                    <p class="mb-0">
                        Le dispositif s'applique aux logements neufs ou en VEFA <strong>acquis entre 2026 et
                        le 31 décembre 2028</strong>. Les acquisitions en dehors de cette période ne sont pas éligibles.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Conditions de location -->
<section class="section">
    <div class="container">
        <h2 class="section-title">
            <i class="fas fa-key me-2 text-bleu-france"></i>
            Conditions de mise en location
        </h2>

        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="card-gouv h-100">
                    <div class="card-body text-center">
                        <div class="card-icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <h5>Durée d'engagement</h5>
                        <p class="small text-muted">
                            Location obligatoire pendant <strong>9 ans minimum</strong>
                            à compter de la mise en location.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card-gouv h-100">
                    <div class="card-body text-center">
                        <div class="card-icon">
                            <i class="fas fa-home"></i>
                        </div>
                        <h5>Résidence principale</h5>
                        <p class="small text-muted">
                            Le logement doit être la <strong>résidence principale</strong>
                            du locataire.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card-gouv h-100">
                    <div class="card-body text-center">
                        <div class="card-icon">
                            <i class="fas fa-couch"></i>
                        </div>
                        <h5>Location nue</h5>
                        <p class="small text-muted">
                            Le bien doit être loué <strong>non meublé</strong>
                            (location nue uniquement).
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card-gouv h-100">
                    <div class="card-body text-center">
                        <div class="card-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <h5>Délai de location</h5>
                        <p class="small text-muted">
                            Mise en location dans les <strong>12 mois</strong>
                            suivant l'acquisition ou l'achèvement.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Conditions sur le locataire -->
<section class="section section-alt">
    <div class="container">
        <h2 class="section-title">
            <i class="fas fa-users me-2 text-bleu-france"></i>
            Conditions relatives au locataire
        </h2>

        <div class="row align-items-center">
            <div class="col-lg-6">
                <h4>Le locataire ne doit pas être :</h4>
                <ul class="list-group list-group-flush mb-4">
                    <li class="list-group-item">
                        <i class="fas fa-times text-danger me-2"></i>
                        Un membre du foyer fiscal du propriétaire
                    </li>
                    <li class="list-group-item">
                        <i class="fas fa-times text-danger me-2"></i>
                        Un ascendant (parent, grand-parent...)
                    </li>
                    <li class="list-group-item">
                        <i class="fas fa-times text-danger me-2"></i>
                        Un descendant (enfant, petit-enfant...)
                    </li>
                </ul>

                <h4>Le locataire doit :</h4>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <i class="fas fa-check text-success me-2"></i>
                        Respecter les plafonds de ressources (si applicable)
                    </li>
                    <li class="list-group-item">
                        <i class="fas fa-check text-success me-2"></i>
                        Utiliser le logement comme résidence principale
                    </li>
                    <li class="list-group-item">
                        <i class="fas fa-check text-success me-2"></i>
                        Fournir un avis d'imposition pour vérification
                    </li>
                </ul>
            </div>
            <div class="col-lg-6">
                <div class="info-box warning">
                    <h5><i class="fas fa-exclamation-triangle me-2"></i>Interdiction stricte</h5>
                    <p>
                        Pour garantir l'intégrité sociale du dispositif et prévenir les abus d'optimisation fiscale,
                        la location aux <strong>ascendants ou descendants est strictement interdite</strong>
                        pendant toute la durée de l'engagement de location (9 ans).
                    </p>
                    <p class="mb-0">
                        En cas de non-respect de cette condition, les avantages fiscaux
                        obtenus seront remis en cause avec application de pénalités.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Plafonds de loyers -->
<section class="section">
    <div class="container">
        <h2 class="section-title">
            <i class="fas fa-euro-sign me-2 text-bleu-france"></i>
            Plafonds de loyers
        </h2>
        <p class="mb-4">
            Les loyers sont plafonnés selon le type de location choisi. Ces plafonds sont exprimés
            en euros par mètre carré de surface habitable, hors charges.
        </p>

        <div class="table-responsive">
            <table class="table table-gouv">
                <thead>
                    <tr>
                        <th>Type de loyer</th>
                        <th>Plafond indicatif</th>
                        <th>Taux d'amortissement</th>
                        <th>Public cible</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Loyer intermédiaire</strong></td>
                        <td>Environ -15% du marché</td>
                        <td class="text-center"><span class="badge bg-primary">3,5%</span></td>
                        <td>Classes moyennes</td>
                    </tr>
                    <tr>
                        <td><strong>Loyer social</strong></td>
                        <td>Plafonds HLM (PLUS)</td>
                        <td class="text-center"><span class="badge bg-primary">4,5%</span></td>
                        <td>Ménages modestes</td>
                    </tr>
                    <tr>
                        <td><strong>Loyer très social</strong></td>
                        <td>Plafonds HLM (PLAI)</td>
                        <td class="text-center"><span class="badge bg-primary">5,5%</span></td>
                        <td>Ménages très modestes</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="info-box mt-4">
            <h5><i class="fas fa-info-circle me-2"></i>Coefficient de surface</h5>
            <p class="mb-0">
                Un coefficient multiplicateur s'applique selon la surface du logement :
                <strong>0,7 + (19 / surface habitable)</strong>. Ce coefficient est plafonné à 1,2.
                Ainsi, les petites surfaces bénéficient d'un plafond de loyer proportionnellement plus élevé.
            </p>
        </div>
    </div>
</section>

<!-- Plafonds de ressources -->
<section class="section section-alt">
    <div class="container">
        <h2 class="section-title">
            <i class="fas fa-wallet me-2 text-bleu-france"></i>
            Plafonds de ressources des locataires
        </h2>
        <p class="mb-4">
            Avec la suppression du zonage géographique, le dispositif Jeanbrun instaure des
            <strong>plafonds de ressources nationaux uniques</strong>. Les ressources du locataire
            (revenu fiscal de référence N-2) ne doivent pas dépasser les plafonds suivants selon
            la composition du foyer.
        </p>

        <div class="row">
            <div class="col-lg-8">
                <div class="table-responsive">
                    <table class="table table-gouv">
                        <thead>
                            <tr>
                                <th>Composition du foyer</th>
                                <th>Plafond loyer intermédiaire</th>
                                <th>Plafond loyer social</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Personne seule</td>
                                <td>35 000 €</td>
                                <td>25 000 €</td>
                            </tr>
                            <tr>
                                <td>Couple</td>
                                <td>50 000 €</td>
                                <td>35 000 €</td>
                            </tr>
                            <tr>
                                <td>+ 1 personne à charge</td>
                                <td>60 000 €</td>
                                <td>42 000 €</td>
                            </tr>
                            <tr>
                                <td>+ 2 personnes à charge</td>
                                <td>72 000 €</td>
                                <td>50 000 €</td>
                            </tr>
                            <tr>
                                <td>+ 3 personnes à charge</td>
                                <td>85 000 €</td>
                                <td>60 000 €</td>
                            </tr>
                            <tr>
                                <td>+ 4 personnes à charge</td>
                                <td>96 000 €</td>
                                <td>68 000 €</td>
                            </tr>
                            <tr>
                                <td>Majoration par personne supplémentaire</td>
                                <td>+10 000 €</td>
                                <td>+7 500 €</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="info-box success">
                    <h5><i class="fas fa-map-marked-alt me-2"></i>Fin du zonage</h5>
                    <p class="mb-0 small">
                        Contrairement au dispositif Pinel, il n'y a plus de distinction entre
                        zones A, B ou C. Les mêmes plafonds s'appliquent sur tout le territoire
                        français, simplifiant ainsi les démarches des investisseurs.
                    </p>
                </div>
            </div>
        </div>

        <p class="text-muted small mt-3">
            <i class="fas fa-info-circle me-1"></i>
            Ces plafonds sont donnés à titre indicatif et peuvent être actualisés annuellement.
            Les plafonds pour les loyers très sociaux sont inférieurs à ceux du loyer social.
        </p>
    </div>
</section>

<!-- Plafonds de l'avantage fiscal -->
<section class="section">
    <div class="container">
        <h2 class="section-title">
            <i class="fas fa-calculator me-2 text-bleu-france"></i>
            Plafonds de l'avantage fiscal
        </h2>
        <p class="mb-4">
            L'amortissement fiscal annuel est plafonné selon le type de location choisi.
            Ces plafonds visent à concentrer l'avantage fiscal sur les investisseurs individuels modérés.
        </p>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card-gouv h-100 text-center">
                    <div class="card-body">
                        <div class="card-icon">
                            <i class="fas fa-percent"></i>
                        </div>
                        <h5>Loyer intermédiaire</h5>
                        <p class="display-6 text-bleu-france mb-2">8 000 €</p>
                        <p class="small text-muted mb-0">par an et par foyer fiscal</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card-gouv h-100 text-center">
                    <div class="card-body">
                        <div class="card-icon">
                            <i class="fas fa-percent"></i>
                        </div>
                        <h5>Loyer social</h5>
                        <p class="display-6 text-bleu-france mb-2">10 000 €</p>
                        <p class="small text-muted mb-0">par an et par foyer fiscal</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card-gouv h-100 text-center">
                    <div class="card-body">
                        <div class="card-icon">
                            <i class="fas fa-percent"></i>
                        </div>
                        <h5>Loyer très social</h5>
                        <p class="display-6 text-bleu-france mb-2">12 000 €</p>
                        <p class="small text-muted mb-0">par an et par foyer fiscal</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="info-box mt-4">
            <h5><i class="fas fa-calendar-alt me-2"></i>Période d'application</h5>
            <p class="mb-0">
                Le dispositif Jeanbrun s'applique aux logements neufs ou en VEFA acquis
                <strong>entre la date d'entrée en vigueur de la loi de finances 2026 et le 31 décembre 2028</strong>.
            </p>
        </div>
    </div>
</section>

<!-- Checklist statique -->
<section class="section section-alt">
    <div class="container">
        <h2 class="section-title text-center">Checklist d'éligibilité</h2>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card-gouv">
                    <div class="card-header">
                        <i class="fas fa-clipboard-check me-2"></i>
                        Vérifiez ces points avant d'investir
                    </div>
                    <div class="card-body">
                        <div class="form-check mb-3">
                            <input class="form-check-input checklist-item" type="checkbox" id="check1">
                            <label class="form-check-label" for="check1">
                                Le bien est situé dans un immeuble collectif en France
                            </label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input checklist-item" type="checkbox" id="check2">
                            <label class="form-check-label" for="check2">
                                Le bien est neuf OU ancien avec 30% de travaux minimum
                            </label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input checklist-item" type="checkbox" id="check3">
                            <label class="form-check-label" for="check3">
                                Acquisition entre 2026 et le 31 décembre 2028
                            </label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input checklist-item" type="checkbox" id="check4">
                            <label class="form-check-label" for="check4">
                                Je m'engage à louer pendant au moins 9 ans
                            </label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input checklist-item" type="checkbox" id="check5">
                            <label class="form-check-label" for="check5">
                                Le logement sera loué nu (non meublé)
                            </label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input checklist-item" type="checkbox" id="check6">
                            <label class="form-check-label" for="check6">
                                Le logement sera la résidence principale du locataire
                            </label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input checklist-item" type="checkbox" id="check7">
                            <label class="form-check-label" for="check7">
                                Je ne louerai pas à un membre de ma famille (ascendants, descendants)
                            </label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input checklist-item" type="checkbox" id="check8">
                            <label class="form-check-label" for="check8">
                                Je respecterai les plafonds de loyers applicables
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input checklist-item" type="checkbox" id="check9">
                            <label class="form-check-label" for="check9">
                                Le locataire respectera les plafonds de ressources
                            </label>
                        </div>

                        <!-- Message de succès -->
                        <div id="checklist-success" class="alert alert-success mt-4" style="display: none;">
                            <i class="fas fa-check-circle me-2"></i>
                            <strong>Parfait, vous êtes prêt.e à investir !</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
// Surveiller les changements sur la checklist
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.checklist-item');
    const successMessage = document.getElementById('checklist-success');

    function checkAllChecked() {
        const allChecked = Array.from(checkboxes).every(checkbox => checkbox.checked);

        if (allChecked) {
            successMessage.style.display = 'block';
            successMessage.style.animation = 'slideIn 0.3s ease-out';
        } else {
            successMessage.style.display = 'none';
        }
    }

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', checkAllChecked);
    });
});
</script>

<!-- CTA -->
<section class="section section-alt">
    <div class="container text-center">
        <h2 class="section-title text-center">Vous êtes éligible ?</h2>
        <p class="lead mb-4">
            Estimez maintenant les avantages fiscaux de votre projet d'investissement.
        </p>
        <a href="/simulation" class="btn-gouv btn-lg">
            <i class="fas fa-calculator me-2"></i>Accéder au simulateur
        </a>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
