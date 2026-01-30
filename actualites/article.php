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

// SEO
$current_page_var = 'actualites';
$page_title = !empty($article['meta']['seo_title']) ? $article['meta']['seo_title'] : $article['meta']['title'] . ' - Actualités Loi Jeanbrun';
$page_description = !empty($article['meta']['description']) ? $article['meta']['description'] : get_excerpt($article['content'], 160);

// Build full URL for Open Graph
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
$current_url = $protocol . $_SERVER['HTTP_HOST'] . get_article_url($article);
$og_image = '';
if (!empty($article['meta']['featured_image'])) {
    $og_image = $protocol . $_SERVER['HTTP_HOST'] . '/actualites/images/' . $article['meta']['featured_image'];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($page_description); ?>">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="article">
    <meta property="og:url" content="<?php echo htmlspecialchars($current_url); ?>">
    <meta property="og:title" content="<?php echo htmlspecialchars($article['meta']['title']); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($page_description); ?>">
    <?php if ($og_image): ?>
    <meta property="og:image" content="<?php echo htmlspecialchars($og_image); ?>">
    <?php endif; ?>

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="<?php echo htmlspecialchars($current_url); ?>">
    <meta property="twitter:title" content="<?php echo htmlspecialchars($article['meta']['title']); ?>">
    <meta property="twitter:description" content="<?php echo htmlspecialchars($page_description); ?>">
    <?php if ($og_image): ?>
    <meta property="twitter:image" content="<?php echo htmlspecialchars($og_image); ?>">
    <?php endif; ?>

    <!-- Canonical URL -->
    <link rel="canonical" href="<?php echo htmlspecialchars($current_url); ?>">

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="/assets/favicon.svg">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+3:wght@300;400;600;700&display=swap" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="/css/style.css">

    <!-- Schema.org JSON-LD -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Article",
        "headline": <?php echo json_encode($article['meta']['title']); ?>,
        "description": <?php echo json_encode($page_description); ?>,
        "image": <?php echo json_encode($og_image); ?>,
        "datePublished": "<?php echo $article['date']; ?>T09:00:00+01:00",
        "dateModified": "<?php echo !empty($article['meta']['modified']) ? $article['meta']['modified'] : $article['date']; ?>T09:00:00+01:00",
        "author": {
            "@type": "Organization",
            "name": "Loi Jeanbrun",
            "url": "<?php echo $protocol . $_SERVER['HTTP_HOST']; ?>"
        },
        "publisher": {
            "@type": "Organization",
            "name": "Loi Jeanbrun",
            "url": "<?php echo $protocol . $_SERVER['HTTP_HOST']; ?>",
            "logo": {
                "@type": "ImageObject",
                "url": "<?php echo $protocol . $_SERVER['HTTP_HOST']; ?>/assets/favicon.svg"
            }
        },
        "mainEntityOfPage": {
            "@type": "WebPage",
            "@id": <?php echo json_encode($current_url); ?>
        }
    }
    </script>

    <!-- Matomo -->
    <script>
      var _paq = window._paq = window._paq || [];
      _paq.push(['trackPageView']);
      _paq.push(['enableLinkTracking']);
      (function() {
        var u="https://monmatomo.com/";
        _paq.push(['setTrackerUrl', u+'matomo.php']);
        _paq.push(['setSiteId', '38']);
        var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
        g.async=true; g.src=u+'matomo.js'; s.parentNode.insertBefore(g,s);
      })();
    </script>
</head>
<body>
    <?php
    // Include navigation from main site
    $nav_html = file_get_contents(__DIR__ . '/../includes/header.php');
    // Extract only the nav section (after <body> tag)
    if (preg_match('/<body>(.*?)<main/s', $nav_html, $matches)) {
        echo $matches[1];
    }
    ?>

    <main>
        <article class="section">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 mx-auto">
                        <!-- Breadcrumb -->
                        <nav aria-label="breadcrumb" class="mb-4">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/">Accueil</a></li>
                                <li class="breadcrumb-item"><a href="/actualites">Actualités</a></li>
                                <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($article['meta']['title']); ?></li>
                            </ol>
                        </nav>

                        <!-- Article Header -->
                        <header class="mb-4">
                            <h1 class="display-5 mb-3"><?php echo htmlspecialchars($article['meta']['title']); ?></h1>
                            <div class="text-muted mb-4">
                                <i class="far fa-calendar me-2"></i>
                                <time datetime="<?php echo $article['date']; ?>">
                                    <?php echo format_date_fr($article['date']); ?>
                                </time>
                            </div>
                        </header>

                        <!-- Featured Image -->
                        <?php if (!empty($article['meta']['featured_image'])): ?>
                            <figure class="mb-4">
                                <img src="/actualites/images/<?php echo htmlspecialchars($article['meta']['featured_image']); ?>"
                                     alt="<?php echo htmlspecialchars($article['meta']['title']); ?>"
                                     class="img-fluid rounded">
                            </figure>
                        <?php endif; ?>

                        <!-- Article Content -->
                        <div class="article-content">
                            <?php echo $article['content']; ?>
                        </div>

                        <!-- Back to blog -->
                        <div class="mt-5 pt-4 border-top">
                            <a href="/actualites" class="btn btn-gouv-outline">
                                <i class="fas fa-arrow-left me-2"></i>
                                Retour aux actualités
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </article>
    </main>

    <?php include '../includes/footer.php'; ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
