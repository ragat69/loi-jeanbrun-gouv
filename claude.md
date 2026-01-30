# Instructions pour Claude Code

Ce fichier contient des instructions et références importantes pour les futures sessions Claude Code sur ce projet.

## À propos du projet

**Site:** Loi Jeanbrun - Dispositif Relance Logement
**Type:** Site vitrine gouvernemental français
**URL locale:** http://loi-jeanbrun-gouv.test
**Design:** Style gouvernement français (bleu-france #000091, rouge-marianne #E1000F)

## Structure du projet

```
/var/vhosts/loi-jeanbrun-gouv.test/
├── index.php                    # Page d'accueil
├── fonctionnement.php           # Comment ça marche
├── avantages.php               # Avantages du dispositif
├── simulation.php              # Simulateur en ligne
├── bailleur-prive.php          # Page bailleurs privés
├── investisseur.php            # Page investisseurs
├── locataire.php               # Page locataires
├── questions-reponses.php      # FAQ
├── actualites/                 # Section blog
│   ├── index.php              # Liste des articles
│   ├── article.php            # Page article individuelle
│   ├── rss.php                # Flux RSS
│   ├── functions.php          # Fonctions du blog
│   ├── posts/                 # Articles Markdown
│   ├── img/                   # Images à la une
│   ├── admin-local/           # Interface admin (localhost only)
│   └── add-image-to-article.php  # Script automatique d'images
├── includes/
│   ├── header.php             # En-tête commun
│   └── footer.php             # Pied de page commun
└── css/
    └── style.css              # Styles personnalisés
```

## Titres de page

⚠️ **IMPORTANT:** Les titres sont gérés avec `$page_title_full` pour un contrôle total.

**Format dans chaque page:**
```php
$page_title_full = 'Titre complet | Loi Jeanbrun - Dispositif Relance Logement';
```

Ne pas utiliser `$page_title` seul. Toujours définir le titre complet avec le pipe et la baseline.

## Section Actualités (Blog)

### 📚 Guide de référence principal

**Pour toute création d'article, TOUJOURS se référer à:**
```
/var/vhosts/loi-jeanbrun-gouv.test/actualites/GUIDE-GENERATION-ARTICLES.md
```

Ce guide contient:
- Processus complet de création d'articles
- Structure du front matter YAML
- Syntaxe Markdown et styles
- Ajout automatique d'images
- Publication et automation
- Bonnes pratiques SEO

### Création rapide d'article

**Workflow standard:**
```bash
# 1. Créer le fichier Markdown
actualites/posts/YYYY-MM-DD-titre-slug.md

# 2. Ajouter automatiquement une image
cd actualites
php add-image-to-article.php "YYYY-MM-DD-titre-slug"

# 3. Publier avec Git
git add posts/YYYY-MM-DD-titre-slug.md img/YYYY-MM-DD-titre-slug.jpg
git commit -m "Ajout article: Titre"
```

### Script d'images automatique

Le script `add-image-to-article.php`:
- ✅ Génère automatiquement les mots-clés en français
- ✅ Cherche sur Google Images (filtre: grande taille + photos)
- ✅ Télécharge 5 candidates et sélectionne la meilleure
- ✅ Scoring intelligent (résolution, ratio, taille)
- ✅ Optimise à 1200px minimum
- ✅ Met à jour le front matter automatiquement

**Ne pas demander les mots-clés à l'utilisateur**, le script les génère automatiquement.

### Front matter obligatoire

```yaml
---
title: Titre complet de l'article
date: 2026-01-30
description: Description SEO (150-160 caractères)
status: published
---
```

### Règles de rédaction OBLIGATOIRES

**Chaque article DOIT respecter:**

1. **1200 mots minimum** (hors front matter)
2. **Style journalistique** - Éviter succession de listes, privilégier paragraphes narratifs
3. **Listes limitées** - Maximum 2-3 listes par article, seulement si nécessaire
4. **Gras parcimonieux** - 3 à 8 mots/expressions en gras, naturellement intégrés
5. **Maillage interne** - Minimum 1 lien, maximum 3 vers pages du site ou autres articles
6. **FAQ obligatoire** - Section "Questions fréquentes" (## H2) en fin d'article avec 3-5 questions
7. **Schema.org FAQ** - Balisage JSON-LD dans le front matter (champ `faq_schema`)

Pages disponibles pour liens internes:
- /simulation, /fonctionnement, /avantages
- /bailleur-prive, /investisseur, /locataire
- /questions-reponses
- Autres articles du blog

**IMPORTANT pour le Schema FAQ:**
Dans l'interface admin, coller UNIQUEMENT le JSON dans le champ "Schema FAQ", SANS les balises `<script>`. Les balises seront ajoutées automatiquement.

## Git et publication

### Commits

Suivre le protocole Git standard (voir système reminder dans les outils):
- Ne jamais commit sans demande explicite
- Utiliser des messages descriptifs
- Ajouter le Co-Authored-By pour les commits

### Branches

- `main` : Branche principale (production)
- Pas de branche de développement configurée actuellement

## Style et design

### Couleurs principales

```css
--bleu-france: #000091
--rouge-marianne: #E1000F
--gris-element: #3A3A3A
```

### CSS important

**Articles de blog:**
- H1 article: Rouge avec barre bleue (inversé)
- H2/H3: Bleu avec barre rouge
- Styles dans `.article-content`

## Simulateur

Le simulateur calcule:
- Amortissement selon le type de bien (neuf/ancien) et type de loyer
- Déficit foncier imputable
- Économies d'impôt sur 9 ans

**Ne pas modifier la logique de calcul** sans validation utilisateur.

## Technologies

- **Backend:** PHP 8+ (pas de framework)
- **Frontend:** Bootstrap 5.3, FontAwesome
- **Blog:** Flat-file Markdown + YAML
- **Serveur:** Apache avec mod_rewrite
- **Images:** GD library pour traitement
- **Git:** Versioning et publication

## Commandes utiles

```bash
# Vérifier le statut Git
cd /var/vhosts/loi-jeanbrun-gouv.test
git status

# Lister les articles
ls -la actualites/posts/

# Tester une page
curl -I http://loi-jeanbrun-gouv.test/actualites

# Ajouter une image à un article
cd actualites
php add-image-to-article.php "nom-article"
```

## URLs importantes

- **Site:** http://loi-jeanbrun-gouv.test
- **Actualités:** http://loi-jeanbrun-gouv.test/actualites
- **RSS:** http://loi-jeanbrun-gouv.test/actualites/rss
- **Admin:** http://localhost/actualites/admin-local/ (localhost uniquement)

## Documentation

### Guides principaux (par ordre de priorité)

1. **[actualites/GUIDE-GENERATION-ARTICLES.md](actualites/GUIDE-GENERATION-ARTICLES.md)** ⭐⭐⭐
   - Guide complet de création d'articles
   - À consulter SYSTÉMATIQUEMENT pour tout travail sur le blog

2. **[actualites/GUIDE-IMAGES-AUTO.md](actualites/GUIDE-IMAGES-AUTO.md)**
   - Documentation du script d'images automatique
   - Système de scoring et génération de mots-clés

3. **[actualites/GUIDE-PUBLIER.md](actualites/GUIDE-PUBLIER.md)**
   - Guide de publication détaillé
   - Workflow Git

4. **[actualites/GUIDE-TITRES.md](actualites/GUIDE-TITRES.md)**
   - Gestion des titres de page

5. **[actualites/README.md](actualites/README.md)**
   - Vue d'ensemble du système de blog

## Règles importantes

### ⚠️ Ne JAMAIS

- Modifier les titres sans utiliser `$page_title_full`
- Commit sans demande explicite de l'utilisateur
- Utiliser d'autres chemins que `/actualites/img/` pour les images
- Créer des fichiers .md sans le bon format de nom (YYYY-MM-DD-slug.md)
- Oublier le front matter YAML dans les articles

### ✅ TOUJOURS

- Se référer à GUIDE-GENERATION-ARTICLES.md pour les articles
- Utiliser le script add-image-to-article.php pour les images
- Vérifier que `status: published` pour publier un article
- Tester les URLs après création/modification
- Suivre les conventions de nommage

## Prompt pour création d'article

Quand l'utilisateur demande de créer un article, utiliser ce workflow:

```
1. Créer le fichier .md dans actualites/posts/
   - Nom: YYYY-MM-DD-titre-slug.md
   - Front matter complet
   - Contenu Markdown structuré

2. Exécuter add-image-to-article.php
   - Pas de mots-clés manuels (auto-détection)

3. Proposer le commit Git (attendre validation)
```

## Historique des modifications importantes

- **2026-01-30:** Création du système d'images automatique avec scoring
- **2026-01-30:** Mise en place de la génération automatique de mots-clés
- **2026-01-30:** Unification des chemins d'images vers `/actualites/img/`
- **Avant:** Mise en place du blog flat-file Markdown
- **Avant:** Système de titres avec `$page_title_full`

## Contact et feedback

Pour signaler des bugs ou donner du feedback:
- GitHub Issues: https://github.com/anthropics/claude-code/issues

---

**Dernière mise à jour:** 2026-01-30

**Version du guide:** 1.0
