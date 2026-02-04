# -*- coding: utf-8 -*-
"""
Calculateur de prix immobiliers depuis les données DVF
"""

from typing import Optional
from statistics import median, mean


class PriceCalculator:
    """Calcule les prix moyens depuis les données DVF."""

    def __init__(self):
        # Ratio pour estimer l'ancien depuis le neuf
        self.ratio_ancien_neuf = 0.85

    def calculate_prices_from_transactions(self, transactions: list) -> dict:
        """
        Calcule les prix au m² depuis une liste de transactions.

        Args:
            transactions: Liste de transactions avec 'prix_m2', 'nature', etc.

        Returns:
            Dict avec 'prix_m2_neuf', 'prix_m2_ancien'
        """
        if not transactions:
            return {
                'prix_m2_neuf': None,
                'prix_m2_ancien': None,
                'count_neuf': 0,
                'count_ancien': 0,
            }

        # Séparer neuf et ancien
        prix_neuf = []
        prix_ancien = []

        for t in transactions:
            prix_m2 = t.get('prix_m2')
            nature = t.get('nature', '').lower()

            if prix_m2 is None:
                continue

            # VEFA = Vente en l'état futur d'achèvement = neuf
            if 'vefa' in nature or 'futur' in nature:
                prix_neuf.append(prix_m2)
            else:
                prix_ancien.append(prix_m2)

        # Si pas assez de données neuf, estimer depuis l'ancien
        result = {
            'prix_m2_neuf': None,
            'prix_m2_ancien': None,
            'count_neuf': len(prix_neuf),
            'count_ancien': len(prix_ancien),
        }

        # Calculer les médianes
        if prix_ancien:
            result['prix_m2_ancien'] = round(median(prix_ancien))

        if prix_neuf:
            result['prix_m2_neuf'] = round(median(prix_neuf))
        elif result['prix_m2_ancien']:
            # Estimer le neuf depuis l'ancien
            result['prix_m2_neuf'] = round(result['prix_m2_ancien'] / self.ratio_ancien_neuf)

        # Si pas d'ancien mais du neuf, estimer l'ancien
        if result['prix_m2_ancien'] is None and result['prix_m2_neuf']:
            result['prix_m2_ancien'] = round(result['prix_m2_neuf'] * self.ratio_ancien_neuf)

        return result

    def calculate_from_dvf_result(self, dvf_result: dict) -> dict:
        """
        Calcule les prix depuis un résultat DVFFetcher.get_appartement_prices().

        Args:
            dvf_result: Dict avec 'transactions', 'prix_median', etc.

        Returns:
            Dict avec 'prix_m2_neuf', 'prix_m2_ancien'
        """
        transactions = dvf_result.get('transactions', [])
        return self.calculate_prices_from_transactions(transactions)

    @staticmethod
    def remove_outliers(values: list, std_factor: float = 2.5) -> list:
        """
        Supprime les valeurs aberrantes (outliers).

        Args:
            values: Liste de valeurs numériques
            std_factor: Facteur d'écart-type pour définir les outliers

        Returns:
            Liste filtrée
        """
        if len(values) < 4:
            return values

        m = mean(values)
        # Calcul de l'écart-type
        variance = sum((x - m) ** 2 for x in values) / len(values)
        std = variance ** 0.5

        if std == 0:
            return values

        lower = m - std_factor * std
        upper = m + std_factor * std

        return [v for v in values if lower <= v <= upper]
