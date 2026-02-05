#!/bin/bash
#
# Script de génération automatique du sitemap
# À exécuter via cron chaque semaine
#

# Chemin absolu vers le répertoire du site
SITE_DIR="/home/VOTRE_USER/loi-jeanbrun-gouv.com"

# Se placer dans le répertoire
cd "$SITE_DIR" || exit 1

# Exécuter le générateur de sitemap
/usr/bin/php generate-sitemap.php > /dev/null 2>&1

# Log de succès (optionnel)
echo "[$(date '+%Y-%m-%d %H:%M:%S')] Sitemap généré avec succès" >> "$SITE_DIR/cron-sitemap.log"

exit 0
