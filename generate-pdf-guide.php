#!/usr/bin/env php
<?php
/**
 * Générateur de PDF pour le Guide Loi Jeanbrun
 *
 * Ce script convertit le template HTML en PDF en utilisant wkhtmltopdf
 * Si wkhtmltopdf n'est pas disponible, il génère une version HTML statique
 */

$templatePath = __DIR__ . '/docs/guide-loi-jeanbrun-template.html';
$pdfPath = __DIR__ . '/docs/guide-loi-jeanbrun-2026.pdf';

if (!file_exists($templatePath)) {
    die("❌ Erreur : Template HTML introuvable à $templatePath\n");
}

echo "📄 Génération du guide PDF Loi Jeanbrun...\n\n";

// Lire le template
$htmlContent = file_get_contents($templatePath);

// Vérifier si wkhtmltopdf est disponible
$wkhtmltopdfPath = trim(shell_exec('which wkhtmltopdf 2>/dev/null'));

if (!empty($wkhtmltopdfPath) && file_exists($wkhtmltopdfPath)) {
    echo "✅ wkhtmltopdf trouvé : $wkhtmltopdfPath\n";
    echo "🔄 Conversion HTML → PDF en cours...\n";

    // Options pour wkhtmltopdf
    $options = [
        '--page-size A4',
        '--margin-top 20mm',
        '--margin-bottom 20mm',
        '--margin-left 20mm',
        '--margin-right 20mm',
        '--encoding UTF-8',
        '--enable-local-file-access',
        '--no-outline',
        '--print-media-type',
        '--dpi 300',
        '--image-quality 95',
    ];

    $command = sprintf(
        '%s %s %s %s 2>&1',
        escapeshellcmd($wkhtmltopdfPath),
        implode(' ', $options),
        escapeshellarg($templatePath),
        escapeshellarg($pdfPath)
    );

    $output = [];
    $returnCode = 0;
    exec($command, $output, $returnCode);

    if ($returnCode === 0 && file_exists($pdfPath)) {
        $fileSize = filesize($pdfPath);
        $fileSizeMB = round($fileSize / 1024 / 1024, 2);

        echo "✅ PDF généré avec succès !\n";
        echo "   📁 Fichier : $pdfPath\n";
        echo "   📊 Taille : $fileSizeMB MB\n";
        echo "\n✨ Le guide est prêt à être téléchargé !\n";
    } else {
        echo "❌ Erreur lors de la génération du PDF\n";
        echo "Sortie : " . implode("\n", $output) . "\n";
        exit(1);
    }
} else {
    echo "⚠️  wkhtmltopdf non trouvé\n";
    echo "💡 Solution : Créer une copie HTML statique accessible en téléchargement\n\n";

    // Créer une version HTML téléchargeable
    $htmlDownloadPath = __DIR__ . '/docs/guide-loi-jeanbrun-2026.html';
    file_put_contents($htmlDownloadPath, $htmlContent);

    echo "✅ Version HTML créée : $htmlDownloadPath\n";
    echo "\n📌 Pour générer le PDF, installez wkhtmltopdf :\n";
    echo "   Ubuntu/Debian: sudo apt-get install wkhtmltopdf\n";
    echo "   MacOS: brew install wkhtmltopdf\n";
    echo "   CentOS/RHEL: sudo yum install wkhtmltopdf\n";
}

echo "\n🎉 Terminé !\n";
