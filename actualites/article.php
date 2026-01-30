<?php
require_once 'functions.php';

// Get article from URL parameters
$date = $_GET['date'] ?? '';
$slug = $_GET['slug'] ?? '';

if (empty($date) || empty($slug)) {
    header('HTTP/1.0 404 Not Found');
    include '../404.php';
    exit;
}

$article = get_article_by_slug($date, $slug);

if (!$article || ($article['meta']['status'] ?? 'published') !== 'published') {
    header('HTTP/1.0 404 Not Found');
    include '../404.php';
    exit;
}

// SEO - Variables pour le header
$current_page = 'actualites';
$page_title_full = !empty($article['meta']['seo_title']) ? $article['meta']['seo_title'] : $article['meta']['title'] . ' - Actualités Loi Jeanbrun';
$page_description = !empty($article['meta']['description']) ? $article['meta']['description'] : get_excerpt($article['content'], 160);

// Build full URL for Open Graph
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
$current_url = $protocol . $_SERVER['HTTP_HOST'] . get_article_url($article);

// Open Graph variables
$og_type = 'article';
$og_url = $current_url;
$og_title = $article['meta']['title'];
$og_description = $page_description;
$og_image = '';
if (!empty($article['meta']['featured_image'])) {
    $og_image = $protocol . $_SERVER['HTTP_HOST'] . '/actualites/img/' . $article['meta']['featured_image'];
}

// Twitter Card
$twitter_card = 'summary_large_image';

// Canonical URL
$canonical_url = $current_url;

// Schema.org JSON-LD
$schema_data = [
    '@context' => 'https://schema.org',
    '@type' => 'Article',
    'headline' => $article['meta']['title'],
    'description' => $page_description,
    'image' => $og_image,
    'datePublished' => $article['date'] . 'T09:00:00+01:00',
    'dateModified' => (!empty($article['meta']['modified']) ? $article['meta']['modified'] : $article['date']) . 'T09:00:00+01:00',
    'author' => [
        '@type' => 'Organization',
        'name' => 'Loi Jeanbrun',
        'url' => $protocol . $_SERVER['HTTP_HOST']
    ],
    'publisher' => [
        '@type' => 'Organization',
        'name' => 'Loi Jeanbrun',
        'url' => $protocol . $_SERVER['HTTP_HOST'],
        'logo' => [
            '@type' => 'ImageObject',
            'url' => $protocol . $_SERVER['HTTP_HOST'] . '/assets/favicon.svg'
        ]
    ],
    'mainEntityOfPage' => [
        '@type' => 'WebPage',
        '@id' => $current_url
    ]
];
$schema_json = json_encode($schema_data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

include '../includes/header.php';
?>

<main>
    <!-- Breadcrumb -->
    <nav class="breadcrumb-gouv">
        <div class="container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Accueil</a></li>
                <li class="breadcrumb-item"><a href="/actualites">Actualités</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($article['meta']['title']); ?></li>
            </ol>
        </div>
    </nav>

    <article class="section">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 mx-auto">
                    <!-- Featured Image (au-dessus du titre) -->
                    <?php if (!empty($article['meta']['featured_image'])): ?>
                        <figure class="mb-4">
                            <img src="/actualites/img/<?php echo htmlspecialchars($article['meta']['featured_image']); ?>"
                                 alt="<?php echo htmlspecialchars($article['meta']['title']); ?>"
                                 class="img-fluid rounded"
                                 style="max-width: 800px; width: 100%; height: auto; display: block; margin: 0 auto;">
                        </figure>
                    <?php endif; ?>

                    <!-- Article Header -->
                    <header class="mb-4">
                        <h1 class="section-title"><?php echo htmlspecialchars($article['meta']['title']); ?></h1>
                        <div class="text-muted mb-3">
                            <i class="far fa-calendar me-2"></i>
                            <time datetime="<?php echo $article['date']; ?>">
                                <?php echo format_date_fr($article['date']); ?>
                            </time>
                        </div>
                    </header>

                    <!-- Article Content -->
                    <div class="article-content">
                        <?php echo $article['content']; ?>
                    </div>

                    <!-- Previous/Next Navigation -->
                    <?php
                    $prev_article = get_previous_article($article);
                    $next_article = get_next_article($article);
                    if ($prev_article || $next_article):
                    ?>
                    <div class="mt-5 pt-4 border-top">
                        <div class="row">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <?php if ($prev_article): ?>
                                    <a href="<?php echo get_article_url($prev_article); ?>" class="text-decoration-none">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-arrow-left me-2 text-muted"></i>
                                            <div>
                                                <div class="small text-muted">Article précédent</div>
                                                <div class="text-primary"><?php echo htmlspecialchars($prev_article['meta']['title']); ?></div>
                                            </div>
                                        </div>
                                    </a>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <?php if ($next_article): ?>
                                    <a href="<?php echo get_article_url($next_article); ?>" class="text-decoration-none">
                                        <div class="d-flex align-items-center justify-content-md-end">
                                            <div class="text-end me-2">
                                                <div class="small text-muted">Article suivant</div>
                                                <div class="text-primary"><?php echo htmlspecialchars($next_article['meta']['title']); ?></div>
                                            </div>
                                            <i class="fas fa-arrow-right text-muted"></i>
                                        </div>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Back to blog -->
                    <div class="mt-4 pt-4 border-top">
                        <a href="/actualites" class="btn btn-gouv-outline">
                            <i class="fas fa-arrow-left me-2"></i>
                            Retour aux actualités
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </article>

    <!-- FAQ Schema.org JSON-LD -->
    <?php if (!empty($article['meta']['faq_schema'])): ?>
    <script type="application/ld+json">
    <?php echo trim($article['meta']['faq_schema']); ?>
    </script>
    <?php endif; ?>
</main>

<script>
// Transform FAQ section into Bootstrap accordion
document.addEventListener('DOMContentLoaded', function() {
    const articleContent = document.querySelector('.article-content');
    if (!articleContent) return;

    // Find the FAQ heading (h2 containing "Questions fréquentes" or "FAQ")
    const headings = articleContent.querySelectorAll('h2');
    let faqHeading = null;

    headings.forEach(h2 => {
        if (h2.textContent.includes('Questions fréquentes') || h2.textContent.includes('FAQ')) {
            faqHeading = h2;
        }
    });

    if (!faqHeading) return;

    // Add faq-section class to the heading
    faqHeading.classList.add('faq-section');

    // Collect all FAQ items (question + answer pairs)
    const faqItems = [];
    let currentElement = faqHeading.nextElementSibling;
    let questionElement = null;

    while (currentElement && currentElement.tagName !== 'H2') {
        if (currentElement.tagName === 'P') {
            const strongElement = currentElement.querySelector('strong');

            // Check if this paragraph starts with a bold text (question)
            if (strongElement && currentElement.firstChild === strongElement) {
                // This is a question
                questionElement = currentElement;
            } else if (questionElement) {
                // This is an answer following a question
                faqItems.push({
                    question: questionElement.textContent,
                    answer: currentElement.innerHTML
                });
                questionElement = null;
            }
        }

        const nextEl = currentElement.nextElementSibling;
        if (currentElement.tagName === 'P') {
            currentElement.remove();
        }
        currentElement = nextEl;
    }

    // Create Bootstrap accordion
    if (faqItems.length > 0) {
        const accordion = document.createElement('div');
        accordion.className = 'accordion accordion-gouv mt-4';
        accordion.id = 'faqAccordion';

        faqItems.forEach((item, index) => {
            const accordionItem = document.createElement('div');
            accordionItem.className = 'accordion-item';

            const headerId = `faqHeading${index}`;
            const collapseId = `faqCollapse${index}`;

            accordionItem.innerHTML = `
                <h3 class="accordion-header" id="${headerId}">
                    <button class="accordion-button ${index === 0 ? '' : 'collapsed'}" type="button"
                            data-bs-toggle="collapse" data-bs-target="#${collapseId}"
                            aria-expanded="${index === 0 ? 'true' : 'false'}" aria-controls="${collapseId}">
                        ${item.question}
                    </button>
                </h3>
                <div id="${collapseId}" class="accordion-collapse collapse ${index === 0 ? 'show' : ''}"
                     aria-labelledby="${headerId}" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        ${item.answer}
                    </div>
                </div>
            `;

            accordion.appendChild(accordionItem);
        });

        faqHeading.parentNode.insertBefore(accordion, faqHeading.nextSibling);
    }
});
</script>

<?php include '../includes/footer.php'; ?>
