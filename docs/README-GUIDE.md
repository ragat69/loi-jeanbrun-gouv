# Guide PDF Loi Jeanbrun - Documentation

## 📚 Contenu du Guide

Le guide complet du dispositif Jeanbrun comprend **40 pages** couvrant :

### Chapitres

1. **Comprendre le dispositif Jeanbrun**
   - Contexte de la crise du logement
   - Présentation du dispositif
   - Comparatif Pinel vs Jeanbrun

2. **Fonctionnement du mécanisme**
   - Principe de l'amortissement fiscal
   - Taux d'amortissement selon le type de bien et de loyer
   - Plafonds et limitations

3. **Avantages fiscaux**
   - Amortissement du bien (jusqu'à 12 000€/an)
   - Déficit foncier amplifié (21 400€/an)
   - Déduction des charges à 100%
   - Intérêts d'emprunt déductibles

4. **Conditions d'éligibilité**
   - Conditions sur le bien immobilier
   - Conditions sur le locataire
   - Plafonds de loyers et de ressources
   - Engagement de location (9 ans)

5. **Mise en pratique**
   - Étapes pour bénéficier du dispositif
   - Exemples de calculs détaillés
   - Erreurs à éviter
   - Conseils d'optimisation

6. **FAQ**
   - 15 questions fréquentes avec réponses détaillées

---

## 📂 Fichiers Générés

### Template HTML
- **Fichier** : `docs/guide-loi-jeanbrun-template.html`
- **Format** : HTML5 avec styles CSS intégrés
- **Utilisation** : Source pour génération PDF ou consultation en ligne

### Version Téléchargeable
- **Fichier** : `docs/guide-loi-jeanbrun-2026.html` (ou .pdf si wkhtmltopdf installé)
- **Format** : HTML ou PDF
- **Poids** : ~500 Ko (HTML) / ~2 MB (PDF)
- **URL** : `/docs/guide-loi-jeanbrun-2026.html`

---

## 🎨 Encart de Téléchargement

### Fichier Include
- **Chemin** : `includes/guide-download-card.php`
- **Type** : Include PHP réutilisable

### Intégration sur une Page

Pour ajouter l'encart de téléchargement sur n'importe quelle page :

```php
<!-- Ajouter où vous voulez afficher l'encart -->
<?php include 'includes/guide-download-card.php'; ?>
```

### Exemples d'intégration

**Sur la homepage** (déjà fait) :
```php
// Avant le footer
<?php include 'includes/guide-download-card.php'; ?>
```

**Sur la page Fonctionnement** :
```php
// En fin de page, avant les CTA
<?php include 'includes/guide-download-card.php'; ?>
```

**Sur la page Avantages** :
```php
// Après la section des avantages fiscaux
<?php include 'includes/guide-download-card.php'; ?>
```

### Personnalisation

Pour personnaliser l'apparence, modifier le fichier :
```
includes/guide-download-card.php
```

Les styles CSS sont inclus dans le fichier pour faciliter la personnalisation.

---

## 🔄 Génération du PDF

### Méthode 1 : Avec wkhtmltopdf (Recommandé)

**Installation :**

```bash
# Ubuntu/Debian
sudo apt-get update
sudo apt-get install wkhtmltopdf

# MacOS
brew install wkhtmltopdf

# CentOS/RHEL
sudo yum install wkhtmltopdf
```

**Génération :**

```bash
php generate-pdf-guide.php
```

Le PDF sera généré dans `docs/guide-loi-jeanbrun-2026.pdf`.

### Méthode 2 : Conversion en ligne

Si vous ne pouvez pas installer wkhtmltopdf :

1. Ouvrir `docs/guide-loi-jeanbrun-template.html` dans Chrome/Firefox
2. Fichier → Imprimer → Enregistrer au format PDF
3. Sauvegarder comme `docs/guide-loi-jeanbrun-2026.pdf`

### Méthode 3 : Service en ligne

Utiliser un service comme :
- https://html2pdf.com
- https://www.sejda.com/html-to-pdf
- https://cloudconvert.com/html-to-pdf

---

## 📊 Analytics

Le bouton de téléchargement inclut un tracking Google Analytics :

```javascript
function trackGuideDownload() {
    gtag('event', 'download', {
        'event_category': 'Guide',
        'event_label': 'Guide Loi Jeanbrun 2026',
        'value': 1
    });
}
```

Pour activer le tracking, assurez-vous que Google Analytics est configuré sur votre site.

---

## 🎯 Mise à Jour du Guide

Pour mettre à jour le contenu :

1. Éditer `docs/guide-loi-jeanbrun-template.html`
2. Régénérer le PDF : `php generate-pdf-guide.php`
3. Le nouveau guide sera automatiquement disponible au téléchargement

---

## ✅ Checklist Déploiement

- [x] Template HTML créé
- [x] Encart de téléchargement créé
- [x] Script de génération PDF créé
- [x] Intégration sur la homepage
- [ ] Installer wkhtmltopdf (optionnel)
- [ ] Générer le PDF final
- [ ] Ajouter l'encart sur d'autres pages (fonctionnement, avantages, etc.)
- [ ] Tester le téléchargement
- [ ] Vérifier le tracking analytics

---

## 🔗 URLs

- **Guide HTML** : https://loi-jeanbrun-gouv.com/docs/guide-loi-jeanbrun-2026.html
- **Guide PDF** : https://loi-jeanbrun-gouv.com/docs/guide-loi-jeanbrun-2026.pdf (après génération)

---

## 📝 Notes

- Le guide est totalement gratuit et ne nécessite aucune inscription
- Le fichier HTML est léger (~500 Ko) et peut servir d'alternative au PDF
- Le design est responsive et imprimable
- Le contenu est basé sur les informations officielles disponibles en février 2026
