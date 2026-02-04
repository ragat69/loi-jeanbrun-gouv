#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Script pour mettre à jour Paris, Lyon, Marseille avec les données agrégées des arrondissements.
"""

import json
import os
import sys

sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))

from data.config import BASE_DIR, OUTPUT_DIR
from data.fetchers import DVFFetcher
from data.processors import PriceCalculator
from data.generators import generate_php_data_file

# Villes à mettre à jour avec leurs codes INSEE
CITIES_TO_UPDATE = {
    'Paris': '75056',
    'Lyon': '69123',
    'Marseille': '13055',
}

def main():
    print("=" * 60)
    print("MISE À JOUR PARIS, LYON, MARSEILLE")
    print("=" * 60)

    # Charger les données existantes
    villes_data_file = os.path.join(BASE_DIR, "villes_data.json")
    with open(villes_data_file, 'r', encoding='utf-8') as f:
        villes_data = json.load(f)

    dvf_fetcher = DVFFetcher()
    price_calculator = PriceCalculator()

    updated = 0
    for ville, code_insee in CITIES_TO_UPDATE.items():
        if ville not in villes_data:
            print(f"\n⚠️  {ville} non trouvée dans les données")
            continue

        print(f"\n🔄 {ville}...")

        # Récupérer les données agrégées des arrondissements
        dvf_result = dvf_fetcher.get_aggregated_arrondissements_prices(code_insee, years=5)

        if dvf_result.get('prix_m2_neuf'):
            old_neuf = villes_data[ville].get('prix_m2_neuf')
            old_ancien = villes_data[ville].get('prix_m2_ancien')

            villes_data[ville]['prix_m2_neuf'] = dvf_result['prix_m2_neuf']
            villes_data[ville]['prix_m2_ancien'] = dvf_result.get('prix_m2_ancien') or int(dvf_result['prix_m2_neuf'] * 0.85)
            villes_data[ville].pop('prix_m2_neuf_estimated', None)
            villes_data[ville].pop('prix_m2_ancien_estimated', None)
            villes_data[ville]['dvf_transactions_count'] = dvf_result.get('count', 0)

            # Métadonnées DVF
            villes_data[ville]['dvf_period'] = dvf_fetcher.get_dvf_period()
            if dvf_result.get('arrondissements_details'):
                villes_data[ville]['dvf_arrondissements'] = dvf_result['arrondissements_details']

            print(f"   Prix neuf:   {old_neuf:>6} → {dvf_result['prix_m2_neuf']:>6} €/m²")
            print(f"   Prix ancien: {old_ancien:>6} → {villes_data[ville]['prix_m2_ancien']:>6} €/m²")
            print(f"   Transactions: {dvf_result['count']:,}")
            updated += 1
        else:
            print(f"   ❌ Pas de données")

    # Sauvegarder le JSON
    with open(villes_data_file, 'w', encoding='utf-8') as f:
        json.dump(villes_data, f, ensure_ascii=False, indent=2)

    # Générer le fichier PHP
    php_data_file = os.path.join(OUTPUT_DIR, "_data", "villes_data.php")
    print(f"\n📄 Génération du fichier PHP...")
    generate_php_data_file(villes_data, php_data_file)

    print("\n" + "=" * 60)
    print(f"✅ TERMINÉ: {updated} villes mises à jour")
    print(f"Fichier PHP: {php_data_file}")
    print("=" * 60)

if __name__ == '__main__':
    main()
