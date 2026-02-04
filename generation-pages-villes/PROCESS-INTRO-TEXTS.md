# Processus de génération des textes introductifs

## Processus actuel (automatique)

Les textes introductifs sont **générés automatiquement** par le script `generate_all_intro_texts.py` selon les caractéristiques de chaque ville (population, zone ABC).

**Ne plus utiliser l'API Anthropic.** Le fichier `generate_intro_texts.py.deprecated` est obsolète.

## Workflow pour ajouter des villes

### 1. Générer les données des nouvelles villes

```bash
# Ajouter 250 villes par exemple
python3 fetch_city_data.py --num-cities 250 --skip-existing
```

Ceci génère automatiquement :
- Les données dans `villes_data.json` (sans intro_text)
- Le fichier PHP `ville/_data/villes_data.php`
- Les pages stubs

### 2. Générer automatiquement les textes introductifs

```bash
python3 generate_all_intro_texts.py
```

Ce script :
- ✅ Identifie automatiquement les villes sans `intro_text`
- ✅ Génère un texte personnalisé pour chaque ville selon :
  - **Population** : Adapte le ton (grande ville, ville moyenne, petite ville)
  - **Zone ABC** : Adapte le discours sur le marché locatif
  - **Templates variés** : Évite la répétition en utilisant plusieurs modèles
- ✅ Sauvegarde dans `villes_data.json`

**Exemple de sortie :**
```
🚀 Génération automatique des textes introductifs

📊 250 villes à traiter sur 499 total

✍️  20/250 textes générés...
✍️  40/250 textes générés...
...
✅ 250 textes ajoutés avec succès !
📝 Fichier mis à jour : villes_data.json

⚠️  N'oubliez pas de regénérer le fichier PHP :
   python3 regenerate_php_data.py
```

### 3. Regénérer le fichier PHP

```bash
python3 regenerate_php_data.py
```

## Algorithme de génération

Le script `generate_all_intro_texts.py` utilise un système de templates intelligents :

### Catégorisation par taille de ville

| Catégorie | Population | Ton du texte |
|-----------|-----------|--------------|
| **Grande ville** | > 100 000 hab | "Métropole dynamique", "Pôle urbain majeur" |
| **Ville moyenne** | 50 000 - 100 000 hab | "Marché en développement", "Équilibre qualité de vie" |
| **Petite ville** | < 50 000 hab | "Marché accessible", "Investissement attractif" |

### Sélection des templates

Pour chaque catégorie, plusieurs templates sont disponibles. La sélection est **déterministe** basée sur un hash du nom de la ville pour :
- Éviter que deux villes similaires aient exactement le même texte
- Garantir la cohérence (même ville = même template à chaque génération)

## Format des textes introductifs

Les textes doivent :
- Utiliser des **placeholders** pour les données dynamiques : `{{population}}`, `{{prix_m2_neuf}}`, `{{zone}}`, `{{plafond_intermediaire}}`, etc.
- Être séparés en 4 paragraphes avec `<br><br>` entre eux
- Mentionner la loi Jeanbrun
- Être personnalisés selon la ville (caractéristiques locales, activités économiques, etc.)

**Exemple de structure :**

```
Ville de {{population}} habitants, [Nom] s'impose en zone {{zone}}.<br><br>La loi Jeanbrun y présente des opportunités...<br><br>Le prix moyen de {{prix_m2_neuf}}€ au m²...<br><br>Les plafonds de loyer à {{plafond_intermediaire}}€/m²...
```

## Placeholders disponibles

| Placeholder | Description |
|-------------|-------------|
| `{{population}}` | Population formatée (ex: "186 334") |
| `{{zone}}` | Zone ABC (Abis, A, B1, B2, C) |
| `{{prix_m2_neuf}}` | Prix m² neuf formaté |
| `{{prix_m2_ancien}}` | Prix m² ancien formaté |
| `{{plafond_intermediaire}}` | Plafond loyer intermédiaire |
| `{{plafond_social}}` | Plafond loyer social |
| `{{taux_vacance}}` | Taux de vacance |
| `{{projets_construction}}` | Nombre de projets construction |
| `{{loyer_marche_m2}}` | Loyer marché formaté |

## Exemples de textes générés

### Grande ville (exemple: Toulouse, 514k hab, Zone A)
```
Avec 514 819 habitants, Toulouse se positionne comme un pôle urbain majeur en zone A.

La loi Jeanbrun y offre des perspectives d'investissement remarquables avec un prix d'accès à 4 545€ au m² dans le neuf.

Le dynamisme local et le marché de l'emploi soutiennent une demande locative pérenne.

Les plafonds de loyer intermédiaire à 14,49€/m² assurent des revenus réguliers tout en optimisant la rentabilité fiscale.
```

### Ville moyenne (exemple: Nevers, 33k hab, Zone C)
```
Ville de 33 469 habitants, Nevers s'inscrit en zone C comme un marché d'investissement accessible.

La loi Jeanbrun y offre des perspectives attractives avec un prix moyen de 2 145€ au m² dans le neuf.

Le marché locatif bénéficie d'une demande régulière adaptée au bassin d'emploi local.

Les plafonds de loyer intermédiaire à 8,82€/m² permettent d'optimiser la rentabilité tout en profitant de l'amortissement fiscal.
```

## Scripts disponibles

| Script | Description |
|--------|-------------|
| `generate_all_intro_texts.py` | **Génération automatique** des textes pour toutes les villes sans intro |
| `regenerate_php_data.py` | Regénère le fichier PHP depuis le JSON |

## Personnalisation manuelle (optionnel)

Si vous souhaitez personnaliser un texte après génération automatique :

```python
import json

with open('villes_data.json', 'r', encoding='utf-8') as f:
    data = json.load(f)

# Modifier le texte d'une ville spécifique
data['NomVille']['intro_text'] = "Votre texte personnalisé..."

with open('villes_data.json', 'w', encoding='utf-8') as f:
    json.dump(data, f, ensure_ascii=False, indent=2)
```

Puis regénérer le PHP : `python3 regenerate_php_data.py`

## Fichiers obsolètes

- ❌ `generate_intro_texts.py.deprecated` - Ancien processus avec API Anthropic (ne plus utiliser)
- ❌ `add_intro_texts_manual.py` - Remplacé par génération automatique
- ❌ `add_missing_intro_texts.py` - Remplacé par génération automatique

---

**Dernière mise à jour** : 2026-02-05
**Version** : 4.0 (génération automatique intelligente, templates par catégorie)
