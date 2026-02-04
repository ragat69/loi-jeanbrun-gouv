#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Script principal pour extraire les données des villes françaises.

Usage:
    python3 fetch_city_data.py --num-cities 200
    python3 fetch_city_data.py --num-cities 200 --skip-existing
    python3 fetch_city_data.py --list-processed
    python3 fetch_city_data.py --force-refresh
"""

import argparse
import json
import os
import re
import sys
import unicodedata
from datetime import datetime
from typing import Optional

# Ajouter le dossier parent au path pour les imports
sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))

from data.config import BASE_DIR, CACHE_DIR, OUTPUT_DIR
from data.fetchers import GeoApiFetcher, ZonageABCFetcher, DVFFetcher, LoyersFetcher
from data.processors import PriceCalculator, FallbackProcessor
from data.generators import generate_php_data_file


class CityDataPipeline:
    """Pipeline principal pour extraire et traiter les données des villes."""

    def __init__(self):
        self.geo_fetcher = GeoApiFetcher()
        self.zonage_fetcher = ZonageABCFetcher()
        self.dvf_fetcher = DVFFetcher()
        self.loyers_fetcher = LoyersFetcher()
        self.price_calculator = PriceCalculator()
        self.fallback_processor = FallbackProcessor()

        self.villes_data_file = os.path.join(BASE_DIR, "villes_data.json")
        self.manifest_file = os.path.join(BASE_DIR, "villes_manifest.json")
        self.php_data_file = os.path.join(OUTPUT_DIR, "_data", "villes_data.php")

    def load_manifest(self) -> dict:
        """Charge le manifest des villes déjà traitées."""
        if os.path.exists(self.manifest_file):
            try:
                with open(self.manifest_file, 'r', encoding='utf-8') as f:
                    return json.load(f)
            except Exception as e:
                print(f"Erreur lors du chargement du manifest: {e}")
        return {
            "generated_at": None,
            "total_cities": 0,
            "last_population_rank": 0,
            "cities": {}
        }

    def save_manifest(self, manifest: dict):
        """Sauvegarde le manifest."""
        manifest["generated_at"] = datetime.now().isoformat()
        with open(self.manifest_file, 'w', encoding='utf-8') as f:
            json.dump(manifest, f, ensure_ascii=False, indent=2)

    def load_villes_data(self) -> dict:
        """Charge les données des villes existantes."""
        if os.path.exists(self.villes_data_file):
            try:
                with open(self.villes_data_file, 'r', encoding='utf-8') as f:
                    return json.load(f)
            except Exception as e:
                print(f"Erreur lors du chargement des données: {e}")
        return {}

    def save_villes_data(self, data: dict):
        """Sauvegarde les données des villes."""
        with open(self.villes_data_file, 'w', encoding='utf-8') as f:
            json.dump(data, f, ensure_ascii=False, indent=2)

    def generate_php_data(self, data: dict):
        """Génère le fichier PHP de données pour le site."""
        print("\n   Génération du fichier PHP de données...")
        try:
            generate_php_data_file(data, self.php_data_file)
        except Exception as e:
            print(f"   ATTENTION: Erreur lors de la génération PHP: {e}")

    def ensure_templates(self):
        """S'assure que les templates sont en place dans le dossier de sortie."""
        import shutil

        # Créer les dossiers
        includes_dir = os.path.join(OUTPUT_DIR, "_includes")
        os.makedirs(includes_dir, exist_ok=True)

        # Copier le template dynamique (seulement s'il n'existe pas)
        template_src = os.path.join(BASE_DIR, "templates", "ville_template_dynamic.php")
        template_dst = os.path.join(includes_dir, "ville_template.php")
        if os.path.exists(template_src) and not os.path.exists(template_dst):
            shutil.copy2(template_src, template_dst)
            print(f"   Template copié: {template_dst}")

        # Copier l'index dynamique (toujours mis à jour car contient le nombre de villes)
        index_src = os.path.join(BASE_DIR, "templates", "index_template_dynamic.php")
        index_dst = os.path.join(OUTPUT_DIR, "index.php")
        if os.path.exists(index_src):
            shutil.copy2(index_src, index_dst)
            print(f"   Index mis à jour: {index_dst}")

    def generate_page_stubs(self, data: dict):
        """Génère les pages stubs pour les nouvelles villes."""
        # S'assurer que les templates sont en place
        self.ensure_templates()

        stub_template_path = os.path.join(BASE_DIR, "templates", "ville_page_stub.php")

        if not os.path.exists(stub_template_path):
            print("   ATTENTION: Template stub non trouvé, pages non générées")
            return

        with open(stub_template_path, 'r', encoding='utf-8') as f:
            stub_template = f.read()

        generated = 0
        for ville, ville_data in data.items():
            filename = ville_data.get('filename')
            if not filename:
                continue

            filepath = os.path.join(OUTPUT_DIR, filename)

            # Ne pas écraser les pages existantes
            if os.path.exists(filepath):
                continue

            content = stub_template.replace('{{VILLE_KEY}}', ville)
            content = content.replace('{{VILLE_NAME}}', ville)

            with open(filepath, 'w', encoding='utf-8') as f:
                f.write(content)
            generated += 1

        if generated > 0:
            print(f"   {generated} nouvelles pages stubs générées")

    @staticmethod
    def slugify(text: str) -> str:
        """
        Convertit un texte en slug URL-friendly.
        Ex: "Saint-Étienne" -> "saint-etienne"
        """
        # Normaliser les accents
        text = unicodedata.normalize('NFKD', text)
        text = ''.join(c for c in text if not unicodedata.combining(c))
        # Minuscules
        text = text.lower()
        # Remplacer les caractères non alphanumériques par des tirets
        text = re.sub(r'[^a-z0-9]+', '-', text)
        # Supprimer les tirets en début/fin
        text = text.strip('-')
        return text

    @staticmethod
    def get_main_postal_code(codes_postaux: list, code_insee: str) -> str:
        """Retourne le code postal principal."""
        if not codes_postaux:
            # Déduire du code INSEE (approximatif)
            return code_insee[:2] + "000"

        # Pour les grandes villes avec plusieurs codes postaux, prendre le "principal"
        # Généralement c'est le premier avec 000 ou 001
        sorted_codes = sorted(codes_postaux)

        # Chercher un code en xxx000
        for code in sorted_codes:
            if code.endswith('000'):
                return code

        # Sinon prendre le premier et normaliser
        first = sorted_codes[0]
        # Remplacer les derniers chiffres par 000
        return first[:2] + "000"

    def process_city(self, commune: dict, force_refresh: bool = False) -> dict:
        """
        Traite une ville et retourne ses données complètes.

        Args:
            commune: Données de la commune depuis l'API Géo
            force_refresh: Force le rafraîchissement des données

        Returns:
            Dict avec toutes les données de la ville
        """
        code_insee = commune['code']
        nom = commune['nom']
        population = commune.get('population', 0)
        code_dept = commune.get('codeDepartement', '')
        dept_info = commune.get('departement', {})
        nom_dept = dept_info.get('nom', '') if isinstance(dept_info, dict) else str(dept_info)

        print(f"  Traitement de {nom} ({code_insee})...")

        # Initialiser les données
        ville_data = {
            'nom': nom,
            'code_insee': code_insee,
            'population': population,
            'departement': f"{code_dept} - {nom_dept}" if nom_dept else code_dept,
        }

        # Récupérer la zone
        zone = self.zonage_fetcher.get_zone(code_insee)
        ville_data['zone'] = zone

        # Récupérer les plafonds de loyer
        ville_data['plafonds_loyer'] = self.fallback_processor.get_plafonds_loyer(zone)

        # Récupérer les prix DVF
        dvf_result = self.dvf_fetcher.get_appartement_prices(code_insee, years=3, force_refresh=force_refresh)
        prices = self.price_calculator.calculate_from_dvf_result(dvf_result)

        ville_data['prix_m2_neuf'] = prices.get('prix_m2_neuf')
        ville_data['prix_m2_ancien'] = prices.get('prix_m2_ancien')
        ville_data['dvf_transactions_count'] = dvf_result.get('count', 0)

        # Récupérer le loyer marché
        loyer_m2 = self.loyers_fetcher.get_loyer_m2(code_insee)
        ville_data['loyer_marche_m2'] = loyer_m2

        # Appliquer les fallbacks pour les données manquantes
        ville_data = self.fallback_processor.fill_missing_data(
            ville_data, zone, population, code_dept
        )

        return ville_data

    def run(self, num_cities: int = 200, skip_existing: bool = False, force_refresh: bool = False) -> dict:
        """
        Exécute le pipeline complet.

        Args:
            num_cities: Nombre de villes à traiter
            skip_existing: Ignorer les villes déjà dans le manifest
            force_refresh: Forcer le rafraîchissement des données

        Returns:
            Dict des données de toutes les villes
        """
        print("=" * 60)
        print("EXTRACTION DES DONNÉES DES VILLES FRANÇAISES")
        print("=" * 60)

        # Créer le dossier de cache si nécessaire
        os.makedirs(CACHE_DIR, exist_ok=True)

        # Charger le manifest et les données existantes
        manifest = self.load_manifest()
        villes_data = self.load_villes_data()

        # Télécharger les données statiques si nécessaire
        print("\n1. Téléchargement des données de référence...")
        self.zonage_fetcher.download_if_needed(force_refresh)
        self.loyers_fetcher.download_if_needed(force_refresh)

        # Récupérer la liste des communes par population
        print("\n2. Récupération des communes par population...")
        # Récupérer plus de communes que nécessaire pour gérer skip_existing
        fetch_limit = num_cities + manifest['total_cities'] + 100
        all_communes = self.geo_fetcher.fetch_communes_by_population(
            limit=min(fetch_limit, 2000),
            force_refresh=force_refresh
        )

        if not all_communes:
            print("ERREUR: Impossible de récupérer la liste des communes")
            return villes_data

        # Filtrer les communes déjà traitées si skip_existing
        if skip_existing:
            processed_codes = set(manifest['cities'].keys())
            communes_to_process = [c for c in all_communes if c['code'] not in processed_codes]
            print(f"   {len(processed_codes)} communes déjà traitées, {len(communes_to_process)} restantes")
        else:
            communes_to_process = all_communes

        # Limiter au nombre demandé
        communes_to_process = communes_to_process[:num_cities]

        if not communes_to_process:
            print("Aucune nouvelle commune à traiter.")
            return villes_data

        print(f"\n3. Traitement de {len(communes_to_process)} communes...")
        print("   (Cela peut prendre quelques minutes en raison des appels API)")

        # Traiter chaque commune
        for i, commune in enumerate(communes_to_process, 1):
            code_insee = commune['code']
            nom = commune['nom']
            population_rank = commune.get('population_rank', i)

            print(f"\n[{i}/{len(communes_to_process)}] {nom} (rang population: {population_rank})")

            try:
                ville_data = self.process_city(commune, force_refresh)

                # Générer le slug et le nom de fichier
                codes_postaux = commune.get('codesPostaux', [])
                code_postal = self.get_main_postal_code(codes_postaux, code_insee)
                ville_slug = self.slugify(nom)
                filename = f"loi-jeanbrun-{ville_slug}-{code_postal}.php"

                ville_data['code_postal'] = code_postal
                ville_data['slug'] = ville_slug
                ville_data['filename'] = filename

                # Ajouter aux données
                villes_data[nom] = ville_data

                # Mettre à jour le manifest
                manifest['cities'][code_insee] = {
                    'nom': nom,
                    'population': commune.get('population', 0),
                    'population_rank': population_rank,
                    'code_postal': code_postal,
                    'processed_at': datetime.now().isoformat(),
                    'filename': filename,
                    'data_sources': {
                        'geo_api': True,
                        'dvf': ville_data.get('dvf_transactions_count', 0) > 0,
                        'zonage': True,
                        'loyers': not ville_data.get('loyer_marche_m2_estimated', False),
                    }
                }

                # Sauvegarder régulièrement (toutes les 10 villes)
                if i % 10 == 0:
                    manifest['total_cities'] = len(manifest['cities'])
                    manifest['last_population_rank'] = max(
                        c.get('population_rank', 0) for c in manifest['cities'].values()
                    )
                    self.save_villes_data(villes_data)
                    self.save_manifest(manifest)
                    print(f"   [Sauvegarde intermédiaire: {len(villes_data)} villes]")

            except Exception as e:
                print(f"   ERREUR pour {nom}: {e}")
                continue

        # Sauvegarde finale
        manifest['total_cities'] = len(manifest['cities'])
        if manifest['cities']:
            manifest['last_population_rank'] = max(
                c.get('population_rank', 0) for c in manifest['cities'].values()
            )

        self.save_villes_data(villes_data)
        self.save_manifest(manifest)

        # Générer automatiquement le fichier PHP de données et les pages stubs
        self.generate_php_data(villes_data)
        self.generate_page_stubs(villes_data)

        print("\n" + "=" * 60)
        print(f"TERMINÉ: {len(villes_data)} villes")
        print(f"  - villes_data.json mis à jour")
        print(f"  - {self.php_data_file} mis à jour")
        print(f"  - Pages stubs générées pour les nouvelles villes")
        print("=" * 60)

        return villes_data

    def refresh_existing_data(self, force_refresh: bool = False):
        """
        Rafraîchit les données (DVF, loyers) des villes déjà traitées.
        Utile quand les APIs deviennent disponibles après une première génération.
        """
        print("=" * 60)
        print("RAFRAÎCHISSEMENT DES DONNÉES EXISTANTES")
        print("=" * 60)

        # Charger les données existantes
        villes_data = self.load_villes_data()
        manifest = self.load_manifest()

        if not villes_data:
            print("Aucune ville à rafraîchir. Exécutez d'abord fetch_city_data.py")
            return

        print(f"\n{len(villes_data)} villes à rafraîchir")

        # Forcer le téléchargement des données de référence
        print("\n1. Téléchargement des données de référence...")
        self.zonage_fetcher.download_if_needed(force_refresh=True)
        self.loyers_fetcher.download_if_needed(force_refresh=True)

        print(f"\n2. Rafraîchissement des données DVF et loyers...")

        updated_count = 0
        for i, (ville, data) in enumerate(villes_data.items(), 1):
            code_insee = data.get('code_insee')
            if not code_insee:
                continue

            print(f"\n[{i}/{len(villes_data)}] {ville}...")

            # Récupérer les nouvelles données DVF
            dvf_result = self.dvf_fetcher.get_appartement_prices(
                code_insee, years=3, force_refresh=True
            )
            prices = self.price_calculator.calculate_from_dvf_result(dvf_result)

            # Mettre à jour si on a des données réelles
            if prices.get('prix_m2_neuf'):
                old_price = data.get('prix_m2_neuf')
                data['prix_m2_neuf'] = prices['prix_m2_neuf']
                data['prix_m2_ancien'] = prices.get('prix_m2_ancien') or int(prices['prix_m2_neuf'] * 0.85)
                data.pop('prix_m2_neuf_estimated', None)
                data.pop('prix_m2_ancien_estimated', None)
                data['dvf_transactions_count'] = dvf_result.get('count', 0)
                print(f"   DVF: {old_price} -> {prices['prix_m2_neuf']} €/m²")
                updated_count += 1
            else:
                print(f"   DVF: pas de données")

            # Récupérer les nouvelles données loyers
            loyer_m2 = self.loyers_fetcher.get_loyer_m2(code_insee)
            if loyer_m2:
                old_loyer = data.get('loyer_marche_m2')
                data['loyer_marche_m2'] = loyer_m2
                data.pop('loyer_marche_m2_estimated', None)
                print(f"   Loyers: {old_loyer} -> {loyer_m2} €/m²")

            # Mettre à jour le manifest
            if code_insee in manifest['cities']:
                manifest['cities'][code_insee]['data_sources']['dvf'] = dvf_result.get('count', 0) > 0
                manifest['cities'][code_insee]['data_sources']['loyers'] = loyer_m2 is not None
                manifest['cities'][code_insee]['refreshed_at'] = datetime.now().isoformat()

            # Sauvegarder régulièrement
            if i % 20 == 0:
                self.save_villes_data(villes_data)
                self.save_manifest(manifest)

        # Sauvegarde finale
        self.save_villes_data(villes_data)
        self.save_manifest(manifest)

        # Générer automatiquement le fichier PHP de données
        self.generate_php_data(villes_data)

        print("\n" + "=" * 60)
        print(f"TERMINÉ: {updated_count} villes mises à jour avec données réelles")
        print(f"Fichier PHP mis à jour: {self.php_data_file}")
        print("=" * 60)

    def list_processed(self):
        """Affiche la liste des villes déjà traitées."""
        manifest = self.load_manifest()

        if not manifest['cities']:
            print("Aucune ville traitée pour le moment.")
            return

        print(f"\nVilles traitées: {manifest['total_cities']}")
        print(f"Dernier rang population: {manifest['last_population_rank']}")
        print("\n" + "-" * 60)

        # Trier par rang de population
        sorted_cities = sorted(
            manifest['cities'].items(),
            key=lambda x: x[1].get('population_rank', 999999)
        )

        for code_insee, info in sorted_cities[:50]:  # Limiter l'affichage
            rank = info.get('population_rank', '?')
            nom = info.get('nom', code_insee)
            pop = info.get('population', 0)
            print(f"  {rank:4}. {nom:25} (pop: {pop:,})")

        if len(sorted_cities) > 50:
            print(f"  ... et {len(sorted_cities) - 50} autres villes")


def main():
    parser = argparse.ArgumentParser(
        description="Extraction des données des villes françaises pour les pages Loi Jeanbrun"
    )
    parser.add_argument(
        '--num-cities', '-n',
        type=int,
        default=200,
        help="Nombre de villes à traiter (défaut: 200)"
    )
    parser.add_argument(
        '--skip-existing', '-s',
        action='store_true',
        help="Ignorer les villes déjà traitées (mode incrémental)"
    )
    parser.add_argument(
        '--force-refresh', '-f',
        action='store_true',
        help="Forcer le rafraîchissement du cache"
    )
    parser.add_argument(
        '--list-processed', '-l',
        action='store_true',
        help="Afficher la liste des villes déjà traitées"
    )
    parser.add_argument(
        '--refresh-data', '-r',
        action='store_true',
        help="Rafraîchir les données (DVF, loyers) des villes déjà traitées"
    )
    parser.add_argument(
        '--output', '-o',
        type=str,
        default=None,
        help="Fichier de sortie (défaut: villes_data.json)"
    )

    args = parser.parse_args()

    pipeline = CityDataPipeline()

    if args.output:
        pipeline.villes_data_file = args.output

    if args.list_processed:
        pipeline.list_processed()
    elif args.refresh_data:
        pipeline.refresh_existing_data(force_refresh=args.force_refresh)
    else:
        pipeline.run(
            num_cities=args.num_cities,
            skip_existing=args.skip_existing,
            force_refresh=args.force_refresh
        )


if __name__ == "__main__":
    main()
