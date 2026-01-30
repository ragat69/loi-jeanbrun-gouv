# Guide de publication d'articles

Ce guide vous explique comment publier des articles sur le blog "Actualités" de votre site, en utilisant soit l'interface d'administration locale, soit directement via Git.

---

## Table des matières

1. [Méthode 1 : Interface d'administration locale](#méthode-1--interface-dadministration-locale) (Recommandé)
2. [Méthode 2 : Publication via Git](#méthode-2--publication-via-git) (Avancé)
3. [Format des articles (Markdown)](#format-des-articles-markdown)
4. [Gestion des images](#gestion-des-images)
5. [SEO et métadonnées](#seo-et-métadonnées)
6. [Dépannage](#dépannage)

---

## Méthode 1 : Interface d'administration locale

### Prérequis

- PHP installé sur votre ordinateur
- Git installé
- Le dépôt cloné localement

### Étape 1 : Démarrer l'interface d'administration

#### Sur Mac/Linux :

```bash
cd /chemin/vers/loi-jeanbrun-gouv
cd actualites/admin-local
./start-admin.sh
```

Ou double-cliquez sur le fichier `start-admin.sh`

#### Sur Windows :

Double-cliquez sur le fichier `start-admin.bat`

Ou en ligne de commande :
```cmd
cd C:\chemin\vers\loi-jeanbrun-gouv\actualites\admin-local
start-admin.bat
```

### Étape 2 : Accéder à l'interface

Une fois le serveur démarré, ouvrez votre navigateur et accédez à :

```
http://localhost:8080/actualites/admin-local/
```

### Étape 3 : Créer un nouvel article

1. **Remplissez le formulaire :**
   - **Titre** : Le titre principal de votre article (obligatoire)
   - **Date** : La date de publication (par défaut : aujourd'hui)
   - **Status** : "Published" pour publier immédiatement, "Draft" pour un brouillon
   - **Image** : Sélectionnez une image depuis votre ordinateur (JPG, PNG, GIF, WebP, max 5MB)
   - **Description** : Résumé court pour le SEO et les réseaux sociaux (150-160 caractères recommandés)
   - **SEO Title** : Titre personnalisé pour les moteurs de recherche (optionnel, 60 caractères max)
   - **Content** : Le contenu de l'article en Markdown

2. **Options de publication :**
   - ✅ **Auto-commit and push to Git** : Cochez pour publier automatiquement (recommandé)
   - Si vous décochez, l'article sera sauvegardé localement mais non publié sur le site

3. **Cliquez sur "Save Article"**

### Étape 4 : Vérification

- Si la publication automatique est activée, l'article apparaîtra sur le site dans quelques minutes
- Visitez `https://votresite.com/actualites` pour voir votre nouvel article

---

## Méthode 2 : Publication via Git

Cette méthode est plus technique mais vous permet de publier depuis n'importe quel ordinateur avec Git.

### Étape 1 : Créer le fichier Markdown

Créez un nouveau fichier dans le dossier `actualites/posts/` avec le format de nom :

```
YYYY-MM-DD-titre-de-l-article.md
```

**Exemple :** `2026-01-30-nouveau-dispositif-fiscal.md`

### Étape 2 : Structurer l'article

Ouvrez le fichier avec un éditeur de texte et ajoutez le contenu au format suivant :

```markdown
---
title: Titre de l'article
date: 2026-01-30
description: Description courte pour le SEO
seo_title: Titre SEO personnalisé (optionnel)
featured_image: 2026-01-30-image-article.jpg
status: published
---

## Introduction

Votre contenu ici en **Markdown**.

### Sous-section

* Point 1
* Point 2

[Lien externe](https://example.com)
```

### Étape 3 : Ajouter une image (optionnel)

1. Placez votre image dans `actualites/images/`
2. Nommez-la avec la même convention : `YYYY-MM-DD-nom-image.jpg`
3. Référencez-la dans le front matter : `featured_image: 2026-01-30-nom-image.jpg`

### Étape 4 : Publier via Git

#### Méthode A : Script automatique (recommandé)

**Mac/Linux :**
```bash
cd actualites/admin-local
./publish-to-git.sh "Nouvel article: Titre de l'article"
```

**Windows :**
```cmd
cd actualites\admin-local
publish-to-git.bat "Nouvel article: Titre de l'article"
```

#### Méthode B : Commandes Git manuelles

```bash
# 1. Se placer à la racine du projet
cd /chemin/vers/loi-jeanbrun-gouv

# 2. Ajouter les fichiers
git add actualites/posts/2026-01-30-nouveau-dispositif-fiscal.md
git add actualites/images/2026-01-30-image-article.jpg

# 3. Commiter
git commit -m "Nouvel article: Nouveau dispositif fiscal"

# 4. Pousser vers le serveur
git push
```

### Étape 5 : Vérification

- Le serveur détectera automatiquement les changements
- L'article sera visible sur le site dans quelques minutes
- Visitez `https://votresite.com/actualites` pour vérifier

---

## Format des articles (Markdown)

### Front Matter (Métadonnées)

Chaque article commence par un bloc de métadonnées entre `---` :

```yaml
---
title: Titre de l'article (obligatoire)
date: 2026-01-30 (obligatoire)
description: Description courte (recommandé)
seo_title: Titre SEO personnalisé (optionnel)
featured_image: nom-image.jpg (optionnel)
status: published ou draft (défaut: published)
---
```

### Syntaxe Markdown

#### Titres

```markdown
# Titre niveau 1 (H1) - À éviter, le titre principal vient du front matter
## Titre niveau 2 (H2)
### Titre niveau 3 (H3)
```

#### Texte formaté

```markdown
**Texte en gras**
*Texte en italique*
```

#### Listes

```markdown
* Élément non ordonné
* Autre élément

1. Élément ordonné
2. Autre élément
```

#### Liens

```markdown
[Texte du lien](https://example.com)
[Lien interne](/fonctionnement)
```

#### Images dans le contenu

```markdown
![Texte alternatif](/actualites/images/nom-image.jpg)
```

#### Citations

```markdown
> Ceci est une citation
```

### Exemple complet

```markdown
---
title: Les avantages du dispositif Jeanbrun pour les investisseurs
date: 2026-01-30
description: Découvrez tous les avantages fiscaux et patrimoniaux du nouveau dispositif Jeanbrun pour optimiser votre investissement locatif.
featured_image: 2026-01-30-avantages-jeanbrun.jpg
status: published
---

## Introduction au dispositif

Le dispositif Jeanbrun offre de **nombreux avantages** pour les investisseurs souhaitant se lancer dans le logement intermédiaire.

### Avantages fiscaux

* Réduction d'impôt jusqu'à 25%
* Étalement sur plusieurs années
* Cumul possible avec d'autres dispositifs

### Avantages patrimoniaux

L'investissement dans le dispositif Jeanbrun permet également :

1. Constitution d'un patrimoine immobilier
2. Revenus locatifs complémentaires
3. Valorisation à long terme

> "Le dispositif Jeanbrun représente une opportunité unique pour les investisseurs." - Expert immobilier

## Pour en savoir plus

Consultez notre [page de simulation](/simulation) pour calculer votre avantage fiscal.
```

---

## Gestion des images

### Formats acceptés

- JPG / JPEG (recommandé)
- PNG
- GIF
- WebP

### Taille recommandée

- **Largeur idéale :** 1200px
- **Taille maximale :** 5MB
- L'image sera automatiquement redimensionnée si elle dépasse 1200px

### Nommage des images

Format : `YYYY-MM-DD-description-image.jpg`

**Exemples :**
- `2026-01-30-dispositif-jeanbrun.jpg`
- `2026-02-15-investissement-immobilier.png`

### Optimisation automatique

Lorsque vous uploadez une image via l'interface d'administration :
- Redimensionnement automatique à 1200px de largeur
- Compression à 85% de qualité
- Conversion du nom de fichier en format URL-friendly

### Droits d'image

⚠️ **Important :** Assurez-vous d'avoir les droits sur les images que vous utilisez.

**Sources d'images gratuites :**
- [Unsplash](https://unsplash.com) - Photos gratuites haute qualité
- [Pexels](https://pexels.com) - Banque d'images libres
- [Pixabay](https://pixabay.com) - Images et illustrations gratuites

---

## SEO et métadonnées

### Optimisation du titre

- **Longueur idéale :** 50-60 caractères
- Inclure le mot-clé principal
- Être descriptif et accrocheur

**Exemples :**
- ✅ "Dispositif Jeanbrun : Guide complet 2026"
- ❌ "Article sur le logement" (trop vague)

### Description (Meta description)

- **Longueur idéale :** 150-160 caractères
- Résumer l'article en une phrase
- Inclure un appel à l'action
- Utiliser des mots-clés pertinents

**Exemple :**
```
Découvrez comment le dispositif Jeanbrun peut vous faire économiser jusqu'à 25% d'impôts sur votre investissement locatif. Guide complet et simulation gratuite.
```

### Image mise en avant (Featured Image)

L'image mise en avant est utilisée pour :
- La miniature dans la liste des articles
- L'image de partage sur les réseaux sociaux (Open Graph)
- L'en-tête de l'article

**Dimensions recommandées :** 1200x630px (ratio 1.91:1)

### Schema.org

Les articles génèrent automatiquement des données structurées Schema.org :
- Type : Article
- Titre, description, image
- Date de publication
- Auteur (organisation)

Cela améliore le référencement et l'affichage dans les résultats Google.

### URLs des articles

Format automatique : `/actualites/YYYY-MM-DD/titre-article`

**Exemple :** `/actualites/2026-01-30/dispositif-jeanbrun-guide-complet`

- Lisible et compréhensible
- Inclut la date pour le contexte
- Optimisé pour le SEO

---

## Dépannage

### L'interface d'administration ne se lance pas

**Problème :** Message d'erreur au lancement de `start-admin.sh`

**Solutions :**
1. Vérifiez que PHP est installé : `php -v`
2. Vérifiez que vous êtes dans le bon dossier
3. Sur Mac/Linux, vérifiez les permissions : `chmod +x start-admin.sh`

### "Access denied" dans l'interface

**Problème :** Message "Access denied. This admin interface only works on localhost."

**Cause :** L'interface est accessible uniquement sur localhost pour des raisons de sécurité.

**Solution :** Assurez-vous d'accéder via `http://localhost:8080` et non via l'IP du serveur.

### L'image ne s'affiche pas

**Problème :** L'article est publié mais l'image n'apparaît pas

**Solutions :**
1. Vérifiez que le nom du fichier image est correct dans le front matter
2. Vérifiez que l'image est bien dans `actualites/images/`
3. Vérifiez le format du fichier (JPG, PNG, GIF, WebP uniquement)
4. Videz le cache de votre navigateur

### Le Git push échoue

**Problème :** Erreur lors du `git push`

**Solutions :**
1. Vérifiez votre connexion internet
2. Assurez-vous d'être authentifié avec Git
3. Faites un `git pull` d'abord pour récupérer les dernières modifications
4. En cas de conflit, résolvez-le avant de pousser

### L'article n'apparaît pas sur le site

**Problème :** L'article est publié mais invisible sur le site

**Solutions :**
1. Vérifiez le status dans le front matter : doit être `published`
2. Attendez quelques minutes pour la synchronisation
3. Videz le cache du site si activé
4. Vérifiez que le serveur a bien récupéré les modifications : `git pull` sur le serveur

### Erreur "Failed to save article"

**Problème :** L'interface affiche une erreur lors de la sauvegarde

**Solutions :**
1. Vérifiez les permissions du dossier `actualites/posts/`
2. Assurez-vous que le dossier existe
3. Vérifiez que le titre ne contient pas de caractères spéciaux problématiques

---

## Conseils et bonnes pratiques

### Fréquence de publication

- **Régularité :** Publiez à intervalles réguliers (hebdomadaire ou bi-mensuel)
- **Qualité > Quantité :** Privilégiez des articles bien documentés
- **Planification :** Préparez vos articles à l'avance

### Longueur des articles

- **Minimum :** 300 mots
- **Idéal :** 600-1000 mots
- **Maximum :** Pas de limite, mais privilégiez la lisibilité

### Structure d'un bon article

1. **Introduction** : Présentez le sujet et l'objectif
2. **Développement** : Structurez avec des sous-titres (H2, H3)
3. **Exemples concrets** : Illustrations, cas pratiques
4. **Conclusion** : Résumez et proposez une action
5. **Liens internes** : Renvoyez vers d'autres pages du site

### Relecture

Avant de publier :
- ✅ Relisez pour corriger les fautes
- ✅ Vérifiez les liens
- ✅ Testez l'affichage sur mobile
- ✅ Optimisez le SEO (titre, description)
- ✅ Vérifiez l'image mise en avant

---

## Support

Si vous rencontrez des difficultés non couvertes par ce guide :

1. Consultez les logs d'erreur PHP
2. Vérifiez la configuration de votre serveur
3. Consultez la documentation Git en cas de problème de synchronisation

---

**Dernière mise à jour :** 30 janvier 2026
