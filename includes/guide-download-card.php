<!-- Encart Téléchargement Guide Loi Jeanbrun -->
<div class="guide-download-wrapper">
    <div class="guide-download-card">
        <div class="container">
            <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="guide-content">
                    <div class="guide-icon">
                        <i class="fas fa-file-pdf"></i>
                    </div>
                    <div class="guide-text">
                        <h3>Guide Complet du Dispositif Jeanbrun 2026</h3>
                        <p class="guide-description">
                            Téléchargez gratuitement notre <a href="/guide-dispositif-jeanbrun" class="guide-link">guide PDF</a> de 40 pages pour tout comprendre
                            sur le dispositif Relance Logement : fonctionnement, avantages fiscaux, conditions d'éligibilité,
                            exemples chiffrés et FAQ.
                        </p>
                        <ul class="guide-features">
                            <li><i class="fas fa-check-circle"></i> Mécanismes d'amortissement détaillés</li>
                            <li><i class="fas fa-check-circle"></i> Exemples de calculs concrets</li>
                            <li><i class="fas fa-check-circle"></i> Tableau comparatif Pinel vs Jeanbrun</li>
                            <li><i class="fas fa-check-circle"></i> FAQ complète</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 text-center">
                <div class="guide-cta">
                    <img src="/images/guide-loi-jeanbrun-pdf.png"
                         alt="Guide Loi Jeanbrun 2026 - PDF Gratuit"
                         class="guide-image"
                         loading="lazy">
                    <a href="/docs/guide-loi-jeanbrun-2026.pdf"
                       class="btn-download"
                       download="Guide-Loi-Jeanbrun-2026.pdf"
                       onclick="trackGuideDownload()"
                       target="_blank">
                        <i class="fas fa-download me-2"></i>
                        Télécharger le guide
                    </a>
                    <p class="guide-note">
                        <i class="fas fa-lock me-1"></i>
                        Aucune inscription requise
                    </p>
                </div>
            </div>
            </div>
        </div>
    </div>
</div>

<style>
.guide-download-wrapper {
    margin: 4rem 0;
    display: flex;
    justify-content: center;
}

.guide-download-card {
    background: linear-gradient(135deg, #000091 0%, #1212FF 100%);
    color: white;
    padding: 3rem 2rem;
    border-radius: 12px;
    box-shadow: 0 10px 40px rgba(0, 0, 145, 0.2);
    max-width: 1200px;
    width: 100%;
}

.guide-content {
    display: flex;
    gap: 1.5rem;
    align-items: flex-start;
}

.guide-icon {
    font-size: 3.5rem;
    color: rgba(255, 255, 255, 0.9);
    flex-shrink: 0;
}

.guide-text h3 {
    color: white;
    font-size: 1.75rem;
    margin-bottom: 1rem;
    font-weight: 700;
}

.guide-description {
    font-size: 1rem;
    line-height: 1.6;
    opacity: 0.95;
    margin-bottom: 1.5rem;
}

.guide-link {
    color: white;
    text-decoration: underline;
    font-weight: 600;
    transition: opacity 0.3s ease;
}

.guide-link:hover {
    color: white;
    opacity: 0.8;
    text-decoration: underline;
}

.guide-features {
    list-style: none;
    padding: 0;
    margin: 0;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 0.75rem;
}

.guide-features li {
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.guide-features i {
    color: #4ade80;
    font-size: 1.1rem;
}

.guide-cta {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    padding: 2rem 1.5rem;
    backdrop-filter: blur(10px);
}

.guide-image {
    width: 100%;
    max-width: 350px;
    height: auto;
    margin-bottom: 1.5rem;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.btn-download {
    display: inline-block;
    background: white;
    color: #000091;
    padding: 1rem 2rem;
    border-radius: 8px;
    font-weight: 700;
    font-size: 1.1rem;
    text-decoration: none;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    width: 100%;
    text-align: center;
}

.btn-download:hover {
    background: #f0f0f0;
    color: #000091;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
}

.btn-download i {
    transition: transform 0.3s ease;
}

.btn-download:hover i {
    transform: translateY(2px);
}

.guide-note {
    margin-top: 1rem;
    font-size: 0.85rem;
    opacity: 0.8;
    margin-bottom: 0;
}

/* Responsive */
@media (max-width: 991px) {
    .guide-download-wrapper {
        margin: 2rem 1rem;
    }

    .guide-download-card {
        padding: 2rem 1rem;
    }

    .guide-content {
        flex-direction: column;
        text-align: center;
    }

    .guide-icon {
        font-size: 2.5rem;
    }

    .guide-text h3 {
        font-size: 1.5rem;
    }

    .guide-features {
        grid-template-columns: 1fr;
        text-align: left;
    }

    .guide-cta {
        margin-top: 2rem;
    }

    .guide-image {
        max-width: 280px;
    }
}
</style>

<script>
function trackGuideDownload() {
    // Analytics tracking (Google Analytics, Matomo, etc.)
    if (typeof gtag !== 'undefined') {
        gtag('event', 'download', {
            'event_category': 'Guide',
            'event_label': 'Guide Loi Jeanbrun 2026',
            'value': 1
        });
    }

    // Afficher une notification de succès (optionnel)
    console.log('Guide téléchargé avec succès');
}
</script>
