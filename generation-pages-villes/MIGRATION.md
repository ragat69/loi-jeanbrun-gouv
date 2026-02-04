# Guide de migration - Générateur Loi Jeanbrun

**À lire en premier par Claude après déplacement du dossier.**

## Premiers pas après migration

1. **Lire ce fichier** (tu es ici)
2. **Ajouter une référence dans le CLAUDE.md principal du site** (voir section ci-dessous)
3. **Adapter `data/config.py`** : modifier `OUTPUT_DIR` selon l'emplacement souhaité pour `/ville/`
4. **Vérifier les chemins** dans les templates si le dossier n'est pas `/ville/`
5. **Tester** : `python3 fetch_city_data.py --list-processed` pour voir l'état actuel
6. **Générer les 200 villes** : `python3 fetch_city_data.py --num-cities 200 --skip-existing`
7. **Déployer** : copier le contenu du dossier de sortie vers `/ville/` sur le site

Si besoin de détails techniques sur le code → lire aussi `GENERATOR_DOCS.md`

### Action Claude : Ajouter au CLAUDE.md principal

**Claude, après avoir lu ce fichier, ajoute automatiquement cette section au `CLAUDE.md` principal du site (à la racine) si elle n'existe pas déjà :**

```markdown
## Générateur pages Loi Jeanbrun

Outil de génération automatique de pages ville pour la Loi Jeanbrun.

**Emplacement** : `[chemin relatif vers ce dossier]/`
**Documentation** : Voir `MIGRATION.md` dans ce dossier pour le contexte complet.

**Commandes principales** :
- Ajouter des villes : `python3 fetch_city_data.py --num-cities 200 --skip-existing`
- Rafraîchir les données : `python3 fetch_city_data.py --refresh-data`
- Voir les villes traitées : `python3 fetch_city_data.py --list-processed`
```

*Adapter le chemin selon l'emplacement réel du dossier après migration.*

---

## Résumé du projet

Système automatisé de génération de pages PHP pour la **Loi Jeanbrun** (dispositif fiscal "Relance Logement" 2026). Génère des pages ville avec données immobilières extraites des APIs gouvernementales françaises.

## Architecture

### Mode dynamique (en place)

```
[dossier du générateur]/
├── fetch_city_data.py              # Script principal - UNE COMMANDE FAIT TOUT
├── generate_jeanbrun_pages.py      # Génération manuelle (optionnel)
├── villes_data.json                # Données JSON (source)
├── villes_manifest.json            # Registre des villes traitées
├── data/
│   ├── config.py                   # ⚠️ CHEMINS À CONFIGURER
│   ├── fetchers/                   # Modules API (geo, dvf, zonage, loyers)
│   ├── processors/                 # Calculs et fallbacks
│   ├── generators/                 # Générateur PHP
│   └── cache/                      # Cache des APIs
├── templates/
│   ├── ville_template_dynamic.php  # Template page ville
│   ├── ville_page_stub.php         # Stub pour pages ville
│   └── index_template_dynamic.php  # Index avec recherche
└── pages_jeanbrun/                 # ⚠️ SORTIE - À DÉPLACER VERS /ville/
    ├── _data/
    │   └── villes_data.php         # Données PHP (généré auto)
    ├── _includes/
    │   └── ville_template.php      # Template commun
    ├── index.php                   # Index avec recherche autocomplete
    └── loi-jeanbrun-*.php          # Pages ville (stubs ~200 octets)
```

### Principe de fonctionnement

1. **Données centralisées** : Tout est dans `_data/villes_data.php`
2. **Pages légères** : Chaque page ville (~200 octets) définit juste `$ville_key` et inclut le template
3. **Template partagé** : `_includes/ville_template.php` contient tout le HTML/PHP
4. **Mise à jour automatique** : Modifier les données = toutes les pages sont à jour sans regénération

### Fonctionnalités des pages

- **Page ville** : Infos, prix, plafonds loyer, simulation, FAQ, liens vers villes du même département
- **Index** : Recherche autocomplete, liste paginée (50/page), triée par population
- **Liens internes** : Ancre "loi Jeanbrun" vers homepage sur chaque page

## Configuration après migration

### 1. Modifier `data/config.py`

```python
# Adapter ces chemins selon votre structure :

# Dossier de sortie des pages générées
OUTPUT_DIR = "/chemin/vers/votre-site/ville"  # ou chemin relatif

# Includes PHP (vérifier que ça correspond à votre site)
PHP_HEADER_INCLUDE = "<?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/header.php'); ?>"
PHP_FOOTER_INCLUDE = "<?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'); ?>"
```

### 2. Vérifier les chemins dans les templates

Les templates utilisent des chemins absolus depuis `DOCUMENT_ROOT` :
- `/ville/_data/villes_data.php`
- `/ville/_includes/ville_template.php`
- `/includes/header.php` et `/includes/footer.php`

Si votre dossier n'est pas `/ville/`, modifier dans :
- `templates/ville_template_dynamic.php` (ligne ~10)
- `templates/index_template_dynamic.php` (ligne ~9)
- `templates/ville_page_stub.php` (ligne ~6)

### 3. Déplacer les pages générées

```bash
# Copier le contenu de pages_jeanbrun/ vers /ville/ sur votre site
cp -r pages_jeanbrun/* /chemin/vers/votre-site/ville/
```

## Commandes

```bash
# Ajouter des villes (TOUT EST AUTOMATIQUE)
python3 fetch_city_data.py --num-cities 200 --skip-existing

# Rafraîchir les données des villes existantes
python3 fetch_city_data.py --refresh-data

# Voir les villes traitées
python3 fetch_city_data.py --list-processed
```

**Une seule commande** met à jour :
- `villes_data.json` (données JSON)
- `_data/villes_data.php` (données PHP)
- `index.php` (copié depuis template)
- Pages stubs pour nouvelles villes

## APIs utilisées

| API | URL | Usage |
|-----|-----|-------|
| Géo API | geo.api.gouv.fr | Liste villes, population, département |
| DVF | api.cquest.org/dvf | Prix immobiliers (souvent down → fallback) |
| Zonage ABC | data.gouv.fr | Classification zones A/B/C |
| Carte des loyers | static.data.gouv.fr | Loyers marché |

## Données par ville

```php
$villes_data['Paris'] = [
    'nom' => 'Paris',
    'code_insee' => '75056',
    'population' => 2103778,
    'departement' => '75 - Paris',
    'zone' => 'Abis',
    'plafonds_loyer' => [
        'intermediaire' => 18.25,
        'social' => 14.0,
        'tres_social' => 10.5,
    ],
    'prix_m2_neuf' => 10000,
    'prix_m2_ancien' => 8500,
    'loyer_marche_m2' => 32.2,
    'taux_vacance' => 6.5,
    'projets_construction' => 10518,
    'code_postal' => '75000',
    'slug' => 'paris',
    'filename' => 'loi-jeanbrun-paris-75000.php',
];
```

## Fonctionnalités implémentées

- [x] Extraction données 200+ villes via APIs
- [x] Mode incrémental (ajouter villes sans refaire les existantes)
- [x] Architecture dynamique (données PHP centralisées)
- [x] Pages ville avec simulation investissement
- [x] Liens vers villes du même département (générés dynamiquement)
- [x] Lien "loi Jeanbrun" vers homepage sur chaque page
- [x] Index avec recherche autocomplete
- [x] Pagination (50 villes/page)
- [x] Mise à jour automatique quand on ajoute des villes

## Problèmes connus

- **API DVF** (api.cquest.org) souvent indisponible (erreur 502) → le système utilise des fallbacks par zone
- **SSL macOS** : Les fetchers désactivent la vérification SSL (nécessaire sur certains Mac)

## Structure des URLs

- Index : `/ville/` ou `/ville/index.php`
- Page ville : `/ville/loi-jeanbrun-{slug}-{code_postal}.php`
- Exemple : `/ville/loi-jeanbrun-paris-75000.php`

## Pour tester après migration

1. Ouvrir `/ville/index.php` dans un navigateur
2. Vérifier que la recherche autocomplete fonctionne
3. Cliquer sur une ville pour voir la page détail
4. Vérifier les liens "loi Jeanbrun" et liens département en bas de page

## Historique des décisions

- **Architecture dynamique** plutôt que statique : permet de rafraîchir les données sans regénérer 200+ pages
- **PHP plutôt que JS** pour les données : fonctionne sans build, SEO-friendly
- **Liens département dynamiques** : se mettent à jour automatiquement quand on ajoute des villes
- **Index avec pagination** : gère des centaines de villes sans problème de performance

## Fichiers de documentation

| Fichier | Contenu |
|---------|---------|
| `MIGRATION.md` | Ce fichier - contexte pour Claude après migration |
| `README.md` | Documentation utilisateur (commandes, structure) |
| `GENERATOR_DOCS.md` | Documentation technique détaillée (anciennement CLAUDE.md, renommé pour éviter conflit) |

**Note** : `CLAUDE.md` a été renommé en `GENERATOR_DOCS.md` pour ne pas entrer en conflit avec le `CLAUDE.md` principal de ton site.

---

**Créé le** : 2026-02-04
**Villes actuelles** : ~20 (test), prévu pour 200+
