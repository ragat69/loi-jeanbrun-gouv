# -*- coding: utf-8 -*-
"""
Générateur du fichier PHP de données pour les villes.

Crée un fichier _data/villes_data.php contenant toutes les données
des villes sous forme de tableau PHP associatif.
"""

import json
import os
from datetime import datetime


def generate_php_data_file(villes_data: dict, output_path: str) -> str:
    """
    Génère le fichier PHP contenant les données de toutes les villes.

    Args:
        villes_data: Dictionnaire des données des villes
        output_path: Chemin du fichier PHP à générer

    Returns:
        Chemin du fichier généré
    """
    # Créer le dossier si nécessaire
    os.makedirs(os.path.dirname(output_path), exist_ok=True)

    php_content = '''<?php
/**
 * Données des villes pour la Loi Jeanbrun
 *
 * Ce fichier est généré automatiquement par le script Python.
 * NE PAS MODIFIER MANUELLEMENT.
 *
 * Pour mettre à jour les données :
 *   python3 fetch_city_data.py --refresh-data
 *   python3 generate_jeanbrun_pages.py --generate-php-data
 *
 * Généré le : ''' + datetime.now().strftime('%Y-%m-%d %H:%M:%S') + '''
 * Nombre de villes : ''' + str(len(villes_data)) + '''
 */

$villes_data = [
'''

    # Générer chaque ville
    for ville_key, data in villes_data.items():
        php_content += f"    '{_escape_php_string(ville_key)}' => [\n"
        php_content += _format_php_array(data, indent=2)
        php_content += "    ],\n"

    php_content += '''];

/**
 * Récupère les données d'une ville par son nom
 *
 * @param string $ville Nom de la ville
 * @return array|null Données de la ville ou null si non trouvée
 */
function get_ville_data($ville) {
    global $villes_data;
    return isset($villes_data[$ville]) ? $villes_data[$ville] : null;
}

/**
 * Récupère les données d'une ville par son slug
 *
 * @param string $slug Slug de la ville
 * @return array|null Données de la ville ou null si non trouvée
 */
function get_ville_by_slug($slug) {
    global $villes_data;
    foreach ($villes_data as $ville => $data) {
        if (isset($data['slug']) && $data['slug'] === $slug) {
            return $data;
        }
    }
    return null;
}

/**
 * Récupère toutes les villes triées par population
 *
 * @return array Liste des villes triées
 */
function get_villes_sorted_by_population() {
    global $villes_data;
    $sorted = $villes_data;
    uasort($sorted, function($a, $b) {
        return ($b['population'] ?? 0) - ($a['population'] ?? 0);
    });
    return $sorted;
}

/**
 * Récupère les villes d'une zone spécifique
 *
 * @param string $zone Zone (Abis, A, B1, B2, C)
 * @return array Liste des villes de cette zone
 */
function get_villes_by_zone($zone) {
    global $villes_data;
    return array_filter($villes_data, function($data) use ($zone) {
        return isset($data['zone']) && $data['zone'] === $zone;
    });
}
'''

    # Écrire le fichier
    with open(output_path, 'w', encoding='utf-8') as f:
        f.write(php_content)

    print(f"Fichier PHP généré: {output_path}")
    print(f"   {len(villes_data)} villes")

    return output_path


def _escape_php_string(s: str) -> str:
    """Échappe une chaîne pour PHP."""
    if not isinstance(s, str):
        return str(s)
    return s.replace("\\", "\\\\").replace("'", "\\'")


def _format_php_array(data: dict, indent: int = 0) -> str:
    """Formate un dictionnaire Python en tableau PHP."""
    result = ""
    indent_str = "    " * indent

    for key, value in data.items():
        key_str = f"'{_escape_php_string(key)}'"

        if isinstance(value, dict):
            result += f"{indent_str}{key_str} => [\n"
            result += _format_php_array(value, indent + 1)
            result += f"{indent_str}],\n"
        elif isinstance(value, bool):
            php_bool = 'true' if value else 'false'
            result += f"{indent_str}{key_str} => {php_bool},\n"
        elif isinstance(value, (int, float)):
            result += f"{indent_str}{key_str} => {value},\n"
        elif value is None:
            result += f"{indent_str}{key_str} => null,\n"
        else:
            result += f"{indent_str}{key_str} => '{_escape_php_string(str(value))}',\n"

    return result


if __name__ == "__main__":
    # Test avec données exemple
    test_data = {
        "Paris": {
            "nom": "Paris",
            "zone": "Abis",
            "population": 2161000,
            "prix_m2_neuf": 10000,
            "plafonds_loyer": {
                "intermediaire": 18.25,
                "social": 14.00
            }
        }
    }
    generate_php_data_file(test_data, "/tmp/test_villes_data.php")
