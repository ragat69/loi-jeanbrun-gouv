# Processus de génération des textes introductifs

## Processus actuel (manuel)

Les textes introductifs pour les pages ville sont rédigés manuellement et ajoutés via un script Python.

**Ne plus utiliser l'API Anthropic.** Le fichier `generate_intro_texts.py.deprecated` est obsolète.

## Workflow pour ajouter des villes

### 1. Générer les données des nouvelles villes

```bash
# Ajouter 30 villes par exemple
python3 fetch_city_data.py --num-cities 30 --skip-existing
```

Ceci génère automatiquement :
- Les données dans `villes_data.json` (sans intro_text)
- Le fichier PHP `ville/_data/villes_data.php`
- Les pages stubs

### 2. Identifier les villes sans texte introductif

```bash
python3 -c "
import json
with open('villes_data.json', 'r', encoding='utf-8') as f:
    data = json.load(f)
villes_sans_intro = [v for v, d in data.items() if 'intro_text' not in d or not d['intro_text']]
print(f'Villes sans intro_text: {len(villes_sans_intro)}')
for v in sorted(villes_sans_intro):
    zone = data[v].get('zone', 'N/A')
    pop = data[v].get('population', 'N/A')
    print(f'  - {v} (Zone {zone}, {pop:,} hab.)')
"
```

### 3. Rédiger les textes introductifs

Créer un nouveau script ou modifier `add_missing_intro_texts.py` :

```python
#!/usr/bin/env python3
"""
Ajoute les textes introductifs pour les nouvelles villes
"""

import json

VILLES_DATA_FILE = "villes_data.json"

# Textes introductifs personnalisés pour chaque ville
intro_texts = {
    "VilleExemple": "Ville de {{population}} habitants, VilleExemple...<br><br>La loi Jeanbrun y...",
    # Ajouter les autres villes...
}

def main():
    with open(VILLES_DATA_FILE, 'r', encoding='utf-8') as f:
        villes_data = json.load(f)

    count = 0
    for ville, text in intro_texts.items():
        if ville in villes_data:
            villes_data[ville]['intro_text'] = text
            print(f"✅ {ville}")
            count += 1

    with open(VILLES_DATA_FILE, 'w', encoding='utf-8') as f:
        json.dump(villes_data, f, ensure_ascii=False, indent=2)

    print(f"\n✅ {count} textes ajoutés!")

if __name__ == "__main__":
    main()
```

### 4. Exécuter le script et regénérer le PHP

```bash
# Ajouter les textes introductifs
python3 add_missing_intro_texts.py

# Regénérer le fichier PHP de données
python3 regenerate_php_data.py
```

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

## Scripts disponibles

| Script | Description |
|--------|-------------|
| `add_intro_texts_manual.py` | Textes pour les 20 premières villes |
| `add_missing_intro_texts.py` | Textes pour les 31 villes suivantes |
| `regenerate_php_data.py` | Regénère le fichier PHP depuis le JSON |

## Fichiers obsolètes

- ❌ `generate_intro_texts.py.deprecated` - Ancien processus avec API Anthropic (ne plus utiliser)

---

**Dernière mise à jour** : 2026-02-04
**Version** : 3.0 (processus manuel, sans API)
