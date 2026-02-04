# -*- coding: utf-8 -*-
"""
Fetcher pour les données DVF (Demandes de Valeurs Foncières) depuis fichier local
Parse le fichier ValeursFoncieres-YYYY-SX.txt publié 2x/an par data.gouv.fr
"""

import csv
import os
from datetime import datetime
from typing import Optional, List, Dict

from ..config import DVF_LOCAL_FILES, CACHE_DIR


# Mapping des grandes villes divisées en arrondissements dans les fichiers DVF
ARRONDISSEMENTS_MAPPING = {
    # Paris : 75056 → arrondissements 75101 à 75120
    '75056': ['75101', '75102', '75103', '75104', '75105', '75106', '75107', '75108',
              '75109', '75110', '75111', '75112', '75113', '75114', '75115', '75116',
              '75117', '75118', '75119', '75120'],
    # Marseille : 13055 → arrondissements 13201 à 13216
    '13055': ['13201', '13202', '13203', '13204', '13205', '13206', '13207', '13208',
              '13209', '13210', '13211', '13212', '13213', '13214', '13215', '13216'],
    # Lyon : 69123 → arrondissements 69381 à 69389
    '69123': ['69381', '69382', '69383', '69384', '69385', '69386', '69387', '69388', '69389'],
}


class DVFFetcher:
    """Parse les transactions immobilières depuis les fichiers DVF locaux."""

    def __init__(self):
        self.dvf_files = DVF_LOCAL_FILES
        self.cache_dir = os.path.join(CACHE_DIR, "dvf_parsed")
        self._index = None  # Index par code INSEE pour accès rapide

    def get_dvf_period(self) -> str:
        """
        Retourne la période couverte par les fichiers DVF.

        Returns:
            Chaîne formatée comme "2020-2025" ou "S2 2020 - S1 2025"
        """
        if not self.dvf_files:
            return "N/A"

        # Extraire les années des noms de fichiers
        years = []
        for f in self.dvf_files:
            basename = os.path.basename(f)
            # Format: ValeursFoncieres-YYYY-SX.txt ou ValeursFoncieres-YYYY.txt
            if 'ValeursFoncieres-' in basename:
                # Extraire l'année
                parts = basename.replace('ValeursFoncieres-', '').replace('.txt', '').split('-')
                if parts:
                    year = parts[0]
                    years.append(year)

        if not years:
            return "N/A"

        min_year = min(years)
        max_year = max(years)

        if min_year == max_year:
            return min_year
        else:
            return f"{min_year}-{max_year}"

    def _build_index(self):
        """
        Construit un index des transactions par code INSEE depuis tous les fichiers DVF.

        Format du fichier DVF:
        - Délimiteur: pipe (|)
        - Colonnes importantes:
          [8] Date mutation
          [9] Nature mutation
          [10] Valeur fonciere
          [18] Code departement
          [19] Code commune
          [21] Section
          [22] No plan
          [36] Type local (Maison/Appartement)
          [38] Surface reelle bati
          [39] Nombre pieces principales

        IMPORTANT: Une mutation peut avoir plusieurs lignes (terrain + bâti + dépendances)
        On groupe par ID composite (date + commune + section + plan + valeur) pour consolider.
        """
        if self._index is not None:
            return

        print(f"📂 Indexation de {len(self.dvf_files)} fichiers DVF...")
        self._index = {}
        mutations = {}  # Groupe par mutation_id

        files_processed = 0
        files_missing = 0

        for dvf_file in self.dvf_files:
            if not os.path.exists(dvf_file):
                print(f"⚠️  Fichier DVF non trouvé: {os.path.basename(dvf_file)}")
                files_missing += 1
                continue

            print(f"   Traitement: {os.path.basename(dvf_file)}", end="")

            try:
                line_count = 0
                with open(dvf_file, 'r', encoding='utf-8') as f:
                    reader = csv.reader(f, delimiter='|')

                    # Skip header
                    next(reader, None)

                    for row in reader:
                        line_count += 1
                        try:
                            if len(row) < 40:
                                continue

                            # Données de base
                            date_mutation = row[8].strip()
                            nature_mutation = row[9].strip()
                            valeur_fonciere = row[10].strip().replace(',', '.')
                            code_dept = row[18].strip()
                            code_commune = row[19].strip()
                            section = row[21].strip() if len(row) > 21 else ''
                            no_plan = row[22].strip() if len(row) > 22 else ''
                            type_local = row[36].strip() if len(row) > 36 else ''

                            if not code_dept or not code_commune or not date_mutation or not valeur_fonciere:
                                continue

                            # Construire le code INSEE (5 chiffres)
                            if code_dept in ['2A', '2B']:
                                code_insee = code_dept + code_commune.zfill(3)
                            elif code_dept.startswith('97') or code_dept.startswith('98'):
                                code_insee = code_dept + code_commune.zfill(2)
                            else:
                                code_insee = code_dept.zfill(2) + code_commune.zfill(3)

                            # Créer un ID unique composite pour la mutation
                            # Date + commune + section + plan + valeur pour éviter les doublons
                            mutation_id = f"{date_mutation}|{code_insee}|{section}|{no_plan}|{valeur_fonciere}"
                            key = (code_insee, mutation_id)

                            if key not in mutations:
                                mutations[key] = {
                                    'date_mutation': date_mutation,
                                    'nature_mutation': nature_mutation,
                                    'valeur_fonciere': valeur_fonciere,
                                    'type_local': '',
                                    'surface_reelle_bati': '',
                                    'nb_pieces': '',
                                    'code_insee': code_insee,
                                }

                            # Si c'est un Appartement ou Maison, prendre les données du bâti
                            if type_local in ['Appartement', 'Maison']:
                                mutations[key]['type_local'] = type_local
                                surface = row[38].strip() if len(row) > 38 else ''
                                if surface:
                                    mutations[key]['surface_reelle_bati'] = surface
                                nb_pieces = row[39].strip() if len(row) > 39 else ''
                                if nb_pieces:
                                    mutations[key]['nb_pieces'] = nb_pieces

                        except Exception:
                            continue

                print(f" ✓ ({line_count:,} lignes)")
                files_processed += 1

            except Exception as e:
                print(f" ✗ Erreur: {e}")
                continue

        # Organiser par code INSEE
        for (code_insee, _), transaction in mutations.items():
            if code_insee not in self._index:
                self._index[code_insee] = []
            # Ne garder que les transactions avec un type_local défini
            if transaction['type_local']:
                self._index[code_insee].append(transaction)

        total_transactions = sum(len(v) for v in self._index.values())
        print(f"✅ Indexation terminée:")
        print(f"   - {files_processed} fichiers traités ({files_missing} manquants)")
        print(f"   - {len(self._index)} communes trouvées")
        print(f"   - {total_transactions:,} transactions indexées")

    def fetch_transactions(self, code_insee: str, force_refresh: bool = False) -> List[Dict]:
        """
        Récupère les transactions pour une commune.

        Args:
            code_insee: Code INSEE de la commune (5 chiffres)
            force_refresh: Ignoré (compatibilité API)

        Returns:
            Liste des transactions
        """
        # S'assurer que l'index est construit
        if self._index is None:
            self._build_index()

        code_insee = str(code_insee).strip()

        # Normaliser le code INSEE
        if len(code_insee) < 5:
            code_insee = code_insee.zfill(5)

        return self._index.get(code_insee, [])

    def get_appartement_prices(self, code_insee: str, years: int = 3, force_refresh: bool = False) -> Dict:
        """
        Récupère les prix des appartements pour une commune.

        Args:
            code_insee: Code INSEE de la commune
            years: Nombre d'années à considérer
            force_refresh: Ignoré (compatibilité API)

        Returns:
            Dict avec 'transactions', 'prix_median', 'prix_moyen', 'prix_m2_neuf', 'prix_m2_ancien', 'count'
        """
        transactions = self.fetch_transactions(code_insee, force_refresh)

        # Filtrer les transactions
        cutoff_year = datetime.now().year - years
        appartement_transactions_neuf = []
        appartement_transactions_ancien = []

        for t in transactions:
            try:
                # Filtrer par type de bien
                type_local = t.get('type_local', '').strip()
                if type_local != 'Appartement':
                    continue

                # Filtrer par date
                date_str = t.get('date_mutation', '')
                if date_str and '/' in date_str:
                    # Format: JJ/MM/YYYY
                    parts = date_str.split('/')
                    if len(parts) == 3:
                        year = int(parts[2])
                        if year < cutoff_year:
                            continue

                # Calculer le prix au m²
                valeur_str = t.get('valeur_fonciere', '').strip()
                surface_str = t.get('surface_reelle_bati', '').strip()

                if not valeur_str or not surface_str:
                    continue

                valeur = float(valeur_str)
                surface = float(surface_str)

                if surface <= 0:
                    continue

                prix_m2 = valeur / surface

                # Filtrer les valeurs aberrantes
                if not (500 < prix_m2 < 30000):
                    continue

                transaction_data = {
                    'prix_m2': prix_m2,
                    'valeur': valeur,
                    'surface': surface,
                    'date': date_str,
                    'nature': t.get('nature_mutation', ''),
                }

                # Séparer neuf (VEFA) et ancien
                nature = t.get('nature_mutation', '').lower()
                if 'futur' in nature or 'vefa' in nature or "achèvement" in nature:
                    appartement_transactions_neuf.append(transaction_data)
                else:
                    appartement_transactions_ancien.append(transaction_data)

            except (ValueError, TypeError):
                continue

        # Calculer les statistiques globales (tous types confondus)
        all_transactions = appartement_transactions_neuf + appartement_transactions_ancien

        if not all_transactions:
            return {
                'transactions': [],
                'prix_median': None,
                'prix_moyen': None,
                'prix_m2_neuf': None,
                'prix_m2_ancien': None,
                'count': 0,
                'count_neuf': 0,
                'count_ancien': 0,
            }

        # Calculer prix moyen neuf et ancien séparément
        prix_m2_neuf = None
        if appartement_transactions_neuf:
            prix_list_neuf = [t['prix_m2'] for t in appartement_transactions_neuf]
            prix_m2_neuf = round(sum(prix_list_neuf) / len(prix_list_neuf), 0)

        prix_m2_ancien = None
        if appartement_transactions_ancien:
            prix_list_ancien = [t['prix_m2'] for t in appartement_transactions_ancien]
            prix_m2_ancien = round(sum(prix_list_ancien) / len(prix_list_ancien), 0)

        # Statistiques globales
        prix_list = [t['prix_m2'] for t in all_transactions]
        prix_list.sort()

        n = len(prix_list)
        if n % 2 == 0:
            prix_median = (prix_list[n//2 - 1] + prix_list[n//2]) / 2
        else:
            prix_median = prix_list[n//2]

        prix_moyen = sum(prix_list) / n

        return {
            'transactions': all_transactions,
            'prix_median': round(prix_median, 2),
            'prix_moyen': round(prix_moyen, 2),
            'prix_m2_neuf': prix_m2_neuf,
            'prix_m2_ancien': prix_m2_ancien,
            'count': n,
            'count_neuf': len(appartement_transactions_neuf),
            'count_ancien': len(appartement_transactions_ancien),
        }

    def get_aggregated_arrondissements_prices(self, code_insee: str, years: int = 3) -> Dict:
        """
        Agrège les prix des arrondissements pour Paris, Lyon, Marseille.

        Pour les villes divisées en arrondissements dans les fichiers DVF, cette méthode
        récupère les données de tous les arrondissements et calcule une moyenne pondérée.

        Args:
            code_insee: Code INSEE de la ville principale (75056, 13055, 69123)
            years: Nombre d'années à considérer

        Returns:
            Dict avec les prix agrégés de tous les arrondissements
        """
        # Vérifier si cette ville a des arrondissements
        if code_insee not in ARRONDISSEMENTS_MAPPING:
            # Pas d'arrondissements, utiliser la méthode normale
            return self.get_appartement_prices(code_insee, years)

        arrondissements = ARRONDISSEMENTS_MAPPING[code_insee]

        # Collecter les données de tous les arrondissements
        all_neuf_prices = []
        all_ancien_prices = []
        total_count = 0
        total_count_neuf = 0
        total_count_ancien = 0
        arrondissements_details = []  # Détail par arrondissement

        for arr_code in arrondissements:
            result = self.get_appartement_prices(arr_code, years)

            if result['count'] > 0:
                # Stocker le détail de cet arrondissement
                arrondissements_details.append({
                    'code_insee': arr_code,
                    'count': result['count'],
                    'prix_m2_ancien': result['prix_m2_ancien'],
                    'prix_m2_neuf': result['prix_m2_neuf'],
                })

                # Pondérer les prix par le nombre de transactions
                if result['prix_m2_neuf']:
                    count_neuf = result['count_neuf']
                    all_neuf_prices.extend([result['prix_m2_neuf']] * count_neuf)
                    total_count_neuf += count_neuf

                if result['prix_m2_ancien']:
                    count_ancien = result['count_ancien']
                    all_ancien_prices.extend([result['prix_m2_ancien']] * count_ancien)
                    total_count_ancien += count_ancien

                total_count += result['count']

        # Calculer les moyennes pondérées
        prix_m2_neuf = None
        if all_neuf_prices:
            prix_m2_neuf = round(sum(all_neuf_prices) / len(all_neuf_prices), 0)

        prix_m2_ancien = None
        if all_ancien_prices:
            prix_m2_ancien = round(sum(all_ancien_prices) / len(all_ancien_prices), 0)

        # Calculer des statistiques globales
        all_prices = all_neuf_prices + all_ancien_prices
        prix_moyen = None
        prix_median = None

        if all_prices:
            prix_moyen = round(sum(all_prices) / len(all_prices), 2)
            sorted_prices = sorted(all_prices)
            n = len(sorted_prices)
            if n % 2 == 0:
                prix_median = round((sorted_prices[n//2 - 1] + sorted_prices[n//2]) / 2, 2)
            else:
                prix_median = round(sorted_prices[n//2], 2)

        return {
            'transactions': [],  # Ne pas retourner toutes les transactions (trop lourd)
            'prix_median': prix_median,
            'prix_moyen': prix_moyen,
            'prix_m2_neuf': prix_m2_neuf,
            'prix_m2_ancien': prix_m2_ancien,
            'count': total_count,
            'count_neuf': total_count_neuf,
            'count_ancien': total_count_ancien,
            'aggregated_from': arrondissements,  # Pour traçabilité
            'arrondissements_details': arrondissements_details,  # Détail par arrondissement
        }
