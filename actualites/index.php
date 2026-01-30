<?php
require_once 'functions.php';

// Get current page from URL
$current_page = 1;
if (isset($_GET['page'])) {
    $current_page = max(1, intval($_GET['page']));
}

// Pagination settings
$per_page = 6;
$offset = ($current_page - 1) * $per_page;

// Get articles
$articles = get_articles($per_page, $offset);
$total_articles = get_article_count();

// SEO
$current_page_var = 'actualites';
$page_title = 'Actualités - Loi Jeanbrun';
$page_description = 'Suivez toutes les actualités et dernières informations sur la loi Jeanbrun et le logement intermédiaire en France.';

if ($current_page > 1) {
    $page_title = 'Actualités - Page ' . $current_page . ' - Loi Jeanbrun';
}

include '../includes/header.php';
?>

<div class="page-header" style="background: linear-gradient(135deg, var(--bleu-france) 0%, #1212FF 100%);">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center text-white">
                <h1>Actualités</h1>
                <p class="lead">Suivez les dernières informations sur la loi Jeanbrun</p>
            </div>
        </div>
    </div>
</div>

<section class="section">
    <div class="container">
        <?php if (empty($articles)): ?>
            <div class="alert alert-info text-center">
                <h3>Aucun article publié pour le moment</h3>
                <p>Revenez bientôt pour découvrir nos actualités sur la loi Jeanbrun.</p>
            </div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($articles as $article): ?>
                    <div class="col-md-6 col-lg-4">
                        <article class="card card-gouv h-100">
                            <?php if (!empty($article['meta']['featured_image'])): ?>
                                <a href="<?php echo get_article_url($article); ?>">
                                    <img src="/actualites/images/<?php echo htmlspecialchars($article['meta']['featured_image']); ?>"
                                         class="card-img-top"
                                         alt="<?php echo htmlspecialchars($article['meta']['title']); ?>"
                                         style="height: 200px; object-fit: cover;">
                                </a>
                            <?php endif; ?>
                            <div class="card-body d-flex flex-column">
                                <div class="text-muted small mb-2">
                                    <i class="far fa-calendar"></i>
                                    <?php echo format_date_fr($article['date']); ?>
                                </div>
                                <h3 class="h5 card-title">
                                    <a href="<?php echo get_article_url($article); ?>" class="text-decoration-none">
                                        <?php echo htmlspecialchars($article['meta']['title']); ?>
                                    </a>
                                </h3>
                                <?php if (!empty($article['meta']['description'])): ?>
                                    <p class="card-text text-muted">
                                        <?php echo htmlspecialchars($article['meta']['description']); ?>
                                    </p>
                                <?php else: ?>
                                    <p class="card-text text-muted">
                                        <?php echo get_excerpt($article['content'], 150); ?>
                                    </p>
                                <?php endif; ?>
                                <a href="<?php echo get_article_url($article); ?>" class="btn btn-gouv-outline mt-auto">
                                    Lire la suite <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </article>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php if ($total_articles > $per_page): ?>
                <div class="mt-5">
                    <?php echo get_pagination($current_page, $total_articles, $per_page, '/actualites'); ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</section>

<?php include '../includes/footer.php'; ?>
