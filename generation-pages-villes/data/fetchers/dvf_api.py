# -*- coding: utf-8 -*-
"""
Fetcher pour l'API DVF (Demandes de Valeurs Foncières)
Récupère les prix de vente immobiliers par commune
"""

import json
import os
import ssl
import time
from datetime import datetime, timedelta
from typing import Optional
import urllib.request
import urllib.error

# Contexte SSL pour macOS
SSL_CONTEXT = ssl.create_default_context()
SSL_CONTEXT.check_hostname = False
SSL_CONTEXT.verify_mode = ssl.CERT_NONE

from ..config import (
    DVF_API_BASE,
    DVF_API_RATE_LIMIT,
    CACHE_DIR,
    CACHE_DVF_VALIDITY_DAYS,
    REQUEST_TIMEOUT,
    MAX_RETRIES,
    RETRY_BACKOFF,
)


class DVFFetcher:
    """Récupère les transactions immobilières depuis l'API DVF."""

    def __init__(self):
        self.cache_dir = os.path.join(CACHE_DIR, "dvf")
        self._last_request_time = 0
        self._rate_limit_interval = 1.0 / DVF_API_RATE_LIMIT

    def _wait_for_rate_limit(self):
        """Respecte le rate limit de l'API."""
        elapsed = time.time() - self._last_request_time
        if elapsed < self._rate_limit_interval:
            time.sleep(self._rate_limit_interval - elapsed)
        self._last_request_time = time.time()

    def _get_cache_file(self, code_insee: str) -> str:
        """Retourne le chemin du fichier cache pour une commune."""
        return os.path.join(self.cache_dir, f"{code_insee}.json")

    def _is_cache_valid(self, code_insee: str) -> bool:
        """Vérifie si le cache est encore valide pour une commune."""
        cache_file = self._get_cache_file(code_insee)
        if not os.path.exists(cache_file):
            return False

        try:
            mtime = os.path.getmtime(cache_file)
            cached_at = datetime.fromtimestamp(mtime)
            expiry = cached_at + timedelta(days=CACHE_DVF_VALIDITY_DAYS)
            return datetime.now() < expiry
        except Exception:
            return False

    def _load_from_cache(self, code_insee: str) -> Optional[list]:
        """Charge les transactions depuis le cache."""
        cache_file = self._get_cache_file(code_insee)
        try:
            with open(cache_file, 'r', encoding='utf-8') as f:
                data = json.load(f)
            return data.get('transactions', [])
        except Exception:
            return None

    def _save_to_cache(self, code_insee: str, transactions: list):
        """Sauvegarde les transactions dans le cache."""
        os.makedirs(self.cache_dir, exist_ok=True)
        cache_file = self._get_cache_file(code_insee)
        data = {
            'cached_at': datetime.now().isoformat(),
            'code_insee': code_insee,
            'transactions': transactions
        }
        with open(cache_file, 'w', encoding='utf-8') as f:
            json.dump(data, f, ensure_ascii=False, indent=2)

    def _make_request(self, url: str) -> Optional[list]:
        """Effectue une requête HTTP avec retry."""
        for attempt in range(MAX_RETRIES):
            try:
                self._wait_for_rate_limit()
                req = urllib.request.Request(
                    url,
                    headers={"User-Agent": "JeanbrunPageGenerator/1.0"}
                )
                with urllib.request.urlopen(req, timeout=REQUEST_TIMEOUT, context=SSL_CONTEXT) as response:
                    data = json.loads(response.read().decode('utf-8'))
                    # L'API retourne une liste directement ou un dict avec 'resultats'
                    if isinstance(data, list):
                        return data
                    elif isinstance(data, dict):
                        return data.get('resultats', data.get('results', []))
                    return []
            except urllib.error.HTTPError as e:
                if e.code == 404:
                    # Pas de données pour cette commune
                    return []
                elif e.code == 429:
                    wait_time = RETRY_BACKOFF[min(attempt, len(RETRY_BACKOFF)-1)]
                    print(f"Rate limited, attente {wait_time}s...")
                    time.sleep(wait_time)
                elif attempt < MAX_RETRIES - 1:
                    wait_time = RETRY_BACKOFF[min(attempt, len(RETRY_BACKOFF)-1)]
                    time.sleep(wait_time)
                else:
                    print(f"Erreur HTTP {e.code} pour DVF")
                    return None
            except Exception as e:
                if attempt < MAX_RETRIES - 1:
                    wait_time = RETRY_BACKOFF[min(attempt, len(RETRY_BACKOFF)-1)]
                    time.sleep(wait_time)
                else:
                    print(f"Erreur DVF: {e}")
                    return None
        return None

    def fetch_transactions(self, code_insee: str, force_refresh: bool = False) -> list:
        """
        Récupère les transactions pour une commune.

        Args:
            code_insee: Code INSEE de la commune
            force_refresh: Force le rafraîchissement du cache

        Returns:
            Liste des transactions
        """
        code_insee = str(code_insee).zfill(5)

        # Vérifier le cache
        if not force_refresh and self._is_cache_valid(code_insee):
            cached = self._load_from_cache(code_insee)
            if cached is not None:
                return cached

        # Construire l'URL
        url = f"{DVF_API_BASE}?code_commune={code_insee}"

        # Faire la requête
        transactions = self._make_request(url)

        if transactions is not None:
            self._save_to_cache(code_insee, transactions)
            return transactions

        # En cas d'erreur, essayer le cache même expiré
        cached = self._load_from_cache(code_insee)
        if cached is not None:
            print(f"Utilisation du cache expiré pour {code_insee}")
            return cached

        return []

    def get_appartement_prices(self, code_insee: str, years: int = 3, force_refresh: bool = False) -> dict:
        """
        Récupère les prix des appartements pour une commune.

        Args:
            code_insee: Code INSEE de la commune
            years: Nombre d'années à considérer
            force_refresh: Force le rafraîchissement

        Returns:
            Dict avec 'transactions', 'prix_median', 'prix_moyen', 'count'
        """
        transactions = self.fetch_transactions(code_insee, force_refresh)

        # Filtrer les transactions
        cutoff_year = datetime.now().year - years
        appartement_transactions = []

        for t in transactions:
            try:
                # Filtrer par type de bien
                type_local = t.get('type_local', '').lower()
                if 'appartement' not in type_local:
                    continue

                # Filtrer par date
                date_str = t.get('date_mutation', '')
                if date_str:
                    year = int(date_str[:4])
                    if year < cutoff_year:
                        continue

                # Calculer le prix au m²
                valeur = t.get('valeur_fonciere')
                surface = t.get('surface_reelle_bati')

                if valeur and surface and surface > 0:
                    prix_m2 = float(valeur) / float(surface)
                    # Filtrer les valeurs aberrantes
                    if 500 < prix_m2 < 30000:
                        appartement_transactions.append({
                            'prix_m2': prix_m2,
                            'valeur': float(valeur),
                            'surface': float(surface),
                            'date': date_str,
                            'nature': t.get('nature_mutation', ''),
                        })
            except (ValueError, TypeError):
                continue

        if not appartement_transactions:
            return {
                'transactions': [],
                'prix_median': None,
                'prix_moyen': None,
                'count': 0
            }

        # Calculer les statistiques
        prix_list = [t['prix_m2'] for t in appartement_transactions]
        prix_list.sort()

        n = len(prix_list)
        if n % 2 == 0:
            prix_median = (prix_list[n//2 - 1] + prix_list[n//2]) / 2
        else:
            prix_median = prix_list[n//2]

        prix_moyen = sum(prix_list) / n

        return {
            'transactions': appartement_transactions,
            'prix_median': round(prix_median, 2),
            'prix_moyen': round(prix_moyen, 2),
            'count': n
        }
