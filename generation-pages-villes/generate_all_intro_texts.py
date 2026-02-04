#!/usr/bin/env python3
"""
Génère automatiquement les textes introductifs pour toutes les villes manquantes
Basé sur les caractéristiques de chaque ville (zone, population, localisation)
"""

import json
import re

VILLES_DATA_FILE = "villes_data.json"

def get_dept_name(dept_str):
    """Extrait le nom du département depuis la chaîne 'XX - Nom'"""
    if not dept_str or ' - ' not in dept_str:
        return None
    return dept_str.split(' - ')[1]

def get_ville_type(pop):
    """Détermine le type de ville selon sa population"""
    if pop >= 100000:
        return "grande_ville"
    elif pop >= 50000:
        return "ville_moyenne"
    else:
        return "ville"

def get_zone_description(zone):
    """Retourne une description adaptée à la zone"""
    descriptions = {
        "Abis": "zone très tendue",
        "A": "zone tendue",
        "B1": "zone intermédiaire dynamique",
        "B2": "zone intermédiaire",
        "C": "zone détendue"
    }
    return descriptions.get(zone, f"zone {zone}")

def generate_intro_text(ville, data):
    """
    Génère un texte introductif personnalisé basé sur les données de la ville
    """
    pop = data.get('population', 0)
    zone = data.get('zone', 'C')
    dept_name = get_dept_name(data.get('departement', ''))
    ville_type = get_ville_type(pop)
    zone_desc = get_zone_description(zone)

    # Templates variés selon la population et la zone
    templates = []

    # Pour les grandes villes (> 100k hab)
    if ville_type == "grande_ville":
        templates = [
            f"{ville} et ses {{{{population}}}} habitants s'imposent comme une métropole dynamique en zone {{{{zone}}}}.<br><br>La loi Jeanbrun y présente des opportunités d'investissement attractives avec un prix moyen de {{{{prix_m2_neuf}}}}€ au m² dans le neuf.<br><br>Le tissu économique diversifié et l'attractivité urbaine garantissent une demande locative stable.<br><br>Les plafonds de loyer à {{{{plafond_intermediaire}}}}€/m² permettent de cibler une large clientèle tout en bénéficiant d'un amortissement fiscal optimisé sur 9 ans.",

            f"Avec {{{{population}}}} habitants, {ville} se positionne comme un pôle urbain majeur en zone {{{{zone}}}}.<br><br>La loi Jeanbrun y offre des perspectives d'investissement remarquables avec un prix d'accès à {{{{prix_m2_neuf}}}}€ au m² dans le neuf.<br><br>Le dynamisme local et le marché de l'emploi soutiennent une demande locative pérenne.<br><br>Les plafonds de loyer intermédiaire à {{{{plafond_intermediaire}}}}€/m² assurent des revenus réguliers tout en optimisant la rentabilité fiscale.",
        ]

    # Pour les villes moyennes (50k-100k hab)
    elif ville_type == "ville_moyenne":
        templates = [
            f"Ville de {{{{population}}}} habitants, {ville} s'affirme en zone {{{{zone}}}} comme un marché en développement.<br><br>La loi Jeanbrun y présente des opportunités d'investissement intéressantes avec un prix moyen de {{{{prix_m2_neuf}}}}€ au m² dans le neuf.<br><br>L'équilibre entre qualité de vie et dynamisme économique assure une demande locative régulière.<br><br>Les plafonds de loyer à {{{{plafond_intermediaire}}}}€/m² permettent de proposer des logements accessibles tout en bénéficiant d'un dispositif fiscal avantageux.",

            f"Avec ses {{{{population}}}} habitants, {ville} combine attractivité locale et accessibilité en zone {{{{zone}}}}.<br><br>La loi Jeanbrun y offre des perspectives d'investissement attractives avec un prix d'accès à {{{{prix_m2_neuf}}}}€ au m² dans le neuf.<br><br>Le marché locatif bénéficie d'une demande stable portée par l'emploi local.<br><br>Les plafonds de loyer intermédiaire à {{{{plafond_intermediaire}}}}€/m² permettent d'optimiser le rendement tout en profitant de l'amortissement fiscal sur 9 ans.",
        ]

    # Pour les petites villes (< 50k hab)
    else:
        templates = [
            f"Commune de {{{{population}}}} habitants, {ville} offre un marché accessible en zone {{{{zone}}}}.<br><br>La loi Jeanbrun y présente des opportunités d'investissement particulièrement intéressantes avec un prix de {{{{prix_m2_neuf}}}}€ au m² dans le neuf.<br><br>La demande locative locale assure une occupation stable des logements.<br><br>Les plafonds de loyer à {{{{plafond_intermediaire}}}}€/m² permettent de proposer des loyers maîtrisés tout en bénéficiant d'un dispositif fiscal avantageux sur 9 ans.",

            f"Ville de {{{{population}}}} habitants, {ville} s'inscrit en zone {{{{zone}}}} comme un marché d'investissement accessible.<br><br>La loi Jeanbrun y offre des perspectives attractives avec un prix moyen de {{{{prix_m2_neuf}}}}€ au m² dans le neuf.<br><br>Le marché locatif bénéficie d'une demande régulière adaptée au bassin d'emploi local.<br><br>Les plafonds de loyer intermédiaire à {{{{plafond_intermediaire}}}}€/m² permettent d'optimiser la rentabilité tout en profitant de l'amortissement fiscal.",
        ]

    # Sélectionner un template basé sur le hash du nom de la ville (pour cohérence)
    import hashlib
    hash_val = int(hashlib.md5(ville.encode()).hexdigest(), 16)
    template = templates[hash_val % len(templates)]

    return template

def main():
    print("🚀 Génération automatique des textes introductifs\n")

    # Charger les données
    with open(VILLES_DATA_FILE, 'r', encoding='utf-8') as f:
        villes_data = json.load(f)

    # Identifier les villes sans intro_text
    villes_a_traiter = {
        ville: data
        for ville, data in villes_data.items()
        if 'intro_text' not in data or not data['intro_text']
    }

    print(f"📊 {len(villes_a_traiter)} villes à traiter sur {len(villes_data)} total\n")

    if not villes_a_traiter:
        print("✅ Toutes les villes ont déjà un texte introductif!")
        return

    # Générer les textes
    count = 0
    for ville, data in sorted(villes_a_traiter.items()):
        intro_text = generate_intro_text(ville, data)
        villes_data[ville]['intro_text'] = intro_text
        count += 1
        if count % 20 == 0:
            print(f"✍️  {count}/{len(villes_a_traiter)} textes générés...")

    # Sauvegarder
    with open(VILLES_DATA_FILE, 'w', encoding='utf-8') as f:
        json.dump(villes_data, f, ensure_ascii=False, indent=2)

    print(f"\n✅ {count} textes ajoutés avec succès !")
    print(f"📝 Fichier mis à jour : {VILLES_DATA_FILE}")
    print("\n⚠️  N'oubliez pas de regénérer le fichier PHP :")
    print("   python3 regenerate_php_data.py")

if __name__ == "__main__":
    main()
