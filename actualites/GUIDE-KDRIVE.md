# Guide d'utilisation du blog avec kDrive

Ce guide vous explique comment configurer votre blog pour travailler avec **kDrive** (ou tout autre service de synchronisation cloud comme Dropbox, Google Drive, OneDrive) afin de pouvoir publier des articles depuis n'importe quel ordinateur.

---

## Table des matières

1. [Concept](#concept)
2. [Configuration initiale](#configuration-initiale)
3. [Utilisation quotidienne](#utilisation-quotidienne)
4. [Synchronisation entre plusieurs ordinateurs](#synchronisation-entre-plusieurs-ordinateurs)
5. [Dépannage](#dépannage)
6. [Alternatives à kDrive](#alternatives-à-kdrive)

---

## Concept

### Comment ça fonctionne ?

```
┌─────────────────────────────────────────────┐
│  Ordinateur Principal (ex: Bureau)         │
│  ┌────────────────────────────────────┐    │
│  │ kDrive/                            │    │
│  │  └─ loi-jeanbrun-gouv/            │    │
│  │      ├─ actualites/               │    │
│  │      │   ├─ posts/                │    │
│  │      │   ├─ images/               │    │
│  │      │   └─ admin-local/          │    │
│  │      └─ ...                        │    │
│  └────────────────────────────────────┘    │
└─────────────────────────────────────────────┘
                    ↕
        (Synchronisation automatique)
                    ↕
┌─────────────────────────────────────────────┐
│         Serveurs kDrive (Cloud)             │
└─────────────────────────────────────────────┘
                    ↕
        (Synchronisation automatique)
                    ↕
┌─────────────────────────────────────────────┐
│  Ordinateur Portable (ex: Laptop)          │
│  ┌────────────────────────────────────┐    │
│  │ kDrive/                            │    │
│  │  └─ loi-jeanbrun-gouv/            │    │
│  │      ├─ actualites/               │    │
│  │      │   ├─ posts/                │    │
│  │      │   ├─ images/               │    │
│  │      │   └─ admin-local/          │    │
│  │      └─ ...                        │    │
│  └────────────────────────────────────┘    │
└─────────────────────────────────────────────┘
```

### Avantages de cette approche

✅ **Synchronisation automatique** entre tous vos ordinateurs
✅ **Sauvegarde automatique** dans le cloud
✅ **Accès depuis n'importe où** (bureau, portable, autre PC)
✅ **Interface d'administration disponible partout**
✅ **Pas besoin de clé USB** pour transférer les fichiers
✅ **Historique des versions** (dépend du service cloud)

---

## Configuration initiale

### Prérequis

- Un compte kDrive (ou Dropbox/Google Drive/OneDrive)
- L'application kDrive installée sur votre ordinateur
- PHP installé sur chaque ordinateur où vous voulez publier
- Git installé sur chaque ordinateur

### Étape 1 : Installer kDrive

#### Sur Windows

1. Téléchargez kDrive Desktop depuis [https://www.infomaniak.com/fr/kdrive](https://www.infomaniak.com/fr/kdrive)
2. Installez l'application
3. Connectez-vous avec vos identifiants
4. Choisissez le dossier de synchronisation (par défaut : `C:\Users\VotreNom\kDrive`)

#### Sur Mac

1. Téléchargez kDrive Desktop depuis [https://www.infomaniak.com/fr/kdrive](https://www.infomaniak.com/fr/kdrive)
2. Installez l'application dans Applications
3. Connectez-vous avec vos identifiants
4. Choisissez le dossier de synchronisation (par défaut : `/Users/VotreNom/kDrive`)

#### Sur Linux

1. Suivez les instructions sur le site d'Infomaniak pour Linux
2. Ou utilisez le client web avec synchronisation manuelle

### Étape 2 : Cloner le dépôt Git dans kDrive

Une fois kDrive installé et configuré :

```bash
# Aller dans le dossier kDrive
cd ~/kDrive  # Mac/Linux
# ou
cd C:\Users\VotreNom\kDrive  # Windows

# Cloner le dépôt
git clone git@github.com:votre-compte/loi-jeanbrun-gouv.git
# ou avec HTTPS
git clone https://github.com/votre-compte/loi-jeanbrun-gouv.git
```

### Étape 3 : Configuration Git

⚠️ **Important :** Pour éviter que Git ne synchronise des fichiers inutiles :

Créez ou modifiez le fichier `.gitignore` à la racine du projet :

```bash
cd loi-jeanbrun-gouv
nano .gitignore
```

Ajoutez ces lignes :

```
# Fichiers kDrive
.kdrive/
*.kdrive
.sync/
~$*

# Fichiers Dropbox (si vous utilisez Dropbox)
.dropbox
.dropbox.attr
*.dropbox

# Fichiers OneDrive
.onedrive
desktop.ini

# Fichiers macOS
.DS_Store
.AppleDouble
.LSOverride

# Thumbnails
._*
```

Sauvegardez et quittez.

### Étape 4 : Vérifier la synchronisation

1. Créez un fichier test dans le dossier kDrive/loi-jeanbrun-gouv
2. Attendez quelques secondes
3. Vérifiez qu'il apparaît dans l'interface web de kDrive
4. Si vous avez un second ordinateur avec kDrive, vérifiez qu'il y apparaît aussi

---

## Utilisation quotidienne

### Workflow standard

#### Sur votre ordinateur principal (Bureau)

1. **Ouvrez le dossier synchronisé :**
   ```bash
   cd ~/kDrive/loi-jeanbrun-gouv
   ```

2. **Lancez l'interface d'administration :**
   ```bash
   cd actualites/admin-local
   ./start-admin.sh  # Mac/Linux
   # ou
   start-admin.bat   # Windows
   ```

3. **Créez votre article** via l'interface web

4. **Publiez avec Git** :
   - Soit en cochant "Auto-commit and push to Git" dans l'interface
   - Soit manuellement avec le script `publish-to-git.sh`

5. **Attendez la synchronisation** :
   - kDrive synchronise automatiquement vos changements
   - Vérifiez l'icône kDrive dans la barre système (coche verte = synchronisé)

#### Sur votre ordinateur portable (en déplacement)

1. **Attendez la synchronisation** :
   - Ouvrez kDrive et attendez que tous les fichiers soient synchronisés
   - L'icône kDrive affiche une coche verte quand c'est fait

2. **Travaillez comme sur votre ordinateur principal :**
   ```bash
   cd ~/kDrive/loi-jeanbrun-gouv/actualites/admin-local
   ./start-admin.sh
   ```

3. **Publiez normalement**

4. **Les changements seront synchronisés automatiquement** vers votre bureau

---

## Synchronisation entre plusieurs ordinateurs

### Configuration multi-ordinateurs

#### Ordinateur 1 (Configuration)

1. Installez kDrive
2. Clonez le dépôt Git dans kDrive
3. Configurez Git avec vos identifiants

#### Ordinateur 2 (Ajout)

1. Installez kDrive avec le même compte
2. Attendez que le dossier `loi-jeanbrun-gouv` se synchronise
3. Installez PHP et Git
4. Configurez Git avec les mêmes identifiants :
   ```bash
   git config --global user.name "Votre Nom"
   git config --global user.email "votre@email.com"
   ```

5. Testez l'accès Git :
   ```bash
   cd ~/kDrive/loi-jeanbrun-gouv
   git status
   ```

### Bonnes pratiques avec plusieurs ordinateurs

#### Toujours faire un `git pull` avant de travailler

```bash
cd ~/kDrive/loi-jeanbrun-gouv
git pull
```

Cela garantit que vous avez la dernière version avant de créer un nouvel article.

#### Éviter de travailler simultanément

⚠️ **Attention :** Ne travaillez pas sur le même article en même temps sur deux ordinateurs différents.

Si vous devez le faire :
1. Sur ordinateur 1 : Créez l'article en "Draft"
2. Publiez-le avec Git
3. Sur ordinateur 2 : Faites un `git pull`
4. Continuez l'édition

#### Résoudre les conflits

Si vous avez un conflit Git (rare) :

```bash
# 1. Récupérer les dernières modifications
git pull

# 2. Git vous signalera les conflits
# Ouvrez les fichiers concernés et résolvez manuellement

# 3. Une fois résolu, terminez la fusion
git add .
git commit -m "Résolution conflit"
git push
```

---

## Gestion des fichiers

### Ce qui est synchronisé par kDrive

✅ Tous les fichiers du projet
✅ Articles (fichiers .md)
✅ Images
✅ Scripts
✅ Configuration

### Ce qui est géré par Git

✅ Articles publiés (dans `posts/`)
✅ Images publiées (dans `images/`)
✅ Code source
✅ Configuration

### Workflow complet

```
1. Vous créez un article sur ordinateur A
   ↓
2. L'article est sauvegardé dans kDrive/loi-jeanbrun-gouv/actualites/posts/
   ↓
3. kDrive synchronise vers le cloud
   ↓
4. Vous cliquez "Publish" avec auto-commit
   ↓
5. Git commit + push vers le serveur de production
   ↓
6. Le serveur récupère les changements (git pull)
   ↓
7. L'article est visible sur le site
   ↓
8. kDrive synchronise aussi vers ordinateur B
   ↓
9. Sur ordinateur B, vous pouvez modifier l'article
```

---

## Dépannage

### kDrive ne synchronise pas

**Problème :** Les fichiers ne se synchronisent pas entre ordinateurs

**Solutions :**
1. Vérifiez la connexion internet
2. Vérifiez l'espace disponible dans kDrive
3. Redémarrez l'application kDrive
4. Vérifiez que la synchronisation n'est pas en pause
5. Vérifiez qu'il n'y a pas d'erreur dans l'interface kDrive

### Conflits de version kDrive

**Problème :** kDrive crée des copies "conflicted copy"

**Solution :**
1. kDrive détecte que le même fichier a été modifié sur deux ordinateurs
2. Il crée une copie de sauvegarde avec "(conflicted copy)" dans le nom
3. Comparez les deux versions
4. Gardez la version correcte
5. Supprimez la copie conflictuelle

### Git et kDrive en conflit

**Problème :** Git signale des modifications non voulues

**Cause :** kDrive peut modifier les métadonnées des fichiers

**Solution :**
```bash
# Réinitialiser les changements non désirés
git checkout -- .

# Ou ignorer les changements de permissions
git config core.fileMode false
```

### L'interface d'administration ne fonctionne pas

**Problème :** Erreur au lancement de l'interface sur un second ordinateur

**Solutions :**
1. Vérifiez que PHP est installé : `php -v`
2. Vérifiez que vous êtes dans le bon dossier
3. Sur Mac/Linux : `chmod +x start-admin.sh`
4. Vérifiez que le port 8080 n'est pas déjà utilisé

### Performance lente

**Problème :** kDrive ralentit l'ordinateur ou Git

**Solutions :**
1. Excluez le dossier `.git` de la synchronisation temps réel dans kDrive
2. Configurez kDrive pour synchroniser à intervalles réguliers plutôt qu'en temps réel
3. Sur Windows : Ajoutez le dossier kDrive aux exclusions de l'antivirus

---

## Alternatives à kDrive

Cette configuration fonctionne avec d'autres services de synchronisation :

### Dropbox

```bash
cd ~/Dropbox
git clone [...] loi-jeanbrun-gouv
```

**Avantages :**
- Très répandu
- Bonne intégration multi-plateformes
- Historique des versions inclus

**Inconvénients :**
- Espace limité en version gratuite (2GB)
- Peut être lent avec de gros dépôts Git

### Google Drive

```bash
cd ~/Google\ Drive
git clone [...] loi-jeanbrun-gouv
```

**Avantages :**
- 15GB gratuits
- Intégration avec l'écosystème Google

**Inconvénients :**
- Synchronisation parfois moins fiable avec Git
- Peut créer des doublons

### OneDrive (Windows)

```bash
cd C:\Users\VotreNom\OneDrive
git clone [...] loi-jeanbrun-gouv
```

**Avantages :**
- Pré-installé sur Windows
- Bonne intégration Windows

**Inconvénients :**
- Parfois incompatible avec les liens symboliques Git
- Peut être lent

### iCloud Drive (Mac)

```bash
cd ~/Library/Mobile\ Documents/com~apple~CloudDocs
git clone [...] loi-jeanbrun-gouv
```

**Avantages :**
- Intégration native macOS/iOS
- 5GB gratuits

**Inconvénients :**
- Synchronisation pas toujours fiable avec Git
- Uniquement pour écosystème Apple

### Syncthing (Auto-hébergé)

Alternative open-source et gratuite pour synchroniser entre vos propres appareils.

**Avantages :**
- Gratuit et open-source
- Synchronisation P2P (pas de cloud)
- Respect de la vie privée

**Inconvénients :**
- Configuration plus technique
- Nécessite que les appareils soient allumés pour synchroniser

---

## Configuration recommandée

### Pour usage personnel (1-2 ordinateurs)

**Option 1 : kDrive ou Dropbox**
- Simple à configurer
- Synchronisation fiable
- Sauvegarde cloud incluse

### Pour équipe (3+ personnes)

**Option 1 : Git uniquement (sans cloud sync)**
- Chacun clone le dépôt localement
- Tout passe par Git
- Plus professionnel

**Option 2 : Cloud + Git**
- Chaque personne a son propre cloud
- Git pour la publication finale

---

## Sécurité

### Fichiers sensibles

⚠️ **Attention :** Si vous stockez des identifiants ou fichiers sensibles :

1. **Ne les committez JAMAIS dans Git**
2. Ajoutez-les au `.gitignore`
3. Utilisez des variables d'environnement
4. Chiffrez les fichiers dans kDrive si nécessaire

### Sauvegarde

Même avec kDrive :
- ✅ Git est votre sauvegarde principale (dépôt distant)
- ✅ kDrive est une synchronisation pratique
- ✅ Faites des backups réguliers du dépôt Git

---

## Checklist de configuration

Cochez au fur et à mesure :

- [ ] kDrive installé sur ordinateur principal
- [ ] Compte kDrive configuré
- [ ] Dépôt Git cloné dans kDrive
- [ ] `.gitignore` configuré pour exclure fichiers cloud
- [ ] PHP installé et testé
- [ ] Interface d'administration testée
- [ ] Premier article créé et synchronisé
- [ ] kDrive installé sur ordinateur secondaire
- [ ] Dépôt synchronisé sur ordinateur secondaire
- [ ] Git configuré sur ordinateur secondaire
- [ ] Test de publication depuis ordinateur secondaire
- [ ] Synchronisation vérifiée entre les deux ordinateurs

---

**Dernière mise à jour :** 30 janvier 2026
