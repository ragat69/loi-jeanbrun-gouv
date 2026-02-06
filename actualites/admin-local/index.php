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

// Handle AJAX request for getting article data
if (isset($_GET['get_article']) && !empty($_GET['slug'])) {
    $slug = $_GET['slug'];
    $filepath = __DIR__ . '/../posts/' . $slug . '.md';

    if (file_exists($filepath)) {
        $content = file_get_contents($filepath);
        $parsed = parse_front_matter($content);

        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'data' => [
                'title' => $parsed['meta']['title'] ?? '',
                'date' => $parsed['meta']['date'] ?? '',
                'description' => $parsed['meta']['description'] ?? '',
                'seo_title' => $parsed['meta']['seo_title'] ?? '',
                'featured_image' => $parsed['meta']['featured_image'] ?? '',
                'content' => $parsed['body'] ?? '',
                'faq_schema' => $parsed['meta']['faq_schema'] ?? '',
                'status' => $parsed['meta']['status'] ?? 'published',
                'slug' => $slug
            ]
        ]);
    } else {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'Article not found']);
    }
    exit;
}

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
            // For updates, use the existing slug if provided
            if ($action === 'update' && !empty($_POST['edit_slug'])) {
                $filename = $_POST['edit_slug'] . '.md';
            } else {
                $slug = !empty($_POST['slug']) ? sanitize_slug($_POST['slug']) : sanitize_slug($title);
                $filename = $date . '-' . $slug . '.md';
            }
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

            // CRITICAL: Convert Windows line endings (CRLF) to Unix (LF)
            // This is essential for FAQ parsing to work correctly
            // The Markdown parser requires LF line endings to properly detect
            // empty lines between questions and answers
            $full_content = str_replace("\r\n", "\n", $full_content);
            $full_content = str_replace("\r", "\n", $full_content);

            // Save file
            if (file_put_contents($filepath, $full_content)) {
                // Build article URL
                $article_slug = preg_replace('/^\d{4}-\d{2}-\d{2}-/', '', basename($filename, '.md'));
                $article_url = 'http://loi-jeanbrun-gouv.test/actualites/' . $date . '/' . $article_slug;

                // Auto-commit to Git if requested
                $action_text = ($action === 'update') ? 'updated' : 'created';
                $view_link = ' <a href="' . $article_url . '" target="_blank" style="color: #000091; font-weight: bold;">View article →</a>';

                if (!empty($_POST['auto_publish'])) {
                    $git_result = git_auto_commit($filename, $title, $action);
                    if ($git_result['success']) {
                        $message = 'Article ' . $action_text . ' and published to Git successfully!' . $view_link;
                    } else {
                        $message = 'Article ' . $action_text . ', but Git commit failed: ' . $git_result['error'] . $view_link;
                    }
                } else {
                    $message = 'Article ' . $action_text . ' successfully! (Not published to Git yet)' . $view_link;
                }

                // Keep article loaded in form after save
                $current_article = [
                    'filename' => basename($filename, '.md'),
                    'title' => $title,
                    'date' => $date,
                    'description' => $description,
                    'seo_title' => $seo_title,
                    'content' => $content,
                    'faq_schema' => $faq_schema,
                    'status' => $status,
                    'featured_image' => $featured_image
                ];
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
    $upload_dir = __DIR__ . '/../img/';
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
function git_auto_commit($filename, $title, $action = 'create') {
    $repo_dir = realpath(__DIR__ . '/../../');
    $commit_prefix = ($action === 'update') ? 'Update article' : 'New article';

    // Build git commands
    $commands = [
        "cd " . escapeshellarg($repo_dir),
        "git add actualites/posts/" . escapeshellarg($filename),
        "git add actualites/img/",
        "git commit -m " . escapeshellarg($commit_prefix . ": " . $title),
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

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="/assets/favicon.svg">
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/favicon-32x32.png">
    <link rel="apple-touch-icon" href="/assets/apple-touch-icon.png">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.css">
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
        /* EasyMDE custom styles */
        .EasyMDEContainer {
            margin-bottom: 1rem;
        }
        .EasyMDEContainer .CodeMirror {
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            font-family: 'Courier New', Courier, monospace;
            font-size: 14px;
            line-height: 1.6;
        }
        .EasyMDEContainer .editor-toolbar {
            border: 1px solid #dee2e6;
            border-bottom: none;
            border-radius: 0.375rem 0.375rem 0 0;
            background: #f8f9fa;
        }
        .EasyMDEContainer .editor-toolbar button {
            color: #495057 !important;
        }
        .EasyMDEContainer .editor-toolbar button:hover,
        .EasyMDEContainer .editor-toolbar button.active {
            background: #e9ecef;
            border-color: #dee2e6;
        }
        .EasyMDEContainer .editor-preview,
        .EasyMDEContainer .editor-preview-side {
            background: #ffffff;
            padding: 1rem;
        }
        /* Heading styles in editor preview */
        .EasyMDEContainer .editor-preview h1,
        .EasyMDEContainer .editor-preview-side h1 {
            font-size: 1.6rem !important;
            font-weight: 700 !important;
            color: #E1000F !important;
            margin-top: 2rem !important;
            margin-bottom: 1rem !important;
        }
        .EasyMDEContainer .editor-preview h2,
        .EasyMDEContainer .editor-preview-side h2 {
            font-size: 1.4rem !important;
            font-weight: 700 !important;
            color: #000091 !important;
            margin-top: 1.75rem !important;
            margin-bottom: 0.875rem !important;
        }
        .EasyMDEContainer .editor-preview h3,
        .EasyMDEContainer .editor-preview-side h3 {
            font-size: 1.2rem !important;
            font-weight: 700 !important;
            color: #18753C !important;
            margin-top: 1.5rem !important;
            margin-bottom: 0.75rem !important;
        }
        .EasyMDEContainer .editor-preview h4,
        .EasyMDEContainer .editor-preview-side h4 {
            font-size: 1.05rem !important;
            font-weight: 600 !important;
            color: #FF6F00 !important;
            margin-top: 1.25rem !important;
            margin-bottom: 0.625rem !important;
        }
        .EasyMDEContainer .editor-preview h5,
        .EasyMDEContainer .editor-preview-side h5 {
            font-size: 0.95rem !important;
            font-weight: 600 !important;
            color: #6E445A !important;
            margin-top: 1rem !important;
            margin-bottom: 0.5rem !important;
        }
        .EasyMDEContainer .CodeMirror-cursor {
            border-left-color: #000091;
        }
        /* Syntax highlighting for HTML in Markdown */
        .cm-tag {
            color: #d73a49;
        }
        .cm-attribute {
            color: #6f42c1;
        }
        .cm-string {
            color: #032f62;
        }
        /* Heading styles in source code editor */
        .cm-header-1 {
            color: #E1000F !important;
            font-weight: bold !important;
            font-size: 1.4rem !important;
            line-height: 1.2 !important;
        }
        .cm-header-2 {
            color: #000091 !important;
            font-weight: bold !important;
            font-size: 1.25rem !important;
            line-height: 1.2 !important;
        }
        .cm-header-3 {
            color: #18753C !important;
            font-weight: bold !important;
            font-size: 1.1rem !important;
            line-height: 1.2 !important;
        }
        .cm-header-4 {
            color: #FF6F00 !important;
            font-weight: 600 !important;
            font-size: 1rem !important;
            line-height: 1.2 !important;
        }
        .cm-header-5 {
            color: #6E445A !important;
            font-weight: 600 !important;
            font-size: 0.95rem !important;
            line-height: 1.2 !important;
        }
        .cm-header-6 {
            color: #6E445A !important;
            font-weight: 500 !important;
            font-size: 0.9rem !important;
            line-height: 1.2 !important;
        }
        .cm-strong {
            font-weight: bold;
        }
        .cm-em {
            font-style: italic;
        }
        .cm-link {
            color: #0366d6;
            text-decoration: underline;
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
                <?php echo $message; ?>
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
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h2 class="h5 mb-0"><i class="fas fa-<?php echo isset($current_article) ? 'edit' : 'plus-circle'; ?> me-2" id="formIcon"></i> <span id="formTitle"><?php echo isset($current_article) ? 'Edit Article' : 'New Article'; ?></span></h2>
                        <?php if (isset($current_article)):
                            $article_slug = preg_replace('/^\d{4}-\d{2}-\d{2}-/', '', $current_article['filename']);
                            $article_url = 'http://loi-jeanbrun-gouv.test/actualites/' . $current_article['date'] . '/' . $article_slug;
                        ?>
                        <a href="<?php echo $article_url; ?>" id="viewArticleBtn" class="btn btn-sm btn-outline-primary" target="_blank">
                            <i class="fas fa-external-link-alt me-1"></i> View Article
                        </a>
                        <?php endif; ?>
                    </div>
                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data" id="articleForm">
                            <input type="hidden" name="action" value="<?php echo isset($current_article) ? 'update' : 'create'; ?>" id="formAction">
                            <input type="hidden" name="edit_slug" value="<?php echo isset($current_article) ? $current_article['filename'] : ''; ?>" id="editSlug">

                            <div class="mb-3">
                                <label class="form-label">Title *</label>
                                <input type="text" name="title" class="form-control" value="<?php echo isset($current_article) ? htmlspecialchars($current_article['title']) : ''; ?>" required>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Date *</label>
                                    <input type="date" name="date" class="form-control" value="<?php echo isset($current_article) ? $current_article['date'] : date('Y-m-d'); ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-select">
                                        <option value="published" <?php echo (isset($current_article) && $current_article['status'] === 'published') ? 'selected' : ''; ?>>Published</option>
                                        <option value="draft" <?php echo (isset($current_article) && $current_article['status'] === 'draft') ? 'selected' : ''; ?>>Draft</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Featured Image</label>
                                <input type="file" name="featured_image" class="form-control" accept="image/*">
                                <input type="hidden" name="existing_image" value="<?php echo isset($current_article) ? htmlspecialchars($current_article['featured_image'] ?? '') : ''; ?>" id="existingImageInput">
                                <?php if (isset($current_article) && !empty($current_article['featured_image'])): ?>
                                <div id="currentImagePreview" style="display:block;" class="mt-2">
                                    <small class="text-muted d-block mb-1">Current image:</small>
                                    <img id="currentImageThumb" src="/actualites/img/<?php echo htmlspecialchars($current_article['featured_image']); ?>" alt="Current image" style="max-width: 200px; max-height: 100px; border: 1px solid #dee2e6; border-radius: 4px;">
                                </div>
                                <?php else: ?>
                                <div id="currentImagePreview" style="display:none;" class="mt-2">
                                    <small class="text-muted d-block mb-1">Current image:</small>
                                    <img id="currentImageThumb" src="" alt="Current image" style="max-width: 200px; max-height: 100px; border: 1px solid #dee2e6; border-radius: 4px;">
                                </div>
                                <?php endif; ?>
                                <small class="text-muted">JPG, PNG, GIF or WebP. Max 5MB. Will be auto-resized to 1200px width. Leave empty to keep existing image when editing.</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Description (SEO)</label>
                                <textarea name="description" class="form-control" rows="2" placeholder="Short description for SEO and social sharing (recommended 150-160 characters)"><?php echo isset($current_article) ? htmlspecialchars($current_article['description'] ?? '') : ''; ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">SEO Title (Optional)</label>
                                <input type="text" name="seo_title" class="form-control" value="<?php echo isset($current_article) ? htmlspecialchars($current_article['seo_title'] ?? '') : ''; ?>" placeholder="Leave empty to use article title">
                                <small class="text-muted">Custom title for search engines (recommended max 60 characters)</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Content (Markdown) *</label>
                                <textarea name="content" class="form-control" rows="15" placeholder="Write your article content here using Markdown...

## Example Heading

This is a paragraph with **bold text** and *italic text*.

* List item 1
* List item 2

[Link text](https://example.com)"><?php echo isset($current_article) ? htmlspecialchars($current_article['content']) : ''; ?></textarea>
                                <small class="text-muted">
                                    Markdown cheatsheet: **bold**, *italic*, [link](url), ## Heading, * list item
                                </small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">
                                    FAQ Schema (JSON)
                                    <span class="badge bg-info">Recommandé</span>
                                </label>
                                <textarea name="faq_schema" class="form-control font-monospace" rows="12"><?php echo isset($current_article) ? htmlspecialchars($current_article['faq_schema'] ?? '') : ''; ?></textarea>
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    <strong>Important :</strong> Coller UNIQUEMENT le JSON, SANS les balises &lt;script&gt;.
                                    Les balises seront ajoutées automatiquement lors de la publication.<br>
                                    <i class="fas fa-check-circle text-success me-1"></i>
                                    <strong>Conversion automatique :</strong> Les fins de ligne Windows (CRLF) sont automatiquement converties en Unix (LF) pour assurer le bon fonctionnement des FAQs en accordéon.
                                </small>
                            </div>

                            <div class="mb-3 form-check">
                                <input type="checkbox" name="auto_publish" class="form-check-input" id="autoPublish">
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
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h2 class="h5 mb-0"><i class="fas fa-list me-2"></i> Articles</h2>
                        <button type="button" class="btn btn-sm btn-primary" id="newArticleBtn" title="Create new article">
                            <i class="fas fa-plus me-1"></i> New
                        </button>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($draft_articles)): ?>
                            <h6 class="text-muted mb-3">Drafts</h6>
                            <?php foreach ($draft_articles as $article): ?>
                                <div class="article-card card mb-2">
                                    <div class="card-body p-2">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div class="flex-grow-1">
                                                <small class="badge badge-draft">Draft</small>
                                                <h6 class="mb-1 mt-1"><?php echo htmlspecialchars($article['meta']['title']); ?></h6>
                                                <small class="text-muted"><?php echo $article['date']; ?></small>
                                            </div>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-sm btn-outline-primary edit-article-btn"
                                                        data-slug="<?php echo $article['filename']; ?>"
                                                        title="Edit article">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger delete-article-btn"
                                                        data-slug="<?php echo $article['filename']; ?>"
                                                        data-title="<?php echo htmlspecialchars($article['meta']['title']); ?>"
                                                        title="Delete article">
                                                    <i class="fas fa-trash"></i>
                                                </button>
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
                                            <div class="flex-grow-1">
                                                <small class="badge badge-published">Published</small>
                                                <h6 class="mb-1 mt-1"><?php echo htmlspecialchars($article['meta']['title']); ?></h6>
                                                <small class="text-muted"><?php echo $article['date']; ?></small>
                                            </div>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-sm btn-outline-primary edit-article-btn"
                                                        data-slug="<?php echo $article['filename']; ?>"
                                                        title="Edit article">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger delete-article-btn"
                                                        data-slug="<?php echo $article['filename']; ?>"
                                                        data-title="<?php echo htmlspecialchars($article['meta']['title']); ?>"
                                                        title="Delete article">
                                                    <i class="fas fa-trash"></i>
                                                </button>
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
                            <li><span class="text-success">✓</span> Line endings auto-fixed (CRLF → LF)</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.js"></script>
    <script>
        // Initialize EasyMDE for the content textarea
        const contentTextarea = document.querySelector('textarea[name="content"]');
        let easyMDE;
        if (contentTextarea) {
            easyMDE = new EasyMDE({
                element: contentTextarea,
                spellChecker: false,
                autofocus: false,
                placeholder: "Écrivez votre article en Markdown ici...",
                status: ["lines", "words", "cursor"],
                toolbar: [
                    "bold", "italic", "heading", "|",
                    "quote", "unordered-list", "ordered-list", "|",
                    "link", "image", "|",
                    {
                        name: "paragraph",
                        action: function(editor) {
                            const cm = editor.codemirror;
                            const selection = cm.getSelection();
                            const replacement = '<p>' + selection + '</p>';
                            cm.replaceSelection(replacement);
                        },
                        className: "fa fa-paragraph",
                        title: "Wrap with <p> tag"
                    },
                    {
                        name: "paragraph-lead",
                        action: function(editor) {
                            const cm = editor.codemirror;
                            const selection = cm.getSelection();
                            const replacement = '<p class="lead">' + selection + '</p>';
                            cm.replaceSelection(replacement);
                        },
                        className: "fa fa-text-height",
                        title: "Wrap with <p class=\"lead\"> tag"
                    },
                    "|",
                    "preview", "side-by-side", "fullscreen", "|",
                    "guide"
                ],
                minHeight: "400px",
                maxHeight: "800px",
                renderingConfig: {
                    codeSyntaxHighlighting: true,
                },
                previewRender: function(plainText) {
                    // Custom preview rendering
                    return this.parent.markdown(plainText);
                }
            });
        }

        // Initialize EasyMDE for FAQ Schema textarea with JSON highlighting
        const faqSchemaTextarea = document.querySelector('textarea[name="faq_schema"]');
        let faqMDE;
        if (faqSchemaTextarea) {
            faqMDE = new EasyMDE({
                element: faqSchemaTextarea,
                spellChecker: false,
                autofocus: false,
                placeholder: '{\n  "@context": "https://schema.org",\n  "@type": "FAQPage",\n  ...\n}',
                status: false,
                toolbar: ["preview", "side-by-side", "fullscreen"],
                minHeight: "250px",
                maxHeight: "400px",
                renderingConfig: {
                    codeSyntaxHighlighting: true,
                },
                previewRender: function(plainText) {
                    // Format JSON for preview
                    try {
                        const formatted = JSON.stringify(JSON.parse(plainText), null, 2);
                        return '<pre><code class="language-json">' + formatted + '</code></pre>';
                    } catch(e) {
                        return '<pre><code class="language-json">' + plainText + '</code></pre>';
                    }
                }
            });
        }

        // Handle edit article functionality
        const editButtons = document.querySelectorAll('.edit-article-btn');
        const formTitle = document.getElementById('formTitle');
        const formIcon = document.getElementById('formIcon');
        const formAction = document.getElementById('formAction');
        const editSlug = document.getElementById('editSlug');
        const viewArticleBtn = document.getElementById('viewArticleBtn');
        const articleForm = document.getElementById('articleForm');

        // Function to load article data
        function loadArticle(slug) {
            fetch(`?get_article=1&slug=${encodeURIComponent(slug)}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(result => {
                    if (result.success) {
                        const data = result.data;

                        // Hide any success/error alerts
                        const alerts = document.querySelectorAll('.alert');
                        alerts.forEach(alert => {
                            const closeBtn = alert.querySelector('.btn-close');
                            if (closeBtn) {
                                closeBtn.click();
                            }
                        });

                        // Update form title and action
                        formTitle.textContent = 'Edit Article';
                        formIcon.className = 'fas fa-edit me-2';
                        formAction.value = 'update';
                        editSlug.value = data.slug;

                        // Update and show view article link
                        // Remove date prefix from slug (YYYY-MM-DD-) to avoid double date in URL
                        const cleanSlug = data.slug.replace(/^\d{4}-\d{2}-\d{2}-/, '');
                        const articleUrl = 'http://loi-jeanbrun-gouv.test/actualites/' + data.date + '/' + cleanSlug;
                        viewArticleBtn.href = articleUrl;
                        viewArticleBtn.style.display = 'inline-block';

                        // Fill form fields
                        document.querySelector('input[name="title"]').value = data.title;
                        document.querySelector('input[name="date"]').value = data.date;
                        document.querySelector('textarea[name="description"]').value = data.description || '';
                        document.querySelector('input[name="seo_title"]').value = data.seo_title || '';
                        document.querySelector('select[name="status"]').value = data.status;
                        document.querySelector('input[name="existing_image"]').value = data.featured_image || '';

                        // Show current image if exists
                        const imagePreview = document.getElementById('currentImagePreview');
                        const imageThumb = document.getElementById('currentImageThumb');
                        if (data.featured_image) {
                            imageThumb.src = '/actualites/img/' + data.featured_image;
                            imagePreview.style.display = 'block';
                        } else {
                            imagePreview.style.display = 'none';
                        }

                        // Update EasyMDE instances
                        if (easyMDE) {
                            easyMDE.value(data.content || '');
                        }
                        if (faqMDE) {
                            faqMDE.value(data.faq_schema || '');
                        }

                        // Scroll to top of page
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    } else {
                        console.error('Error loading article:', result.error);
                    }
                })
                .catch(error => {
                    console.error('Failed to load article:', error);
                });
        }

        // Function to reset form
        function resetForm() {
            formTitle.textContent = 'New Article';
            formIcon.className = 'fas fa-plus-circle me-2';
            formAction.value = 'create';
            editSlug.value = '';
            viewArticleBtn.style.display = 'none';
            articleForm.reset();
            document.querySelector('input[name="date"]').value = '<?php echo date('Y-m-d'); ?>';
            document.querySelector('input[name="existing_image"]').value = '';
            document.getElementById('currentImagePreview').style.display = 'none';

            // Reset EasyMDE instances
            if (easyMDE) {
                easyMDE.value('');
            }
            if (faqMDE) {
                faqMDE.value('');
            }
        }


        // Attach click handlers to edit buttons
        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                const slug = this.getAttribute('data-slug');
                loadArticle(slug);
            });
        });

        // New article button
        const newArticleBtn = document.getElementById('newArticleBtn');
        if (newArticleBtn) {
            newArticleBtn.addEventListener('click', function() {
                // Hide any success/error alerts
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(alert => {
                    const closeBtn = alert.querySelector('.btn-close');
                    if (closeBtn) {
                        closeBtn.click();
                    }
                });

                resetForm();
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        }

        // Delete article buttons
        const deleteButtons = document.querySelectorAll('.delete-article-btn');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const slug = this.getAttribute('data-slug');
                const title = this.getAttribute('data-title');

                if (confirm(`Are you sure you want to delete "${title}"?\n\nThis action cannot be undone.`)) {
                    // Create a form and submit it
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '';

                    const actionInput = document.createElement('input');
                    actionInput.type = 'hidden';
                    actionInput.name = 'action';
                    actionInput.value = 'delete';

                    const filenameInput = document.createElement('input');
                    filenameInput.type = 'hidden';
                    filenameInput.name = 'filename';
                    filenameInput.value = slug;

                    form.appendChild(actionInput);
                    form.appendChild(filenameInput);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    </script>
</body>
</html>
