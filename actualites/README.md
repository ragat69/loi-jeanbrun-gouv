# Blog "Actualités" - Loi Jeanbrun

Système de blog léger et performant basé sur des fichiers Markdown, sans base de données.

## 🎯 Caractéristiques

- ✅ **Aucune base de données** - Articles stockés en fichiers Markdown
- ✅ **Interface d'administration locale** - Sécurisée, accessible uniquement sur localhost
- ✅ **SEO optimisé** - Métadonnées complètes, Schema.org JSON-LD, Open Graph
- ✅ **Git intégré** - Publication automatique via Git
- ✅ **Images optimisées** - Redimensionnement et compression automatiques
- ✅ **Responsive** - Design adapté mobile/tablette/desktop
- ✅ **Réutilisable** - Peut être copié sur d'autres sites facilement
- ✅ **Automatisation** - Support pour génération d'articles par IA

## 📁 Structure

```
actualites/
├── posts/                  # Articles au format Markdown
│   ├── 2026-01-15-lancement-dispositif-jeanbrun.md
│   ├── 2026-01-20-guide-investisseurs.md
│   └── ...
├── images/                 # Images des articles
│   ├── 2026-01-15-image.jpg
│   └── ...
├── admin-local/           # Interface d'administration locale
│   ├── index.php
│   ├── start-admin.sh     # Lancement Mac/Linux
│   ├── start-admin.bat    # Lancement Windows
│   ├── publish-to-git.sh  # Publication Git (Mac/Linux)
│   └── publish-to-git.bat # Publication Git (Windows)
├── index.php              # Page liste des articles
├── article.php            # Page affichage d'un article
├── functions.php          # Bibliothèque de fonctions
├── GUIDE-PUBLIER.md       # Guide de publication
├── GUIDE-KDRIVE.md        # Guide synchronisation kDrive
├── GUIDE-AUTOMATISATION.md # Guide automatisation IA
└── README.md              # Ce fichier
```

## 🚀 Démarrage rapide

### Publier un article (méthode interface)

1. **Lancer l'interface d'administration :**
   ```bash
   cd actualites/admin-local
   ./start-admin.sh  # Mac/Linux
   # ou
   start-admin.bat   # Windows
   ```

2. **Accéder à l'interface :**
   Ouvrez http://localhost:8080/actualites/admin-local/

3. **Créer votre article :**
   - Remplissez le formulaire
   - Uploadez une image (optionnel)
   - Cochez "Auto-commit and push to Git"
   - Cliquez sur "Save Article"

4. **Vérifier :**
   L'article apparaît sur https://votresite.com/actualites

### Publier un article (méthode Git)

1. **Créer un fichier Markdown :**
   ```bash
   cd actualites/posts
   nano 2026-01-30-mon-article.md
   ```

2. **Ajouter le contenu :**
   ```markdown
   ---
   title: Titre de mon article
   date: 2026-01-30
   description: Description courte
   featured_image: 2026-01-30-image.jpg
   status: published
   ---

   ## Introduction

   Contenu de l'article en **Markdown**.
   ```

3. **Publier :**
   ```bash
   cd ../../actualites/admin-local
   ./publish-to-git.sh "Nouvel article: Mon article"
   ```

## 📖 Documentation

- **[GUIDE-PUBLIER.md](GUIDE-PUBLIER.md)** - Guide complet de publication (interface + Git)
- **[GUIDE-KDRIVE.md](GUIDE-KDRIVE.md)** - Configuration avec kDrive/Dropbox
- **[GUIDE-AUTOMATISATION.md](GUIDE-AUTOMATISATION.md)** - Automatisation avec IA + cron

## 🔧 Configuration

### URLs générées

- Homepage blog : `/actualites`
- Pagination : `/actualites/page-2`, `/actualites/page-3`, etc.
- Article : `/actualites/2026-01-30/titre-de-article`

### Format des articles

Chaque article est un fichier Markdown avec :
- **Nom de fichier** : `YYYY-MM-DD-titre-article.md`
- **Front matter** : Métadonnées en YAML entre `---`
- **Contenu** : Markdown standard

### Métadonnées disponibles

```yaml
---
title: Titre (obligatoire)
date: 2026-01-30 (obligatoire)
description: Description pour SEO (recommandé)
seo_title: Titre personnalisé SEO (optionnel)
featured_image: nom-image.jpg (optionnel)
status: published ou draft (défaut: published)
---
```

## 🎨 Personnalisation

Le blog utilise le même design que le site principal :
- **Navigation** : Partagée via `includes/header.php`
- **Footer** : Partagé via `includes/footer.php`
- **Styles** : Bootstrap 5.3 + `css/style.css`
- **Couleurs** : Variables CSS du gouvernement français

Pour personnaliser :
1. Modifiez `css/style.css` pour les styles globaux
2. Les articles utilisent automatiquement les classes Bootstrap
3. Le markdown est converti en HTML avec les classes appropriées

## 🔒 Sécurité

- ✅ **Admin local uniquement** - Interface accessible seulement sur localhost
- ✅ **Validation des uploads** - Types et tailles d'images contrôlés
- ✅ **Sanitisation** - Tous les inputs sont nettoyés
- ✅ **Git sécurisé** - Utilise vos credentials SSH/HTTPS existants

## 🌐 SEO

Chaque article génère automatiquement :
- **Meta title** personnalisable
- **Meta description** optimisée
- **Open Graph** tags (Facebook, LinkedIn)
- **Twitter Cards**
- **Schema.org Article** (JSON-LD)
- **Canonical URL**
- **URL friendly** avec date et slug

## 📱 Responsive

Le blog est entièrement responsive :
- **Mobile** : Navigation collapsible, images fluides
- **Tablette** : Grille adaptative (2 colonnes)
- **Desktop** : Grille 3 colonnes

## ♻️ Réutilisation

Pour réutiliser ce blog sur un autre site :

1. **Copier le dossier `actualites/`**
2. **Copier les entrées .htaccess**
3. **Adapter le header/footer** selon le nouveau site
4. **Ajuster les couleurs** dans `style.css`

Tout est autonome et portable !

## 🤖 Automatisation

Le blog supporte la génération automatique d'articles via :
- API OpenAI ou Claude pour le contenu
- API Unsplash ou Pexels pour les images
- Cron pour la planification

Voir [GUIDE-AUTOMATISATION.md](GUIDE-AUTOMATISATION.md) pour la mise en place.

## 🛠️ Dépannage

### L'article n'apparaît pas

- Vérifiez le `status: published` dans le front matter
- Videz le cache du navigateur
- Vérifiez que le fichier est bien dans `posts/`

### L'image ne s'affiche pas

- Vérifiez le nom du fichier dans `featured_image`
- Vérifiez que l'image est dans `images/`
- Vérifiez le format (JPG, PNG, GIF, WebP)

### Git push échoue

- Vérifiez votre connexion internet
- Vérifiez vos credentials Git
- Faites `git pull` avant de push

## 📊 Performance

- **Aucune requête DB** - Lecture simple de fichiers
- **Cache possible** - Peut être mis en cache facilement
- **Images optimisées** - Redimensionnement automatique
- **Markdown léger** - Parsing rapide

## 🔄 Mises à jour

Pour mettre à jour le système de blog :

```bash
git pull
```

Les articles existants ne seront pas affectés.

## 📝 Licence

Fait pour le site Loi Jeanbrun. Libre de réutilisation pour vos projets.

## 🆘 Support

Consultez les guides :
1. [GUIDE-PUBLIER.md](GUIDE-PUBLIER.md) - Problèmes de publication
2. [GUIDE-KDRIVE.md](GUIDE-KDRIVE.md) - Problèmes de synchronisation
3. [GUIDE-AUTOMATISATION.md](GUIDE-AUTOMATISATION.md) - Problèmes d'automatisation

---

**Version** : 1.0.0
**Date** : 30 janvier 2026
