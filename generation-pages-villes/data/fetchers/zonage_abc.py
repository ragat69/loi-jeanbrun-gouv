# -*- coding: utf-8 -*-
"""
Fetcher pour le zonage ABC (data.gouv.fr)
Récupère la classification des communes en zones Abis, A, B1, B2, C
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
    ZONAGE_ABC_URL,
    CACHE_DIR,
    CACHE_ZONAGE_VALIDITY_DAYS,
    REQUEST_TIMEOUT,
)


class ZonageABCFetcher:
    """Parse le fichier CSV des zones ABC depuis data.gouv.fr."""

    def __init__(self):
        self.cache_file = os.path.join(CACHE_DIR, "zonage_abc.csv")
        self.metadata_file = os.path.join(CACHE_DIR, "zonage_abc_metadata.json")
        self._data: Optional[dict] = None

    def _is_cache_valid(self) -> bool:
        """Vérifie si le cache est encore valide."""
        if not os.path.exists(self.cache_file):
            return False

        try:
            mtime = os.path.getmtime(self.cache_file)
            cached_at = datetime.fromtimestamp(mtime)
            expiry = cached_at + timedelta(days=CACHE_ZONAGE_VALIDITY_DAYS)
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
            print("Zonage ABC: utilisation du cache")
            return True

        print("Téléchargement du fichier zonage ABC...")
        os.makedirs(os.path.dirname(self.cache_file), exist_ok=True)

        try:
            req = urllib.request.Request(
                ZONAGE_ABC_URL,
                headers={"User-Agent": "JeanbrunPageGenerator/1.0"}
            )
            with urllib.request.urlopen(req, timeout=REQUEST_TIMEOUT, context=SSL_CONTEXT) as response:
                content = response.read()

            with open(self.cache_file, 'wb') as f:
                f.write(content)

            print(f"Zonage ABC téléchargé: {len(content)} octets")
            self._data = None  # Reset le cache en mémoire
            return True

        except Exception as e:
            print(f"Erreur lors du téléchargement du zonage ABC: {e}")
            # Utiliser le cache existant si disponible
            return os.path.exists(self.cache_file)

    def _load_data(self) -> dict:
        """Charge et parse le fichier CSV."""
        if self._data is not None:
            return self._data

        self._data = {}

        if not os.path.exists(self.cache_file):
            print("Fichier zonage ABC non trouvé, téléchargement...")
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
                        zone_col = None

                        for col in fieldnames:
                            col_lower = col.lower().strip()
                            # Code INSEE: CODGEO ou similaire
                            if col_lower in ['codgeo', 'code_insee', 'codeinsee', 'code']:
                                code_col = col
                            # Zone: colonne contenant 'zonage' ou 'zone'
                            elif 'zonage' in col_lower or col_lower == 'zone':
                                zone_col = col

                        # Fallback si colonnes non trouvées
                        if not code_col and len(fieldnames) >= 1:
                            code_col = fieldnames[0]  # Première colonne
                        if not zone_col and len(fieldnames) >= 4:
                            zone_col = fieldnames[3]  # 4ème colonne

                        for row in reader:
                            code_insee = row.get(code_col, '').strip() if code_col else None
                            zone_raw = row.get(zone_col, '').strip().upper() if zone_col else None

                            if not code_insee or not zone_raw:
                                continue

                            # Normaliser la zone
                            zone = None
                            if zone_raw in ['A BIS', 'ABIS']:
                                zone = 'Abis'
                            elif zone_raw == 'A':
                                zone = 'A'
                            elif zone_raw == 'B1':
                                zone = 'B1'
                            elif zone_raw == 'B2':
                                zone = 'B2'
                            elif zone_raw == 'C':
                                zone = 'C'

                            if zone:
                                # Normaliser le code INSEE (5 chiffres)
                                code_insee = code_insee.zfill(5)
                                self._data[code_insee] = zone

                    print(f"Zonage ABC chargé: {len(self._data)} communes")
                    break

                except UnicodeDecodeError:
                    continue

        except Exception as e:
            print(f"Erreur lors du parsing du zonage ABC: {e}")

        return self._data

    def get_zone(self, code_insee: str) -> str:
        """
        Retourne la zone pour un code INSEE donné.

        Args:
            code_insee: Code INSEE de la commune

        Returns:
            Zone (Abis, A, B1, B2, C) ou 'C' par défaut
        """
        data = self._load_data()
        code_insee = str(code_insee).zfill(5)
        return data.get(code_insee, 'C')  # Défaut: zone C

    def get_all_zones(self) -> dict:
        """Retourne le dictionnaire complet code_insee -> zone."""
        return self._load_data().copy()
