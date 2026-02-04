#!/usr/bin/env python3
"""
Générateur de textes introductifs pour les pages ville
Utilise l'API Claude pour créer des textes uniques et contextualisés
Les chiffres sont remplacés par des variables PHP pour rester dynamiques
"""

import json
import os
import sys
from anthropic import Anthropic

# Configuration
VILLES_DATA_FILE = "villes_data.json"
ANTHROPIC_API_KEY = os.environ.get("ANTHROPIC_API_KEY")

if not ANTHROPIC_API_KEY:
    print("❌ Erreur : Variable d'environnement ANTHROPIC_API_KEY non définie")
    print("   Définissez-la avec : export ANTHROPIC_API_KEY='votre_clé'")
    sys.exit(1)

# Initialiser le client Anthropic
client = Anthropic(api_key=ANTHROPIC_API_KEY)

def load_villes_data():
    """Charge les données des villes depuis le JSON"""
    if not os.path.exists(VILLES_DATA_FILE):
        print(f"❌ Fichier {VILLES_DATA_FILE} non trouvé")
        sys.exit(1)

    with open(VILLES_DATA_FILE, 'r', encoding='utf-8') as f:
        return json.load(f)

def generate_intro_text(ville_name, data):
    """
    Génère un texte introductif unique pour une ville
    Utilise Claude Haiku pour réduire les coûts
    """

    prompt = f"""Tu es un expert en investissement immobilier locatif en France.

Rédige un paragraphe introductif de 120-150 mots pour une page web sur la loi Jeanbrun à {ville_name}.

Contexte de la ville :
- Zone : {data['zone']}
- Population : {data['population']} habitants
- Prix m² neuf : {data['prix_m2_neuf']}€
- Prix m² ancien : {data['prix_m2_ancien']}€
- Loyer marché : {data['loyer_marche_m2']}€/m²
- Plafond loyer intermédiaire : {data['plafonds_loyer']['intermediaire']}€/m²
- Taux de vacance : {data['taux_vacance']}%
- Département : {data['departement']}

IMPÉRATIF :
1. Le texte doit être unique et personnalisé pour {ville_name}
2. Mentionne OBLIGATOIREMENT "loi Jeanbrun" (sans lien) - ce sera transformé en lien plus tard
3. REMPLACE tous les chiffres par des PLACEHOLDERS entre doubles accolades :
   - Population → {{{{population}}}}
   - Prix m² → {{{{prix_m2_neuf}}}} ou {{{{prix_m2_ancien}}}}
   - Loyer marché → {{{{loyer_marche_m2}}}}
   - Plafond → {{{{plafond_intermediaire}}}}
   - Taux vacance → {{{{taux_vacance}}}}
4. Parle du marché locatif, de l'attractivité, des opportunités d'investissement
5. Ton professionnel mais accessible
6. Un seul paragraphe, pas de titre

Exemple de structure (à adapter) :
"Avec ses {{{{population}}}} habitants, [Ville] présente un marché immobilier [caractérisation]. La loi Jeanbrun offre ici [opportunité spécifique]. Le prix moyen au m² de {{{{prix_m2_neuf}}}}€ pour le neuf permet [analyse]. Les plafonds de loyer en zone [X] à {{{{plafond_intermediaire}}}}€/m² offrent [perspective]. [Conclusion sur l'attractivité]."

IMPORTANT : Retourne UNIQUEMENT le paragraphe, sans introduction ni explication."""

    try:
        response = client.messages.create(
            model="claude-haiku-3-5-sonnet-20241022",  # Haiku pour réduire les coûts
            max_tokens=300,
            messages=[{
                "role": "user",
                "content": prompt
            }]
        )

        intro_text = response.content[0].text.strip()
        return intro_text

    except Exception as e:
        print(f"❌ Erreur lors de la génération pour {ville_name}: {e}")
        return None

def save_intro_texts(villes_data):
    """Sauvegarde les textes intro dans le fichier JSON"""
    with open(VILLES_DATA_FILE, 'w', encoding='utf-8') as f:
        json.dump(villes_data, f, ensure_ascii=False, indent=2)
    print(f"✅ Données sauvegardées dans {VILLES_DATA_FILE}")

def main():
    print("🚀 Génération des textes introductifs avec Claude API\n")

    # Charger les données
    villes_data = load_villes_data()
    total_villes = len(villes_data)

    print(f"📊 {total_villes} villes trouvées\n")

    # Demander confirmation
    estimated_cost = (total_villes * 300 / 1_000_000) * 0.80  # Haiku input: $0.80/MTok
    estimated_cost += (total_villes * 150 / 1_000_000) * 4.00  # Haiku output: $4/MTok

    print(f"💰 Coût estimé : ~${estimated_cost:.2f}")
    response = input("\n⚠️  Voulez-vous continuer ? (o/n) : ")

    if response.lower() != 'o':
        print("❌ Annulé")
        return

    # Générer les textes
    print("\n🔄 Génération en cours...\n")

    success_count = 0
    skip_count = 0
    error_count = 0

    for idx, (ville_name, data) in enumerate(villes_data.items(), 1):
        # Skip si le texte existe déjà
        if data.get('intro_text'):
            print(f"⏭️  [{idx}/{total_villes}] {ville_name} - texte déjà existant (ignoré)")
            skip_count += 1
            continue

        print(f"✍️  [{idx}/{total_villes}] Génération pour {ville_name}...", end=" ")

        intro_text = generate_intro_text(ville_name, data)

        if intro_text:
            data['intro_text'] = intro_text
            print("✅")
            success_count += 1
        else:
            print("❌")
            error_count += 1

    # Sauvegarder
    if success_count > 0:
        save_intro_texts(villes_data)

    # Résumé
    print("\n" + "="*60)
    print("📊 RÉSUMÉ")
    print("="*60)
    print(f"✅ Générés    : {success_count}")
    print(f"⏭️  Ignorés    : {skip_count}")
    print(f"❌ Erreurs    : {error_count}")
    print(f"📝 Total      : {total_villes}")
    print("="*60)

    if success_count > 0:
        print("\n⚠️  N'oubliez pas de regénérer le fichier PHP avec :")
        print("   python3 data/generators/php_generator.py")

if __name__ == "__main__":
    main()
