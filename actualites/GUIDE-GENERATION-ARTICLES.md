# Guide complet : Génération automatique d'articles

Ce guide décrit le processus complet de création et publication d'articles pour la section Actualités du site Loi Jeanbrun.

## Table des matières

1. [Vue d'ensemble](#vue-densemble)
2. [Création d'un article](#création-dun-article)
3. [Structure et syntaxe](#structure-et-syntaxe)
4. [Ajout automatique d'image](#ajout-automatique-dimage)
5. [Publication](#publication)
6. [Automatisation avec IA](#automatisation-avec-ia)

---

## Vue d'ensemble

Le système de blog utilise des fichiers Markdown avec front matter YAML, stockés dans `actualites/posts/`. Chaque article suit un processus simple:

```
Création → Ajout image → Publication
```

**Formats supportés:**
- Markdown avec front matter YAML
- Images: JPG, PNG, WebP (converties en JPG)
- Résolution minimale: 1200px de large

**URLs générées:**
```
/actualites                              → Liste des articles
/actualites/YYYY-MM-DD/titre-article     → Article individuel
```

---

## Création d'un article

### Méthode 1 : Interface admin locale (recommandée)

**Accès:** http://localhost/actualites/admin-local/

L'interface admin permet de:
- Créer des articles avec formulaire visuel
- Uploader une image (optimisée automatiquement)
- Prévisualiser le résultat
- Commit Git automatique (optionnel)

**Restrictions de sécurité:**
- ⚠️ Accessible uniquement en localhost
- Bloqué si accès depuis l'extérieur

### Méthode 2 : Création manuelle

Créer un fichier dans `actualites/posts/` avec le format:
```
YYYY-MM-DD-titre-slug.md
```

**Exemple:** `2026-01-30-nouvelle-aide-logement.md`

**Convention de nommage:**
- Date au format ISO (YYYY-MM-DD)
- Slug en minuscules
- Mots séparés par des tirets
- Pas d'accents ni caractères spéciaux
- Extension `.md`

---

## Structure et syntaxe

### Front matter obligatoire

Chaque article commence par un bloc YAML entre `---`:

```yaml
---
title: Titre complet de l'article
date: 2026-01-30
description: Description courte pour SEO et réseaux sociaux (150-160 caractères)
status: published
---
```

### Front matter optionnel

```yaml
---
title: Titre de l'article
date: 2026-01-30
description: Description courte
seo_title: Titre SEO personnalisé (si différent du titre)
featured_image: 2026-01-30-article.jpg
modified: 2026-01-31
status: published
---
```

**Champs disponibles:**
- `title` : Titre affiché (obligatoire)
- `date` : Date de publication au format YYYY-MM-DD (obligatoire)
- `description` : Meta description pour SEO (obligatoire, 150-160 caractères)
- `seo_title` : Titre personnalisé pour `<title>` (optionnel)
- `featured_image` : Nom du fichier image (optionnel, auto-généré)
- `modified` : Date de dernière modification (optionnel)
- `status` : `published` ou `draft` (obligatoire)

### Syntaxe Markdown

Le contenu utilise Markdown standard:

```markdown
## Titre niveau 2

### Titre niveau 3

**Texte en gras** et *texte en italique*

- Liste à puces
- Item 2

1. Liste numérotée
2. Item 2

[Lien interne](/simulation)
[Lien externe](https://example.com)

> Citation en bloc

`Code inline`
```

**Styles appliqués automatiquement:**
- H1 (titre) : Rouge avec barre bleue (inversé par rapport au site)
- H2 : Bleu avec barre rouge
- H3 : Bleu avec barre rouge (plus petit)
- Liens : Bleu France avec soulignement au survol
- Citations : Bordure bleue à gauche
- Listes : Puces bleues personnalisées

### Règles de rédaction ⭐ IMPORTANT

**Ces règles DOIVENT être respectées pour tous les articles:**

#### 1. Longueur minimum: 1200 mots

Chaque article doit contenir **au minimum 1200 mots** de contenu (hors front matter).
- Développez les idées en profondeur
- Ajoutez des exemples concrets et chiffrés
- Incluez des contextes et explications détaillées
- Préférez la qualité à la quantité de sections

#### 2. Style journalistique

**ÉVITER** une succession de listes à puces. Privilégier un **style narratif et journalistique**:

✅ **BON (style journalistique):**
```markdown
Le dispositif Jeanbrun s'adresse avant tout aux classes moyennes, ces ménages qui gagnent trop pour prétendre au logement social mais pas assez pour accéder facilement à la propriété dans les zones tendues. Avec des **plafonds de ressources adaptés**, le dispositif permet à un couple sans enfant de bénéficier du dispositif jusqu'à 56 000€ de revenus annuels en zone A bis.
```

❌ **MAUVAIS (trop de listes):**
```markdown
Le dispositif cible:
- Les classes moyennes
- Les ménages aux revenus modérés
- Les couples sans enfant
- Les familles monoparentales
```

**Utilisation des listes:**
- Les listes sont acceptables pour des énumérations courtes et factuelles
- Maximum 2-3 listes par article
- Préférer les paragraphes narratifs pour développer les idées

#### 3. Mise en gras avec parcimonie

Mettre en gras **quelques mots ou expressions clés** pour faciliter la lecture:
- 3 à 8 éléments en gras par article (pas plus)
- Uniquement les concepts importants ou chiffres clés
- Utilisation naturelle, pas systématique
- Éviter de mettre en gras des phrases entières

✅ **BON:**
```markdown
La réduction d'impôt peut atteindre **25% du montant investi**, avec un plafond de **300 000€ sur 9 ans**.
```

❌ **MAUVAIS (trop de gras):**
```markdown
**La réduction d'impôt** peut atteindre **25%** du **montant investi**, avec un **plafond** de **300 000€** sur **9 ans**.
```

#### 4. Maillage interne obligatoire

Chaque article DOIT contenir **entre 1 et 3 liens internes** vers d'autres pages du site:

**Pages disponibles pour liens:**
- `/simulation` - Simulateur
- `/fonctionnement` - Comment ça marche
- `/avantages` - Avantages du dispositif
- `/bailleur-prive` - Page bailleurs privés
- `/investisseur` - Page investisseurs
- `/locataire` - Page locataires
- `/questions-reponses` - FAQ
- Autres articles du blog

**Règles:**
- Minimum: 1 lien interne
- Maximum: 3 liens internes
- Liens naturels dans le texte, pas forcés
- Privilégier les liens pertinents au contexte

✅ **BON:**
```markdown
Pour estimer précisément votre réduction d'impôt, utilisez notre [simulateur en ligne](/simulation).
```

#### 5. FAQ obligatoire avec Schema.org

Chaque article DOIT contenir une **section FAQ** en bas, avant la navigation précédent/suivant:

**Structure de la FAQ:**
- Titre: `## Questions fréquentes` (H2)
- 3 à 5 questions pertinentes au sujet de l'article
- Format accordion (accordéon) pour l'affichage
- Balisage Schema.org JSON-LD pour le référencement

**Format Markdown de la FAQ:**
```markdown
## Questions fréquentes

**Question 1 : Titre de la question ?**

Réponse détaillée à la question 1.

**Question 2 : Titre de la question ?**

Réponse détaillée à la question 2.
```

**Balisage Schema.org (JSON-LD):**

Ajouter dans le front matter YAML un champ `faq_schema` contenant le JSON (sans les balises `<script>`):

```yaml
---
title: Mon article
date: 2026-01-30
description: Description
status: published
faq_schema: |
  {
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
      },
      {
        "@type": "Question",
        "name": "Question 2 ?",
        "acceptedAnswer": {
          "@type": "Answer",
          "text": "Réponse à la question 2."
        }
      }
    ]
  }
---
```

⚠️ **Important:** Dans l'interface admin, coller UNIQUEMENT le JSON dans le champ "Schema FAQ", **SANS** les balises `<script>`. Les balises seront ajoutées automatiquement lors de la publication.

**Résumé checklist:**
- [ ] 1200 mots minimum
- [ ] Style journalistique (paragraphes narratifs)
- [ ] 2-3 listes maximum
- [ ] 3-8 mots/expressions en gras
- [ ] 1-3 liens internes pertinents
- [ ] FAQ avec 3-5 questions en fin d'article
- [ ] Schema.org JSON-LD pour la FAQ

### Exemple complet

```markdown
---
title: Les 5 avantages du dispositif Jeanbrun pour les investisseurs
date: 2026-01-30
description: Découvrez les 5 avantages majeurs du dispositif Jeanbrun pour optimiser votre investissement immobilier locatif en France.
status: published
---

## Introduction

Le dispositif Jeanbrun offre des avantages significatifs pour les investisseurs en immobilier locatif. Voici les 5 points clés à connaître.

### 1. Réduction d'impôt substantielle

La **réduction d'impôt peut atteindre 25% du montant investi**, soit jusqu'à 50 000€ sur un investissement de 200 000€. Cette économie fiscale se répartit sur 9 ans.

### 2. Demande locative forte

Les logements intermédiaires répondent à un besoin réel :

- Zones tendues identifiées par l'État
- Demande supérieure à l'offre
- Locataires stables (classes moyennes)

Pour plus d'informations, consultez notre [simulateur](/simulation).

> "Le dispositif Jeanbrun m'a permis d'optimiser ma fiscalité tout en contribuant à l'offre de logement." - Investisseur à Lyon
```

---

## Ajout automatique d'image

### Script automatique (recommandé)

Le script `add-image-to-article.php` analyse l'article et trouve automatiquement une image pertinente sur Google Images.

**Commande:**
```bash
cd /var/vhosts/loi-jeanbrun-gouv.test/actualites
php add-image-to-article.php "2026-01-30-titre-article"
```

**Processus automatique:**
1. 📖 Lecture du titre et description
2. 🔍 Génération automatique des mots-clés en français
3. 🌐 Recherche sur Google Images (filtre: grande taille + photos)
4. ⬇️ Téléchargement de 5 images candidates
5. 🎯 Sélection de la meilleure (système de scoring)
6. 🔄 Optimisation (1200px minimum)
7. 📝 Mise à jour du front matter

**Système de scoring (max 100 points):**
- **Résolution** (50 pts) : Privilégie >= 1600px
- **Ratio** (30 pts) : Format paysage 1.3-2.0 idéal pour bannières
- **Taille** (20 pts) : 150-800 KB optimal (qualité/poids)

**Génération des mots-clés:**

Le script détecte automatiquement le thème et génère des mots-clés pertinents:

| Thème détecté | Mots-clés générés |
|--------------|-------------------|
| Classes moyennes | `classes moyennes logement france` |
| Investissement | `investissement immobilier locatif france` |
| Comparaison Pinel | `loi pinel vs jeanbrun immobilier france` |
| Avantages fiscaux | `avantage fiscal immobilier france` |
| Réduction impôt | `réduction impot immobilier france` |
| Lancement | `nouveau dispositif logement france` |
| Bailleur/Propriétaire | `bailleur privé logement france` |
| Dispositif (général) | `dispositif logement intermédiaire france` |
| Défaut | `logement intermédiaire france immobilier` |

**Exemple de résultat:**
```
📖 Lecture de l'article...
🔍 Mots-clés générés: investissement immobilier locatif france
🔍 Recherche sur Google Images...
✓ 85 image(s) trouvée(s)
⬇️ Téléchargement des images candidates...
  Image #1: https://blog.example.com/image.jpg
    ✓ 2500x1667 - 298 KB
  Image #2: ...
✓ 5 image(s) téléchargée(s)
🎯 Analyse et sélection de la meilleure image...
✅ Meilleure image sélectionnée: 2500x1667 (100 pts) ⭐⭐⭐
🔄 Traitement final...
📐 Dimensions: 2500x1667
✓ Image déjà >= 1200px
✅ Image sauvegardée: 2026-01-30-titre-article.jpg
📝 Mise à jour de l'article...
✓ featured_image mis à jour

🎉 Terminé!
```

### Ajout manuel d'image

Si vous préférez choisir l'image manuellement:

1. Placer l'image dans `actualites/img/`
2. Nommer avec le même nom que l'article: `2026-01-30-article.jpg`
3. Ajouter dans le front matter:
   ```yaml
   featured_image: 2026-01-30-article.jpg
   ```

**Spécifications techniques:**
- Format: JPG recommandé (PNG/WebP acceptés, convertis automatiquement en JPG)
- Largeur:
  - **Minimum:** 1200px (upscale automatique si inférieur)
  - **Maximum:** 2500px (downscale automatique si supérieur)
  - **Plage optimale:** 1200-2500px (conservée telle quelle)
- Ratio: Format paysage 1.3-2.0 idéal pour bannières
- Poids: 150-800 KB recommandé
- Qualité: 90% JPEG
- L'image apparaît au-dessus du titre (max 800px affiché, 1200-2500px source)

---

## Publication

### Avec Git (méthode standard)

```bash
cd /var/vhosts/loi-jeanbrun-gouv.test/actualites

# Ajouter les fichiers
git add posts/2026-01-30-titre-article.md
git add img/2026-01-30-titre-article.jpg

# Commit
git commit -m "Ajout article: Titre de l'article"

# Push (si repository distant configuré)
git push origin main
```

### Avec l'admin local

L'interface admin propose une option "Commit Git automatique" qui effectue automatiquement:
```bash
git add posts/[article].md img/[image].jpg
git commit -m "Ajout article: [titre]"
```

### Publication immédiate

Les articles avec `status: published` apparaissent immédiatement sur:
- Page d'accueil des actualités: `/actualites`
- Flux RSS: `/actualites/rss`
- URLs individuelles: `/actualites/YYYY-MM-DD/slug`

### Brouillons

Pour garder un article en brouillon (non visible publiquement):
```yaml
status: draft
```

---

## Automatisation avec IA

### Génération via Claude

Pour générer un article complet automatiquement avec Claude, utilisez cette structure de prompt:

```
Génère un article de blog pour le site loi-jeanbrun-gouv.test sur le thème:
[THÈME DE L'ARTICLE]

Spécifications:
- Public cible: [investisseurs / classes moyennes / bailleurs / grand public]
- Ton: [informatif / pédagogique / technique]
- Longueur: [court 500 mots / moyen 800 mots / long 1200+ mots]

Format requis:
- Front matter YAML complet (title, date, description, status)
- Contenu Markdown avec titres H2/H3
- Au moins 1 liste à puces ou numérotée
- 1-2 liens internes vers /simulation ou /bailleur-prive
- Optionnel: citation ou témoignage
- Description SEO optimisée (150-160 caractères)

Le fichier doit être créé dans actualites/posts/ avec le format YYYY-MM-DD-slug.md
```

**Exemple concret:**
```
Génère un article de blog sur "Comment les classes moyennes peuvent accéder
au logement avec la loi Jeanbrun"

Spécifications:
- Public cible: Classes moyennes (salariés, jeunes actifs)
- Ton: Pédagogique et rassurant
- Longueur: 800 mots

Inclure:
- Définition des classes moyennes selon le dispositif
- Plafonds de ressources avec exemples
- Avantages concrets (loyers modérés, qualité)
- 2-3 témoignages courts
- Lien vers le simulateur
```

### Workflow complet automatisé

```bash
# 1. Génération de l'article avec Claude
# Claude crée le fichier dans actualites/posts/

# 2. Ajout automatique d'image
cd /var/vhosts/loi-jeanbrun-gouv.test/actualites
php add-image-to-article.php "2026-01-30-article"

# 3. Publication Git
git add posts/2026-01-30-article.md img/2026-01-30-article.jpg
git commit -m "Ajout article: [titre]"
git push
```

**Avec Claude en mode automatique:**
Claude peut exécuter toute la chaîne automatiquement:
1. Créer le fichier article .md
2. Exécuter add-image-to-article.php
3. Faire le commit Git

Il suffit de demander: "Crée un article complet sur [sujet] et publie-le"

---

## Checklist de publication

Avant de publier un article, vérifier:

### Contenu
- [ ] Front matter complet (title, date, description, status)
- [ ] Description SEO entre 150-160 caractères
- [ ] Au moins 2-3 sections (H2 ou H3)
- [ ] 1-2 liens internes pertinents
- [ ] Pas de fautes d'orthographe

### Image
- [ ] Image présente et pertinente
- [ ] Résolution >= 1200px
- [ ] Format paysage (ratio 1.3-2.0)
- [ ] Poids raisonnable (< 1 MB)

### Technique
- [ ] Nom de fichier correct (YYYY-MM-DD-slug.md)
- [ ] Status = published
- [ ] Article visible sur /actualites
- [ ] Image affichée correctement
- [ ] Pas d'erreurs dans les logs

### SEO
- [ ] Title unique et descriptif
- [ ] Description unique (pas de copier-coller)
- [ ] URL propre et lisible
- [ ] Image avec alt text (automatique)
- [ ] Open Graph fonctionnel

---

## Ressources

### Documentation technique
- [GUIDE-PUBLIER.md](GUIDE-PUBLIER.md) - Guide de publication détaillé
- [GUIDE-IMAGES-AUTO.md](GUIDE-IMAGES-AUTO.md) - Documentation du script d'images
- [README.md](README.md) - Vue d'ensemble du système

### Fichiers clés
- `actualites/posts/` - Articles (fichiers .md)
- `actualites/img/` - Images à la une
- `actualites/functions.php` - Fonctions PHP du blog
- `actualites/add-image-to-article.php` - Script d'ajout d'images

### URLs importantes
- Admin local: http://localhost/actualites/admin-local/
- Blog public: http://loi-jeanbrun-gouv.test/actualites
- Flux RSS: http://loi-jeanbrun-gouv.test/actualites/rss

---

## Dépannage

### L'article n'apparaît pas
- Vérifier `status: published` (pas draft)
- Vérifier le format de la date (YYYY-MM-DD)
- Vérifier le front matter (syntaxe YAML correcte)

### L'image ne s'affiche pas
- Vérifier que le fichier existe dans `actualites/img/`
- Vérifier le nom dans `featured_image:`
- Vérifier les permissions (644 pour les fichiers)

### Erreur du script d'images
- Vérifier la connexion internet
- Google peut bloquer les requêtes (attendre quelques minutes)
- Essayer de relancer le script
- Vérifier que PHP a les extensions GD et cURL

### Problèmes de formatage
- Vérifier la syntaxe Markdown
- Pas d'espaces avant les `---` du front matter
- Un saut de ligne après le front matter

---

## Bonnes pratiques

### Fréquence de publication
- **Minimum:** 1 article par mois
- **Idéal:** 1 article par semaine
- **Maximum:** Pas plus de 1 par jour (éviter le spam)

### Thèmes d'articles
- Actualités législatives (changements, nouveautés)
- Guides pratiques (investisseurs, locataires)
- Études de cas et témoignages
- Comparaisons avec autres dispositifs
- FAQ et questions fréquentes

### Optimisation SEO
- Varier les sujets et mots-clés
- Utiliser des titres accrocheurs
- Inclure des chiffres et statistiques
- Ajouter des appels à l'action (simulateur, contact)
- Mettre à jour les anciens articles si nécessaire

### Style éditorial
- Ton professionnel mais accessible
- Phrases courtes et claires
- Éviter le jargon technique excessif
- Utiliser des exemples concrets
- Structurer avec des listes et sections

### Navigation entre articles

Les pages d'articles incluent automatiquement une navigation précédent/suivant :
- **Article précédent** (plus ancien) : Affiché à gauche avec flèche ←
- **Article suivant** (plus récent) : Affiché à droite avec flèche →
- Les liens s'affichent en dessous du contenu, au-dessus du bouton "Retour aux actualités"
- Si aucun article précédent ou suivant n'existe, l'espace reste vide
- L'ordre est basé sur la date de publication (champ `date` du front matter)

Cette navigation améliore l'expérience utilisateur et le SEO en facilitant la découverte de contenu.

---

**Dernière mise à jour:** 2026-01-30
