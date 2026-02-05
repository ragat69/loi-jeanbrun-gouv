#!/usr/bin/env php
<?php
/**
 * Générateur de PDF pour le Guide Loi Jeanbrun
 * Utilise Google Chrome en mode headless pour la conversion HTML → PDF
 */

$templatePath = __DIR__ . '/docs/guide-loi-jeanbrun-template.html';
$pdfPath = __DIR__ . '/docs/guide-loi-jeanbrun-2026.pdf';

if (!file_exists($templatePath)) {
    die("❌ Erreur : Template HTML introuvable à $templatePath\n");
}

echo "📄 Génération du guide PDF Loi Jeanbrun avec Chrome...\n\n";

// Trouver le chemin de Chrome
$chromePaths = [
    '/usr/bin/google-chrome',
    '/usr/bin/chromium',
    '/usr/bin/chromium-browser',
    '/usr/bin/chrome',
];

$chromePath = null;
foreach ($chromePaths as $path) {
    if (file_exists($path)) {
        $chromePath = $path;
        break;
    }
}

if (!$chromePath) {
    die("❌ Erreur : Chrome/Chromium introuvable\n");
}

echo "✅ Chrome trouvé : $chromePath\n";
echo "🔄 Conversion HTML → PDF en cours...\n";

// Préparer l'URL du fichier
$fileUrl = 'file://' . realpath($templatePath);

// Commande Chrome headless pour générer le PDF
$command = sprintf(
    '%s --headless --disable-gpu --no-sandbox --print-to-pdf=%s --print-to-pdf-no-header %s 2>&1',
    escapeshellcmd($chromePath),
    escapeshellarg($pdfPath),
    escapeshellarg($fileUrl)
);

$output = [];
$returnCode = 0;
exec($command, $output, $returnCode);

if (file_exists($pdfPath)) {
    $fileSize = filesize($pdfPath);
    $fileSizeMB = round($fileSize / 1024 / 1024, 2);

    echo "✅ PDF généré avec succès !\n";
    echo "   📁 Fichier : $pdfPath\n";
    echo "   📊 Taille : $fileSizeMB MB\n";
    echo "\n✨ Le guide est prêt à être téléchargé !\n";
} else {
    echo "❌ Erreur lors de la génération du PDF\n";
    if (!empty($output)) {
        echo "Sortie : " . implode("\n", $output) . "\n";
    }
    exit(1);
}

echo "\n🎉 Terminé !\n";
