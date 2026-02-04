# -*- coding: utf-8 -*-
"""
Fetcher pour l'API Géo (geo.api.gouv.fr)
Récupère la liste des communes triées par population
"""

import json
import os
import ssl
import time
from datetime import datetime, timedelta
from typing import Optional
import urllib.request
import urllib.error

# Contexte SSL pour macOS (contourne les problèmes de certificats)
SSL_CONTEXT = ssl.create_default_context()
SSL_CONTEXT.check_hostname = False
SSL_CONTEXT.verify_mode = ssl.CERT_NONE

from ..config import (
    GEO_API_BASE,
    GEO_API_RATE_LIMIT,
    CACHE_DIR,
    CACHE_GEO_VALIDITY_DAYS,
    REQUEST_TIMEOUT,
    MAX_RETRIES,
    RETRY_BACKOFF,
)


class GeoApiFetcher:
    """Récupère les communes depuis l'API Géo, triées par population décroissante."""

    def __init__(self):
        self.cache_file = os.path.join(CACHE_DIR, "geo_communes.json")
        self._last_request_time = 0
        self._rate_limit_interval = 1.0 / GEO_API_RATE_LIMIT

    def _wait_for_rate_limit(self):
        """Respecte le rate limit de l'API."""
        elapsed = time.time() - self._last_request_time
        if elapsed < self._rate_limit_interval:
            time.sleep(self._rate_limit_interval - elapsed)
        self._last_request_time = time.time()

    def _make_request(self, url: str) -> dict:
        """Effectue une requête HTTP avec retry."""
        for attempt in range(MAX_RETRIES):
            try:
                self._wait_for_rate_limit()
                req = urllib.request.Request(
                    url,
                    headers={"User-Agent": "JeanbrunPageGenerator/1.0"}
                )
                with urllib.request.urlopen(req, timeout=REQUEST_TIMEOUT, context=SSL_CONTEXT) as response:
                    return json.loads(response.read().decode('utf-8'))
            except urllib.error.HTTPError as e:
                if e.code == 429:  # Rate limited
                    wait_time = RETRY_BACKOFF[min(attempt, len(RETRY_BACKOFF)-1)]
                    print(f"Rate limited, attente {wait_time}s...")
                    time.sleep(wait_time)
                elif attempt < MAX_RETRIES - 1:
                    wait_time = RETRY_BACKOFF[min(attempt, len(RETRY_BACKOFF)-1)]
                    print(f"Erreur HTTP {e.code}, retry dans {wait_time}s...")
                    time.sleep(wait_time)
                else:
                    raise
            except Exception as e:
                if attempt < MAX_RETRIES - 1:
                    wait_time = RETRY_BACKOFF[min(attempt, len(RETRY_BACKOFF)-1)]
                    print(f"Erreur: {e}, retry dans {wait_time}s...")
                    time.sleep(wait_time)
                else:
                    raise
        return {}

    def _is_cache_valid(self) -> bool:
        """Vérifie si le cache est encore valide."""
        if not os.path.exists(self.cache_file):
            return False

        try:
            with open(self.cache_file, 'r', encoding='utf-8') as f:
                data = json.load(f)

            cached_at = datetime.fromisoformat(data.get('cached_at', '2000-01-01'))
            expiry = cached_at + timedelta(days=CACHE_GEO_VALIDITY_DAYS)
            return datetime.now() < expiry
        except Exception:
            return False

    def _load_from_cache(self) -> Optional[list]:
        """Charge les données depuis le cache."""
        try:
            with open(self.cache_file, 'r', encoding='utf-8') as f:
                data = json.load(f)
            return data.get('communes', [])
        except Exception:
            return None

    def _save_to_cache(self, communes: list):
        """Sauvegarde les données dans le cache."""
        os.makedirs(os.path.dirname(self.cache_file), exist_ok=True)
        data = {
            'cached_at': datetime.now().isoformat(),
            'communes': communes
        }
        with open(self.cache_file, 'w', encoding='utf-8') as f:
            json.dump(data, f, ensure_ascii=False, indent=2)

    def fetch_communes_by_population(self, limit: int = 1000, force_refresh: bool = False) -> list:
        """
        Récupère les communes triées par population (décroissante).

        Args:
            limit: Nombre maximum de communes à récupérer
            force_refresh: Force le rafraîchissement du cache

        Returns:
            Liste de dictionnaires avec les données des communes
        """
        # Vérifier le cache
        if not force_refresh and self._is_cache_valid():
            cached = self._load_from_cache()
            if cached:
                print(f"Chargé {len(cached)} communes depuis le cache")
                return cached[:limit]

        print(f"Récupération des communes depuis l'API Géo (tri par population)...")

        # Construire l'URL - récupérer TOUTES les communes
        # L'API ne supporte pas le tri par population, on doit trier localement
        fields = "nom,code,population,codesPostaux,codeDepartement,departement"

        # Récupérer toutes les communes (environ 35000)
        # On peut filtrer par population minimum pour réduire
        all_communes = []

        # Récupérer par départements pour éviter timeout
        # D'abord essayer de tout récupérer d'un coup
        url = f"{GEO_API_BASE}/communes?fields={fields}"

        print("   Téléchargement de toutes les communes...")
        communes = self._make_request(url)

        if communes:
            # Trier par population décroissante
            communes.sort(key=lambda x: x.get('population', 0), reverse=True)

            # Garder les N premières
            top_communes = communes[:max(limit + 500, 2000)]  # Garder une marge

            # Ajouter le rang de population
            for i, commune in enumerate(top_communes):
                commune['population_rank'] = i + 1

            # Sauvegarder en cache
            self._save_to_cache(top_communes)
            print(f"   Récupéré et trié {len(top_communes)} communes (top population)")

            return top_communes[:limit]

        return []

    def get_commune_by_code(self, code_insee: str) -> Optional[dict]:
        """
        Récupère une commune spécifique par son code INSEE.

        Args:
            code_insee: Code INSEE de la commune

        Returns:
            Dictionnaire avec les données de la commune ou None
        """
        fields = "nom,code,population,codesPostaux,codeDepartement,departement"
        url = f"{GEO_API_BASE}/communes/{code_insee}?fields={fields}"

        try:
            return self._make_request(url)
        except Exception as e:
            print(f"Erreur lors de la récupération de {code_insee}: {e}")
            return None

    @staticmethod
    def format_departement(code_dept: str, nom_dept: str) -> str:
        """Formate le département au format '75 - Paris'."""
        return f"{code_dept} - {nom_dept}"

    @staticmethod
    def get_main_postal_code(codes_postaux: list) -> str:
        """
        Retourne le code postal principal (le plus court ou le premier).
        Pour Paris, retourne 75000 au lieu de 75001.
        """
        if not codes_postaux:
            return "00000"

        # Trier par longueur puis alphabétiquement
        sorted_codes = sorted(codes_postaux, key=lambda x: (len(x), x))
        code = sorted_codes[0]

        # Normaliser: remplacer les derniers chiffres par 000 si > 1 code postal
        if len(codes_postaux) > 1:
            # Garder les 2 premiers chiffres + 000
            code = code[:2] + "000"

        return code
