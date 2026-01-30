# Guide : Ajout automatique d'images aux articles

## Utilisation simplifiée

Le script `add-image-to-article.php` ajoute automatiquement une image à la une à vos articles en analysant leur contenu et en cherchant sur Google Images.

### Commande

```bash
cd /var/vhosts/loi-jeanbrun-gouv.test/actualites
php add-image-to-article.php "nom-fichier-article"
```

**Exemple:**
```bash
php add-image-to-article.php "2026-01-20-guide-investisseurs"
```

### Ce que fait le script

1. **📖 Lecture de l'article** : Analyse le titre et la description
2. **🔍 Génération des mots-clés** : Crée automatiquement une recherche en français adaptée au thème
3. **🌐 Recherche Google Images** : Trouve des images de grande taille (filtre automatique)
4. **⬇️ Téléchargement de 5 candidates** : Télécharge les 5 premières images accessibles
5. **🎯 Sélection intelligente** : Analyse et compare selon plusieurs critères
6. **🔄 Optimisation** : Redimensionne à 1200px minimum si nécessaire
7. **📝 Mise à jour** : Ajoute automatiquement le `featured_image` dans le front matter

### Système de scoring

Le script attribue un score à chaque image selon 3 critères:

#### 1. Résolution (max 50 points)
- **50 pts** : Largeur >= 1600px ⭐ Excellent
- **40 pts** : Largeur >= 1200px ✓ Bon
- **20 pts** : Largeur >= 800px ~ Acceptable
- **5 pts** : Largeur < 800px ⚠️ Faible

#### 2. Ratio d'aspect (max 30 points)
- **30 pts** : Ratio 1.3-2.0 ⭐ Format paysage idéal pour bannières
- **20 pts** : Ratio 1.0-1.3 ✓ Format carré/légèrement paysage
- **15 pts** : Ratio 2.0-2.5 ~ Format très large acceptable
- **5 pts** : Autres ratios ⚠️ Formats extrêmes

#### 3. Taille de fichier (max 20 points)
- **20 pts** : 150-800 KB ⭐ Qualité/poids optimal
- **15 pts** : 800-1500 KB ✓ Haute qualité (un peu lourd)
- **10 pts** : 100-150 KB ~ Acceptable
- **5 pts** : Autres tailles ⚠️ Trop petit ou trop gros

**Score maximum : 100 points**

### Génération automatique des mots-clés

Le script analyse le titre et la description pour générer des mots-clés pertinents :

| Thème détecté | Mots-clés générés |
|--------------|-------------------|
| Classes moyennes | `classes moyennes logement france` |
| Investissement | `investissement immobilier locatif france` |
| Comparaison Pinel | `loi pinel vs jeanbrun immobilier france` |
| Avantages fiscaux | `avantage fiscal immobilier france` |
| Lancement | `nouveau dispositif logement france` |
| Bailleur | `bailleur privé logement france` |
| Dispositif (général) | `dispositif logement intermédiaire france` |
| Défaut | `logement intermédiaire france immobilier` |

### Exemples de résultats

**Article "guide-investisseurs" :**
- Mots-clés : `investissement immobilier locatif france`
- Image sélectionnée : 2500x1667 (100 pts) ⭐⭐⭐
- Ratio idéal 1.5, taille 298 KB

**Article "lancement-dispositif" :**
- Mots-clés : `nouveau dispositif logement france`
- Image sélectionnée : 5824x3281 (95 pts) ⭐⭐⭐
- Très haute résolution, ratio 1.77

**Article "classes-moyennes" :**
- Mots-clés : `classes moyennes logement france`
- Image sélectionnée : 1284x904 (90 pts) ⭐⭐
- Bon ratio 1.42, taille 189 KB

## Avantages

✅ **Automatique** : Plus besoin de chercher manuellement des images
✅ **Intelligent** : Sélectionne la meilleure parmi 5 candidates
✅ **Pertinent** : Mots-clés adaptés au contenu de l'article
✅ **Optimisé** : Images de qualité avec bon ratio qualité/poids
✅ **Légal** : Recherche sur Google Images en français

## Notes

- Le script nécessite PHP avec les extensions GD (pour le traitement d'images) et cURL
- Les images sont recherchées avec les filtres "grande taille" et "photos uniquement"
- Les fichiers temporaires sont automatiquement nettoyés
- Si une image existe déjà, elle sera remplacée
- Le front matter de l'article est automatiquement mis à jour

### Traitement des images

- **Minimum :** 1200px de large (upscale si nécessaire)
- **Maximum :** 2500px de large (downscale si > 2500px)
- **Plage optimale :** 1200-2500px (conservée telle quelle)
- **Format de sortie :** JPG à 90% de qualité

## Dépannage

**❌ Aucune image trouvée**
- Vérifiez votre connexion internet
- Google Images peut parfois bloquer les requêtes automatiques (attendez quelques minutes)

**❌ Échec du téléchargement**
- Normal, certaines images ne sont pas accessibles directement
- Le script essaie automatiquement les suivantes

**❌ Image invalide**
- Certains sites bloquent le téléchargement direct
- Le script passe automatiquement à l'image suivante
