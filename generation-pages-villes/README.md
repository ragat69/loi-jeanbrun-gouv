# Générateur de pages Loi Jeanbrun

Système automatisé pour générer des pages PHP/Bootstrap pour les villes françaises, avec données extraites des APIs gouvernementales.

## Commandes rapides

```bash
# Première exécution : 200 premières villes
# (génère automatiquement les données PHP + pages stubs)
python3 fetch_city_data.py --num-cities 200

# Ajouter 200 villes supplémentaires
# (met à jour automatiquement les données PHP + crée les nouvelles pages)
python3 fetch_city_data.py --num-cities 200 --skip-existing

# Rafraîchir les données (DVF, loyers) des villes existantes
# (met à jour automatiquement les données PHP)
python3 fetch_city_data.py --refresh-data

# Voir les villes déjà traitées
python3 fetch_city_data.py --list-processed
```

**Note :** `fetch_city_data.py` génère automatiquement :
- Le fichier JSON (`villes_data.json`)
- Le fichier PHP de données (`pages_jeanbrun/_data/villes_data.php`)
- Les pages stubs pour les nouvelles villes

Vous n'avez plus besoin d'exécuter `generate_jeanbrun_pages.py` après avoir ajouté des villes.

## Architecture dynamique

Les données sont stockées dans un fichier PHP central. Les pages ville sont légères et incluent ce fichier.

**Avantages :**
- Rafraîchir les données ne nécessite pas de regénérer les pages
- Pages très légères (~200 octets vs ~15 Ko)
- Liens entre villes du même département générés automatiquement
- Un seul fichier à mettre à jour quand les APIs changent

**Structure générée :**
```
pages_jeanbrun/
├── _data/
│   └── villes_data.php          # Toutes les données (généré auto)
├── _includes/
│   └── ville_template.php       # Template commun
├── index.php                    # Index dynamique
└── loi-jeanbrun-*.php           # Pages ville (stubs ~200 octets)
```

**Workflow simplifié :**
```bash
# 1. Ajouter des villes
python3 fetch_city_data.py --num-cities 200 --skip-existing

# 2. Générer les textes introductifs pour les nouvelles villes
python3 generate_all_intro_texts.py

# 3. Régénérer le fichier PHP avec les textes
python3 regenerate_php_data.py

# Rafraîchir les données des villes existantes (sans toucher aux textes)
python3 fetch_city_data.py --refresh-data
# → Le fichier PHP est mis à jour automatiquement
```

**Important :** Les textes introductifs sont générés automatiquement selon la population et la zone de chaque ville. Le script `generate_all_intro_texts.py` crée des textes personnalisés avec des templates variés pour éviter la répétition.

### Mode statique (optionnel)

Si vous préférez des pages autonomes avec données en dur :

```bash
python3 generate_jeanbrun_pages.py
```

## Structure du projet

```
jeanbrun-generator/
├── fetch_city_data.py          # Script extraction données API
├── generate_jeanbrun_pages.py  # Script génération pages PHP
├── data/
│   ├── config.py               # Configuration (URLs, plafonds, chemins)
│   ├── fetchers/               # Modules de récupération des données
│   │   ├── geo_api.py          # API Géo (population, département)
│   │   ├── zonage_abc.py       # Zones ABC (CSV data.gouv.fr)
│   │   ├── dvf_api.py          # Prix immobiliers (DVF)
│   │   └── loyers.py           # Loyers marché (Carte des loyers)
│   ├── processors/             # Modules de traitement
│   │   ├── price_calculator.py # Calcul prix moyen depuis DVF
│   │   └── fallbacks.py        # Estimations données manquantes
│   └── cache/                  # Cache des données API
├── templates/
│   ├── ville_template.php      # Template Bootstrap pour pages ville
│   └── index_template.php      # Template page d'accueil
├── villes_data.json            # Données générées (toutes les villes)
├── villes_manifest.json        # Registre des villes traitées
└── README.md                   # Ce fichier
```

## Fichiers clés

| Fichier | Description |
|---------|-------------|
| `villes_data.json` | Données de toutes les villes (ne pas modifier manuellement) |
| `villes_manifest.json` | Registre des villes traitées avec timestamps et rangs population |
| `templates/ville_template.php` | Template Bootstrap à personnaliser selon votre site |
| `data/config.py` | Configuration (chemins PHP includes, URLs API, plafonds loyer) |

## Rafraîchir les données (DVF, loyers)

Si les APIs DVF ou loyers étaient indisponibles lors de la génération initiale (erreurs 502/404), vous pouvez rafraîchir les données sans tout regénérer :

```bash
# 1. Rafraîchir les données des villes existantes
python3 fetch_city_data.py --refresh-data

# 2. Regénérer les pages PHP avec les nouvelles données
python3 generate_jeanbrun_pages.py --force
```

Le script va :
- Retélécharger les données DVF pour chaque ville
- Mettre à jour les prix avec les données réelles (si disponibles)
- Conserver les fallbacks pour les villes sans données
- Afficher un résumé des mises à jour

## Mode incrémental

Les villes sont toujours traitées par **ordre décroissant de population** :

1. **Jour 1** : `--num-cities 200` → villes rang 1-200 (Paris à ~Villeurbanne)
2. **Jour 8** : `--num-cities 200 --skip-existing` → villes rang 201-400
3. **Jour 15** : `--num-cities 100 --skip-existing` → villes rang 401-500

Le manifest (`villes_manifest.json`) garde trace du dernier rang traité.

## Configuration pour votre site

### 1. Modifier les chemins PHP includes

Dans `data/config.py` :

```python
# Adapter selon votre structure
PHP_HEADER_INCLUDE = "<?php include('../includes/header.php'); ?>"
PHP_FOOTER_INCLUDE = "<?php include('../includes/footer.php'); ?>"

# Dossier de sortie
OUTPUT_DIR = "../../ville"  # Relatif au générateur
```

### 2. Personnaliser les templates

Les templates dans `templates/` utilisent des placeholders `{{variable}}` :

- `{{ville}}` - Nom de la ville
- `{{zone}}` - Zone ABC (Abis, A, B1, B2, C)
- `{{population_formatted}}` - Population formatée
- `{{prix_m2_neuf_formatted}}` - Prix m² neuf
- `{{plafond_intermediaire}}` - Plafond loyer intermédiaire
- etc.

Adaptez les classes CSS Bootstrap selon votre design.

## Structure des données par ville

```json
{
  "Paris": {
    "nom": "Paris",
    "code_insee": "75056",
    "zone": "Abis",
    "population": 2161000,
    "departement": "75 - Paris",
    "prix_m2_neuf": 11500,
    "prix_m2_ancien": 9775,
    "loyer_marche_m2": 30.5,
    "plafonds_loyer": {
      "intermediaire": 18.25,
      "social": 14.00,
      "tres_social": 10.50
    },
    "taux_vacance": 6.5,
    "projets_construction": 10805,
    "code_postal": "75000",
    "slug": "paris",
    "filename": "loi-jeanbrun-paris-75000.php"
  }
}
```

## Sources de données

| Donnée | Source | Fréquence MAJ |
|--------|--------|---------------|
| Liste villes + population | [API Géo](https://geo.api.gouv.fr/) | Hebdomadaire |
| Zone ABC | [data.gouv.fr](https://www.data.gouv.fr/) | Mensuelle |
| Prix immobiliers | Fichier DVF local (data.gouv.fr) | Semestrielle (2x/an) |
| Loyers marché | [Carte des loyers](https://www.data.gouv.fr/) | Annuelle |

## Plafonds de loyer par zone

| Zone | Intermédiaire | Social | Très social |
|------|---------------|--------|-------------|
| Abis | 18,25 €/m² | 14,00 €/m² | 10,50 €/m² |
| A | 14,49 €/m² | 11,11 €/m² | 8,33 €/m² |
| B1 | 10,93 €/m² | 8,38 €/m² | 6,29 €/m² |
| B2 | 9,50 €/m² | 7,28 €/m² | 5,46 €/m² |
| C | 9,17 €/m² | 7,03 €/m² | 5,27 €/m² |

## Ajouter une ville spécifique

Si une ville particulière est demandée hors du top population :

```bash
# Trouver son code INSEE sur geo.api.gouv.fr
# Puis modifier fetch_city_data.py pour ajouter la fonction --add-city
# (fonctionnalité à implémenter si besoin)
```

## Intégration dans votre site

1. Copier ce dossier dans `tools/jeanbrun-generator/`
2. Adapter `data/config.py` avec vos chemins
3. Personnaliser les templates
4. Exécuter les scripts
5. Les pages sont générées dans le dossier configuré

## Vérifier la disponibilité des APIs

Pour demander à Claude de vérifier si les APIs sont disponibles, dites simplement :
> "Vérifie si les APIs DVF et loyers sont disponibles"

### Tests manuels

```bash
# Test API Géo (devrait retourner des données JSON)
curl -s "https://geo.api.gouv.fr/communes?code=75056&fields=nom,population"

# Test API DVF (api.cquest.org) - souvent instable
curl -s "https://api.cquest.org/dvf?code_commune=75056" | head -c 200

# Test Carte des loyers (devrait retourner HTTP 200)
curl -sI "https://static.data.gouv.fr/resources/carte-des-loyers-indicateurs-de-loyers-dannonce-par-commune-en-2024/20241205-153050/pred-app-mef-dhup.csv" | head -3
```

### État actuel des sources de données

| Source | Type | État |
|--------|------|------|
| **Géo API** | API (geo.api.gouv.fr) | Stable |
| **DVF** | Fichier local (data.gouv.fr) | Stable (màj manuelle 2x/an) |
| **Carte des loyers** | CSV (static.data.gouv.fr) | Stable |
| **Zonage ABC** | CSV (data.gouv.fr) | Stable |

## Données DVF (prix immobiliers)

Le système utilise des fichiers DVF locaux au lieu de l'API (qui était instable).

### Fichiers actuels
Le parser charge automatiquement tous les fichiers DVF disponibles pour un historique complet :
```
generation-pages-villes/ValeursFoncieres-2020-S2.txt
generation-pages-villes/ValeursFoncieres-2021.txt
generation-pages-villes/ValeursFoncieres-2022.txt
generation-pages-villes/ValeursFoncieres-2023.txt
generation-pages-villes/ValeursFoncieres-2024.txt
generation-pages-villes/ValeursFoncieres-2025-S1.txt
```

**Total indexé** : ~4,9 millions de transactions sur 5 ans

### Mise à jour des fichiers DVF

Les données DVF sont publiées **2 fois par an** par data.gouv.fr :
- **Semestre 1** (janvier-juin) : publié en juillet-août
- **Semestre 2** (juillet-décembre) : publié en janvier-février

**Processus de mise à jour :**

1. Télécharger le nouveau fichier depuis [data.gouv.fr](https://www.data.gouv.fr/fr/datasets/demandes-de-valeurs-foncieres/)
2. Placer le fichier `ValeursFoncieres-YYYY-SX.txt` dans `generation-pages-villes/`
3. Ajouter le fichier à la liste dans `data/config.py` :
   ```python
   DVF_LOCAL_FILES = [
       os.path.join(BASE_DIR, "ValeursFoncieres-2020-S2.txt"),
       ...
       os.path.join(BASE_DIR, "ValeursFoncieres-YYYY-SX.txt"),  # Nouveau fichier
   ]
   ```
4. Rafraîchir les données des villes :
   ```bash
   python3 fetch_city_data.py --refresh-data
   python3 regenerate_php_data.py
   ```

Le parser :
- Détecte automatiquement les prix neuf (VEFA) et ancien
- Groupe les mutations multi-lignes (terrain + bâti) par ID composite
- Index : ~4,9M transactions, 33k communes, temps d'indexation ~70s

## Textes introductifs

Les textes introductifs sont **générés automatiquement** selon les caractéristiques de chaque ville.

### Génération automatique

Le script `generate_all_intro_texts.py` génère des textes personnalisés basés sur :
- **Population** : Détermine le ton (métropole, ville moyenne, petite ville)
- **Zone ABC** : Adapte le discours sur l'attractivité du marché
- **Templates variés** : Évite la répétition en utilisant plusieurs modèles

**Processus :**
```bash
# 1. Après avoir ajouté des villes
python3 fetch_city_data.py --num-cities 200 --skip-existing

# 2. Générer automatiquement les textes pour les villes sans intro
python3 generate_all_intro_texts.py
# → Identifie les villes sans texte
# → Génère des textes personnalisés selon population et zone
# → Sauvegarde dans villes_data.json

# 3. Régénérer le fichier PHP
python3 regenerate_php_data.py
```

**Types de templates :**
- **Grandes villes** (>100k hab) : Ton "métropole dynamique"
- **Villes moyennes** (50-100k hab) : Ton "marché en développement"
- **Petites villes** (<50k hab) : Ton "marché accessible"

Chaque ville reçoit un template sélectionné de façon déterministe (basé sur un hash du nom) pour éviter que deux villes similaires aient exactement le même texte.

**Documentation complète :** [PROCESS-INTRO-TEXTS.md](PROCESS-INTRO-TEXTS.md)

## Dépannage

### Erreur "Fichier villes_data.json non trouvé"
Exécutez d'abord `python3 fetch_city_data.py --num-cities 10` pour générer les données.

### Erreur de téléchargement des CSV
Vérifiez votre connexion internet. Les fichiers sont mis en cache, donc un échec ponctuel n'est pas grave si le cache existe.

### Données manquantes pour certaines villes
Le système utilise des fallbacks automatiques basés sur la zone et le département. Les champs estimés sont marqués avec `_estimated: true` dans le JSON.

---

**Dernière mise à jour** : 2026-02-05
**Version** : 3.1 (génération automatique des textes intro, DVF multi-fichiers, métadonnées sur pages)
