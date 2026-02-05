# Guide : Sitemap et Configuration Cron sur o2switch

## ✅ Fichiers générés

Votre site dispose maintenant de :
- **sitemap_index.xml** - Index principal (6 sitemaps)
- **sitemap_main.xml** - 9 pages statiques
- **sitemap_blog.xml** - 10 articles blog
- **sitemap_cities_1.xml** à **sitemap_cities_4.xml** - 33 436 pages villes
- **robots.txt** - Configuration robots avec lien vers sitemap
- **generate-sitemap.php** - Script de génération
- **cron-sitemap.sh** - Script pour le cron

**Total : 33 455 URLs indexables**

---

## 📤 Soumission aux moteurs de recherche

### Google Search Console

1. **Accéder à Google Search Console**
   - URL : https://search.google.com/search-console
   - Connectez-vous avec votre compte Google

2. **Ajouter votre propriété (si pas déjà fait)**
   - Cliquez sur "Ajouter une propriété"
   - Choisissez "Préfixe d'URL" : `https://loi-jeanbrun-gouv.com`
   - Vérifiez la propriété (DNS, fichier HTML, ou Google Analytics)

3. **Soumettre le sitemap**
   - Dans le menu de gauche : **Sitemaps**
   - Dans "Ajouter un sitemap", entrez : `sitemap_index.xml`
   - Cliquez sur "Envoyer"

4. **Vérification**
   - Attendez 24-48h pour que Google crawle le sitemap
   - Vérifiez le statut dans l'onglet "Sitemaps"
   - Statut attendu : "Réussite" avec 33 455 URLs découvertes

### Bing Webmaster Tools

1. **Accéder à Bing Webmaster Tools**
   - URL : https://www.bing.com/webmasters
   - Connectez-vous avec votre compte Microsoft

2. **Ajouter votre site (si pas déjà fait)**
   - Cliquez sur "Ajouter un site"
   - Entrez : `https://loi-jeanbrun-gouv.com`
   - Vérifiez via fichier XML, balise meta, ou DNS

3. **Soumettre le sitemap**
   - Dans le menu : **Sitemaps** → **Soumettre un sitemap**
   - Entrez l'URL complète : `https://loi-jeanbrun-gouv.com/sitemap_index.xml`
   - Cliquez sur "Soumettre"

4. **Option alternative (import depuis GSC)**
   - Dans Bing Webmaster Tools, option "Importer depuis Google Search Console"
   - Plus rapide si vous avez déjà configuré GSC

---

## ⏰ Configuration du Cron sur o2switch

### Étape 1 : Modifier le script cron-sitemap.sh

Avant de configurer le cron, éditez `cron-sitemap.sh` pour mettre le bon chemin :

```bash
# Ouvrir le fichier
nano cron-sitemap.sh

# Modifier la ligne SITE_DIR avec votre chemin réel sur o2switch
SITE_DIR="/home/VOTRE_USER/loi-jeanbrun-gouv.com"
# Exemple : SITE_DIR="/home/votrelogin/www"

# Sauvegarder : Ctrl+O puis Entrée, puis Ctrl+X
```

### Étape 2 : Accéder au cPanel o2switch

1. Connectez-vous au cPanel o2switch
   - URL : https://www.o2switch.fr/cpanel (ou depuis votre espace client)
   - Login + mot de passe cPanel

2. Recherchez "Cron" dans la barre de recherche
   - Ou trouvez **"Tâches Cron"** dans la section "Avancé"

### Étape 3 : Configurer la tâche cron

1. **Paramètres de fréquence**
   - **Minute** : 0
   - **Heure** : 3 (3h du matin)
   - **Jour** : *
   - **Mois** : *
   - **Jour de la semaine** : 0 (Dimanche)

   Ou utilisez le **menu déroulant** : "Une fois par semaine"

2. **Commande**
   ```bash
   /home/VOTRE_USER/loi-jeanbrun-gouv.com/cron-sitemap.sh
   ```

   Remplacez `VOTRE_USER` par votre nom d'utilisateur o2switch.

3. **Email de notification (optionnel)**
   - Laissez vide si vous ne voulez pas d'email
   - Ou ajoutez `> /dev/null 2>&1` à la fin de la commande pour désactiver les emails

4. **Cliquez sur "Ajouter une nouvelle tâche Cron"**

### Exemple complet de commande cron

```bash
# Exécution tous les dimanches à 3h du matin, sans email
/home/votrelogin/www/cron-sitemap.sh > /dev/null 2>&1
```

### Étape 4 : Vérifier le fonctionnement

**Test manuel immédiat :**
```bash
# Via SSH
ssh votrelogin@ssh.o2switch.net
cd www
./cron-sitemap.sh
cat cron-sitemap.log
```

**Ou via cPanel → Gestionnaire de fichiers :**
- Naviguez vers `cron-sitemap.log`
- Vérifiez qu'une ligne de log apparaît après exécution

---

## 🔍 Vérification et maintenance

### Vérifier que les sitemaps sont accessibles

Testez ces URLs dans votre navigateur :
- https://loi-jeanbrun-gouv.com/robots.txt
- https://loi-jeanbrun-gouv.com/sitemap_index.xml
- https://loi-jeanbrun-gouv.com/sitemap_main.xml
- https://loi-jeanbrun-gouv.com/sitemap_cities_1.xml

**Résultat attendu :** XML bien formaté, pas d'erreur 404

### Régénérer manuellement le sitemap

Si vous ajoutez des villes ou articles et voulez forcer la régénération :

```bash
# Via SSH
cd /home/votrelogin/www
php generate-sitemap.php
```

Ou via cPanel → Terminal :
```bash
cd www
php generate-sitemap.php
```

### Après ajout de nouvelles villes

1. **Regénérer le sitemap** : `php generate-sitemap.php`
2. **Dans Google Search Console** :
   - Allez dans "Sitemaps"
   - Cliquez sur votre sitemap existant
   - Google va automatiquement détecter les changements (24-48h)
   - Ou forcez en retirant puis resoumettant le sitemap

3. **Dans Bing Webmaster Tools** :
   - Allez dans "Sitemaps"
   - Cliquez sur "Soumettre à nouveau"

---

## 📊 Monitoring

### Google Search Console - Métriques à suivre

Après quelques semaines :
- **Couverture** : Vérifier que les 33 455 URLs sont indexées
- **Performances** : Suivre impressions/clics sur vos pages villes
- **Expérience** : Vérifier Core Web Vitals

### Bing Webmaster Tools - Métriques à suivre

- **Rapport de crawl** : Vérifier que les URLs sont crawlées
- **Index** : Nombre de pages indexées
- **Trafic de recherche** : Performances dans Bing

---

## ⚠️ Dépannage

### Le cron ne s'exécute pas

1. Vérifier les permissions :
   ```bash
   chmod +x /home/votrelogin/www/cron-sitemap.sh
   chmod +x /home/votrelogin/www/generate-sitemap.php
   ```

2. Vérifier le chemin dans `cron-sitemap.sh` :
   ```bash
   # Doit correspondre à l'emplacement réel
   pwd  # Pour voir votre chemin actuel
   ```

3. Tester manuellement :
   ```bash
   bash /home/votrelogin/www/cron-sitemap.sh
   ```

### Sitemap non trouvé par Google

1. Vérifier que le fichier est accessible publiquement
2. Vérifier `robots.txt` contient bien la ligne Sitemap
3. Forcer Google à recrawler via GSC

### Erreurs dans le sitemap

1. Valider le XML :
   - https://www.xml-sitemaps.com/validate-xml-sitemap.html
   - Collez l'URL de votre sitemap

2. Si erreur, régénérer :
   ```bash
   rm sitemap_*.xml
   php generate-sitemap.php
   ```

---

## 🚀 Optimisations futures

### Ajouter les images au sitemap

Si vous voulez indexer les images des articles, modifiez `generate-sitemap.php` pour inclure les balises `<image:image>`.

### Notification Slack/Discord

Ajoutez un webhook dans `cron-sitemap.sh` pour être notifié à chaque génération.

### Monitoring automatique

Configurez un service comme UptimeRobot pour vérifier que `sitemap_index.xml` est toujours accessible.

---

**Dernière mise à jour** : 2026-02-05
**Fichiers générés** : 33 455 URLs dans 7 fichiers sitemaps
