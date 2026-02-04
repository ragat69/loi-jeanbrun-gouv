# Changements DVF - Passage de l'API au fichier local, puis multi-fichiers

## Date: 2026-02-04 (v2 - Multi-fichiers)

### Modifications v2

#### 1. Parser multi-fichiers
- ✅ Modifié `dvf_file.py` pour charger TOUS les fichiers DVF (2020-S2 à 2025-S1)
- ✅ Indexation de ~4,9 millions de transactions (au lieu de 60k)
- ✅ Identifiant composite unique : `date|commune|section|plan|valeur`
- ✅ Correction du bug : "No disposition" n'est pas un ID unique
- ✅ Temps d'indexation : ~70s pour 20M de lignes

#### 2. Configuration mise à jour
- ✅ `data/config.py` : `DVF_LOCAL_FILES` (liste) au lieu de `DVF_LOCAL_FILE`
- ✅ Support des 6 fichiers DVF disponibles (2020-S2 à 2025-S1)

#### 3. Documentation
- ✅ `README.md` : Section DVF mise à jour (multi-fichiers)
- ✅ `CHANGELOG-DVF.md` : Historique des modifications

### Fichiers DVF actuels
```
generation-pages-villes/ValeursFoncieres-2020-S2.txt  (2,065,003 lignes)
generation-pages-villes/ValeursFoncieres-2021.txt     (4,674,176 lignes)
generation-pages-villes/ValeursFoncieres-2022.txt     (4,675,007 lignes)
generation-pages-villes/ValeursFoncieres-2023.txt     (3,812,327 lignes)
generation-pages-villes/ValeursFoncieres-2024.txt     (3,489,149 lignes)
generation-pages-villes/ValeursFoncieres-2025-S1.txt  (1,387,077 lignes)
```
- **Période totale** : Juillet 2020 - Juin 2025 (5 ans)
- **Total** : 20,102,739 lignes
- **Transactions indexées** : 4,917,728
- **Communes** : 33,389

---

## Date: 2026-02-04 (v1 - Fichier unique)

### Modifications effectuées

#### 1. Nouveau parser DVF local
- ✅ Créé `data/fetchers/dvf_file.py` pour parser le fichier DVF local
- ✅ Remplacé l'import dans `data/fetchers/__init__.py`
- ✅ Parse correctement les mutations multi-lignes (terrain + bâti)
- ✅ Sépare prix neuf (VEFA) et prix ancien
- ✅ Filtre les valeurs aberrantes (500-30000€/m²)

#### 2. Configuration mise à jour
- ✅ `data/config.py` : Ajout de `DVF_LOCAL_FILE`
- ✅ Suppression de `DVF_API_BASE` et `DVF_API_RATE_LIMIT`

#### 3. Documentation
- ✅ `README.md` : Section "Données DVF" ajoutée
- ✅ `README.md` : État des APIs mis à jour
- ✅ `README.md` : Sources de données mises à jour
- ✅ Fichier obsolète marqué : `dvf_api.py` (conservé pour compatibilité)

### Colonnes utilisées
- [8] Date mutation
- [9] Nature mutation
- [10] Valeur fonciere
- [18] Code departement
- [19] Code commune
- [21] Section cadastrale
- [22] No plan
- [36] Type local (Maison/Appartement)
- [38] Surface reelle bati
- [39] Nombre pieces principales

### Test du parser
```bash
python3 -c "
from data.fetchers.dvf_file import DVFFetcher
dvf = DVFFetcher()

# Test Paris 1er arrondissement (5 ans d'historique)
result = dvf.get_appartement_prices('75101', years=5)
print(f'Transactions: {result[\"count\"]}')
print(f'Prix m² ancien: {result[\"prix_m2_ancien\"]}€/m²')
# Résultat attendu : ~1400 transactions, ~13 000€/m²
"
```

### Pour rafraîchir les données des villes existantes

```bash
# Rafraîchir toutes les villes avec les données DVF (historique complet 2020-2025)
python3 fetch_city_data.py --refresh-data

# Regénérer le fichier PHP
python3 regenerate_php_data.py
```

Les villes bénéficieront automatiquement de l'historique complet de 5 ans pour des statistiques plus fiables.

### Processus de mise à jour semestrielle

Quand un nouveau fichier DVF est publié (2x/an):

1. Télécharger depuis [data.gouv.fr/dvf](https://www.data.gouv.fr/fr/datasets/demandes-de-valeurs-foncieres/)
2. Remplacer `ValeursFoncieres-YYYY-SX.txt`
3. Mettre à jour `data/config.py` si le nom change
4. Lancer `python3 fetch_city_data.py --refresh-data`
5. Regénérer avec `python3 regenerate_php_data.py`

### Avantages du nouveau système

✅ **Pas de dépendance à l'API DVF** (qui était en 502)
✅ **Données officielles** publiées 2x/an
✅ **Performance** : indexation en mémoire, accès rapide
✅ **Fiabilité** : pas d'erreurs réseau
✅ **Séparation neuf/ancien** automatique (VEFA vs ventes classiques)

### Notes techniques

- Le parser groupe les lignes multiples d'une même mutation par `mutation_id`
- Seules les transactions avec `Type local = "Appartement"` sont conservées
- Les prix aberrants (< 500€/m² ou > 30000€/m²) sont filtrés
- Les données sont mises en cache en mémoire pour accès rapide
