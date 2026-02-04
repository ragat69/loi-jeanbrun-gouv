#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Script rapide pour ajouter dvf_period à toutes les villes dans le JSON.
"""

import json
import os
import sys

sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))

from data.config import BASE_DIR, OUTPUT_DIR
from data.fetchers import DVFFetcher
from data.generators import generate_php_data_file

def main():
    print("=" * 60)
    print("AJOUT DE dvf_period À TOUTES LES VILLES")
    print("=" * 60)

    # Charger les données existantes
    villes_data_file = os.path.join(BASE_DIR, "villes_data.json")
    with open(villes_data_file, 'r', encoding='utf-8') as f:
        villes_data = json.load(f)

    dvf_fetcher = DVFFetcher()
    dvf_period = dvf_fetcher.get_dvf_period()

    print(f"\nPériode DVF détectée: {dvf_period}")
    print(f"Nombre de villes à mettre à jour: {len(villes_data)}")

    updated = 0
    already_had = 0

    for ville_name, ville_data in villes_data.items():
        if 'dvf_period' in ville_data:
            already_had += 1
        else:
            ville_data['dvf_period'] = dvf_period
            updated += 1

    print(f"\n✅ Villes mises à jour: {updated}")
    print(f"   Villes qui avaient déjà le champ: {already_had}")

    # Sauvegarder le JSON
    with open(villes_data_file, 'w', encoding='utf-8') as f:
        json.dump(villes_data, f, ensure_ascii=False, indent=2)

    # Générer le fichier PHP
    php_data_file = os.path.join(OUTPUT_DIR, "_data", "villes_data.php")
    print(f"\n📄 Génération du fichier PHP...")
    generate_php_data_file(villes_data, php_data_file)

    print("\n" + "=" * 60)
    print(f"✅ TERMINÉ")
    print(f"Fichier JSON: {villes_data_file}")
    print(f"Fichier PHP: {php_data_file}")
    print("=" * 60)

if __name__ == '__main__':
    main()
