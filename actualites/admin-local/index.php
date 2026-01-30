<?php
/**
 * Local Blog Admin Interface
 * SECURITY: Only works on localhost
 */

// Security check - only allow localhost
$allowed_hosts = ['localhost', '127.0.0.1', '::1'];
if (!in_array($_SERVER['REMOTE_ADDR'] ?? '', $allowed_hosts) &&
    !in_array($_SERVER['SERVER_NAME'] ?? '', $allowed_hosts)) {
    die('Access denied. This admin interface only works on localhost.');
}

require_once '../functions.php';

// Handle form submissions
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'create' || $action === 'update') {
        $title = $_POST['title'] ?? '';
        $content = $_POST['content'] ?? '';
        $description = $_POST['description'] ?? '';
        $seo_title = $_POST['seo_title'] ?? '';
        $faq_schema = $_POST['faq_schema'] ?? '';
        $status = $_POST['status'] ?? 'published';
        $date = $_POST['date'] ?? date('Y-m-d');
        $featured_image = $_POST['existing_image'] ?? '';

        // Handle image upload
        if (!empty($_FILES['featured_image']['name'])) {
            $upload_result = handle_image_upload($_FILES['featured_image'], $date, $title);
            if ($upload_result['success']) {
                $featured_image = $upload_result['filename'];
            } else {
                $error = $upload_result['error'];
            }
        }

        if (empty($error)) {
            $slug = !empty($_POST['slug']) ? sanitize_slug($_POST['slug']) : sanitize_slug($title);
            $filename = $date . '-' . $slug . '.md';
            $filepath = __DIR__ . '/../posts/' . $filename;

            // Build front matter
            $front_matter = "---\n";
            $front_matter .= "title: " . $title . "\n";
            $front_matter .= "date: " . $date . "\n";
            $front_matter .= "description: " . $description . "\n";
            if (!empty($seo_title)) {
                $front_matter .= "seo_title: " . $seo_title . "\n";
            }
            if (!empty($featured_image)) {
                $front_matter .= "featured_image: " . $featured_image . "\n";
            }
            $front_matter .= "status: " . $status . "\n";
            if (!empty($faq_schema)) {
                // Add FAQ schema as multiline YAML string
                $front_matter .= "faq_schema: |\n";
                // Indent each line of the JSON with 2 spaces for YAML multiline
                $schema_lines = explode("\n", trim($faq_schema));
                foreach ($schema_lines as $line) {
                    $front_matter .= "  " . $line . "\n";
                }
            }
            $front_matter .= "---\n\n";

            $full_content = $front_matter . $content;

            // Save file
            if (file_put_contents($filepath, $full_content)) {
                // Auto-commit to Git if requested
                if (!empty($_POST['auto_publish'])) {
                    $git_result = git_auto_commit($filename, $title);
                    if ($git_result['success']) {
                        $message = 'Article saved and published to Git successfully!';
                    } else {
                        $message = 'Article saved, but Git commit failed: ' . $git_result['error'];
                    }
                } else {
                    $message = 'Article saved successfully! (Not published to Git yet)';
                }
            } else {
                $error = 'Failed to save article file.';
            }
        }
    } elseif ($action === 'delete') {
        $filename = $_POST['filename'] ?? '';
        $filepath = __DIR__ . '/../posts/' . $filename . '.md';
        if (file_exists($filepath) && unlink($filepath)) {
            $message = 'Article deleted successfully!';
        } else {
            $error = 'Failed to delete article.';
        }
    }
}

// Get all articles for listing
$all_articles = get_articles();

// Add draft articles
$posts_dir = __DIR__ . '/../posts';
$files = glob($posts_dir . '/*.md');
$draft_articles = [];
foreach ($files as $file) {
    $article = get_article_from_file($file);
    if ($article && ($article['meta']['status'] ?? 'published') === 'draft') {
        $draft_articles[] = $article;
    }
}

// Handle image upload
function handle_image_upload($file, $date, $title) {
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $max_size = 5 * 1024 * 1024; // 5MB

    if (!in_array($file['type'], $allowed_types)) {
        return ['success' => false, 'error' => 'Invalid file type. Only JPG, PNG, GIF, and WebP allowed.'];
    }

    if ($file['size'] > $max_size) {
        return ['success' => false, 'error' => 'File too large. Maximum 5MB allowed.'];
    }

    // Generate filename
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $slug = sanitize_slug($title);
    $filename = $date . '-' . $slug . '.' . $extension;
    $upload_dir = __DIR__ . '/../images/';
    $upload_path = $upload_dir . $filename;

    // Create directory if it doesn't exist
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    // Move uploaded file
    if (move_uploaded_file($file['tmp_name'], $upload_path)) {
        // Optimize image (simple resize)
        optimize_image($upload_path, 1200);
        return ['success' => true, 'filename' => $filename];
    }

    return ['success' => false, 'error' => 'Failed to upload file.'];
}

// Simple image optimization
function optimize_image($filepath, $max_width = 1200) {
    $info = getimagesize($filepath);
    if (!$info) return;

    list($width, $height, $type) = $info;

    // Only resize if larger than max
    if ($width <= $max_width) return;

    $ratio = $max_width / $width;
    $new_width = $max_width;
    $new_height = intval($height * $ratio);

    // Load image based on type
    switch ($type) {
        case IMAGETYPE_JPEG:
            $source = imagecreatefromjpeg($filepath);
            break;
        case IMAGETYPE_PNG:
            $source = imagecreatefrompng($filepath);
            break;
        case IMAGETYPE_GIF:
            $source = imagecreatefromgif($filepath);
            break;
        default:
            return;
    }

    // Create new image
    $destination = imagecreatetruecolor($new_width, $new_height);
    imagecopyresampled($destination, $source, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

    // Save
    switch ($type) {
        case IMAGETYPE_JPEG:
            imagejpeg($destination, $filepath, 85);
            break;
        case IMAGETYPE_PNG:
            imagepng($destination, $filepath, 8);
            break;
        case IMAGETYPE_GIF:
            imagegif($destination, $filepath);
            break;
    }

    imagedestroy($source);
    imagedestroy($destination);
}

// Git auto-commit
function git_auto_commit($filename, $title) {
    $repo_dir = realpath(__DIR__ . '/../../');

    // Build git commands
    $commands = [
        "cd " . escapeshellarg($repo_dir),
        "git add actualites/posts/" . escapeshellarg($filename),
        "git add actualites/images/",
        "git commit -m " . escapeshellarg("New article: " . $title),
        "git push"
    ];

    $command = implode(' && ', $commands) . ' 2>&1';
    exec($command, $output, $return_code);

    if ($return_code === 0) {
        return ['success' => true];
    } else {
        return ['success' => false, 'error' => implode("\n", $output)];
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog Admin - Local</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body {
            background: #f8f9fa;
            padding: 20px 0;
        }
        .admin-header {
            background: linear-gradient(135deg, #000091 0%, #1212FF 100%);
            color: white;
            padding: 30px 0;
            margin-bottom: 30px;
        }
        .card {
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .article-card {
            transition: transform 0.2s;
        }
        .article-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        .badge-draft {
            background: #ffc107;
        }
        .badge-published {
            background: #28a745;
        }
    </style>
</head>
<body>
    <div class="admin-header">
        <div class="container">
            <h1><i class="fas fa-edit me-2"></i> Blog Admin</h1>
            <p class="mb-0">Local interface - Create and manage articles</p>
        </div>
    </div>

    <div class="container">
        <?php if ($message): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle me-2"></i>
                <?php echo htmlspecialchars($message); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-circle me-2"></i>
                <?php echo htmlspecialchars($error); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <!-- Create New Article -->
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header bg-white">
                        <h2 class="h5 mb-0"><i class="fas fa-plus-circle me-2"></i> New Article</h2>
                    </div>
                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data" id="articleForm">
                            <input type="hidden" name="action" value="create">

                            <div class="mb-3">
                                <label class="form-label">Title *</label>
                                <input type="text" name="title" class="form-control" required>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Date *</label>
                                    <input type="date" name="date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-select">
                                        <option value="published">Published</option>
                                        <option value="draft">Draft</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Featured Image</label>
                                <input type="file" name="featured_image" class="form-control" accept="image/*">
                                <small class="text-muted">JPG, PNG, GIF or WebP. Max 5MB. Will be auto-resized to 1200px width.</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Description (SEO)</label>
                                <textarea name="description" class="form-control" rows="2" placeholder="Short description for SEO and social sharing (recommended 150-160 characters)"></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">SEO Title (Optional)</label>
                                <input type="text" name="seo_title" class="form-control" placeholder="Leave empty to use article title">
                                <small class="text-muted">Custom title for search engines (recommended max 60 characters)</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Content (Markdown) *</label>
                                <textarea name="content" class="form-control" rows="15" required placeholder="Write your article content here using Markdown...

## Example Heading

This is a paragraph with **bold text** and *italic text*.

* List item 1
* List item 2

[Link text](https://example.com)"></textarea>
                                <small class="text-muted">
                                    Markdown cheatsheet: **bold**, *italic*, [link](url), ## Heading, * list item
                                </small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">
                                    FAQ Schema (JSON)
                                    <span class="badge bg-info">Recommandé</span>
                                </label>
                                <textarea name="faq_schema" class="form-control font-monospace" rows="12" placeholder='{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    {
      "@type": "Question",
      "name": "Question 1 ?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Réponse à la question 1."
      }
    }
  ]
}'></textarea>
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    <strong>Important :</strong> Coller UNIQUEMENT le JSON, SANS les balises &lt;script&gt;.
                                    Les balises seront ajoutées automatiquement lors de la publication.
                                </small>
                            </div>

                            <div class="mb-3 form-check">
                                <input type="checkbox" name="auto_publish" class="form-check-input" id="autoPublish" checked>
                                <label class="form-check-label" for="autoPublish">
                                    <i class="fas fa-code-branch me-1"></i> Auto-commit and push to Git
                                </label>
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save me-2"></i> Save Article
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Article List -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header bg-white">
                        <h2 class="h5 mb-0"><i class="fas fa-list me-2"></i> Articles</h2>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($draft_articles)): ?>
                            <h6 class="text-muted mb-3">Drafts</h6>
                            <?php foreach ($draft_articles as $article): ?>
                                <div class="article-card card mb-2">
                                    <div class="card-body p-2">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <small class="badge badge-draft">Draft</small>
                                                <h6 class="mb-1 mt-1"><?php echo htmlspecialchars($article['meta']['title']); ?></h6>
                                                <small class="text-muted"><?php echo $article['date']; ?></small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            <hr>
                        <?php endif; ?>

                        <h6 class="text-muted mb-3">Published</h6>
                        <?php if (empty($all_articles)): ?>
                            <p class="text-muted">No articles yet.</p>
                        <?php else: ?>
                            <?php foreach (array_slice($all_articles, 0, 10) as $article): ?>
                                <div class="article-card card mb-2">
                                    <div class="card-body p-2">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <small class="badge badge-published">Published</small>
                                                <h6 class="mb-1 mt-1"><?php echo htmlspecialchars($article['meta']['title']); ?></h6>
                                                <small class="text-muted"><?php echo $article['date']; ?></small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-body">
                        <h6><i class="fas fa-info-circle me-2"></i> Quick Help</h6>
                        <ul class="small mb-0">
                            <li>Titles are auto-converted to URLs</li>
                            <li>Images are auto-optimized</li>
                            <li>Use Markdown for formatting</li>
                            <li>Git auto-commit available</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
