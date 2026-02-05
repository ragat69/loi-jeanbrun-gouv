#!/usr/bin/env php
<?php
/**
 * Générateur de Sitemaps pour loi-jeanbrun-gouv.com
 *
 * Génère automatiquement :
 * - sitemap_index.xml (index principal)
 * - sitemap_main.xml (pages statiques)
 * - sitemap_blog.xml (articles blog)
 * - sitemap_cities_*.xml (pages villes, par chunks de 10000)
 */

define('BASE_URL', 'https://loi-jeanbrun-gouv.com');
define('SITE_ROOT', __DIR__);

// Configuration
$staticPages = [
    '' => ['priority' => '1.0', 'changefreq' => 'weekly'],
    'fonctionnement' => ['priority' => '0.9', 'changefreq' => 'monthly'],
    'avantages' => ['priority' => '0.9', 'changefreq' => 'monthly'],
    'simulation' => ['priority' => '0.9', 'changefreq' => 'monthly'],
    'eligibilite' => ['priority' => '0.8', 'changefreq' => 'monthly'],
    'bailleur-prive' => ['priority' => '0.8', 'changefreq' => 'monthly'],
    'pinel-vs-jeanbrun' => ['priority' => '0.7', 'changefreq' => 'monthly'],
    'vincent-jeanbrun' => ['priority' => '0.6', 'changefreq' => 'yearly'],
    'mentions-legales' => ['priority' => '0.3', 'changefreq' => 'yearly'],
];

echo "🗺️  Génération des sitemaps pour " . BASE_URL . "\n\n";

// ============================================
// 1. SITEMAP PAGES STATIQUES
// ============================================
echo "📄 Génération sitemap_main.xml...\n";

$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"></urlset>');

foreach ($staticPages as $page => $config) {
    $url = $xml->addChild('url');
    $url->addChild('loc', BASE_URL . ($page ? '/' . $page : ''));
    $url->addChild('lastmod', date('Y-m-d'));
    $url->addChild('changefreq', $config['changefreq']);
    $url->addChild('priority', $config['priority']);
}

$dom = new DOMDocument('1.0', 'UTF-8');
$dom->preserveWhiteSpace = false;
$dom->formatOutput = true;
$dom->loadXML($xml->asXML());
$dom->save(SITE_ROOT . '/sitemap_main.xml');

echo "   ✅ " . count($staticPages) . " pages statiques\n";

// ============================================
// 2. SITEMAP BLOG
// ============================================
echo "\n📰 Génération sitemap_blog.xml...\n";

$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"></urlset>');

$blogPosts = glob(SITE_ROOT . '/actualites/posts/*.md');
$blogCount = 0;

foreach ($blogPosts as $postFile) {
    $filename = basename($postFile, '.md');

    // Parse front matter pour obtenir la date
    $content = file_get_contents($postFile);
    if (preg_match('/^---\s*\n.*?date:\s*(\d{4}-\d{2}-\d{2})/s', $content, $matches)) {
        $date = $matches[1];
        $slug = preg_replace('/^\d{4}-\d{2}-\d{2}-/', '', $filename);

        $url = $xml->addChild('url');
        $url->addChild('loc', BASE_URL . '/actualites/' . $date . '/' . $slug);
        $url->addChild('lastmod', $date);
        $url->addChild('changefreq', 'monthly');
        $url->addChild('priority', '0.7');
        $blogCount++;
    }
}

$dom = new DOMDocument('1.0', 'UTF-8');
$dom->preserveWhiteSpace = false;
$dom->formatOutput = true;
$dom->loadXML($xml->asXML());
$dom->save(SITE_ROOT . '/sitemap_blog.xml');

echo "   ✅ " . $blogCount . " articles blog\n";

// ============================================
// 3. SITEMAPS VILLES (par chunks)
// ============================================
echo "\n🏙️  Génération sitemaps villes...\n";

$cityFiles = glob(SITE_ROOT . '/ville/loi-jeanbrun-*.php');
$totalCities = count($cityFiles);
$chunkSize = 10000;
$chunks = array_chunk($cityFiles, $chunkSize);

$citySitemaps = [];

foreach ($chunks as $chunkIndex => $chunk) {
    $chunkNumber = $chunkIndex + 1;
    $sitemapFile = 'sitemap_cities_' . $chunkNumber . '.xml';

    echo "   Génération $sitemapFile (" . count($chunk) . " villes)...\n";

    $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"></urlset>');

    foreach ($chunk as $cityFile) {
        $filename = basename($cityFile, '.php');
        // Extraire le slug de "loi-jeanbrun-paris-75000.php" -> "paris-75000"
        $slug = str_replace('loi-jeanbrun-', '', $filename);

        $url = $xml->addChild('url');
        $url->addChild('loc', BASE_URL . '/ville/' . $slug);
        $url->addChild('lastmod', date('Y-m-d'));
        $url->addChild('changefreq', 'monthly');
        $url->addChild('priority', '0.6');
    }

    $dom = new DOMDocument('1.0', 'UTF-8');
    $dom->preserveWhiteSpace = false;
    $dom->formatOutput = true;
    $dom->loadXML($xml->asXML());
    $dom->save(SITE_ROOT . '/' . $sitemapFile);

    $citySitemaps[] = $sitemapFile;
}

echo "   ✅ " . $totalCities . " pages villes dans " . count($chunks) . " fichiers\n";

// ============================================
// 4. INDEX PRINCIPAL
// ============================================
echo "\n📑 Génération sitemap_index.xml...\n";

$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"></sitemapindex>');

// Ajouter tous les sitemaps
$allSitemaps = array_merge(['sitemap_main.xml', 'sitemap_blog.xml'], $citySitemaps);

foreach ($allSitemaps as $sitemap) {
    $sitemapNode = $xml->addChild('sitemap');
    $sitemapNode->addChild('loc', BASE_URL . '/' . $sitemap);
    $sitemapNode->addChild('lastmod', date('c'));
}

$dom = new DOMDocument('1.0', 'UTF-8');
$dom->preserveWhiteSpace = false;
$dom->formatOutput = true;
$dom->loadXML($xml->asXML());
$dom->save(SITE_ROOT . '/sitemap_index.xml');

echo "   ✅ Index avec " . count($allSitemaps) . " sitemaps\n";

// ============================================
// RÉSUMÉ
// ============================================
echo "\n" . str_repeat("=", 60) . "\n";
echo "✅ GÉNÉRATION TERMINÉE\n";
echo str_repeat("=", 60) . "\n";
echo "Pages statiques : " . count($staticPages) . "\n";
echo "Articles blog   : " . $blogCount . "\n";
echo "Pages villes    : " . $totalCities . "\n";
echo "Total URLs      : " . (count($staticPages) + $blogCount + $totalCities) . "\n";
echo "\nFichiers générés :\n";
echo "  - sitemap_index.xml (index principal)\n";
echo "  - sitemap_main.xml\n";
echo "  - sitemap_blog.xml\n";
foreach ($citySitemaps as $citySitemap) {
    echo "  - $citySitemap\n";
}
echo "\nURL du sitemap : " . BASE_URL . "/sitemap_index.xml\n";
echo str_repeat("=", 60) . "\n";
