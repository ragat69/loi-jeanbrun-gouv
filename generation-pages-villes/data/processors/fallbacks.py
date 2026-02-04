# -*- coding: utf-8 -*-
"""
Gestionnaire de fallbacks pour les données manquantes
"""

from typing import Optional

from ..config import (
    PLAFONDS_LOYER,
    PRIX_M2_PAR_ZONE,
    RATIO_ANCIEN_NEUF,
    RENDEMENT_PAR_ZONE,
    TAUX_VACANCE_PAR_ZONE,
    RATIO_CONSTRUCTION_POPULATION,
)


class FallbackProcessor:
    """Gère les estimations pour les données manquantes."""

    def __init__(self):
        self.departement_averages: dict = {}

    def set_departement_averages(self, averages: dict):
        """
        Définit les moyennes par département (pré-calculées).

        Args:
            averages: Dict {code_dept: {'prix_m2': x, 'loyer_m2': y}}
        """
        self.departement_averages = averages

    def get_plafonds_loyer(self, zone: str) -> dict:
        """
        Retourne les plafonds de loyer pour une zone.

        Args:
            zone: Zone (Abis, A, B1, B2, C)

        Returns:
            Dict avec 'intermediaire', 'social', 'tres_social'
        """
        zone = zone if zone in PLAFONDS_LOYER else 'C'
        return PLAFONDS_LOYER[zone].copy()

    def estimate_prix_m2_neuf(self, zone: str, code_dept: Optional[str] = None) -> int:
        """
        Estime le prix au m² neuf.

        Args:
            zone: Zone (Abis, A, B1, B2, C)
            code_dept: Code département (optionnel, pour affiner)

        Returns:
            Prix estimé au m²
        """
        # D'abord essayer la moyenne départementale
        if code_dept and code_dept in self.departement_averages:
            dept_avg = self.departement_averages[code_dept].get('prix_m2')
            if dept_avg:
                return int(dept_avg)

        # Sinon utiliser l'estimation par zone
        zone = zone if zone in PRIX_M2_PAR_ZONE else 'C'
        return PRIX_M2_PAR_ZONE[zone]

    def estimate_prix_m2_ancien(self, prix_m2_neuf: int) -> int:
        """
        Estime le prix au m² ancien depuis le prix neuf.

        Args:
            prix_m2_neuf: Prix au m² neuf

        Returns:
            Prix estimé au m² ancien
        """
        return int(prix_m2_neuf * RATIO_ANCIEN_NEUF)

    def estimate_loyer_m2(self, zone: str, prix_m2: Optional[int] = None) -> float:
        """
        Estime le loyer au m² depuis la zone et/ou le prix.

        Args:
            zone: Zone (Abis, A, B1, B2, C)
            prix_m2: Prix au m² (optionnel, pour affiner)

        Returns:
            Loyer estimé au m²
        """
        zone = zone if zone in RENDEMENT_PAR_ZONE else 'C'
        rendement = RENDEMENT_PAR_ZONE[zone]

        if prix_m2:
            # Estimer depuis le prix: loyer annuel = prix * rendement, loyer mensuel = / 12
            loyer_m2 = (prix_m2 * rendement) / 12
            return round(loyer_m2, 2)

        # Sinon utiliser une estimation basée sur les plafonds intermédiaires
        # (le loyer marché est généralement au-dessus du plafond)
        plafond = PLAFONDS_LOYER[zone]['intermediaire']
        # Le loyer marché est typiquement 20-50% au-dessus du plafond intermédiaire
        facteur = 1.3 if zone in ['Abis', 'A'] else 1.2
        return round(plafond * facteur, 2)

    def estimate_taux_vacance(self, zone: str) -> float:
        """
        Estime le taux de vacance selon la zone.

        Args:
            zone: Zone (Abis, A, B1, B2, C)

        Returns:
            Taux de vacance en %
        """
        zone = zone if zone in TAUX_VACANCE_PAR_ZONE else 'C'
        return TAUX_VACANCE_PAR_ZONE[zone]

    def estimate_projets_construction(self, population: int) -> int:
        """
        Estime le nombre de projets de construction.

        Args:
            population: Population de la commune

        Returns:
            Nombre de projets estimé
        """
        return max(100, int(population * RATIO_CONSTRUCTION_POPULATION))

    def fill_missing_data(self, ville_data: dict, zone: str, population: int, code_dept: str) -> dict:
        """
        Remplit les données manquantes avec des estimations.

        Args:
            ville_data: Dict avec les données connues
            zone: Zone de la commune
            population: Population
            code_dept: Code département

        Returns:
            Dict complété avec les estimations
        """
        result = ville_data.copy()

        # Zone
        if not result.get('zone'):
            result['zone'] = zone

        # Plafonds loyer
        if not result.get('plafonds_loyer'):
            result['plafonds_loyer'] = self.get_plafonds_loyer(zone)

        # Prix m² neuf
        if not result.get('prix_m2_neuf'):
            result['prix_m2_neuf'] = self.estimate_prix_m2_neuf(zone, code_dept)
            result['prix_m2_neuf_estimated'] = True

        # Prix m² ancien
        if not result.get('prix_m2_ancien'):
            result['prix_m2_ancien'] = self.estimate_prix_m2_ancien(result['prix_m2_neuf'])
            result['prix_m2_ancien_estimated'] = True

        # Loyer marché
        if not result.get('loyer_marche_m2'):
            result['loyer_marche_m2'] = self.estimate_loyer_m2(zone, result.get('prix_m2_neuf'))
            result['loyer_marche_m2_estimated'] = True

        # Taux de vacance
        if not result.get('taux_vacance'):
            result['taux_vacance'] = self.estimate_taux_vacance(zone)
            result['taux_vacance_estimated'] = True

        # Projets construction
        if not result.get('projets_construction'):
            result['projets_construction'] = self.estimate_projets_construction(population)
            result['projets_construction_estimated'] = True

        return result
