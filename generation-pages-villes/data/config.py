# -*- coding: utf-8 -*-
"""
Configuration pour le générateur de pages Loi Jeanbrun
"""

import os

# =============================================================================
# CHEMINS
# =============================================================================

# Dossier racine du projet
BASE_DIR = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))

# Dossier de cache
CACHE_DIR = os.path.join(BASE_DIR, "data", "cache")

# Dossier de sortie des pages (à adapter selon votre site)
# Configuré pour générer directement dans /ville/ à la racine du site
OUTPUT_DIR = "/var/vhosts/loi-jeanbrun-gouv.test/ville"

# Dossier des templates
TEMPLATES_DIR = os.path.join(BASE_DIR, "templates")

# =============================================================================
# FORMAT DES FICHIERS GÉNÉRÉS
# =============================================================================

# Extension des fichiers générés
OUTPUT_EXTENSION = ".php"

# Format du nom de fichier
# {ville_slug} = nom en minuscules, sans accents, tirets pour espaces
# {code_postal} = code postal principal
FILENAME_FORMAT = "loi-jeanbrun-{ville_slug}-{code_postal}"

# =============================================================================
# PHP INCLUDES (à adapter selon votre site)
# =============================================================================

PHP_HEADER_INCLUDE = "<?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/header.php'); ?>"
PHP_FOOTER_INCLUDE = "<?php include($_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'); ?>"

# =============================================================================
# APIs
# =============================================================================

# API Géo (geo.api.gouv.fr)
GEO_API_BASE = "https://geo.api.gouv.fr"
GEO_API_RATE_LIMIT = 10  # appels par seconde

# DVF Fichiers locaux (données publiées 2x/an)
# Liste de tous les fichiers DVF disponibles pour un historique complet
DVF_LOCAL_FILES = [
    os.path.join(BASE_DIR, "ValeursFoncieres-2020-S2.txt"),
    os.path.join(BASE_DIR, "ValeursFoncieres-2021.txt"),
    os.path.join(BASE_DIR, "ValeursFoncieres-2022.txt"),
    os.path.join(BASE_DIR, "ValeursFoncieres-2023.txt"),
    os.path.join(BASE_DIR, "ValeursFoncieres-2024.txt"),
    os.path.join(BASE_DIR, "ValeursFoncieres-2025-S1.txt"),
]

# Fichier DVF le plus récent (rétrocompatibilité)
DVF_LOCAL_FILE = DVF_LOCAL_FILES[-1]

# =============================================================================
# URLs DES DONNÉES (data.gouv.fr)
# =============================================================================

# Zonage ABC
# Source: https://www.data.gouv.fr/fr/datasets/classement-des-communes-par-zone-a-b-c/
ZONAGE_ABC_URL = "https://www.data.gouv.fr/fr/datasets/r/13f7282b-8a25-43ab-9713-8bb4e476df55"

# Carte des loyers (indicateurs loyers appartements)
# Source: https://www.data.gouv.fr/fr/datasets/carte-des-loyers-indicateurs-de-loyers-dannonce-par-commune-en-2024/
CARTE_LOYERS_URL = "https://static.data.gouv.fr/resources/carte-des-loyers-indicateurs-de-loyers-dannonce-par-commune-en-2024/20241205-153050/pred-app-mef-dhup.csv"

# =============================================================================
# PLAFONDS DE LOYER PAR ZONE (Loc'Avantages / Loi Jeanbrun)
# =============================================================================

PLAFONDS_LOYER = {
    "Abis": {"intermediaire": 18.25, "social": 14.00, "tres_social": 10.50},
    "A": {"intermediaire": 14.49, "social": 11.11, "tres_social": 8.33},
    "B1": {"intermediaire": 10.93, "social": 8.38, "tres_social": 6.29},
    "B2": {"intermediaire": 9.50, "social": 7.28, "tres_social": 5.46},
    "C": {"intermediaire": 9.17, "social": 7.03, "tres_social": 5.27},
}

# =============================================================================
# ESTIMATIONS PAR DÉFAUT (FALLBACKS)
# =============================================================================

# Prix moyens au m² par zone (estimation neuf)
PRIX_M2_PAR_ZONE = {
    "Abis": 10000,
    "A": 5500,
    "B1": 4000,
    "B2": 3000,
    "C": 2500,
}

# Ratio ancien/neuf
RATIO_ANCIEN_NEUF = 0.85

# Rendement locatif par zone (pour estimer loyer depuis prix)
RENDEMENT_PAR_ZONE = {
    "Abis": 0.035,  # 3.5%
    "A": 0.040,     # 4%
    "B1": 0.045,    # 4.5%
    "B2": 0.050,    # 5%
    "C": 0.055,     # 5.5%
}

# Taux de vacance par zone
TAUX_VACANCE_PAR_ZONE = {
    "Abis": 6.5,
    "A": 6.5,
    "B1": 7.5,
    "B2": 9.0,
    "C": 9.0,
}

# Ratio projets construction / population
RATIO_CONSTRUCTION_POPULATION = 0.005  # 0.5% de la population

# =============================================================================
# CACHE
# =============================================================================

# Durée de validité du cache (en jours)
CACHE_GEO_VALIDITY_DAYS = 7       # Données Géo API
CACHE_ZONAGE_VALIDITY_DAYS = 30   # Zonage ABC
CACHE_LOYERS_VALIDITY_DAYS = 365  # Carte des loyers
CACHE_DVF_VALIDITY_DAYS = 30      # Données DVF

# =============================================================================
# TIMEOUTS
# =============================================================================

REQUEST_TIMEOUT = 30  # secondes
MAX_RETRIES = 3
RETRY_BACKOFF = [1, 2, 4]  # secondes entre chaque retry
