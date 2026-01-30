<?php
/**
 * Script pour ajouter une image à la une à un article via Google Images
 * Usage: php add-image-to-article.php [article-filename]
 */

if ($argc < 2) {
    die("Usage: php add-image-to-article.php [article-filename]\n");
}

$article_filename = $argv[1];

$posts_dir = __DIR__ . '/posts/';
$img_dir = __DIR__ . '/img/';
$article_path = $posts_dir . $article_filename . '.md';

if (!file_exists($article_path)) {
    die("❌ Article not found: $article_path\n");
}

// Lire l'article pour générer automatiquement les mots-clés
echo "📖 Lecture de l'article...\n";
$article_content = file_get_contents($article_path);

// Extraire le front matter
if (!preg_match('/^---\n(.*?)\n---\n(.*)$/s', $article_content, $matches)) {
    die("❌ Front matter non trouvé\n");
}

$front_matter = $matches[1];
$content = $matches[2];

// Parser le front matter
$meta = [];
foreach (explode("\n", $front_matter) as $line) {
    if (strpos($line, ':') !== false) {
        list($key, $value) = explode(':', $line, 2);
        $meta[trim($key)] = trim($value);
    }
}

// Générer automatiquement les mots-clés de recherche
$search_query = generate_search_keywords($meta, $content);
echo "🔍 Mots-clés générés: $search_query\n";

// Rechercher sur Google Images
echo "🔍 Recherche sur Google Images...\n";
$image_urls = search_google_images($search_query);

if (empty($image_urls)) {
    die("❌ Aucune image trouvée\n");
}

echo "✓ " . count($image_urls) . " image(s) trouvée(s)\n";

// Télécharger les 5 premières images candidates
echo "⬇️  Téléchargement des images candidates...\n";
$candidates = download_candidates($image_urls, 5);

if (empty($candidates)) {
    die("❌ Aucune image téléchargée avec succès\n");
}

echo "✓ " . count($candidates) . " image(s) téléchargée(s)\n";

// Choisir la meilleure image
echo "🎯 Analyse et sélection de la meilleure image...\n";
$best_image = select_best_image($candidates, $img_dir);

if (!$best_image) {
    // Nettoyer les fichiers temporaires
    foreach ($candidates as $candidate) {
        if (file_exists($candidate['temp_file'])) {
            unlink($candidate['temp_file']);
        }
    }
    die("❌ Aucune image valide trouvée\n");
}

echo "✅ Meilleure image sélectionnée: {$best_image['width']}x{$best_image['height']} ({$best_image['score']} pts)\n";

// Redimensionner si nécessaire
echo "🔄 Traitement final...\n";
$resized_file = resize_image($best_image['temp_file'], 1200);

if (!$resized_file) {
    // Nettoyer
    foreach ($candidates as $candidate) {
        if (file_exists($candidate['temp_file'])) {
            unlink($candidate['temp_file']);
        }
    }
    die("❌ Échec du redimensionnement\n");
}

// Renommer avec le nom de l'article
$final_filename = $article_filename . '.jpg';
$final_path = $img_dir . $final_filename;

if (file_exists($final_path)) {
    unlink($final_path);
}

rename($resized_file, $final_path);

// Nettoyer tous les fichiers temporaires
foreach ($candidates as $candidate) {
    if (file_exists($candidate['temp_file'])) {
        unlink($candidate['temp_file']);
    }
}

echo "✅ Image sauvegardée: $final_filename\n";

// Mettre à jour l'article
echo "📝 Mise à jour de l'article...\n";
update_article_featured_image($article_path, $final_filename);

echo "\n🎉 Terminé!\n";
echo "Image ajoutée à l'article: /actualites/img/$final_filename\n";

// ============================================================================
// FONCTIONS
// ============================================================================

/**
 * Génère automatiquement les mots-clés de recherche à partir de l'article
 */
function generate_search_keywords($meta, $content) {
    $title = $meta['title'] ?? '';
    $description = $meta['description'] ?? '';

    // Mots-clés spécifiques au contexte (ordre de priorité: du plus spécifique au plus général)
    $title_lower = mb_strtolower($title);
    $desc_lower = mb_strtolower($description);
    $combined = $title_lower . ' ' . $desc_lower;

    // Thèmes spécifiques (prioritaires)
    if (strpos($combined, 'classe') !== false && strpos($combined, 'moyenne') !== false) {
        return 'classes moyennes logement france';
    } elseif (strpos($combined, 'investisseur') !== false || strpos($combined, 'investissement') !== false) {
        return 'investissement immobilier locatif france';
    } elseif (strpos($combined, 'comparaison') !== false || strpos($combined, 'pinel') !== false) {
        return 'loi pinel vs jeanbrun immobilier france';
    } elseif (strpos($combined, 'avantage') !== false && strpos($combined, 'fiscal') !== false) {
        return 'avantage fiscal immobilier france';
    } elseif (strpos($combined, 'réduction') !== false && strpos($combined, 'impôt') !== false) {
        return 'réduction impot immobilier france';
    } elseif (strpos($combined, 'lancement') !== false) {
        return 'nouveau dispositif logement france';
    } elseif (strpos($combined, 'bailleur') !== false || strpos($combined, 'propriétaire') !== false) {
        return 'bailleur privé logement france';
    }
    // Thèmes généraux (par défaut)
    elseif (strpos($combined, 'dispositif') !== false) {
        return 'dispositif logement intermédiaire france';
    } else {
        return 'logement intermédiaire france immobilier';
    }
}

/**
 * Recherche des images sur Google Images et retourne les URLs
 */
function search_google_images($query) {
    $query_encoded = urlencode($query);

    // URL de recherche Google Images avec filtres pour des images de qualité
    $url = "https://www.google.com/search?q=" . $query_encoded .
           "&tbm=isch&tbs=isz:l,itp:photo"; // isz:l = large images, itp:photo = photos only

    // User agent pour éviter d'être bloqué
    $user_agent = "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36";

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);

    $html = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code !== 200 || !$html) {
        echo "⚠️  Erreur HTTP $http_code lors de la recherche\n";
        return [];
    }

    // Extraire les URLs des images du HTML
    $images = [];

    // Méthode 1: Chercher dans les balises img
    if (preg_match_all('/"(https?:\/\/[^"]+\.(?:jpg|jpeg|png|webp)[^"]*)"/', $html, $matches)) {
        foreach ($matches[1] as $img_url) {
            // Filtrer les URLs qui ne sont pas des miniatures Google
            if (strpos($img_url, 'gstatic.com') === false &&
                strpos($img_url, 'googleusercontent.com') === false &&
                strlen($img_url) > 20) {
                $images[] = $img_url;
            }
        }
    }

    // Méthode 2: Chercher dans le JavaScript (plus fiable)
    if (preg_match_all('/\["(https?:\/\/[^"]+\.(?:jpg|jpeg|png|webp)[^"]*)",\d+,\d+\]/', $html, $matches)) {
        foreach ($matches[1] as $img_url) {
            if (strpos($img_url, 'gstatic.com') === false &&
                strpos($img_url, 'googleusercontent.com') === false) {
                $images[] = $img_url;
            }
        }
    }

    // Dédupliquer
    $images = array_unique($images);

    return array_values($images);
}

/**
 * Télécharge les N premières images candidates
 */
function download_candidates($image_urls, $max_count = 5) {
    $candidates = [];
    $count = 0;

    foreach ($image_urls as $index => $img_url) {
        if ($count >= $max_count) {
            break;
        }

        echo "  Image #" . ($index + 1) . ": " . substr($img_url, 0, 60) . "...\n";

        // Télécharger l'image
        $image_data = download_image($img_url);

        if (!$image_data) {
            echo "    ⚠️  Échec du téléchargement\n";
            continue;
        }

        // Sauvegarder temporairement
        $temp_file = sys_get_temp_dir() . '/candidate_' . uniqid() . '.jpg';
        file_put_contents($temp_file, $image_data);

        // Vérifier les dimensions
        $info = @getimagesize($temp_file);
        if (!$info) {
            echo "    ⚠️  Image invalide\n";
            unlink($temp_file);
            continue;
        }

        list($width, $height, $type) = $info;
        $filesize = strlen($image_data);

        echo "    ✓ {$width}x{$height} - " . round($filesize / 1024) . " KB\n";

        $candidates[] = [
            'url' => $img_url,
            'temp_file' => $temp_file,
            'width' => $width,
            'height' => $height,
            'filesize' => $filesize,
            'type' => $type
        ];

        $count++;
    }

    return $candidates;
}

/**
 * Sélectionne la meilleure image parmi les candidates
 */
function select_best_image($candidates, $img_dir) {
    $best = null;
    $best_score = -1;

    foreach ($candidates as &$candidate) {
        $score = 0;

        // Critère 1: Résolution (priorité aux images >= 1200px de large)
        if ($candidate['width'] >= 1600) {
            $score += 50;
        } elseif ($candidate['width'] >= 1200) {
            $score += 40;
        } elseif ($candidate['width'] >= 800) {
            $score += 20;
        } else {
            $score += 5;
        }

        // Critère 2: Ratio (privilégier les formats paysage ou carrés, pas trop étroits)
        $ratio = $candidate['width'] / $candidate['height'];
        if ($ratio >= 1.3 && $ratio <= 2.0) {
            $score += 30; // Format paysage idéal pour les bannières
        } elseif ($ratio >= 1.0 && $ratio < 1.3) {
            $score += 20; // Format carré ou légèrement paysage
        } elseif ($ratio > 2.0 && $ratio <= 2.5) {
            $score += 15; // Format très paysage acceptable
        } else {
            $score += 5; // Formats extrêmes
        }

        // Critère 3: Taille de fichier (privilégier les images de qualité moyenne à haute)
        $kb = $candidate['filesize'] / 1024;
        if ($kb >= 150 && $kb <= 800) {
            $score += 20; // Taille idéale pour qualité/poids
        } elseif ($kb > 800 && $kb <= 1500) {
            $score += 15; // Haute qualité, un peu lourd
        } elseif ($kb >= 100 && $kb < 150) {
            $score += 10; // Acceptable
        } else {
            $score += 5; // Trop petit ou trop gros
        }

        $candidate['score'] = $score;

        if ($score > $best_score) {
            $best_score = $score;
            $best = $candidate;
        }
    }

    return $best;
}

/**
 * Télécharge une image depuis une URL
 */
function download_image($url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');

    $data = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code !== 200) {
        return false;
    }

    return $data;
}

/**
 * Redimensionne une image à une largeur minimale et maximale
 */
function resize_image($source_file, $min_width = 1200, $max_width = 2500) {
    $info = @getimagesize($source_file);
    if (!$info) {
        echo "⚠️  Impossible de lire l'image\n";
        return false;
    }

    list($width, $height, $type) = $info;

    echo "📐 Dimensions: {$width}x{$height}\n";

    // Charger l'image source selon son type
    switch ($type) {
        case IMAGETYPE_JPEG:
            $source = @imagecreatefromjpeg($source_file);
            break;
        case IMAGETYPE_PNG:
            $source = @imagecreatefrompng($source_file);
            break;
        case IMAGETYPE_GIF:
            $source = @imagecreatefromgif($source_file);
            break;
        case IMAGETYPE_WEBP:
            $source = @imagecreatefromwebp($source_file);
            break;
        default:
            echo "⚠️  Format d'image non supporté\n";
            return false;
    }

    if (!$source) {
        return false;
    }

    // Si l'image est trop grande (> 2500px), la réduire à 2500px
    if ($width > $max_width) {
        echo "⚠️  Image trop grande, réduction à {$max_width}px\n";
        $ratio = $max_width / $width;
        $new_width = $max_width;
        $new_height = intval($height * $ratio);

        echo "📏 Redimensionnement à: {$new_width}x{$new_height}\n";

        $destination = imagecreatetruecolor($new_width, $new_height);
        imagecopyresampled($destination, $source, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

        $output_file = sys_get_temp_dir() . '/resized_' . uniqid() . '.jpg';
        imagejpeg($destination, $output_file, 90);

        imagedestroy($source);
        imagedestroy($destination);

        return $output_file;
    }

    // Si l'image est entre min et max, la garder telle quelle
    if ($width >= $min_width && $width <= $max_width) {
        echo "✓ Image dans la plage optimale ({$min_width}-{$max_width}px)\n";
        $output_file = sys_get_temp_dir() . '/final_' . uniqid() . '.jpg';
        imagejpeg($source, $output_file, 90);
        imagedestroy($source);
        return $output_file;
    }

    // Sinon, upscaler à min_width (1200px)
    $ratio = $min_width / $width;
    $new_width = $min_width;
    $new_height = intval($height * $ratio);

    echo "📏 Agrandissement à: {$new_width}x{$new_height}\n";

    $destination = imagecreatetruecolor($new_width, $new_height);
    imagecopyresampled($destination, $source, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

    $output_file = sys_get_temp_dir() . '/resized_' . uniqid() . '.jpg';
    imagejpeg($destination, $output_file, 90);

    imagedestroy($source);
    imagedestroy($destination);

    return $output_file;
}

/**
 * Met à jour le front matter de l'article avec le featured_image
 */
function update_article_featured_image($article_path, $image_filename) {
    $content = file_get_contents($article_path);

    if (preg_match('/^---\n(.*?)\n---/s', $content, $matches)) {
        $front_matter = $matches[1];

        // Si featured_image existe déjà, le remplacer
        if (strpos($front_matter, 'featured_image:') !== false) {
            $front_matter = preg_replace('/featured_image:.*/', 'featured_image: ' . $image_filename, $front_matter);
            echo "✓ featured_image mis à jour\n";
        } else {
            // Sinon, l'ajouter après la description
            $front_matter .= "\nfeatured_image: " . $image_filename;
            echo "✓ featured_image ajouté\n";
        }

        $content = preg_replace('/^---\n.*?\n---/s', "---\n" . $front_matter . "\n---", $content);
        file_put_contents($article_path, $content);
    } else {
        echo "⚠️  Front matter non trouvé\n";
    }
}
