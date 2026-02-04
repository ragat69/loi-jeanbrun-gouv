# Fetchers module
from .geo_api import GeoApiFetcher
from .zonage_abc import ZonageABCFetcher
from .dvf_file import DVFFetcher  # Utilise le fichier local DVF au lieu de l'API
from .loyers import LoyersFetcher

__all__ = ['GeoApiFetcher', 'ZonageABCFetcher', 'DVFFetcher', 'LoyersFetcher']
