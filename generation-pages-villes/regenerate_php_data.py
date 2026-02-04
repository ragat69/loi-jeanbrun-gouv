#!/usr/bin/env python3
"""
Régénère le fichier PHP de données à partir du JSON
"""

import json
import sys
sys.path.insert(0, '.')

from data.generators import generate_php_data_file
from data.config import OUTPUT_DIR

# Charger les données JSON
with open('villes_data.json', 'r', encoding='utf-8') as f:
    villes_data = json.load(f)

# Générer le fichier PHP
php_data_path = f"{OUTPUT_DIR}/_data/villes_data.php"
generate_php_data_file(villes_data, php_data_path)

print(f"\n✅ Fichier PHP régénéré avec succès !")
print(f"📍 Emplacement : {php_data_path}")
print(f"📊 {len(villes_data)} villes incluses")
