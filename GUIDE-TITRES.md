# Guide de gestion des titres de pages

## Vue d'ensemble

Le système de titres vous donne un **contrôle total** sur le titre `<title>` de chaque page, visible dans l'onglet du navigateur et les résultats de recherche Google.

---

## Comment ça fonctionne

### Système actuel (contrôle total)

Toutes les pages du site utilisent maintenant `$page_title_full` avec le titre complet écrit en entier :

```php
<?php
$page_title_full = 'Accueil | Loi Jeanbrun - Dispositif Relance Logement';
$page_description = '...';
include 'includes/header.php';
?>
```

**Résultat dans le code HTML :**
```html
<title>Accueil | Loi Jeanbrun - Dispositif Relance Logement</title>
```

### Avantages

✅ **Visibilité totale** : Vous voyez le titre complet directement dans le code PHP
✅ **Modification facile** : Changez n'importe quelle partie du titre
✅ **Contrôle absolu** : Plus de génération automatique
✅ **SEO précis** : Optimisez chaque titre pour le référencement

---

## Modification des titres

### Étape 1 : Ouvrir le fichier PHP

Exemple : Modifier le titre de la page d'accueil

```bash
nano index.php
```

### Étape 2 : Modifier la variable `$page_title_full`

**Avant :**
```php
$page_title_full = 'Accueil | Loi Jeanbrun - Dispositif Relance Logement';
```

**Après (exemples) :**
```php
// Version courte
$page_title_full = 'Loi Jeanbrun - Accueil';

// Version optimisée SEO
$page_title_full = 'Dispositif Jeanbrun 2026 : Investissement Locatif Fiscal';

// Version longue personnalisée
$page_title_full = 'Loi Jeanbrun - Tout savoir sur le dispositif Relance Logement 2026';

// Sans suffixe du tout
$page_title_full = 'Investissement Locatif avec la Loi Jeanbrun';
```

### Étape 3 : Enregistrer

Le titre sera immédiatement mis à jour sur le site.

---

## Liste des pages et leurs titres actuels

| Fichier | Variable | Titre actuel |
|---------|----------|--------------|
| [index.php](index.php) | `$page_title_full` | Accueil \| Loi Jeanbrun - Dispositif Relance Logement |
| [fonctionnement.php](fonctionnement.php) | `$page_title_full` | Fonctionnement \| Loi Jeanbrun - Dispositif Relance Logement |
| [avantages.php](avantages.php) | `$page_title_full` | Avantages fiscaux \| Loi Jeanbrun - Dispositif Relance Logement |
| [bailleur-prive.php](bailleur-prive.php) | `$page_title_full` | Statut Bailleur Prive \| Loi Jeanbrun - Dispositif Relance Logement |
| [eligibilite.php](eligibilite.php) | `$page_title_full` | Éligibilité \| Loi Jeanbrun - Dispositif Relance Logement |
| [simulation.php](simulation.php) | `$page_title_full` | Simulation \| Loi Jeanbrun - Dispositif Relance Logement |
| [vincent-jeanbrun.php](vincent-jeanbrun.php) | `$page_title_full` | Vincent Jeanbrun \| Loi Jeanbrun - Dispositif Relance Logement |
| [pinel-vs-jeanbrun.php](pinel-vs-jeanbrun.php) | `$page_title_full` | Comparatif Pinel vs Jeanbrun \| Loi Jeanbrun - Dispositif Relance Logement |
| [mentions-legales.php](mentions-legales.php) | `$page_title_full` | Mentions legales \| Loi Jeanbrun - Dispositif Relance Logement |

---

## Bonnes pratiques SEO pour les titres

### Longueur optimale

- **Idéal** : 50-60 caractères
- **Maximum** : 70 caractères (sinon Google coupe le titre)
- **Minimum** : 30 caractères

### Structure recommandée

```
[Mot-clé principal] | [Marque/Site]
```

**Exemples :**
```
Dispositif Jeanbrun 2026 | Guide Complet Investissement
Simulation Loi Jeanbrun | Calculez vos Économies d'Impôt
Vincent Jeanbrun | Ministre du Logement - Biographie
```

### Éléments à inclure

✅ **Mot-clé principal** au début (important pour le SEO)
✅ **Valeur ajoutée** (Guide, Simulateur, Comparatif, etc.)
✅ **Nom du site/marque** à la fin (optionnel)
✅ **Année** si pertinent (2026)

### À éviter

❌ Titres trop longs (> 70 caractères)
❌ Titres identiques sur plusieurs pages
❌ Bourrage de mots-clés
❌ Titres non descriptifs ("Page 1", "Accueil")
❌ TOUT EN MAJUSCULES

---

## Système alternatif (si besoin)

Si vous voulez revenir à un système semi-automatique, vous pouvez utiliser `$page_title` au lieu de `$page_title_full` :

```php
// Avec $page_title (ajoute automatiquement le suffixe)
$page_title = 'Accueil';
// Résultat : Accueil | Loi Jeanbrun - Dispositif Relance Logement

// Avec $page_title_full (titre complet personnalisé)
$page_title_full = 'Mon titre totalement personnalisé';
// Résultat : Mon titre totalement personnalisé
```

Le système dans [includes/header.php](includes/header.php:7-19) gère les deux :

1. **Priorité 1** : `$page_title_full` → Utilisé tel quel
2. **Priorité 2** : `$page_title` → Suffixe ajouté automatiquement
3. **Par défaut** : Titre par défaut du site

---

## Exemples de modifications courantes

### Ajouter l'année

**Avant :**
```php
$page_title_full = 'Fonctionnement | Loi Jeanbrun - Dispositif Relance Logement';
```

**Après :**
```php
$page_title_full = 'Fonctionnement Loi Jeanbrun 2026 | Guide Complet';
```

### Optimiser pour un mot-clé

**Avant :**
```php
$page_title_full = 'Avantages fiscaux | Loi Jeanbrun - Dispositif Relance Logement';
```

**Après :**
```php
$page_title_full = 'Défiscalisation Immobilière Loi Jeanbrun | Avantages 2026';
```

### Simplifier

**Avant :**
```php
$page_title_full = 'Éligibilité | Loi Jeanbrun - Dispositif Relance Logement';
```

**Après :**
```php
$page_title_full = 'Êtes-vous éligible à la Loi Jeanbrun ?';
```

---

## Vérification

### Voir le titre dans le navigateur

1. Ouvrez la page dans votre navigateur
2. Regardez le texte dans l'onglet
3. Ou faites un clic droit → "Afficher le code source"
4. Cherchez la balise `<title>`

### Voir le titre dans les résultats Google

1. Faites une recherche Google : `site:votresite.com`
2. Les titres affichés correspondent à vos balises `<title>`

### Tester avec l'outil Google

Testez vos titres avec : https://www.highervisibility.com/seo/tools/serp-snippet-optimizer/

---

## Pour le blog (Actualités)

Les articles du blog utilisent un système différent, géré dans [actualites/article.php](actualites/article.php).

Le titre est généré à partir des métadonnées de l'article :

```markdown
---
title: Mon article
seo_title: Titre personnalisé pour Google (optionnel)
---
```

Si `seo_title` est défini, il sera utilisé. Sinon, le titre de l'article + "Actualités Loi Jeanbrun".

Voir [actualites/GUIDE-PUBLIER.md](actualites/GUIDE-PUBLIER.md) pour plus de détails.

---

## Support

Pour toute question sur les titres ou le SEO, consultez :
- Google Search Central : https://developers.google.com/search/docs/appearance/title-link
- Yoast SEO Title Guide : https://yoast.com/page-titles-seo/

---

**Dernière mise à jour :** 30 janvier 2026
