# Documentation Projet : Générateur de Pages Loi Jeanbrun

## Contexte du Projet

Création d'un système de génération automatique de pages HTML pour la Loi Jeanbrun (dispositif "Relance Logement" 2026), un mécanisme fiscal d'investissement locatif en France.

### Objectif
Générer des pages spécifiques par ville avec des informations différenciées (plafonds de loyer, prix du marché, simulations) pour optimiser le SEO et fournir du contenu géolocalisé.

### Problématique initiale
La Loi Jeanbrun s'applique uniformément à toutes les communes de France (pas de zonage restrictif pour l'éligibilité), mais les **plafonds de loyer varient selon la zone géographique** (Abis, A, B1, B2, C). Il fallait donc différencier les pages par ville malgré l'uniformité du dispositif.

## Ce qui a été réalisé

### 1. Script de génération Python
**Fichier:** `generate_jeanbrun_pages.py`

Script qui génère automatiquement des pages HTML personnalisées pour chaque ville.

**Fonctionnalités:**
- Dictionnaire `villes_data` contenant les données spécifiques de 10 grandes villes
- Fonction `generer_page_ville()` pour créer des pages individuelles
- Fonction `generer_page_index()` pour la page d'accueil
- Calculs automatiques (simulations, rendements, gains fiscaux)

**Données stockées par ville:**
```python
{
    "zone": "A",                    # Zonage ABC (détermine plafonds de loyer)
    "prix_m2_neuf": 5000,           # Prix marché neuf
    "prix_m2_ancien": 4000,         # Prix marché ancien
    "loyer_marche_m2": 15,          # Loyer marché moyen
    "population": 500000,
    "plafonds_loyer": {             # Spécifiques à la zone
        "intermediaire": 14.49,
        "social": 11.11,
        "tres_social": 8.33
    },
    "taux_vacance": 7.0,
    "projets_construction": 1500,
    "departement": "XX - Nom"
}
```

### 2. Pages HTML générées (11 fichiers)

**Page d'accueil:** `index.html`
- Vue d'ensemble du dispositif Jeanbrun
- Liste des 10 villes avec liens
- Tableau comparatif des zones et plafonds
- Navigation vers pages détaillées

**Pages ville:** (10 fichiers)
- `paris.html` (Zone Abis)
- `lyon.html`, `nice.html` (Zone A)
- `marseille.html`, `toulouse.html`, `nantes.html`, `montpellier.html`, `strasbourg.html`, `bordeaux.html`, `lille.html` (Zone B1)

### 3. Éléments différenciants par page

Chaque page ville contient des **informations spécifiques uniques:**

**Données géographiques:**
- Zone de classement (Abis/A/B1/B2/C)
- Département
- Population

**Marché immobilier local:**
- Prix au m² neuf et ancien
- Prix moyens par typologie (T2, T3, T4)
- Loyer de marché moyen

**Plafonds de loyer spécifiques:**
- Variables selon la zone
- Calculs pour chaque typologie de logement
- Taux d'amortissement associés

**Simulations personnalisées:**
- Basées sur les prix locaux
- Calculs d'amortissement spécifiques
- Gains fiscaux selon le marché local
- Rendement locatif adapté

**Contexte local:**
- Taux de vacance
- Projets de construction prévus
- Opportunités du territoire

## Structure des Fichiers

```
/
├── generate_jeanbrun_pages.py          # Script principal de génération
├── README.md                            # Documentation utilisateur
├── CLAUDE.md                            # Documentation technique (ce fichier)
└── pages_jeanbrun/                      # Dossier de sortie
    ├── index.html                       # Page d'accueil
    ├── paris.html                       # Pages ville
    ├── marseille.html
    ├── lyon.html
    ├── toulouse.html
    ├── nice.html
    ├── nantes.html
    ├── montpellier.html
    ├── strasbourg.html
    ├── bordeaux.html
    └── lille.html
```

## Utilisation du Script

### Génération initiale
```bash
python3 generate_jeanbrun_pages.py
```

Sortie: 11 fichiers HTML dans `/home/claude/pages_jeanbrun/`

### Ajouter une nouvelle ville

1. Ouvrir `generate_jeanbrun_pages.py`
2. Ajouter une entrée dans le dictionnaire `villes_data`:

```python
"Rennes": {
    "zone": "B1",
    "prix_m2_neuf": 4300,
    "prix_m2_ancien": 3500,
    "loyer_marche_m2": 13,
    "population": 220000,
    "plafonds_loyer": {
        "intermediaire": 10.93,
        "social": 8.38,
        "tres_social": 6.29
    },
    "taux_vacance": 6.8,
    "projets_construction": 1200,
    "departement": "35 - Ille-et-Vilaine"
}
```

3. Régénérer: `python3 generate_jeanbrun_pages.py`

### Modifier les données d'une ville

Éditer directement les valeurs dans `villes_data`, puis régénérer.

## Sources de Données Suggérées

Pour alimenter le script avec des données réelles:

### Zonage géographique
- **data.gouv.fr**: Base des zonages ABC
- **Géo API** (api.gouv.fr): Codes communes

### Prix immobiliers
- **DVF (Demandes de Valeurs Foncières)**: data.gouv.fr ou API DVF
- **Notaires de France**: Base BIEN (certaines données payantes)
- **SeLoger, LeBonCoin**: APIs ou scraping (vérifier CGU)

### Plafonds de loyer
- **Référentiel Loc'Avantages**: Base officielle
- **Décrets d'application**: À paraître pour la Loi Jeanbrun

### Marché locatif
- **Observatoire des loyers (OLS)**: Loyers médians
- **CLAMEUR**: Données d'encadrement des loyers

### Construction
- **Sit@del2** (SDES): Permis de construire, mises en chantier
- **Géoportail de l'urbanisme**: PLU, zonages

### Données démographiques
- **INSEE API**: Population, revenus
- **Base SIRENE**: Activité économique

## Intégration dans un Site Existant

### Option 1: Pages standalone
1. Copier les fichiers HTML
2. Ajouter le CSS du site via `<link>` dans `<head>`
3. Adapter la navigation

### Option 2: Extraire le contenu
1. Parser les `<section>` des pages générées
2. Intégrer dans des templates existants
3. Appliquer automatiquement le CSS

### Option 3: Système de templates
Modifier le script pour utiliser des templates Jinja2 ou similaire:

```python
from jinja2 import Template

template = Template(open('template.html').read())
html = template.render(ville=ville, data=data)
```

## Prochaines Évolutions Possibles

### 1. Automatisation complète via API
**Objectif**: Générer les pages avec données en temps réel

```python
import requests

# Récupérer prix DVF
response = requests.get('https://api.dvf.etalab.gouv.fr/...')
prix_m2 = response.json()['prix_moyen']

# Intégrer dans villes_data
villes_data['Paris']['prix_m2_neuf'] = prix_m2
```

**APIs à intégrer:**
- DVF pour les prix
- INSEE pour démographie
- Sit@del pour construction
- Géo API pour zonage

### 2. Génération de pages pour toutes les communes de France
**Approche:**
- Récupérer la liste des communes via Géo API
- Déterminer le zonage via data.gouv.fr
- Générer automatiquement les plafonds de loyer
- Créer des pages simplifiées pour petites communes

**Défi:** ~35 000 communes, besoin d'optimisation (génération à la demande, cache, etc.)

### 3. Ajout de graphiques et visualisations
```python
import matplotlib.pyplot as plt

# Générer graphique évolution prix
plt.plot(annees, prix)
plt.savefig(f'{ville}_evolution_prix.png')
```

Intégrer les images dans les pages HTML.

### 4. Système de mise à jour automatique
**Cron job quotidien/hebdomadaire:**
```bash
0 2 * * 1 cd /path/to/projet && python3 update_data.py && python3 generate_jeanbrun_pages.py
```

Script `update_data.py` qui:
- Récupère les dernières données des APIs
- Met à jour `villes_data`
- Lance la régénération

### 5. Backend dynamique
Transformer en application web avec Flask/Django:

```python
@app.route('/loi-jeanbrun/<ville>')
def page_ville(ville):
    data = get_ville_data(ville)  # Depuis BDD
    return render_template('ville.html', ville=ville, data=data)
```

**Avantages:**
- Données dynamiques
- Pas de régénération statique
- Possibilité de simulateur interactif

### 6. Calculateur interactif
Ajouter JavaScript pour simulation en temps réel:

```javascript
function calculerAmortissement() {
    const prix = parseFloat(document.getElementById('prix').value);
    const taux = 0.035;
    const amortissement = prix * 0.80 * taux;
    document.getElementById('resultat').textContent = amortissement;
}
```

### 7. Export en autres formats
- **PDF**: Utiliser wkhtmltopdf ou WeasyPrint
- **JSON**: API pour autres applications
- **CSV**: Tableaux de données exploitables

## Points Techniques Importants

### Calculs d'amortissement
```python
# Assiette amortissable = 80% du prix d'achat
assiette = prix_acquisition * 0.80

# Amortissement annuel selon le type de loyer
# Loyer intermédiaire: 3.5% plafonné à 8 000€
# Loyer social: 4.5% plafonné à 10 000€
# Loyer très social: 5.5% plafonné à 12 000€
amortissement = min(assiette * taux, plafond)

# Gain fiscal (TMI 30%)
gain_fiscal = amortissement * 0.30
```

### Plafonds de loyer par zone
Les plafonds sont basés sur le référentiel Loc'Avantages:

| Zone | Loyer intermédiaire | Loyer social | Loyer très social |
|------|---------------------|--------------|-------------------|
| Abis | 18.25 €/m²         | 14.00 €/m²   | 10.50 €/m²       |
| A    | 14.49 €/m²         | 11.11 €/m²   | 8.33 €/m²        |
| B1   | 10.93 €/m²         | 8.38 €/m²    | 6.29 €/m²        |
| B2   | 9.50 €/m²          | 7.28 €/m²    | 5.46 €/m²        |
| C    | 9.17 €/m²          | 7.03 €/m²    | 5.27 €/m²        |

### Pages sans CSS
Choix délibéré pour faciliter l'intégration dans n'importe quel site. Le HTML sémantique permet d'appliquer facilement n'importe quel framework CSS.

## Notes sur la Loi Jeanbrun

### Différences avec dispositif Pinel
- **Pinel**: Réduction d'impôt + zonage restrictif (zones tendues uniquement)
- **Jeanbrun**: Amortissement fiscal + pas de zonage (toute la France)

### Conditions d'éligibilité
- Logement en immeuble collectif (appartements uniquement)
- Neuf ou ancien avec travaux ≥ 30% du prix
- Engagement 9 ans
- Plafonds de loyer et de ressources locataires
- Pas de location à famille proche

### Avantages fiscaux
- Amortissement 3.5% à 5.5% selon type de loyer
- Déficit foncier imputable sur revenu global (21 400€ jusqu'en 2027)
- Réduction significative de l'imposition sur revenus locatifs

## Workflow de Développement

### Pour ajouter des fonctionnalités

1. **Backup**: Copier le fichier Python actuel
2. **Modifier**: Éditer `generate_jeanbrun_pages.py`
3. **Tester**: Générer quelques pages pour vérifier
4. **Valider**: Comparer avec pages précédentes
5. **Déployer**: Régénérer toutes les pages

### Pour mettre à jour les données

1. **Sources**: Identifier sources de données récentes
2. **Extraction**: Récupérer les nouvelles données (API/scraping)
3. **Validation**: Vérifier cohérence des données
4. **Mise à jour**: Modifier `villes_data`
5. **Régénération**: Lancer le script

### Pour déboguer

```python
# Ajouter des prints pour tracer l'exécution
print(f"Génération de la page pour {ville}")
print(f"Prix moyen T3: {prix_moyen_t3_neuf}")
print(f"Loyer mensuel: {loyer_mensuel}")
```

Ou utiliser un débogueur:
```bash
python3 -m pdb generate_jeanbrun_pages.py
```

## Commandes Utiles

```bash
# Générer les pages
python3 generate_jeanbrun_pages.py

# Vérifier la structure HTML
html5validator pages_jeanbrun/*.html

# Compter les pages générées
ls -1 pages_jeanbrun/*.html | wc -l

# Créer l'archive
zip -r pages_jeanbrun.zip pages_jeanbrun/

# Taille des fichiers
du -sh pages_jeanbrun/

# Rechercher dans tous les fichiers
grep -r "Zone Abis" pages_jeanbrun/
```

## Contact et Support

Pour modifications ou questions sur le code:
- Script Python documenté avec commentaires
- Structure modulaire pour faciliter extensions
- README.md pour guide utilisateur

---

**Dernière mise à jour**: 04/02/2026
**Version**: 1.0
**Statut**: Production ready
