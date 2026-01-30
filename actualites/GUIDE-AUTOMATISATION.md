# Guide d'automatisation de la publication d'articles

Ce guide vous explique comment automatiser la création et la publication d'articles sur votre blog en utilisant l'IA et des tâches planifiées (cron).

---

## Table des matières

1. [Vue d'ensemble](#vue-densemble)
2. [Prérequis](#prérequis)
3. [Script de génération automatique](#script-de-génération-automatique)
4. [Configuration cron](#configuration-cron)
5. [Sources d'images automatiques](#sources-dimages-automatiques)
6. [Personnalisation](#personnalisation)
7. [Monitoring et logs](#monitoring-et-logs)
8. [Dépannage](#dépannage)

---

## Vue d'ensemble

### Workflow automatisé

```
┌─────────────────────────────────────────────────────┐
│  1. Cron déclenche le script (ex: tous les lundis) │
└─────────────────────────────────────────────────────┘
                        ↓
┌─────────────────────────────────────────────────────┐
│  2. Script demande à l'IA de générer un article     │
│     (OpenAI, Claude, ou autre API)                  │
└─────────────────────────────────────────────────────┘
                        ↓
┌─────────────────────────────────────────────────────┐
│  3. Script télécharge une image depuis Unsplash     │
│     ou Pexels (gratuit, libre de droits)            │
└─────────────────────────────────────────────────────┘
                        ↓
┌─────────────────────────────────────────────────────┐
│  4. Script crée le fichier .md avec front matter    │
│     dans actualites/posts/                          │
└─────────────────────────────────────────────────────┘
                        ↓
┌─────────────────────────────────────────────────────┐
│  5. Script commit et push vers Git                  │
└─────────────────────────────────────────────────────┘
                        ↓
┌─────────────────────────────────────────────────────┐
│  6. Serveur détecte les changements                 │
│     Article publié automatiquement !                │
└─────────────────────────────────────────────────────┘
```

### Cas d'usage

- **Publication régulière** : Maintenir un rythme de publication constant
- **Actualités automatiques** : Générer des résumés de nouvelles du secteur
- **Contenu SEO** : Créer du contenu optimisé pour le référencement
- **Gain de temps** : Libérer du temps pour la relecture et l'amélioration

---

## Prérequis

### Sur le serveur

- PHP 7.4+ avec extension cURL
- Git configuré avec accès push
- Cron (généralement pré-installé sur Linux)
- Accès SSH au serveur

### API et services

- **Clé API OpenAI** (https://platform.openai.com) ou
- **Clé API Anthropic/Claude** (https://console.anthropic.com) ou
- **Autre API d'IA de votre choix**

- **Clé API Unsplash** (https://unsplash.com/developers) - Gratuit
  ou
- **Clé API Pexels** (https://www.pexels.com/api/) - Gratuit

---

## Script de génération automatique

### Créer le script principal

Créez un fichier `auto-generate-article.php` dans le dossier `actualites/` :

```bash
cd /var/vhosts/loi-jeanbrun-gouv.test/actualites
nano auto-generate-article.php
```

### Code du script

```php
<?php
/**
 * Script de génération automatique d'articles
 * Usage: php auto-generate-article.php
 */

// Configuration
$config = [
    'openai_api_key' => 'VOTRE_CLE_API_OPENAI',
    'unsplash_api_key' => 'VOTRE_CLE_API_UNSPLASH',
    'blog_topic' => 'dispositif Jeanbrun et logement intermédiaire',
    'auto_publish' => true, // true pour publier automatiquement, false pour draft
];

// Thèmes d'articles possibles
$article_topics = [
    "Les avantages du dispositif Jeanbrun pour les investisseurs",
    "Comment le logement intermédiaire répond à la crise du logement",
    "Guide pratique : réussir son investissement Jeanbrun",
    "Le rôle du logement intermédiaire dans les grandes métropoles",
    "Témoignages d'investisseurs ayant utilisé le dispositif Jeanbrun",
    "Comparaison : Jeanbrun vs autres dispositifs fiscaux",
    "L'impact social du logement intermédiaire en France",
    "Les zones éligibles au dispositif Jeanbrun en 2026",
];

// Choisir un sujet aléatoire
$topic = $article_topics[array_rand($article_topics)];

echo "🤖 Génération automatique d'article...\n";
echo "📝 Sujet: $topic\n\n";

// 1. Générer le contenu avec OpenAI
echo "1️⃣  Génération du contenu...\n";
$article_content = generate_article_with_ai($topic, $config['openai_api_key']);

if (!$article_content) {
    die("❌ Erreur lors de la génération du contenu\n");
}

echo "✅ Contenu généré (" . str_word_count($article_content['content']) . " mots)\n\n";

// 2. Télécharger une image
echo "2️⃣  Téléchargement d'une image...\n";
$image_filename = download_unsplash_image($topic, $config['unsplash_api_key']);

if (!$image_filename) {
    echo "⚠️  Pas d'image trouvée, l'article sera publié sans image\n";
}

echo "✅ Image téléchargée: $image_filename\n\n";

// 3. Créer le fichier markdown
echo "3️⃣  Création du fichier article...\n";
$filename = create_article_file($article_content, $image_filename, $config['auto_publish']);

echo "✅ Article créé: $filename\n\n";

// 4. Publier avec Git
if ($config['auto_publish']) {
    echo "4️⃣  Publication sur Git...\n";
    $git_result = publish_to_git($filename, $article_content['title']);

    if ($git_result) {
        echo "✅ Article publié avec succès!\n";
        echo "🌐 Visible sur le site dans quelques minutes\n";
    } else {
        echo "⚠️  Article créé mais non publié sur Git (vérifiez manuellement)\n";
    }
} else {
    echo "📄 Article sauvegardé en brouillon\n";
}

echo "\n🎉 Terminé!\n";

// ============================================================================
// FONCTIONS
// ============================================================================

/**
 * Génère un article avec OpenAI
 */
function generate_article_with_ai($topic, $api_key) {
    $prompt = "Écris un article de blog en français sur le sujet suivant : \"$topic\".

L'article doit :
- Faire entre 600 et 800 mots
- Être informatif et bien structuré
- Utiliser des sous-titres (##)
- Inclure des listes à puces quand pertinent
- Avoir un ton professionnel mais accessible
- Être optimisé pour le SEO

Format de réponse :
{
  \"title\": \"Titre de l'article\",
  \"description\": \"Description courte de 150-160 caractères\",
  \"content\": \"Contenu en Markdown\"
}";

    $ch = curl_init('https://api.openai.com/v1/chat/completions');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $api_key
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
        'model' => 'gpt-4',
        'messages' => [
            ['role' => 'system', 'content' => 'Tu es un expert en rédaction d\'articles de blog sur l\'immobilier et la fiscalité.'],
            ['role' => 'user', 'content' => $prompt]
        ],
        'temperature' => 0.7,
    ]));

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code !== 200) {
        echo "Erreur API: HTTP $http_code\n";
        return false;
    }

    $result = json_decode($response, true);
    $content_json = $result['choices'][0]['message']['content'] ?? null;

    if (!$content_json) {
        return false;
    }

    // Extraire le JSON de la réponse
    preg_match('/\{.*\}/s', $content_json, $matches);
    if (!$matches) {
        return false;
    }

    return json_decode($matches[0], true);
}

/**
 * Télécharge une image depuis Unsplash
 */
function download_unsplash_image($query, $api_key) {
    $query_encoded = urlencode($query);
    $url = "https://api.unsplash.com/photos/random?query=$query_encoded&orientation=landscape";

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Client-ID ' . $api_key
    ]);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code !== 200) {
        return false;
    }

    $data = json_decode($response, true);
    $image_url = $data['urls']['regular'] ?? null;

    if (!$image_url) {
        return false;
    }

    // Télécharger l'image
    $image_data = file_get_contents($image_url);
    $date = date('Y-m-d');
    $filename = $date . '-' . uniqid() . '.jpg';
    $filepath = __DIR__ . '/images/' . $filename;

    file_put_contents($filepath, $image_data);

    return $filename;
}

/**
 * Crée le fichier markdown de l'article
 */
function create_article_file($article, $image_filename, $publish = true) {
    $date = date('Y-m-d');
    $slug = sanitize_slug($article['title']);
    $filename = $date . '-' . $slug;
    $filepath = __DIR__ . '/posts/' . $filename . '.md';

    // Front matter
    $content = "---\n";
    $content .= "title: " . $article['title'] . "\n";
    $content .= "date: $date\n";
    $content .= "description: " . $article['description'] . "\n";
    if ($image_filename) {
        $content .= "featured_image: $image_filename\n";
    }
    $content .= "status: " . ($publish ? 'published' : 'draft') . "\n";
    $content .= "---\n\n";

    // Content
    $content .= $article['content'];

    file_put_contents($filepath, $content);

    return $filename . '.md';
}

/**
 * Publie l'article sur Git
 */
function publish_to_git($filename, $title) {
    $repo_dir = dirname(__DIR__);

    $commands = [
        "cd " . escapeshellarg($repo_dir),
        "git add actualites/posts/" . escapeshellarg($filename),
        "git add actualites/images/",
        "git commit -m " . escapeshellarg("[Auto] New article: " . $title),
        "git push"
    ];

    $command = implode(' && ', $commands) . ' 2>&1';
    exec($command, $output, $return_code);

    return $return_code === 0;
}

/**
 * Nettoie une chaîne pour créer un slug URL
 */
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
```

### Rendre le script exécutable

```bash
chmod +x auto-generate-article.php
```

### Tester le script

```bash
php auto-generate-article.php
```

---

## Configuration cron

### Ajouter une tâche cron

Sur le serveur, éditez le crontab :

```bash
crontab -e
```

### Exemples de planification

#### Tous les lundis à 9h

```cron
0 9 * * 1 /usr/bin/php /var/vhosts/loi-jeanbrun-gouv.test/actualites/auto-generate-article.php >> /var/log/blog-auto.log 2>&1
```

#### Tous les mercredis et vendredis à 10h

```cron
0 10 * * 3,5 /usr/bin/php /var/vhosts/loi-jeanbrun-gouv.test/actualites/auto-generate-article.php >> /var/log/blog-auto.log 2>&1
```

#### Tous les jours à 8h30

```cron
30 8 * * * /usr/bin/php /var/vhosts/loi-jeanbrun-gouv.test/actualites/auto-generate-article.php >> /var/log/blog-auto.log 2>&1
```

#### Une fois par semaine (dimanche à 20h)

```cron
0 20 * * 0 /usr/bin/php /var/vhosts/loi-jeanbrun-gouv.test/actualites/auto-generate-article.php >> /var/log/blog-auto.log 2>&1
```

### Format cron

```
* * * * * commande
│ │ │ │ │
│ │ │ │ └─── Jour de la semaine (0-7, 0 et 7 = dimanche)
│ │ │ └───── Mois (1-12)
│ │ └─────── Jour du mois (1-31)
│ └───────── Heure (0-23)
└─────────── Minute (0-59)
```

### Vérifier que cron fonctionne

```bash
# Voir les logs
tail -f /var/log/blog-auto.log

# Lister les tâches cron
crontab -l
```

---

## Sources d'images automatiques

### Unsplash API (Recommandé)

**Inscription :**
1. Créez un compte sur https://unsplash.com/join
2. Allez sur https://unsplash.com/developers
3. Créez une nouvelle application
4. Copiez votre "Access Key"

**Avantages :**
- Photos haute qualité
- Gratuites et libres de droits
- 50 requêtes/heure (gratuit)

**Utilisation dans le script :**
```php
$config['unsplash_api_key'] = 'VOTRE_ACCESS_KEY';
```

### Pexels API

**Inscription :**
1. Créez un compte sur https://www.pexels.com/
2. Demandez une clé API sur https://www.pexels.com/api/
3. Copiez votre API Key

**Modifier le script pour Pexels :**
```php
function download_pexels_image($query, $api_key) {
    $query_encoded = urlencode($query);
    $url = "https://api.pexels.com/v1/search?query=$query_encoded&per_page=1&orientation=landscape";

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: ' . $api_key
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($response, true);
    $image_url = $data['photos'][0]['src']['large'] ?? null;

    if (!$image_url) {
        return false;
    }

    $image_data = file_get_contents($image_url);
    $date = date('Y-m-d');
    $filename = $date . '-' . uniqid() . '.jpg';
    $filepath = __DIR__ . '/images/' . $filename;

    file_put_contents($filepath, $image_data);

    return $filename;
}
```

---

## Personnalisation

### Modifier les sujets d'articles

Éditez la liste `$article_topics` dans le script :

```php
$article_topics = [
    "Vos sujets personnalisés ici",
    "Un autre sujet",
    // ...
];
```

### Changer la fréquence

Modifiez le crontab pour ajuster la fréquence de publication.

### Utiliser Claude au lieu d'OpenAI

```php
function generate_article_with_claude($topic, $api_key) {
    $ch = curl_init('https://api.anthropic.com/v1/messages');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'x-api-key: ' . $api_key,
        'anthropic-version: 2023-06-01'
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
        'model' => 'claude-3-5-sonnet-20241022',
        'max_tokens' => 2000,
        'messages' => [
            ['role' => 'user', 'content' => "Écris un article de blog sur : $topic (format JSON avec title, description, content)"]
        ]
    ]));

    $response = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($response, true);
    $content = $result['content'][0]['text'] ?? null;

    if (!$content) {
        return false;
    }

    preg_match('/\{.*\}/s', $content, $matches);
    return json_decode($matches[0], true);
}
```

### Mode brouillon par défaut

Pour générer en mode brouillon et relire avant publication :

```php
$config['auto_publish'] = false;
```

Ensuite, vérifiez les articles dans l'interface d'administration et publiez manuellement.

---

## Monitoring et logs

### Créer un fichier de log détaillé

Modifiez le script pour logger plus d'informations :

```php
// Au début du script
$log_file = __DIR__ . '/logs/auto-generation.log';

function log_message($message) {
    global $log_file;
    $timestamp = date('Y-m-d H:i:s');
    file_put_contents($log_file, "[$timestamp] $message\n", FILE_APPEND);
    echo $message . "\n";
}

// Utiliser log_message() au lieu de echo
log_message("🤖 Génération automatique d'article...");
```

### Créer un dashboard de monitoring

Créez `actualites/admin-local/stats.php` :

```php
<?php
// Afficher les statistiques des articles auto-générés
$posts_dir = __DIR__ . '/../posts';
$files = glob($posts_dir . '/*.md');

$auto_generated = 0;
foreach ($files as $file) {
    $content = file_get_contents($file);
    if (strpos($content, '[Auto]') !== false) {
        $auto_generated++;
    }
}

echo "Articles auto-générés : $auto_generated\n";
echo "Articles manuels : " . (count($files) - $auto_generated) . "\n";
echo "Total : " . count($files) . "\n";
```

### Notifications par email

Ajoutez à la fin du script :

```php
// Envoyer un email de notification
$to = 'votre@email.com';
$subject = '✅ Nouvel article publié automatiquement';
$message = "Article publié : {$article_content['title']}\nURL: https://votresite.com/actualites/{$date}/{$slug}";

mail($to, $subject, $message);
```

---

## Dépannage

### Le cron ne s'exécute pas

**Vérifications :**
1. Le chemin vers PHP est correct : `which php`
2. Le chemin vers le script est absolu
3. Les permissions sont correctes : `chmod +x script.php`
4. Vérifiez les logs cron : `grep CRON /var/log/syslog`

### L'API retourne une erreur

**OpenAI :**
- Vérifiez la clé API
- Vérifiez le crédit disponible sur votre compte
- Vérifiez la limite de requêtes

**Unsplash :**
- Vérifiez la clé API
- Limitée à 50 requêtes/heure (gratuit)

### Git push échoue

**Solutions :**
1. Configurez Git pour push sans mot de passe (SSH keys)
2. Vérifiez les permissions du dépôt
3. Testez manuellement : `git push` depuis le serveur

### Articles de mauvaise qualité

**Améliorations :**
1. Affinez le prompt pour l'IA
2. Ajoutez des exemples d'articles dans le prompt
3. Utilisez un modèle plus puissant (GPT-4 au lieu de GPT-3.5)
4. Passez en mode brouillon et relisez avant publication

---

## Sécurité

### Protéger les clés API

**Ne jamais commiter les clés dans Git !**

Utilisez un fichier de configuration séparé :

```php
// config.php (ajouté au .gitignore)
<?php
return [
    'openai_api_key' => 'sk-...',
    'unsplash_api_key' => '...',
];
```

```php
// Dans le script
$config = require __DIR__ . '/config.php';
```

Ajoutez au `.gitignore` :
```
actualites/config.php
```

---

## Checklist de mise en place

- [ ] Script créé et testé manuellement
- [ ] Clés API obtenues (OpenAI + Unsplash)
- [ ] Configuration sécurisée (pas de clés dans Git)
- [ ] Crontab configuré
- [ ] Premier article auto-généré avec succès
- [ ] Logs configurés
- [ ] Notification par email (optionnel)
- [ ] Monitoring en place

---

**Dernière mise à jour :** 30 janvier 2026
