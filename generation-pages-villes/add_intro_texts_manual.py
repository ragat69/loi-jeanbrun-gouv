#!/usr/bin/env python3
"""
Ajoute les textes introductifs manuellement rédigés pour chaque ville
"""

import json
import os

VILLES_DATA_FILE = "villes_data.json"

# Textes introductifs personnalisés pour chaque ville
intro_texts = {
    "Paris": "Capitale française avec ses {{population}} habitants, Paris représente le marché immobilier le plus dynamique en zone {{zone}}.<br><br>La loi Jeanbrun offre ici des opportunités exceptionnelles pour les investisseurs avisés.<br><br>Avec un prix moyen de {{prix_m2_neuf}}€ au m² pour le neuf, le marché parisien bénéficie de plafonds de loyer intermédiaire à {{plafond_intermediaire}}€/m² qui permettent d'optimiser la rentabilité tout en profitant d'un amortissement fiscal généreux.<br><br>La demande locative reste soutenue avec un taux de vacance de seulement {{taux_vacance}}%, garantissant une occupation stable.",

    "Marseille": "Deuxième ville de France avec {{population}} habitants, Marseille se positionne comme un marché attractif en zone {{zone}}.<br><br>La loi Jeanbrun y trouve un terrain particulièrement favorable grâce à un prix d'acquisition abordable de {{prix_m2_neuf}}€ au m² dans le neuf.<br><br>Les plafonds de loyer à {{plafond_intermediaire}}€/m² permettent de cibler une large clientèle tout en bénéficiant d'un amortissement fiscal avantageux.<br><br>Le dynamisme méditerranéen et les {{projets_construction}} projets de construction prévus confirment l'attractivité du marché marseillais pour l'investissement locatif.",

    "Lyon": "Métropole dynamique de {{population}} habitants, Lyon s'impose comme une valeur sûre en zone {{zone}}.<br><br>La loi Jeanbrun y offre un équilibre optimal entre accessibilité et rentabilité.<br><br>Le prix moyen de {{prix_m2_neuf}}€ au m² pour le neuf permet d'investir dans une ville en pleine expansion, tandis que les plafonds de loyer à {{plafond_intermediaire}}€/m² assurent des revenus locatifs attractifs.<br><br>La demande locative lyonnaise reste forte, soutenue par un tissu économique robuste et un marché de l'emploi dynamique.",

    "Toulouse": "Ville rose forte de {{population}} habitants, Toulouse combine attractivité économique et qualité de vie en zone {{zone}}.<br><br>La loi Jeanbrun y présente des opportunités remarquables avec un prix d'accès à {{prix_m2_neuf}}€ au m² dans le neuf.<br><br>L'aéronautique et la tech font de Toulouse un marché locatif particulièrement dynamique.<br><br>Les plafonds de loyer intermédiaire à {{plafond_intermediaire}}€/m² permettent de cibler étudiants et jeunes actifs tout en optimisant le rendement locatif grâce à l'amortissement fiscal du dispositif.",

    "Nice": "Perle de la Côte d'Azur avec {{population}} habitants, Nice bénéficie du classement en zone {{zone}}.<br><br>La loi Jeanbrun y trouve un marché particulièrement attractif pour l'investissement locatif.<br><br>Avec un prix de {{prix_m2_neuf}}€ au m² dans le neuf et des plafonds de loyer à {{plafond_intermediaire}}€/m², le marché niçois offre une rentabilité intéressante.<br><br>La demande locative reste soutenue toute l'année grâce au climat méditerranéen et à l'attractivité touristique, avec un taux de vacance maîtrisé à {{taux_vacance}}%.",

    "Nantes": "Métropole ligérienne de {{population}} habitants, Nantes s'affirme en zone {{zone}} comme un marché en pleine croissance.<br><br>La loi Jeanbrun y présente des opportunités d'investissement particulièrement attractives.<br><br>Le prix d'accès à {{prix_m2_neuf}}€ au m² dans le neuf permet d'investir dans une ville dynamique, tandis que les plafonds de loyer à {{plafond_intermediaire}}€/m² assurent des revenus locatifs réguliers.<br><br>Les {{projets_construction}} projets de construction témoignent du dynamisme immobilier nantais et de son potentiel de valorisation.",

    "Montpellier": "Ville méditerranéenne de {{population}} habitants, Montpellier connaît une croissance démographique soutenue en zone {{zone}}.<br><br>La loi Jeanbrun y offre des perspectives d'investissement remarquables avec un prix moyen de {{prix_m2_neuf}}€ au m² pour le neuf.<br><br>Le marché étudiant particulièrement développé garantit une demande locative pérenne.<br><br>Les plafonds de loyer intermédiaire à {{plafond_intermediaire}}€/m² permettent de cibler efficacement cette population tout en bénéficiant d'un amortissement fiscal optimisé sur 9 ans.",

    "Strasbourg": "Capitale européenne forte de {{population}} habitants, Strasbourg bénéficie d'un marché locatif dynamique en zone {{zone}}.<br><br>La loi Jeanbrun y trouve un terrain favorable grâce à un prix d'accès maîtrisé à {{prix_m2_neuf}}€ au m² dans le neuf.<br><br>Les institutions européennes et le tissu universitaire assurent une demande locative stable.<br><br>Les plafonds de loyer à {{plafond_intermediaire}}€/m² permettent de proposer des logements de qualité tout en optimisant la rentabilité via l'amortissement fiscal du dispositif.",

    "Bordeaux": "Métropole bordelaise de {{population}} habitants, Bordeaux s'impose comme une valeur refuge en zone {{zone}}.<br><br>La loi Jeanbrun y présente des opportunités d'investissement attractives avec un prix de {{prix_m2_neuf}}€ au m² dans le neuf.<br><br>Le dynamisme économique porté par l'aéronautique et le digital garantit une demande locative soutenue.<br><br>Les plafonds de loyer intermédiaire à {{plafond_intermediaire}}€/m² permettent de cibler jeunes actifs et cadres tout en bénéficiant d'un amortissement fiscal avantageux.",

    "Lille": "Métropole nordiste de {{population}} habitants, Lille bénéficie d'une position stratégique en zone {{zone}}.<br><br>La loi Jeanbrun y offre des opportunités d'investissement particulièrement intéressantes avec un prix d'accès à {{prix_m2_neuf}}€ au m² dans le neuf.<br><br>La proximité de la Belgique et de l'Angleterre assure un marché locatif dynamique.<br><br>Les plafonds de loyer à {{plafond_intermediaire}}€/m² permettent de proposer des logements accessibles tout en optimisant le rendement grâce à l'amortissement fiscal.",

    "Rennes": "Capitale bretonne de {{population}} habitants, Rennes connaît une croissance soutenue en zone {{zone}}.<br><br>La loi Jeanbrun y présente des perspectives d'investissement remarquables avec un prix moyen de {{prix_m2_neuf}}€ au m² pour le neuf.<br><br>Le dynamisme universitaire et technologique garantit une demande locative pérenne.<br><br>Les plafonds de loyer intermédiaire à {{plafond_intermediaire}}€/m² permettent de cibler étudiants et jeunes actifs tout en bénéficiant d'un dispositif fiscal avantageux sur 9 ans.",

    "Toulon": "Ville maritime de {{population}} habitants, Toulon bénéficie du climat méditerranéen en zone {{zone}}.<br><br>La loi Jeanbrun y offre des opportunités d'investissement attractives avec un prix d'accès à {{prix_m2_neuf}}€ au m² dans le neuf.<br><br>La base navale et le développement touristique assurent une demande locative stable.<br><br>Les plafonds de loyer à {{plafond_intermediaire}}€/m² permettent de proposer des logements à des tarifs maîtrisés tout en optimisant la rentabilité via l'amortissement fiscal.",

    "Reims": "Cité des sacres forte de {{population}} habitants, Reims s'affirme en zone {{zone}} comme un marché en développement.<br><br>La loi Jeanbrun y présente des opportunités intéressantes avec un prix moyen de {{prix_m2_neuf}}€ au m² dans le neuf.<br><br>La proximité de Paris et le tissu industriel garantissent une demande locative régulière.<br><br>Les plafonds de loyer intermédiaire à {{plafond_intermediaire}}€/m² permettent de cibler une large clientèle tout en bénéficiant d'un amortissement fiscal optimisé.",

    "Saint-Étienne": "Ville ligérienne de {{population}} habitants, Saint-Étienne offre un rapport qualité-prix attractif en zone {{zone}}.<br><br>La loi Jeanbrun y présente des opportunités d'investissement particulièrement accessibles avec un prix de {{prix_m2_neuf}}€ au m² dans le neuf.<br><br>La reconversion industrielle et le développement du design assurent un renouveau du marché locatif.<br><br>Les plafonds de loyer à {{plafond_intermediaire}}€/m² permettent de proposer des logements abordables tout en bénéficiant d'un dispositif fiscal avantageux.",

    "Le Havre": "Port normand de {{population}} habitants, Le Havre bénéficie d'une position stratégique en zone {{zone}}.<br><br>La loi Jeanbrun y offre des perspectives d'investissement intéressantes avec un prix d'accès à {{prix_m2_neuf}}€ au m² dans le neuf.<br><br>L'activité portuaire et le développement éolien offshore garantissent une demande locative stable.<br><br>Les plafonds de loyer intermédiaire à {{plafond_intermediaire}}€/m² permettent de cibler employés du port et jeunes actifs tout en optimisant le rendement locatif.",

    "Villeurbanne": "Ville limitrophe de Lyon avec {{population}} habitants, Villeurbanne profite du dynamisme métropolitain en zone {{zone}}.<br><br>La loi Jeanbrun y présente des opportunités remarquables avec un prix de {{prix_m2_neuf}}€ au m² dans le neuf, plus accessible que Lyon.<br><br>La proximité immédiate de la Part-Dieu assure une demande locative soutenue.<br><br>Les plafonds de loyer à {{plafond_intermediaire}}€/m² permettent de proposer une alternative attractive tout en bénéficiant d'un amortissement fiscal optimisé.",

    "Dijon": "Capitale bourguignonne de {{population}} habitants, Dijon s'affirme en zone {{zone}} comme un marché équilibré.<br><br>La loi Jeanbrun y offre des opportunités d'investissement attractives avec un prix moyen de {{prix_m2_neuf}}€ au m² dans le neuf.<br><br>Le patrimoine gastronomique et culturel assure une qualité de vie recherchée.<br><br>Les plafonds de loyer intermédiaire à {{plafond_intermediaire}}€/m² permettent de cibler une clientèle variée tout en bénéficiant d'un dispositif fiscal avantageux sur 9 ans.",

    "Angers": "Ville ligérienne de {{population}} habitants, Angers combine douceur de vivre et dynamisme économique en zone {{zone}}.<br><br>La loi Jeanbrun y présente des perspectives d'investissement remarquables avec un prix d'accès à {{prix_m2_neuf}}€ au m² dans le neuf.<br><br>Le végétal et le numérique font d'Angers un marché locatif en croissance.<br><br>Les plafonds de loyer à {{plafond_intermediaire}}€/m² permettent de proposer des logements de qualité tout en optimisant la rentabilité fiscale.",

    "Grenoble": "Capitale alpine de {{population}} habitants, Grenoble bénéficie d'un cadre exceptionnel en zone {{zone}}.<br><br>La loi Jeanbrun y offre des opportunités d'investissement attractives avec un prix de {{prix_m2_neuf}}€ au m² dans le neuf.<br><br>La technologie et la recherche assurent une demande locative qualitative.<br><br>Les plafonds de loyer intermédiaire à {{plafond_intermediaire}}€/m² permettent de cibler chercheurs et étudiants tout en bénéficiant d'un amortissement fiscal optimisé sur 9 ans.",

    "Saint-Denis": "Ville francilienne de {{population}} habitants, Saint-Denis bénéficie de la proximité parisienne en zone {{zone}}.<br><br>La loi Jeanbrun y présente des opportunités d'investissement particulièrement intéressantes avec un prix d'accès à {{prix_m2_neuf}}€ au m² dans le neuf, plus abordable que Paris.<br><br>Le développement urbain et le Grand Paris Express garantissent un potentiel de valorisation important.<br><br>Les plafonds de loyer à {{plafond_intermediaire}}€/m² permettent de proposer une alternative attractive à la capitale."
}

def main():
    print("🚀 Ajout des textes introductifs pour les 20 villes\n")

    # Charger les données
    with open(VILLES_DATA_FILE, 'r', encoding='utf-8') as f:
        villes_data = json.load(f)

    # Ajouter les textes intro
    count = 0
    for ville, text in intro_texts.items():
        if ville in villes_data:
            villes_data[ville]['intro_text'] = text
            print(f"✅ {ville}")
            count += 1
        else:
            print(f"⚠️  {ville} - non trouvée dans les données")

    # Sauvegarder
    with open(VILLES_DATA_FILE, 'w', encoding='utf-8') as f:
        json.dump(villes_data, f, ensure_ascii=False, indent=2)

    print(f"\n✅ {count} textes ajoutés avec succès !")
    print(f"📝 Fichier mis à jour : {VILLES_DATA_FILE}")
    print("\n⚠️  N'oubliez pas de regénérer le fichier PHP :")
    print("   python3 data/generators/php_generator.py")

if __name__ == "__main__":
    main()
