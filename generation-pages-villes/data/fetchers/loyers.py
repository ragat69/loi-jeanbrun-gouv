# -*- coding: utf-8 -*-
"""
Fetcher pour la Carte des loyers (data.gouv.fr)
Récupère les loyers médians par commune
"""

import csv
import os
import ssl
from datetime import datetime, timedelta
from typing import Optional
import urllib.request
import urllib.error

# Contexte SSL pour macOS
SSL_CONTEXT = ssl.create_default_context()
SSL_CONTEXT.check_hostname = False
SSL_CONTEXT.verify_mode = ssl.CERT_NONE

from ..config import (
    CARTE_LOYERS_URL,
    CACHE_DIR,
    CACHE_LOYERS_VALIDITY_DAYS,
    REQUEST_TIMEOUT,
)


class LoyersFetcher:
    """Parse le fichier CSV de la Carte des loyers depuis data.gouv.fr."""

    def __init__(self):
        self.cache_file = os.path.join(CACHE_DIR, "carte_loyers.csv")
        self._data: Optional[dict] = None

    def _is_cache_valid(self) -> bool:
        """Vérifie si le cache est encore valide."""
        if not os.path.exists(self.cache_file):
            return False

        try:
            mtime = os.path.getmtime(self.cache_file)
            cached_at = datetime.fromtimestamp(mtime)
            expiry = cached_at + timedelta(days=CACHE_LOYERS_VALIDITY_DAYS)
            return datetime.now() < expiry
        except Exception:
            return False

    def download_if_needed(self, force_refresh: bool = False) -> bool:
        """
        Télécharge le fichier CSV si nécessaire.

        Args:
            force_refresh: Force le téléchargement même si le cache est valide

        Returns:
            True si le téléchargement a réussi ou si le cache est valide
        """
        if not force_refresh and self._is_cache_valid():
            print("Carte des loyers: utilisation du cache")
            return True

        print("Téléchargement de la Carte des loyers...")
        os.makedirs(os.path.dirname(self.cache_file), exist_ok=True)

        try:
            req = urllib.request.Request(
                CARTE_LOYERS_URL,
                headers={"User-Agent": "JeanbrunPageGenerator/1.0"}
            )
            with urllib.request.urlopen(req, timeout=REQUEST_TIMEOUT, context=SSL_CONTEXT) as response:
                content = response.read()

            with open(self.cache_file, 'wb') as f:
                f.write(content)

            print(f"Carte des loyers téléchargée: {len(content)} octets")
            self._data = None  # Reset le cache en mémoire
            return True

        except Exception as e:
            print(f"Erreur lors du téléchargement de la Carte des loyers: {e}")
            # Utiliser le cache existant si disponible
            return os.path.exists(self.cache_file)

    def _load_data(self) -> dict:
        """Charge et parse le fichier CSV."""
        if self._data is not None:
            return self._data

        self._data = {}

        if not os.path.exists(self.cache_file):
            print("Fichier Carte des loyers non trouvé, téléchargement...")
            if not self.download_if_needed():
                return self._data

        try:
            # Essayer différents encodages
            for encoding in ['utf-8', 'latin-1', 'cp1252']:
                try:
                    with open(self.cache_file, 'r', encoding=encoding, newline='') as f:
                        # Détecter le délimiteur
                        sample = f.read(4096)
                        f.seek(0)

                        delimiter = ';' if ';' in sample else ','

                        reader = csv.DictReader(f, delimiter=delimiter)

                        # Identifier les colonnes
                        fieldnames = reader.fieldnames if reader.fieldnames else []
                        code_col = None
                        loyer_col = None

                        for col in fieldnames:
                            col_lower = col.lower().strip()
                            # Code INSEE: INSEE_C, CODGEO, code_insee...
                            if col_lower in ['insee_c', 'codgeo', 'code_insee', 'codeinsee']:
                                code_col = col
                            # Loyer: loypredm2, loyer_m2, loyer_median...
                            if col_lower in ['loypredm2', 'loyer_m2', 'loyerm2', 'loyer_median']:
                                loyer_col = col

                        if not code_col or not loyer_col:
                            print(f"   Colonnes trouvées: {fieldnames[:5]}")
                            continue

                        for row in reader:
                            code_insee = row.get(code_col, '').strip()
                            loyer_str = row.get(loyer_col, '').strip()

                            if not code_insee or not loyer_str:
                                continue

                            try:
                                # Remplacer virgule par point pour les décimaux
                                loyer_m2 = float(loyer_str.replace(',', '.'))
                                if loyer_m2 > 0:
                                    # Normaliser le code INSEE (5 chiffres)
                                    code_insee = code_insee.zfill(5)
                                    self._data[code_insee] = round(loyer_m2, 2)
                            except ValueError:
                                continue

                    print(f"Carte des loyers chargée: {len(self._data)} communes")
                    break

                except UnicodeDecodeError:
                    continue

        except Exception as e:
            print(f"Erreur lors du parsing de la Carte des loyers: {e}")

        return self._data

    def get_loyer_m2(self, code_insee: str) -> Optional[float]:
        """
        Retourne le loyer médian au m² pour un code INSEE donné.
        Gère les cas spéciaux (Paris, Lyon, Marseille par arrondissements).

        Args:
            code_insee: Code INSEE de la commune

        Returns:
            Loyer médian au m² ou None si non disponible
        """
        data = self._load_data()
        code_insee = str(code_insee).zfill(5)

        # Cas direct
        if code_insee in data:
            return data[code_insee]

        # Cas spéciaux: Paris, Lyon, Marseille (divisées en arrondissements)
        # Paris: 75056 -> arrondissements 75101-75120
        # Lyon: 69123 -> arrondissements 69381-69389
        # Marseille: 13055 -> arrondissements 13201-13216
        arrondissement_prefixes = {
            '75056': '751',   # Paris arrondissements
            '69123': '6938',  # Lyon arrondissements
            '13055': '132',   # Marseille arrondissements
        }

        if code_insee in arrondissement_prefixes:
            prefix = arrondissement_prefixes[code_insee]
            loyers = [v for k, v in data.items() if k.startswith(prefix)]
            if loyers:
                # Retourner la médiane des arrondissements
                loyers.sort()
                n = len(loyers)
                if n % 2 == 0:
                    return round((loyers[n//2 - 1] + loyers[n//2]) / 2, 2)
                return round(loyers[n//2], 2)

        return None

    def get_all_loyers(self) -> dict:
        """Retourne le dictionnaire complet code_insee -> loyer_m2."""
        return self._load_data().copy()
