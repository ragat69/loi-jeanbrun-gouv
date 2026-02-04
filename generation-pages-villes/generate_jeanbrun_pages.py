#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Générateur de pages PHP pour la Loi Jeanbrun par ville.

Lit les données depuis villes_data.json et génère des pages PHP
utilisant les templates Bootstrap.

Deux modes disponibles:
- Mode statique (défaut): Génère des pages PHP complètes avec données injectées
- Mode dynamique (--dynamic): Génère un fichier PHP de données + pages légères

Usage:
    python3 generate_jeanbrun_pages.py                    # Mode statique
    python3 generate_jeanbrun_pages.py --dynamic          # Mode dynamique
    python3 generate_jeanbrun_pages.py --output-dir /path/to/output
"""

import argparse
import json
import os
import re
import shutil
import sys

# Ajouter le dossier parent au path pour les imports
sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))

from data.config import (
    BASE_DIR,
    OUTPUT_DIR,
    TEMPLATES_DIR,
    OUTPUT_EXTENSION,
    FILENAME_FORMAT,
    PLAFONDS_LOYER,
)
from data.generators.php_data_generator import generate_php_data_file


def format_number(n, decimals=0):
    """Formate un nombre avec séparateurs de milliers."""
    if n is None:
        return "N/A"
    if decimals == 0:
        return f"{int(n):,}".replace(",", " ")
    return f"{n:,.{decimals}f}".replace(",", " ")


def load_villes_data():
    """Charge les données des villes depuis le fichier JSON."""
    data_file = os.path.join(BASE_DIR, "villes_data.json")
    if not os.path.exists(data_file):
        print(f"ERREUR: Fichier {data_file} non trouvé.")
        print("Exécutez d'abord: python3 fetch_city_data.py --num-cities 200")
        sys.exit(1)

    with open(data_file, 'r', encoding='utf-8') as f:
        return json.load(f)


def load_template(template_name):
    """Charge un template depuis le dossier templates."""
    template_file = os.path.join(TEMPLATES_DIR, template_name)
    if not os.path.exists(template_file):
        print(f"ERREUR: Template {template_file} non trouvé.")
        sys.exit(1)

    with open(template_file, 'r', encoding='utf-8') as f:
        return f.read()


def generate_ville_page(ville, data, template):
    """
    Génère le contenu HTML/PHP pour une page ville.

    Args:
        ville: Nom de la ville
        data: Données de la ville
        template: Contenu du template

    Returns:
        Contenu de la page générée
    """
    # Extraire les données
    zone = data.get('zone', 'C')
    population = data.get('population', 0)
    departement = data.get('departement', '')
    prix_m2_neuf = data.get('prix_m2_neuf', 0)
    prix_m2_ancien = data.get('prix_m2_ancien', 0)
    loyer_marche_m2 = data.get('loyer_marche_m2', 0)
    taux_vacance = data.get('taux_vacance', 7.0)
    projets_construction = data.get('projets_construction', 0)
    plafonds = data.get('plafonds_loyer', PLAFONDS_LOYER.get(zone, PLAFONDS_LOYER['C']))

    # Calculs pour la simulation (T3 de 65m²)
    surface_t3 = 65
    prix_t3_neuf = prix_m2_neuf * surface_t3
    prix_t3_ancien = prix_m2_ancien * surface_t3
    apport = prix_t3_neuf * 0.20
    emprunt = prix_t3_neuf * 0.80

    plafond_intermediaire = plafonds.get('intermediaire', 10.93)
    loyer_mensuel = plafond_intermediaire * surface_t3
    loyer_annuel = loyer_mensuel * 12

    assiette_amortissable = prix_t3_neuf * 0.80
    amortissement_annuel = min(assiette_amortissable * 0.035, 8000)
    gain_fiscal_annuel = amortissement_annuel * 0.30
    gain_fiscal_9ans = gain_fiscal_annuel * 9

    rendement_brut = (loyer_annuel / prix_t3_neuf * 100) if prix_t3_neuf > 0 else 0

    # Dictionnaire de remplacement
    replacements = {
        # Infos générales
        '{{ville}}': ville,
        '{{zone}}': zone,
        '{{departement}}': departement,
        '{{population_formatted}}': format_number(population),
        '{{taux_vacance}}': str(taux_vacance),
        '{{projets_construction_formatted}}': format_number(projets_construction),

        # Prix du marché
        '{{prix_m2_neuf_formatted}}': format_number(prix_m2_neuf),
        '{{prix_m2_ancien_formatted}}': format_number(prix_m2_ancien),
        '{{loyer_marche_m2}}': str(loyer_marche_m2),

        # Prix par typologie
        '{{prix_t2_neuf_formatted}}': format_number(prix_m2_neuf * 45),
        '{{prix_t3_neuf_formatted}}': format_number(prix_t3_neuf),
        '{{prix_t4_neuf_formatted}}': format_number(prix_m2_neuf * 85),
        '{{prix_t2_ancien_formatted}}': format_number(prix_m2_ancien * 45),
        '{{prix_t3_ancien_formatted}}': format_number(prix_t3_ancien),
        '{{prix_t4_ancien_formatted}}': format_number(prix_m2_ancien * 85),

        # Plafonds de loyer
        '{{plafond_intermediaire}}': str(plafonds.get('intermediaire', 0)),
        '{{plafond_social}}': str(plafonds.get('social', 0)),
        '{{plafond_tres_social}}': str(plafonds.get('tres_social', 0)),

        # Loyers par typologie
        '{{loyer_intermediaire_t2}}': f"{plafonds.get('intermediaire', 0) * 45:.2f}",
        '{{loyer_intermediaire_t3}}': f"{plafonds.get('intermediaire', 0) * 65:.2f}",
        '{{loyer_intermediaire_t4}}': f"{plafonds.get('intermediaire', 0) * 85:.2f}",
        '{{loyer_social_t2}}': f"{plafonds.get('social', 0) * 45:.2f}",
        '{{loyer_social_t3}}': f"{plafonds.get('social', 0) * 65:.2f}",
        '{{loyer_social_t4}}': f"{plafonds.get('social', 0) * 85:.2f}",
        '{{loyer_tres_social_t2}}': f"{plafonds.get('tres_social', 0) * 45:.2f}",
        '{{loyer_tres_social_t3}}': f"{plafonds.get('tres_social', 0) * 65:.2f}",
        '{{loyer_tres_social_t4}}': f"{plafonds.get('tres_social', 0) * 85:.2f}",

        # Simulation
        '{{apport_formatted}}': format_number(apport),
        '{{emprunt_formatted}}': format_number(emprunt),
        '{{loyer_mensuel}}': f"{loyer_mensuel:.2f}",
        '{{loyer_annuel}}': f"{loyer_annuel:.2f}",
        '{{assiette_formatted}}': format_number(assiette_amortissable),
        '{{amortissement_annuel_formatted}}': format_number(amortissement_annuel),
        '{{gain_fiscal_annuel_formatted}}': format_number(gain_fiscal_annuel),
        '{{gain_fiscal_9ans_formatted}}': format_number(gain_fiscal_9ans),
        '{{rendement_brut}}': f"{rendement_brut:.2f}",
    }

    # Appliquer les remplacements
    content = template
    for placeholder, value in replacements.items():
        content = content.replace(placeholder, value)

    return content


def generate_index_page(villes_data, template):
    """
    Génère la page d'index avec la liste de toutes les villes.

    Args:
        villes_data: Dict de toutes les villes
        template: Contenu du template

    Returns:
        Contenu de la page d'index
    """
    # Grouper les villes par zone
    villes_par_zone = {'Abis': [], 'A': [], 'B1': [], 'B2': [], 'C': []}

    for ville, data in villes_data.items():
        zone = data.get('zone', 'C')
        if zone not in villes_par_zone:
            zone = 'C'
        villes_par_zone[zone].append((ville, data))

    # Trier chaque groupe par population décroissante
    for zone in villes_par_zone:
        villes_par_zone[zone].sort(key=lambda x: x[1].get('population', 0), reverse=True)

    # Générer le contenu des zones
    zones_content = ""
    zone_names = {
        'Abis': 'Zone Abis (Paris et communes limitrophes)',
        'A': 'Zone A (Grandes agglomérations)',
        'B1': 'Zone B1 (Agglomérations moyennes)',
        'B2': 'Zone B2 (Autres communes)',
        'C': 'Zone C (Reste du territoire)',
    }

    for zone in ['Abis', 'A', 'B1', 'B2', 'C']:
        villes = villes_par_zone.get(zone, [])
        if not villes:
            continue

        plafond = PLAFONDS_LOYER.get(zone, PLAFONDS_LOYER['C'])['intermediaire']

        zones_content += f'''
            <section class="card mb-4">
                <div class="card-header">
                    <h2 class="h5 mb-0">{zone_names[zone]}</h2>
                    <small class="text-muted">Plafond loyer intermédiaire : {plafond} €/m²</small>
                </div>
                <div class="card-body">
                    <div class="row">
        '''

        for ville, data in villes[:20]:  # Limiter à 20 par zone pour lisibilité
            filename = data.get('filename', f"loi-jeanbrun-{ville.lower()}.php")
            pop = format_number(data.get('population', 0))
            zones_content += f'''
                        <div class="col-6 col-md-4 col-lg-3 mb-2">
                            <a href="{filename}" class="text-decoration-none">
                                <strong>{ville}</strong>
                                <small class="text-muted d-block">{pop} hab.</small>
                            </a>
                        </div>
            '''

        if len(villes) > 20:
            zones_content += f'''
                        <div class="col-12 mt-2">
                            <small class="text-muted">... et {len(villes) - 20} autres villes</small>
                        </div>
            '''

        zones_content += '''
                    </div>
                </div>
            </section>
        '''

    # Générer les lignes du tableau
    table_rows = ""
    # Trier toutes les villes par population
    all_villes = sorted(villes_data.items(), key=lambda x: x[1].get('population', 0), reverse=True)

    for ville, data in all_villes[:50]:  # Top 50 dans le tableau
        filename = data.get('filename', f"loi-jeanbrun-{ville.lower()}.php")
        zone = data.get('zone', 'C')
        pop = format_number(data.get('population', 0))
        prix_neuf = format_number(data.get('prix_m2_neuf', 0))
        plafond = data.get('plafonds_loyer', {}).get('intermediaire', 0)
        loyer_marche = data.get('loyer_marche_m2', 0)

        table_rows += f'''
                                <tr>
                                    <td><a href="{filename}">{ville}</a></td>
                                    <td><span class="badge bg-secondary">{zone}</span></td>
                                    <td>{pop}</td>
                                    <td>{prix_neuf} €</td>
                                    <td>{plafond} €/m²</td>
                                    <td>{loyer_marche} €/m²</td>
                                </tr>
        '''

    # Remplacer dans le template
    content = template
    content = content.replace('{{zones_content}}', zones_content)
    content = content.replace('{{table_rows}}', table_rows)

    return content


def generate_dynamic_pages(villes_data: dict, output_dir: str, force: bool = False):
    """
    Génère les pages en mode dynamique.

    Structure générée:
        output_dir/
        ├── _data/
        │   └── villes_data.php      # Données PHP
        ├── _includes/
        │   └── ville_template.php   # Template commun
        ├── index.php                # Index dynamique
        └── loi-jeanbrun-*.php       # Pages ville (stubs)

    Args:
        villes_data: Dictionnaire des données des villes
        output_dir: Dossier de sortie
        force: Forcer la regénération
    """
    print("\n" + "=" * 60)
    print("MODE DYNAMIQUE")
    print("=" * 60)

    # Créer les dossiers
    data_dir = os.path.join(output_dir, "_data")
    includes_dir = os.path.join(output_dir, "_includes")
    os.makedirs(data_dir, exist_ok=True)
    os.makedirs(includes_dir, exist_ok=True)

    # 1. Générer le fichier PHP de données
    print("\n1. Génération du fichier de données PHP...")
    php_data_path = os.path.join(data_dir, "villes_data.php")
    generate_php_data_file(villes_data, php_data_path)

    # 2. Copier le template dynamique
    print("\n2. Installation du template dynamique...")
    template_src = os.path.join(TEMPLATES_DIR, "ville_template_dynamic.php")
    template_dst = os.path.join(includes_dir, "ville_template.php")

    if os.path.exists(template_src):
        # Adapter le chemin du fichier de données dans le template
        with open(template_src, 'r', encoding='utf-8') as f:
            template_content = f.read()

        # Le template utilise déjà le bon chemin relatif
        with open(template_dst, 'w', encoding='utf-8') as f:
            f.write(template_content)
        print(f"   -> {template_dst}")
    else:
        print(f"   ATTENTION: Template {template_src} non trouvé")

    # 3. Copier l'index dynamique
    print("\n3. Génération de l'index dynamique...")
    index_src = os.path.join(TEMPLATES_DIR, "index_template_dynamic.php")
    index_dst = os.path.join(output_dir, "index.php")

    if os.path.exists(index_src):
        shutil.copy2(index_src, index_dst)
        print(f"   -> {index_dst}")
    else:
        print(f"   ATTENTION: Index {index_src} non trouvé")

    # 4. Générer les pages stub pour chaque ville
    print(f"\n4. Génération de {len(villes_data)} pages ville (stubs)...")
    stub_template = load_template("ville_page_stub.php")

    generated = 0
    for i, (ville, data) in enumerate(villes_data.items(), 1):
        filename = data.get('filename')
        if not filename:
            slug = data.get('slug', ville.lower())
            code_postal = data.get('code_postal', '00000')
            filename = f"{FILENAME_FORMAT.format(ville_slug=slug, code_postal=code_postal)}{OUTPUT_EXTENSION}"

        filepath = os.path.join(output_dir, filename)

        # Vérifier si on doit regénérer
        if not force and os.path.exists(filepath):
            continue

        # Remplacer les placeholders
        content = stub_template.replace('{{VILLE_KEY}}', ville)
        content = content.replace('{{VILLE_NAME}}', ville)

        with open(filepath, 'w', encoding='utf-8') as f:
            f.write(content)
        generated += 1

        if i % 50 == 0 or i == len(villes_data):
            print(f"   [{i}/{len(villes_data)}] pages traitées")

    print(f"\n   {generated} pages générées/mises à jour")


def main():
    parser = argparse.ArgumentParser(
        description="Génération des pages PHP Loi Jeanbrun"
    )
    parser.add_argument(
        '--output-dir', '-o',
        type=str,
        default=OUTPUT_DIR,
        help=f"Dossier de sortie (défaut: {OUTPUT_DIR})"
    )
    parser.add_argument(
        '--force', '-f',
        action='store_true',
        help="Regénérer toutes les pages même si elles existent"
    )
    parser.add_argument(
        '--dynamic', '-d',
        action='store_true',
        help="Mode dynamique: fichier PHP de données + pages légères (recommandé)"
    )
    parser.add_argument(
        '--refresh-data-only',
        action='store_true',
        help="Ne regénérer que le fichier de données PHP (mode dynamique)"
    )

    args = parser.parse_args()
    output_dir = args.output_dir

    print("=" * 60)
    print("GÉNÉRATION DES PAGES LOI JEANBRUN")
    print("=" * 60)

    # Charger les données
    print("\n1. Chargement des données...")
    villes_data = load_villes_data()
    print(f"   {len(villes_data)} villes chargées")

    # Mode refresh data only
    if args.refresh_data_only:
        print("\n2. Rafraîchissement du fichier de données uniquement...")
        data_dir = os.path.join(output_dir, "_data")
        os.makedirs(data_dir, exist_ok=True)
        php_data_path = os.path.join(data_dir, "villes_data.php")
        generate_php_data_file(villes_data, php_data_path)
        print("\n" + "=" * 60)
        print(f"TERMINÉ: Données mises à jour dans {php_data_path}")
        print("=" * 60)
        return

    # Créer le dossier de sortie
    os.makedirs(output_dir, exist_ok=True)
    print(f"\n2. Dossier de sortie: {output_dir}")

    # Mode dynamique ou statique
    if args.dynamic:
        generate_dynamic_pages(villes_data, output_dir, args.force)
    else:
        # Mode statique (ancien comportement)
        print("\nMode statique (pages complètes)")

        # Charger les templates
        print("\n3. Chargement des templates...")
        ville_template = load_template("ville_template.php")
        index_template = load_template("index_template.php")
        print("   Templates chargés")

        # Générer la page d'index
        print("\n4. Génération de la page d'index...")
        index_content = generate_index_page(villes_data, index_template)
        index_path = os.path.join(output_dir, f"index{OUTPUT_EXTENSION}")
        with open(index_path, 'w', encoding='utf-8') as f:
            f.write(index_content)
        print(f"   -> {index_path}")

        # Générer les pages des villes
        print(f"\n5. Génération de {len(villes_data)} pages ville...")
        for i, (ville, data) in enumerate(villes_data.items(), 1):
            filename = data.get('filename')
            if not filename:
                # Générer le nom de fichier
                slug = data.get('slug', ville.lower())
                code_postal = data.get('code_postal', '00000')
                filename = f"{FILENAME_FORMAT.format(ville_slug=slug, code_postal=code_postal)}{OUTPUT_EXTENSION}"

            filepath = os.path.join(output_dir, filename)

            # Vérifier si on doit regénérer
            if not args.force and os.path.exists(filepath):
                continue

            content = generate_ville_page(ville, data, ville_template)
            with open(filepath, 'w', encoding='utf-8') as f:
                f.write(content)

            if i % 50 == 0 or i == len(villes_data):
                print(f"   [{i}/{len(villes_data)}] pages générées")

    print("\n" + "=" * 60)
    print(f"TERMINÉ: Pages générées dans {output_dir}")
    print("=" * 60)


if __name__ == "__main__":
    main()
