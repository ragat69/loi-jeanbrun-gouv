#!/usr/bin/env python3
"""
Ajoute les textes introductifs pour les 31 villes manquantes
"""

import json
import os

VILLES_DATA_FILE = "villes_data.json"

# Textes introductifs personnalisés pour chaque ville
intro_texts = {
    "Nîmes": "Ville gardoise de {{population}} habitants, Nîmes conjugue patrimoine romain et modernité en zone {{zone}}.<br><br>La loi Jeanbrun y présente des opportunités d'investissement attractives avec un prix d'accès à {{prix_m2_neuf}}€ au m² dans le neuf.<br><br>Le tourisme culturel et le développement économique assurent une demande locative stable.<br><br>Les plafonds de loyer à {{plafond_intermediaire}}€/m² permettent de proposer des logements accessibles tout en bénéficiant d'un amortissement fiscal optimisé sur 9 ans.",

    "Aix-en-Provence": "Cité provençale de {{population}} habitants, Aix-en-Provence rayonne par son art de vivre en zone {{zone}}.<br><br>La loi Jeanbrun y offre des perspectives d'investissement remarquables avec un prix de {{prix_m2_neuf}}€ au m² dans le neuf.<br><br>Le dynamisme universitaire et la proximité de Marseille garantissent une demande locative pérenne.<br><br>Les plafonds de loyer intermédiaire à {{plafond_intermediaire}}€/m² permettent de cibler étudiants et jeunes actifs tout en optimisant la rentabilité fiscale.",

    "Clermont-Ferrand": "Capitale auvergnate de {{population}} habitants, Clermont-Ferrand s'affirme comme un pôle économique majeur en zone {{zone}}.<br><br>La loi Jeanbrun y présente des opportunités intéressantes avec un prix moyen de {{prix_m2_neuf}}€ au m² dans le neuf.<br><br>L'industrie du pneumatique et le tissu universitaire assurent une demande locative régulière.<br><br>Les plafonds de loyer à {{plafond_intermediaire}}€/m² permettent de proposer des logements de qualité tout en bénéficiant d'un dispositif fiscal avantageux.",

    "Le Mans": "Ville sarthoise de {{population}} habitants, Le Mans combine patrimoine historique et innovation en zone {{zone}}.<br><br>La loi Jeanbrun y offre des perspectives d'investissement attractives avec un prix d'accès à {{prix_m2_neuf}}€ au m² dans le neuf.<br><br>La renommée internationale des 24 Heures et le développement technologique garantissent une attractivité soutenue.<br><br>Les plafonds de loyer intermédiaire à {{plafond_intermediaire}}€/m² permettent de cibler une large clientèle tout en optimisant le rendement locatif.",

    "Brest": "Port finistérien de {{population}} habitants, Brest s'impose comme une métropole maritime dynamique en zone {{zone}}.<br><br>La loi Jeanbrun y présente des opportunités remarquables avec un prix moyen de {{prix_m2_neuf}}€ au m² dans le neuf.<br><br>La recherche océanographique et les activités navales assurent une demande locative stable.<br><br>Les plafonds de loyer à {{plafond_intermediaire}}€/m² permettent de proposer des logements accessibles tout en bénéficiant d'un amortissement fiscal avantageux sur 9 ans.",

    "Tours": "Capitale tourangelle de {{population}} habitants, Tours bénéficie d'une position stratégique en zone {{zone}}.<br><br>La loi Jeanbrun y offre des perspectives d'investissement intéressantes avec un prix de {{prix_m2_neuf}}€ au m² dans le neuf.<br><br>La douceur de vivre ligérienne et le tissu universitaire garantissent une demande locative pérenne.<br><br>Les plafonds de loyer intermédiaire à {{plafond_intermediaire}}€/m² permettent de cibler étudiants et jeunes actifs tout en optimisant la rentabilité fiscale.",

    "Amiens": "Préfecture picarde de {{population}} habitants, Amiens conjugue patrimoine gothique et renouveau urbain en zone {{zone}}.<br><br>La loi Jeanbrun y présente des opportunités d'investissement attractives avec un prix d'accès à {{prix_m2_neuf}}€ au m² dans le neuf.<br><br>La proximité de Paris et le développement économique assurent une demande locative régulière.<br><br>Les plafonds de loyer à {{plafond_intermediaire}}€/m² permettent de proposer des logements de qualité tout en bénéficiant d'un dispositif fiscal optimisé.",

    "Annecy": "Perle des Alpes avec {{population}} habitants, Annecy offre un cadre de vie exceptionnel en zone {{zone}}.<br><br>La loi Jeanbrun y présente des perspectives d'investissement remarquables avec un prix de {{prix_m2_neuf}}€ au m² dans le neuf.<br><br>Le lac et les montagnes garantissent une attractivité touristique et résidentielle permanente.<br><br>Les plafonds de loyer intermédiaire à {{plafond_intermediaire}}€/m² permettent de cibler une clientèle qualitative tout en optimisant le rendement locatif grâce à l'amortissement fiscal.",

    "Limoges": "Capitale du Limousin avec {{population}} habitants, Limoges s'affirme par son savoir-faire en zone {{zone}}.<br><br>La loi Jeanbrun y offre des opportunités d'investissement accessibles avec un prix moyen de {{prix_m2_neuf}}€ au m² dans le neuf.<br><br>Les arts du feu et le développement universitaire assurent une demande locative stable.<br><br>Les plafonds de loyer à {{plafond_intermediaire}}€/m² permettent de proposer des logements abordables tout en bénéficiant d'un amortissement fiscal avantageux sur 9 ans.",

    "Metz": "Ville mosellane de {{population}} habitants, Metz conjugue patrimoine médiéval et modernité en zone {{zone}}.<br><br>La loi Jeanbrun y présente des perspectives d'investissement intéressantes avec un prix d'accès à {{prix_m2_neuf}}€ au m² dans le neuf.<br><br>La proximité luxembourgeoise et le centre Pompidou assurent une attractivité culturelle et économique.<br><br>Les plafonds de loyer intermédiaire à {{plafond_intermediaire}}€/m² permettent de cibler une large clientèle tout en optimisant la rentabilité fiscale.",

    "Perpignan": "Capitale catalane de {{population}} habitants, Perpignan bénéficie du climat méditerranéen en zone {{zone}}.<br><br>La loi Jeanbrun y offre des opportunités d'investissement attractives avec un prix de {{prix_m2_neuf}}€ au m² dans le neuf.<br><br>Le soleil et la proximité espagnole garantissent une demande locative régulière.<br><br>Les plafonds de loyer à {{plafond_intermediaire}}€/m² permettent de proposer des logements accessibles tout en bénéficiant d'un dispositif fiscal optimisé sur 9 ans.",

    "Boulogne-Billancourt": "Ville francilienne de {{population}} habitants, Boulogne-Billancourt bénéficie d'une position privilégiée en zone {{zone}}.<br><br>La loi Jeanbrun y présente des opportunités remarquables avec un prix de {{prix_m2_neuf}}€ au m² dans le neuf, offrant une alternative à Paris.<br><br>La proximité immédiate de la capitale et le tissu économique dynamique assurent une demande locative soutenue.<br><br>Les plafonds de loyer intermédiaire à {{plafond_intermediaire}}€/m² permettent de cibler cadres et jeunes actifs tout en optimisant le rendement locatif.",

    "Besançon": "Capitale comtoise de {{population}} habitants, Besançon s'affirme par son patrimoine horloger en zone {{zone}}.<br><br>La loi Jeanbrun y offre des perspectives d'investissement intéressantes avec un prix moyen de {{prix_m2_neuf}}€ au m² dans le neuf.<br><br>Le microtechnique et le tissu universitaire garantissent une demande locative stable.<br><br>Les plafonds de loyer à {{plafond_intermediaire}}€/m² permettent de proposer des logements de qualité tout en bénéficiant d'un amortissement fiscal avantageux sur 9 ans.",

    "Rouen": "Capitale normande de {{population}} habitants, Rouen conjugue patrimoine historique et dynamisme portuaire en zone {{zone}}.<br><br>La loi Jeanbrun y présente des opportunités d'investissement attractives avec un prix d'accès à {{prix_m2_neuf}}€ au m² dans le neuf.<br><br>La proximité de Paris et le développement économique assurent une demande locative régulière.<br><br>Les plafonds de loyer intermédiaire à {{plafond_intermediaire}}€/m² permettent de cibler une large clientèle tout en optimisant la rentabilité fiscale.",

    "Orléans": "Ville ligérienne de {{population}} habitants, Orléans s'impose par sa position stratégique en zone {{zone}}.<br><br>La loi Jeanbrun y offre des perspectives d'investissement remarquables avec un prix de {{prix_m2_neuf}}€ au m² dans le neuf.<br><br>La proximité parisienne et le tissu économique diversifié garantissent une demande locative pérenne.<br><br>Les plafonds de loyer à {{plafond_intermediaire}}€/m² permettent de proposer des logements accessibles tout en bénéficiant d'un dispositif fiscal optimisé sur 9 ans.",

    "Montreuil": "Ville limitrophe de Paris avec {{population}} habitants, Montreuil profite du dynamisme métropolitain en zone {{zone}}.<br><br>La loi Jeanbrun y présente des opportunités particulièrement intéressantes avec un prix de {{prix_m2_neuf}}€ au m² dans le neuf, plus accessible que Paris.<br><br>La proximité immédiate de la capitale et le renouveau urbain assurent une demande locative soutenue.<br><br>Les plafonds de loyer intermédiaire à {{plafond_intermediaire}}€/m² permettent de proposer une alternative attractive tout en optimisant le rendement locatif.",

    "Caen": "Capitale normande de {{population}} habitants, Caen combine patrimoine ducal et modernité en zone {{zone}}.<br><br>La loi Jeanbrun y offre des perspectives d'investissement attractives avec un prix moyen de {{prix_m2_neuf}}€ au m² dans le neuf.<br><br>Le tissu universitaire et la proximité des plages garantissent une demande locative régulière.<br><br>Les plafonds de loyer à {{plafond_intermediaire}}€/m² permettent de cibler étudiants et jeunes actifs tout en bénéficiant d'un amortissement fiscal avantageux sur 9 ans.",

    "Saint-Paul": "Ville réunionnaise de {{population}} habitants, Saint-Paul bénéficie du climat tropical en zone {{zone}}.<br><br>La loi Jeanbrun y présente des opportunités d'investissement remarquables avec un prix de {{prix_m2_neuf}}€ au m² dans le neuf.<br><br>Le tourisme et le développement économique insulaire assurent une demande locative stable.<br><br>Les plafonds de loyer intermédiaire à {{plafond_intermediaire}}€/m² permettent de proposer des logements de qualité tout en optimisant la rentabilité fiscale dans ce département d'outre-mer.",

    "Argenteuil": "Ville du Val-d'Oise avec {{population}} habitants, Argenteuil bénéficie de la proximité parisienne en zone {{zone}}.<br><br>La loi Jeanbrun y offre des perspectives d'investissement intéressantes avec un prix d'accès à {{prix_m2_neuf}}€ au m² dans le neuf, plus abordable que Paris.<br><br>Les liaisons rapides avec la capitale garantissent une demande locative soutenue.<br><br>Les plafonds de loyer à {{plafond_intermediaire}}€/m² permettent de proposer une alternative attractive tout en bénéficiant d'un dispositif fiscal optimisé sur 9 ans.",

    "Mulhouse": "Ville alsacienne de {{population}} habitants, Mulhouse s'affirme par son industrie et sa culture en zone {{zone}}.<br><br>La loi Jeanbrun y présente des opportunités d'investissement accessibles avec un prix moyen de {{prix_m2_neuf}}€ au m² dans le neuf.<br><br>Le tissu industriel et la proximité suisse assurent une demande locative régulière.<br><br>Les plafonds de loyer à {{plafond_intermediaire}}€/m² permettent de proposer des logements abordables tout en bénéficiant d'un amortissement fiscal avantageux sur 9 ans.",

    "Nancy": "Capitale lorraine de {{population}} habitants, Nancy rayonne par son patrimoine Art Nouveau en zone {{zone}}.<br><br>La loi Jeanbrun y offre des perspectives d'investissement attractives avec un prix de {{prix_m2_neuf}}€ au m² dans le neuf.<br><br>Le tissu universitaire et la qualité de vie garantissent une demande locative stable.<br><br>Les plafonds de loyer intermédiaire à {{plafond_intermediaire}}€/m² permettent de cibler étudiants et jeunes actifs tout en optimisant la rentabilité fiscale.",

    "Tourcoing": "Ville nordiste de {{population}} habitants, Tourcoing bénéficie du dynamisme métropolitain lillois en zone {{zone}}.<br><br>La loi Jeanbrun y présente des opportunités d'investissement particulièrement accessibles avec un prix moyen de {{prix_m2_neuf}}€ au m² dans le neuf.<br><br>La proximité de Lille et de la Belgique assurent une demande locative régulière.<br><br>Les plafonds de loyer à {{plafond_intermediaire}}€/m² permettent de proposer des logements abordables tout en bénéficiant d'un dispositif fiscal optimisé sur 9 ans.",

    "Roubaix": "Ville textile de {{population}} habitants, Roubaix connaît un renouveau urbain dynamique en zone {{zone}}.<br><br>La loi Jeanbrun y offre des perspectives d'investissement attractives avec un prix d'accès à {{prix_m2_neuf}}€ au m² dans le neuf.<br><br>La reconversion industrielle et la proximité lilloise garantissent une demande locative en croissance.<br><br>Les plafonds de loyer à {{plafond_intermediaire}}€/m² permettent de proposer des logements accessibles tout en bénéficiant d'un amortissement fiscal avantageux sur 9 ans.",

    "Nanterre": "Préfecture des Hauts-de-Seine avec {{population}} habitants, Nanterre bénéficie d'une position stratégique en zone {{zone}}.<br><br>La loi Jeanbrun y présente des opportunités remarquables avec un prix de {{prix_m2_neuf}}€ au m² dans le neuf, offrant une alternative à Paris.<br><br>Le quartier d'affaires de La Défense à proximité assure une demande locative soutenue.<br><br>Les plafonds de loyer intermédiaire à {{plafond_intermediaire}}€/m² permettent de cibler cadres et employés tout en optimisant le rendement locatif.",

    "Vitry-sur-Seine": "Ville du Val-de-Marne avec {{population}} habitants, Vitry-sur-Seine profite du dynamisme francilien en zone {{zone}}.<br><br>La loi Jeanbrun y offre des perspectives d'investissement intéressantes avec un prix moyen de {{prix_m2_neuf}}€ au m² dans le neuf, plus accessible que Paris.<br><br>Les liaisons rapides avec la capitale et le renouveau urbain garantissent une demande locative régulière.<br><br>Les plafonds de loyer à {{plafond_intermediaire}}€/m² permettent de proposer une alternative attractive tout en bénéficiant d'un dispositif fiscal optimisé.",

    "Asnières-sur-Seine": "Ville des Hauts-de-Seine avec {{population}} habitants, Asnières-sur-Seine bénéficie d'une localisation privilégiée en zone {{zone}}.<br><br>La loi Jeanbrun y présente des opportunités remarquables avec un prix de {{prix_m2_neuf}}€ au m² dans le neuf, offrant une alternative intéressante à Paris.<br><br>La proximité immédiate de la capitale et les excellentes liaisons de transport assurent une demande locative soutenue.<br><br>Les plafonds de loyer intermédiaire à {{plafond_intermediaire}}€/m² permettent de cibler une large clientèle tout en optimisant le rendement locatif.",

    "Créteil": "Préfecture du Val-de-Marne avec {{population}} habitants, Créteil s'affirme comme un pôle économique majeur en zone {{zone}}.<br><br>La loi Jeanbrun y offre des perspectives d'investissement attractives avec un prix d'accès à {{prix_m2_neuf}}€ au m² dans le neuf, plus abordable que Paris.<br><br>Les centres commerciaux et le tissu économique garantissent une demande locative stable.<br><br>Les plafonds de loyer à {{plafond_intermediaire}}€/m² permettent de proposer des logements accessibles tout en bénéficiant d'un amortissement fiscal optimisé sur 9 ans.",

    "Avignon": "Cité des Papes avec {{population}} habitants, Avignon rayonne par son patrimoine culturel en zone {{zone}}.<br><br>La loi Jeanbrun y présente des opportunités d'investissement intéressantes avec un prix moyen de {{prix_m2_neuf}}€ au m² dans le neuf.<br><br>Le festival international et le tourisme assurent une attractivité permanente.<br><br>Les plafonds de loyer à {{plafond_intermediaire}}€/m² permettent de cibler une clientèle variée tout en bénéficiant d'un dispositif fiscal avantageux sur 9 ans.",

    "Colombes": "Ville des Hauts-de-Seine avec {{population}} habitants, Colombes profite du dynamisme métropolitain en zone {{zone}}.<br><br>La loi Jeanbrun y offre des perspectives d'investissement remarquables avec un prix de {{prix_m2_neuf}}€ au m² dans le neuf, plus accessible que Paris.<br><br>Les liaisons rapides avec la capitale et le cadre de vie agréable garantissent une demande locative soutenue.<br><br>Les plafonds de loyer intermédiaire à {{plafond_intermediaire}}€/m² permettent de proposer une alternative attractive tout en optimisant le rendement locatif.",

    "Poitiers": "Capitale poitevine de {{population}} habitants, Poitiers conjugue patrimoine historique et innovation en zone {{zone}}.<br><br>La loi Jeanbrun y présente des opportunités d'investissement attractives avec un prix moyen de {{prix_m2_neuf}}€ au m² dans le neuf.<br><br>Le tissu universitaire et le Futuroscope assurent une demande locative pérenne.<br><br>Les plafonds de loyer à {{plafond_intermediaire}}€/m² permettent de cibler étudiants et jeunes actifs tout en bénéficiant d'un amortissement fiscal avantageux sur 9 ans.",

    "Saint-Denis": "Ville francilienne de {{population}} habitants, Saint-Denis bénéficie de la proximité parisienne en zone {{zone}}.<br><br>La loi Jeanbrun y présente des opportunités d'investissement particulièrement intéressantes avec un prix d'accès à {{prix_m2_neuf}}€ au m² dans le neuf, plus abordable que Paris.<br><br>Le développement urbain et le Grand Paris Express garantissent un potentiel de valorisation important.<br><br>Les plafonds de loyer à {{plafond_intermediaire}}€/m² permettent de proposer une alternative attractive à la capitale."
}

def main():
    print("🚀 Ajout des textes introductifs pour les 31 villes manquantes\n")

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
    print("   python3 regenerate_php_data.py")

if __name__ == "__main__":
    main()
