# Génération de textes introductifs avec IA

## Description

Ce système génère automatiquement des textes introductifs **uniques** pour chaque page ville en utilisant l'API Claude d'Anthropic. Les textes sont personnalisés selon les données de chaque ville (marché immobilier, zone, population).

## Avantages

✅ **Contenu unique** : Chaque ville a un texte différent, optimisé SEO
✅ **Données dynamiques** : Les chiffres se mettent à jour automatiquement lors des refresh
✅ **Lien interne SEO** : "loi Jeanbrun" pointe vers la homepage
✅ **Économique** : ~$0.30-0.50 pour 200 villes avec Claude Haiku
✅ **Génération unique** : Une fois générés, les textes ne sont plus regénérés

## Architecture

```
Texte généré avec placeholders :
"Avec ses {{population}} habitants, Paris présente..."
       ↓
Stocké dans villes_data.json :
"intro_text": "Avec ses {{population}} habitants..."
       ↓
Converti en PHP dans villes_data.php
       ↓
Affiché dans le template avec variables PHP dynamiques :
"Avec ses 2 103 778 habitants, Paris présente..."
       ↓
"loi Jeanbrun" → lien vers homepage
```

## Prérequis

1. **Clé API Anthropic**
   - Créer un compte sur https://console.anthropic.com/
   - Obtenir une clé API
   - Définir la variable d'environnement :
     ```bash
     export ANTHROPIC_API_KEY='sk-ant-...'
     ```

2. **Package Python Anthropic**
   ```bash
   pip install anthropic
   ```

3. **Données des villes**
   - Le fichier `villes_data.json` doit exister
   - Généré par `fetch_city_data.py`

## Utilisation

### 1. Générer les textes introductifs

```bash
cd generation-pages-villes/
export ANTHROPIC_API_KEY='votre_clé_api'
python3 generate_intro_texts.py
```

Le script :
1. Charge les données depuis `villes_data.json`
2. Affiche le coût estimé (~$0.30-0.50 pour 200 villes)
3. Demande confirmation
4. Génère un texte unique pour chaque ville
5. **Ignore** les villes ayant déjà un texte (pas de doublon)
6. Sauvegarde dans `villes_data.json`

### 2. Regénérer le fichier PHP

```bash
python3 data/generators/php_generator.py
```

Cette commande met à jour `/ville/_data/villes_data.php` avec les nouveaux textes.

### 3. Vérifier le résultat

Ouvrir n'importe quelle page ville dans le navigateur :
- Le texte intro apparaît en haut, dans une card avec bordure bleue
- Les chiffres sont dynamiques (formatés avec espaces)
- "loi Jeanbrun" est un lien cliquable vers la homepage

## Format des textes générés

Les textes contiennent :
- **120-150 mots** (paragraphe concis)
- **Placeholders dynamiques** : `{{population}}`, `{{prix_m2_neuf}}`, etc.
- **Mention obligatoire** : "loi Jeanbrun" (converti en lien)
- **Ton professionnel** mais accessible
- **Contenu contextualisé** : marché locatif, opportunités d'investissement

### Exemple de texte généré

```
"Avec ses {{population}} habitants, Lyon se positionne comme un marché
immobilier dynamique de zone B1. La loi Jeanbrun offre ici des opportunités
attractives pour les investisseurs, avec un prix moyen au m² de {{prix_m2_neuf}}€
pour le neuf. Les plafonds de loyer à {{plafond_intermediaire}}€/m² permettent
de cibler un large public tout en bénéficiant d'un amortissement fiscal avantageux."
```

Après affichage :
```
"Avec ses 522 000 habitants, Lyon se positionne comme un marché immobilier
dynamique de zone B1. La loi Jeanbrun offre ici des opportunités attractives..."
                        ↑ lien vers /
```

## Variables disponibles

Les placeholders suivants peuvent être utilisés dans les textes :

| Placeholder | Description | Format |
|-------------|-------------|--------|
| `{{population}}` | Nombre d'habitants | `522 000` |
| `{{prix_m2_neuf}}` | Prix m² neuf | `4 500` |
| `{{prix_m2_ancien}}` | Prix m² ancien | `3 800` |
| `{{loyer_marche_m2}}` | Loyer marché | `15,50` |
| `{{plafond_intermediaire}}` | Plafond loyer intermédiaire | `10,93` |
| `{{plafond_social}}` | Plafond loyer social | `8,38` |
| `{{plafond_tres_social}}` | Plafond loyer très social | `6,29` |
| `{{taux_vacance}}` | Taux de vacance | `6,5` |

## Ajouter des villes avec textes

Workflow complet pour ajouter 100 nouvelles villes avec textes :

```bash
# 1. Extraire les données des villes
python3 fetch_city_data.py --num-cities 100 --skip-existing

# 2. Générer les textes intro pour les nouvelles villes
export ANTHROPIC_API_KEY='votre_clé'
python3 generate_intro_texts.py

# 3. Regénérer le PHP
python3 data/generators/php_generator.py
```

Les pages ville afficheront automatiquement les nouveaux textes.

## Regénérer un texte spécifique

Si un texte ne convient pas, vous pouvez le regénérer :

1. **Supprimer le champ** `intro_text` de la ville dans `villes_data.json`
2. **Relancer** : `python3 generate_intro_texts.py`
3. **Mettre à jour le PHP** : `python3 data/generators/php_generator.py`

Le script ne regénère que les villes sans texte.

## Coûts estimés

Modèle utilisé : **Claude Haiku 3.5** (le plus économique)

| Nombre de villes | Coût estimé |
|------------------|-------------|
| 20 villes | ~$0.05 |
| 50 villes | ~$0.12 |
| 100 villes | ~$0.25 |
| 200 villes | ~$0.50 |
| 500 villes | ~$1.25 |

*Estimation basée sur 300 tokens input + 150 tokens output par ville*

## Personnalisation

### Modifier le prompt

Éditer `generate_intro_texts.py`, fonction `generate_intro_text()` :

```python
prompt = f"""Tu es un expert en investissement immobilier locatif en France.

Rédige un paragraphe introductif de 120-150 mots...

[Votre prompt personnalisé ici]
"""
```

### Modifier le modèle utilisé

Dans `generate_intro_texts.py` :

```python
response = client.messages.create(
    model="claude-haiku-3-5-sonnet-20241022",  # Changer ici
    max_tokens=300,
    messages=[...]
)
```

Modèles disponibles :
- `claude-haiku-3-5-sonnet-20241022` : Économique (~$0.50/200 villes)
- `claude-3-5-sonnet-20241022` : Qualité supérieure (~$3/200 villes)
- `claude-opus-4-5-20251101` : Top qualité (~$15/200 villes)

## Dépannage

### Erreur "ANTHROPIC_API_KEY non définie"

```bash
export ANTHROPIC_API_KEY='sk-ant-api03-...'
python3 generate_intro_texts.py
```

### Erreur "Fichier villes_data.json non trouvé"

Générer d'abord les données des villes :

```bash
python3 fetch_city_data.py --num-cities 20
```

### Les textes n'apparaissent pas sur les pages

Vérifier que `villes_data.php` est à jour :

```bash
python3 data/generators/php_generator.py
```

### Texte sans formatage des chiffres

Vérifier que les placeholders utilisent la bonne syntaxe : `{{population}}` et non `{population}`.

## Sécurité

- Ne jamais commiter `ANTHROPIC_API_KEY` dans Git
- Ajouter `.env` au `.gitignore` si vous stockez la clé
- Les textes générés sont statiques et safe (pas d'injection)

## Maintenance

### Rafraîchir tous les textes (déconseillé)

Si vous voulez vraiment tout regénérer :

1. Supprimer tous les `intro_text` du JSON (script à créer)
2. Relancer la génération
3. Coût : ~$0.50 pour 200 villes

⚠️ **Non recommandé** : Les textes sont bons du premier coup, pas besoin de les regénérer sauf modification du prompt.

---

**Créé le** : 2026-02-04
**Version** : 1.0
**Coût moyen** : ~$0.30-0.50 pour 200 villes
