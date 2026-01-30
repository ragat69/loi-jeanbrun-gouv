<?php
/**
 * Blog Functions Library
 * Handles markdown parsing, article management, and SEO
 */

// Simple markdown parser (lightweight, no dependencies)
class SimpleMarkdown {
    public static function parse($text) {
        // Headers
        $text = preg_replace('/^### (.+)$/m', '<h3>$1</h3>', $text);
        $text = preg_replace('/^## (.+)$/m', '<h2>$1</h2>', $text);
        $text = preg_replace('/^# (.+)$/m', '<h1>$1</h1>', $text);

        // Bold and italic
        $text = preg_replace('/\*\*(.+?)\*\*/', '<strong>$1</strong>', $text);
        $text = preg_replace('/\*(.+?)\*/', '<em>$1</em>', $text);

        // Links
        $text = preg_replace('/\[(.+?)\]\((.+?)\)/', '<a href="$2">$1</a>', $text);

        // Images
        $text = preg_replace('/!\[(.+?)\]\((.+?)\)/', '<img src="$2" alt="$1" class="img-fluid">', $text);

        // Lists
        $text = preg_replace_callback('/(?:^|\n)(\*.+?)(?=\n\n|\n#|\z)/s', function($matches) {
            $items = preg_replace('/^\* (.+)$/m', '<li>$1</li>', $matches[1]);
            return "\n<ul>\n" . $items . "\n</ul>\n";
        }, $text);

        $text = preg_replace_callback('/(?:^|\n)(\d+\..+?)(?=\n\n|\n#|\z)/s', function($matches) {
            $items = preg_replace('/^\d+\. (.+)$/m', '<li>$1</li>', $matches[1]);
            return "\n<ol>\n" . $items . "\n</ol>\n";
        }, $text);

        // Blockquotes (support multi-line)
        $text = preg_replace_callback('/(?:^|\n)((?:> .+\n?)+)/m', function($matches) {
            $quote = preg_replace('/^> (.+)$/m', '$1', $matches[1]);
            $quote = trim($quote);
            return "\n<blockquote class=\"blockquote\"><p>" . $quote . "</p></blockquote>\n";
        }, $text);

        // Paragraphs (convert double line breaks)
        $text = preg_replace('/\n\n/', '</p><p>', $text);
        $text = '<p>' . $text . '</p>';

        // Clean up empty paragraphs
        $text = preg_replace('/<p><\/p>/', '', $text);
        $text = preg_replace('/<p>(<h[1-6]>)/', '$1', $text);
        $text = preg_replace('/(<\/h[1-6]>)<\/p>/', '$1', $text);
        $text = preg_replace('/<p>(<ul>|<ol>|<blockquote>)/', '$1', $text);
        $text = preg_replace('/(<\/ul>|<\/ol>|<\/blockquote>)<\/p>/', '$1', $text);

        return $text;
    }
}

// Parse article front matter (YAML-like)
function parse_front_matter($content) {
    $front_matter = [];
    $body = $content;

    // Check for front matter between --- markers
    if (preg_match('/^---\s*\n(.*?)\n---\s*\n(.*)$/s', $content, $matches)) {
        $front_matter_text = $matches[1];
        $body = $matches[2];

        // Parse each line
        $lines = explode("\n", $front_matter_text);
        foreach ($lines as $line) {
            if (preg_match('/^(.+?):\s*(.+)$/', trim($line), $line_matches)) {
                $key = trim($line_matches[1]);
                $value = trim($line_matches[2], " \t\n\r\0\x0B\"'");
                $front_matter[$key] = $value;
            }
        }
    }

    return ['meta' => $front_matter, 'body' => $body];
}

// Get all articles
function get_articles($limit = null, $offset = 0) {
    $posts_dir = __DIR__ . '/posts';
    $articles = [];

    if (!is_dir($posts_dir)) {
        return $articles;
    }

    $files = glob($posts_dir . '/*.md');

    foreach ($files as $file) {
        $article = get_article_from_file($file);
        if ($article && ($article['meta']['status'] ?? 'published') === 'published') {
            $articles[] = $article;
        }
    }

    // Sort by date (newest first)
    usort($articles, function($a, $b) {
        return strtotime($b['meta']['date']) - strtotime($a['meta']['date']);
    });

    // Apply pagination
    if ($limit !== null) {
        $articles = array_slice($articles, $offset, $limit);
    }

    return $articles;
}

// Get single article from filename
function get_article_from_file($file) {
    if (!file_exists($file)) {
        return null;
    }

    $content = file_get_contents($file);
    $parsed = parse_front_matter($content);

    $filename = basename($file, '.md');

    // Extract date from filename (format: YYYY-MM-DD-title)
    if (preg_match('/^(\d{4}-\d{2}-\d{2})-(.+)$/', $filename, $matches)) {
        $date = $matches[1];
        $slug = $matches[2];
    } else {
        $date = date('Y-m-d', filemtime($file));
        $slug = $filename;
    }

    return [
        'filename' => $filename,
        'slug' => $slug,
        'date' => $date,
        'meta' => array_merge([
            'title' => ucwords(str_replace('-', ' ', $slug)),
            'date' => $date,
            'featured_image' => '',
            'description' => '',
            'status' => 'published'
        ], $parsed['meta']),
        'content' => SimpleMarkdown::parse($parsed['body'])
    ];
}

// Get single article by slug
function get_article_by_slug($date, $slug) {
    $posts_dir = __DIR__ . '/posts';
    $filename = $date . '-' . $slug;
    $file = $posts_dir . '/' . $filename . '.md';

    return get_article_from_file($file);
}

// Generate article URL
function get_article_url($article) {
    return '/actualites/' . $article['date'] . '/' . $article['slug'];
}

// Get total article count
function get_article_count() {
    $posts_dir = __DIR__ . '/posts';
    $files = glob($posts_dir . '/*.md');
    $count = 0;

    foreach ($files as $file) {
        $content = file_get_contents($file);
        $parsed = parse_front_matter($content);
        if (($parsed['meta']['status'] ?? 'published') === 'published') {
            $count++;
        }
    }

    return $count;
}

// Generate pagination HTML
function get_pagination($current_page, $total_items, $per_page, $base_url) {
    $total_pages = ceil($total_items / $per_page);

    if ($total_pages <= 1) {
        return '';
    }

    $html = '<nav aria-label="Navigation de pagination"><ul class="pagination justify-content-center">';

    // Previous
    if ($current_page > 1) {
        $prev_url = $current_page == 2 ? $base_url : $base_url . '/page-' . ($current_page - 1);
        $html .= '<li class="page-item"><a class="page-link" href="' . $prev_url . '">Précédent</a></li>';
    } else {
        $html .= '<li class="page-item disabled"><span class="page-link">Précédent</span></li>';
    }

    // Pages
    for ($i = 1; $i <= $total_pages; $i++) {
        $page_url = $i == 1 ? $base_url : $base_url . '/page-' . $i;
        if ($i == $current_page) {
            $html .= '<li class="page-item active"><span class="page-link">' . $i . '</span></li>';
        } else {
            $html .= '<li class="page-item"><a class="page-link" href="' . $page_url . '">' . $i . '</a></li>';
        }
    }

    // Next
    if ($current_page < $total_pages) {
        $next_url = $base_url . '/page-' . ($current_page + 1);
        $html .= '<li class="page-item"><a class="page-link" href="' . $next_url . '">Suivant</a></li>';
    } else {
        $html .= '<li class="page-item disabled"><span class="page-link">Suivant</span></li>';
    }

    $html .= '</ul></nav>';

    return $html;
}

// Generate excerpt from content
function get_excerpt($content, $length = 200) {
    $text = strip_tags($content);
    if (strlen($text) <= $length) {
        return $text;
    }
    return substr($text, 0, $length) . '...';
}

// Format date in French
function format_date_fr($date) {
    $months = [
        '01' => 'janvier', '02' => 'février', '03' => 'mars', '04' => 'avril',
        '05' => 'mai', '06' => 'juin', '07' => 'juillet', '08' => 'août',
        '09' => 'septembre', '10' => 'octobre', '11' => 'novembre', '12' => 'décembre'
    ];

    $parts = explode('-', $date);
    if (count($parts) == 3) {
        return (int)$parts[2] . ' ' . $months[$parts[1]] . ' ' . $parts[0];
    }

    return $date;
}

// Sanitize slug for URL
function sanitize_slug($text) {
    $text = strtolower($text);
    $text = str_replace(['é', 'è', 'ê', 'ë'], 'e', $text);
    $text = str_replace(['à', 'â', 'ä'], 'a', $text);
    $text = str_replace(['ù', 'û', 'ü'], 'u', $text);
    $text = str_replace(['ô', 'ö'], 'o', $text);
    $text = str_replace(['î', 'ï'], 'i', $text);
    $text = str_replace('ç', 'c', $text);
    $text = preg_replace('/[^a-z0-9]+/', '-', $text);
    $text = trim($text, '-');
    return $text;
}

// Get previous article (older)
function get_previous_article($current_article) {
    $all_articles = get_articles(); // Already sorted by date (newest first)

    foreach ($all_articles as $index => $article) {
        if ($article['filename'] === $current_article['filename']) {
            // Previous article is the next one in the array (older)
            return isset($all_articles[$index + 1]) ? $all_articles[$index + 1] : null;
        }
    }

    return null;
}

// Get next article (newer)
function get_next_article($current_article) {
    $all_articles = get_articles(); // Already sorted by date (newest first)

    foreach ($all_articles as $index => $article) {
        if ($article['filename'] === $current_article['filename']) {
            // Next article is the previous one in the array (newer)
            return isset($all_articles[$index - 1]) ? $all_articles[$index - 1] : null;
        }
    }

    return null;
}
