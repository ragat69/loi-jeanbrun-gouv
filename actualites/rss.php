<?php
require_once 'functions.php';

// Configuration
$site_url = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
$site_url .= $_SERVER['HTTP_HOST'];
$blog_url = $site_url . '/actualites';

// Récupérer les 20 derniers articles
$articles = get_articles(20, 0);

// Générer le XML
header('Content-Type: application/rss+xml; charset=UTF-8');

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:dc="http://purl.org/dc/elements/1.1/">
    <channel>
        <title>Actualités - Loi Jeanbrun</title>
        <link><?php echo htmlspecialchars($blog_url); ?></link>
        <description>Suivez toutes les actualités et dernières informations sur la loi Jeanbrun et le logement intermédiaire en France.</description>
        <language>fr-FR</language>
        <lastBuildDate><?php echo date('r'); ?></lastBuildDate>
        <atom:link href="<?php echo htmlspecialchars($site_url . '/actualites/rss.php'); ?>" rel="self" type="application/rss+xml"/>
        <image>
            <url><?php echo htmlspecialchars($site_url . '/assets/favicon.svg'); ?></url>
            <title>Loi Jeanbrun</title>
            <link><?php echo htmlspecialchars($blog_url); ?></link>
        </image>

        <?php foreach ($articles as $article): ?>
        <item>
            <title><?php echo htmlspecialchars($article['meta']['title']); ?></title>
            <link><?php echo htmlspecialchars($site_url . get_article_url($article)); ?></link>
            <guid><?php echo htmlspecialchars($site_url . get_article_url($article)); ?></guid>
            <pubDate><?php echo date('r', strtotime($article['date'])); ?></pubDate>
            <dc:creator>Loi Jeanbrun</dc:creator>
            <?php if (!empty($article['meta']['description'])): ?>
            <description><![CDATA[<?php echo $article['meta']['description']; ?>]]></description>
            <?php else: ?>
            <description><![CDATA[<?php echo get_excerpt($article['content'], 200); ?>]]></description>
            <?php endif; ?>
            <?php if (!empty($article['meta']['featured_image'])): ?>
            <enclosure url="<?php echo htmlspecialchars($site_url . '/actualites/img/' . $article['meta']['featured_image']); ?>" type="image/jpeg"/>
            <?php endif; ?>
        </item>
        <?php endforeach; ?>

    </channel>
</rss>
