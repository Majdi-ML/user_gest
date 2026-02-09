# SyndicDesktop - Application de Gestion Syndicale

## 📝 Vue d'ensemble

Application Symfony 5.4 complète pour la gestion d'un syndicat de copropriété, incluant la gestion financière, des résidents, des employés, et de la documentation administrative.

## 🆕 Nouvelles Fonctionnalités (Février 2026)

### 1. 💰 Module Recette

**Description:** Nouveau système de gestion des recettes du syndicat, remplaçant l'ancien calcul basé sur les frais syndic.

**Caractéristiques:**
- **Type de recette:**
  - Cotisation Syndic
  - Autre revenu
  
- **Nature de recette:**
  - Espèce
  - Bancaire

- **Fonctionnalités:**
  - Création, modification, suppression de recettes
  - Upload de fichiers justificatifs (PDF/Images)
  - Organisation par année avec onglets
  - Tableaux récapitulatifs par mois/type/nature
  - Export PDF et Excel
  - Thème violet moderne

**Accès:** Menu latéral > Gérer vos > Recette

**Fichiers créés:**
```
src/Entity/Recette.php
src/Form/RecetteType.php
src/Controller/RecetteController.php
src/Repository/RecetteRepository.php
templates/recette/
  ├── index.html.twig
  ├── new.html.twig
  ├── edit.html.twig
  ├── show.html.twig
  ├── _form.html.twig
  └── _delete_form.html.twig
```

---

### 2. 📊 Dashboard Financier Amélioré

**Description:** Refonte complète du dashboard pour afficher les recettes et dépenses par nature (Espèce/Bancaire).

**Nouvelles colonnes:**
- Mois
- Recette Espèce
- Recette Banque
- Total Recette
- Dépense Espèce
- Dépense Banque
- Total Dépense
- Solde Caisse

**Calcul:**
- Les recettes proviennent de l'entité **Recette** (non plus de Frais Syndic)
- Les dépenses sont filtrées par nature (Espèce/Bancaire)
- Solde calculé automatiquement mois par mois

**Fichiers modifiés:**
```
src/Controller/DashboardController.php
templates/dashboard/index.html.twig
```

---

### 3. 💳 Nature de Paiement/Dépense

**Description:** Simplification du système de nature de paiement avec suppression de l'entité NaturePaiement.

**Changements:**

#### Dépenses
- Nouveau champ `nature_depense` (string)
- Nouveau type de dépense: **FRAIS_BANQUE**
- Choix: Espèce / Bancaire
- Affiché dans la page de détails avec badge coloré

#### Cotisation Syndic (ex-Frais Syndic)
- Champ `nature_paiement` converti en string
- Choix: espece / bancaire
- Plus de relation avec l'entité NaturePaiement

**Fichiers supprimés:**
```
src/Entity/NaturePaiement.php
src/Repository/NaturePaiementRepository.php
src/Form/NaturePaiementType.php
src/Controller/NaturePaiementController.php
templates/nature_paiement/
```

**Fichiers modifiés:**
```
src/Entity/FraisSyndicReglement.php
src/Entity/Cautionnement.php
src/Entity/Depense.php
src/Form/FraisSyndicReglementType.php
src/Form/CautionnementType.php
src/Form/DepenseType.php
```

---

### 4. 🔄 Renommage: Frais Syndic → Cotisation Syndic

**Description:** Clarification de la terminologie pour mieux refléter la fonction de suivi des paiements.

**Changements:**
- Menu latéral: "Cotisation Syndic" au lieu de "Frais Syndic"
- Tous les titres et labels mis à jour
- Fonction: **Suivi des paiements uniquement** (non utilisé pour le calcul des recettes)

**Fichiers modifiés:**
```
templates/base.html.twig
templates/frais_syndic_reglement/*.html.twig
```

---

### 5. ⚙️ Page Configuration Centralisée

**Description:** Interface unique pour gérer tous les paramètres et types de données du système.

**Modules configurables:**
- **Type Papier** - Types de documents administratifs
- **Type Rassemblement** - Types d'assemblées/réunions
- **Status Bureau** - Statuts des membres du bureau
- **Fonction Bureau** - Fonctions au sein du bureau
- **Frais** - Montants des cotisations
- **Fonction Employé** - Fonctions des employés

**Fonctionnalités:**
- Interface par onglets
- Opérations CRUD en AJAX (sans rechargement)
- Ajout rapide via champ de saisie
- Modification en ligne avec prompt
- Suppression sécurisée avec confirmation
- Design moderne avec thème violet

**Accès:** Menu latéral > Configuration (en bas de la sidebar)

**Fichiers créés:**
```
src/Controller/ConfigurationController.php
templates/configuration/index.html.twig
```

**Fichiers supprimés:** (anciens contrôleurs et templates individuels)
```
src/Controller/TypeRassmblementController.php
src/Controller/TypePapierController.php
src/Controller/StatusBureauController.php
src/Controller/FonctionBureauController.php
src/Controller/FraisSyndicController.php
src/Controller/FonctionEmployeController.php
src/Form/TypeRassmblementType.php
src/Form/TypePapierType.php
src/Form/StatusBureauType.php
src/Form/FonctionBureauType.php
src/Form/FraisSyndicType.php
src/Form/FonctionEmployeType.php
templates/type_rassmblement/
templates/type_papier/
templates/status_bureau/
templates/fonction_bureau/
templates/frais_syndic/
templates/fonction_employe/
```

---

### 6. 🎨 Modernisation des Templates

**Description:** Refonte visuelle complète avec thème violet moderne.

**Améliorations:**
- Couleur principale: `#7c3aed` (violet)
- Dégradés modernes
- Animations fluides (fade-in, hover effects)
- Layout info-grid pour affichage pleine largeur
- Badges colorés pour les statuts
- Cards avec ombres et bordures arrondies
- Icônes Bootstrap Icons
- Design responsive

**Templates modernisés:**
```
templates/recette/*.html.twig
templates/depense/show.html.twig
templates/configuration/index.html.twig
templates/dashboard/index.html.twig
```

---

## 🗄️ Structure de la Base de Données

### Nouvelles Tables

#### Table: `recette`
```sql
id                  INTEGER PRIMARY KEY
user_id             INTEGER (FK → user)
montant             DOUBLE PRECISION
date_recette        DATE
description         VARCHAR(255)
type_recette        VARCHAR(255)  -- 'Cotisation Syndic' | 'Autre revenu'
nature_recette      VARCHAR(255)  -- 'Espece' | 'Bancaire'
attached_file       VARCHAR(255)
```

### Tables Modifiées

#### Table: `depense`
```sql
-- Ajout de:
nature_depense      VARCHAR(255) DEFAULT 'Espece'  -- 'Espece' | 'Bancaire'
```

#### Table: `frais_syndic_reglement`
```sql
-- Modifié de FK vers string:
nature_paiement     VARCHAR(255)  -- 'espece' | 'bancaire'
```

#### Table: `cautionnement`
```sql
-- Modifié de FK vers string:
Nature_Paiement     VARCHAR(255)  -- 'espece' | 'bancaire'
```

### Tables Supprimées
```sql
-- Supprimée:
nature_paiement
```

---

## 🚀 Installation et Migration

### 1. Supprimer l'ancienne base de données
```bash
rm var/data.db
```

### 2. Générer et appliquer les migrations
```bash
php bin/console make:migration
php bin/console doctrine:migrations:migrate
```

### 3. Vider le cache
```bash
php bin/console cache:clear
```

---

## 📂 Configuration VichUploader

Nouveau mapping pour les fichiers de recettes:

```yaml
# config/packages/vich_uploader.yaml
vich_uploader:
    mappings:
        recette_files:
            uri_prefix: /uploads/recettes
            upload_destination: '%kernel.project_dir%/public/uploads/recettes'
            namer: Vich\UploaderBundle\Naming\OrignameNamer
            delete_on_remove: true
            delete_on_update: true
            inject_on_load: false
```

---

## 🎯 Flux de Travail Financier

### Ancien système:
```
Frais Syndic → Calcul recettes → Dashboard
```

### Nouveau système:
```
Recette (saisie directe) → Dashboard
Cotisation Syndic (suivi paiements uniquement)
Dépense (avec nature) → Dashboard
```

### Avantages:
- ✅ Saisie directe des recettes avec justificatifs
- ✅ Distinction Espèce/Bancaire
- ✅ Meilleure traçabilité
- ✅ Dashboard plus précis
- ✅ Cotisation Syndic = suivi des impayés

---

## 🔐 Sécurité

- Authentification requise pour toutes les pages
- Protection CSRF sur tous les formulaires
- Validation côté serveur et client
- Relations OneToMany avec User pour traçabilité
- Suppression sécurisée avec confirmation

---

## 🎨 Design System

### Couleurs
```css
Primary: #7c3aed (violet)
Dark: #6d28d9
Light: #a78bfa
Warning: #f59e0b
Danger: #ef4444
Success: #10b981
```

### Typography
- Titres: Source Sans Pro, sans-serif
- Corps: Open Sans, sans-serif

### Composants réutilisables
```
templates/_modern_show_styles.html.twig
templates/_modern_form_styles.html.twig
templates/_modern_index_styles.html.twig
```

---

## 📱 Routes Principales

### Recette
```
GET  /recette              → Liste des recettes
GET  /recette/new          → Formulaire de création
POST /recette/new          → Enregistrement
GET  /recette/{id}         → Détails
GET  /recette/{id}/edit    → Formulaire d'édition
POST /recette/{id}/edit    → Mise à jour
POST /recette/{id}         → Suppression
```

### Configuration
```
GET  /configuration                           → Page principale
POST /configuration/type-papier/add          → Ajouter type papier
POST /configuration/type-papier/{id}/edit    → Modifier type papier
POST /configuration/type-papier/{id}/delete  → Supprimer type papier
... (similaire pour tous les types)
```

### Dashboard
```
GET  /dashboard              → Tableau financier
GET  /dashboard/caisse/edit/{id}    → Modifier caisse
POST /dashboard/caisse/delete/{id}  → Supprimer caisse
```

---

## 🛠️ Technologies Utilisées

- **Backend:** Symfony 5.4, PHP 8.1+
- **Base de données:** SQLite (fichier data.db)
- **ORM:** Doctrine
- **Templating:** Twig
- **Frontend:** Bootstrap 5, Bootstrap Icons
- **JavaScript:** Vanilla JS, jQuery (AJAX)
- **Upload:** VichUploaderBundle
- **Export:** jsPDF, xlsx.js

---

## 📊 Statistiques du Projet

### Nouvelles entités: 1
- Recette

### Entités modifiées: 3
- Depense
- FraisSyndicReglement
- Cautionnement

### Entités supprimées: 1
- NaturePaiement

### Nouveaux contrôleurs: 2
- RecetteController
- ConfigurationController

### Contrôleurs supprimés: 6
- TypeRassmblementController
- TypePapierController
- StatusBureauController
- FonctionBureauController
- FraisSyndicController
- FonctionEmployeController

---

## 🐛 Résolution de Problèmes

### Erreur de migration: "Cannot add a NOT NULL column"
**Solution:** Ajouter DEFAULT dans la migration
```php
$this->addSql('ALTER TABLE depense ADD COLUMN nature_depense VARCHAR(255) NOT NULL DEFAULT \'Espece\'');
```

### Erreur SQLite: "does not support alter foreign key"
**Solution:** Supprimer data.db et recréer la base complète
```bash
rm var/data.db
php bin/console doctrine:migrations:migrate
```

---

## 📞 Support

Pour toute question ou problème:
1. Vérifier que toutes les migrations sont appliquées
2. Vider le cache: `php bin/console cache:clear`
3. Vérifier les permissions sur `/public/uploads/recettes`

---

## 📝 Changelog

### Version 2.0 - Février 2026

**Ajouté:**
- Module Recette complet
- Page Configuration centralisée
- Nature Espèce/Bancaire pour dépenses et recettes
- Type FRAIS_BANQUE pour dépenses
- Dashboard avec colonnes séparées Espèce/Bancaire

**Modifié:**
- "Frais Syndic" renommé en "Cotisation Syndic"
- Dashboard utilise Recette au lieu de FraisSyndicReglement
- Nature de paiement convertie en champ string
- Templates modernisés avec thème violet

**Supprimé:**
- Entité NaturePaiement
- 6 contrôleurs de configuration individuels
- Liens navbar pour types et fonctions
- 6 dossiers de templates obsolètes

**Corrigé:**
- Erreurs de migration NOT NULL
- Problèmes d'affichage des natures de paiement
- Calcul des recettes dans le dashboard

---

## 👥 Contributeurs

Développé et maintenu par l'équipe SyndicDesktop.

---

## � Déploiement en Production avec PHP Desktop

### 🗑️ Fichiers à Supprimer

Avant de déployer en production, supprimer les fichiers de développement:

```bash
# Fichiers de développement
Remove-Item .env.test -Force -ErrorAction SilentlyContinue
Remove-Item phpunit.xml.dist -Force -ErrorAction SilentlyContinue
Remove-Item .gitignore -Force -ErrorAction SilentlyContinue

# Dossiers de développement et tests
Remove-Item -Recurse -Force tests/
Remove-Item -Recurse -Force var/cache/* -ErrorAction SilentlyContinue
Remove-Item -Recurse -Force var/log/* -ErrorAction SilentlyContinue

# Fichiers temporaires
Remove-Item -Recurse -Force .git/ -ErrorAction SilentlyContinue
Remove-Item docker-compose.yml -Force -ErrorAction SilentlyContinue
Remove-Item docker-compose.override.yml -Force -ErrorAction SilentlyContinue
```

### ✅ Fichiers à Garder

**Dossiers essentiels:**
```
bin/                    # Scripts console Symfony
config/                 # Configuration de l'application
migrations/             # Migrations de base de données
public/                 # Point d'entrée et assets
  ├── index.php         # REQUIS
  ├── assetsback/       # CSS, JS, images
  └── uploads/          # Fichiers uploadés
src/                    # Code source application
  ├── Controller/
  ├── Entity/
  ├── Form/
  ├── Repository/
  └── Kernel.php
templates/              # Templates Twig
translations/           # Fichiers de langue
var/                    # Cache et logs (vide)
  ├── cache/
  └── log/
vendor/                 # Dépendances Composer
composer.json           # Configuration Composer
composer.lock           # Versions des dépendances
.env                    # Variables d'environnement
```

**Fichiers critiques:**
```
.env                    # IMPORTANT: Configuration prod
var/data.db             # IMPORTANT: Base de données SQLite
composer.json
composer.lock
```

### 🚀 Commandes de Préparation pour Production

#### 1. Configurer l'environnement de production

Éditer le fichier `.env`:
```bash
# Changer en production
APP_ENV=prod
APP_DEBUG=0

# Configuration base de données (SQLite)
DATABASE_URL="sqlite:///%kernel.project_dir%/var/data.db"

# Générer une nouvelle clé secrète
APP_SECRET=VOTRE_CLE_SECRETE_UNIQUE_ICI
```

#### 2. Installer les dépendances en mode production
```bash
composer install --no-dev --optimize-autoloader
```

#### 3. Vider et optimiser le cache
```bash
php bin/console cache:clear --env=prod --no-debug
php bin/console cache:warmup --env=prod --no-debug
```

#### 4. Appliquer les migrations
```bash
php bin/console doctrine:migrations:migrate --no-interaction
```

#### 5. Créer un utilisateur administrateur (si nécessaire)
```bash
php bin/console make:user
# Suivre les instructions pour créer un compte admin
```

#### 6. Définir les permissions correctes
```bash
# Donner les droits d'écriture pour uploads et cache
icacls "var" /grant Everyone:F /t
icacls "public\uploads" /grant Everyone:F /t
```

### 🖥️ Configuration PHP Desktop

#### 1. Télécharger PHP Desktop
- Télécharger depuis: https://github.com/cztomczak/phpdesktop/releases
- Version recommandée: **PHP Desktop Chrome** (dernière version)

#### 2. Structure du projet PHP Desktop
```
phpdesktop-chrome/
├── phpdesktop-chrome.exe
├── settings.json              # Configuration à modifier
├── www/                       # COPIER VOTRE APPLICATION ICI
│   ├── bin/
│   ├── config/
│   ├── migrations/
│   ├── public/
│   │   └── index.php
│   ├── src/
│   ├── templates/
│   ├── translations/
│   ├── var/
│   │   └── data.db
│   ├── vendor/
│   ├── .env
│   ├── composer.json
│   └── composer.lock
```

#### 3. Configurer settings.json

Fichier `phpdesktop-chrome/settings.json`:
```json
{
  "application": {
    "name": "SyndicDesktop",
    "default_locale": "fr-FR"
  },
  "main_window": {
    "title": "Gestion Syndicale",
    "icon": "www/public/assetsback/img/logo.png",
    "default_size": [1280, 800],
    "minimum_size": [1024, 600],
    "maximum_size": [0, 0],
    "disable_maximize_button": false,
    "center_on_screen": true,
    "start_maximized": true,
    "start_fullscreen": false
  },
  "web_server": {
    "listen_on": ["127.0.0.1", 54007],
    "www_directory": "www/public",
    "index_files": ["index.php"],
    "cgi_interpreter": "php/php-cgi.exe",
    "cgi_extensions": ["php"],
    "cgi_temp_dir": ""
  },
  "chrome": {
    "log_file": "debug.log",
    "log_severity": "info",
    "cache_path": "webcache/",
    "external_navigation": true,
    "devtools": false,
    "remote_debugging_port": 0,
    "context_menu": {
      "enable_menu": false,
      "devtools": false
    }
  }
}
```

#### 4. Configuration Apache/Nginx (si nécessaire)

**Option A: Utiliser le serveur intégré PHP Desktop** (recommandé)
- Configuration automatique via `settings.json`
- Aucune configuration supplémentaire requise

**Option B: Serveur externe (Apache/Nginx)**

Fichier `.htaccess` dans `public/`:
```apache
DirectoryIndex index.php

<IfModule mod_negotiation.c>
    Options -MultiViews
</IfModule>

<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_URI}::$0 ^(/.+)/(.*)::\2$
    RewriteRule .* - [E=BASE:%1]
    RewriteCond %{HTTP:Authorization} .+
    RewriteRule ^ - [E=HTTP_AUTHORIZATION:%0]
    RewriteCond %{ENV:REDIRECT_STATUS} =""
    RewriteRule ^index\.php(?:/(.*)|$) %{ENV:BASE}/$1 [R=301,L]
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ %{ENV:BASE}/index.php [L]
</IfModule>
```

### 📋 Checklist de Déploiement

**Avant de copier dans PHP Desktop:**
- [ ] `.env` configuré en `APP_ENV=prod` et `APP_DEBUG=0`
- [ ] `composer install --no-dev --optimize-autoloader` exécuté
- [ ] Cache vidé et réchauffé en mode prod
- [ ] Migrations appliquées
- [ ] `var/data.db` présent avec les données
- [ ] Dossier `tests/` supprimé
- [ ] Fichiers Docker supprimés
- [ ] Permissions sur `var/` et `public/uploads/`

**Configuration PHP Desktop:**
- [ ] PHP Desktop téléchargé et extrait
- [ ] `settings.json` configuré
- [ ] Application copiée dans `phpdesktop-chrome/www/`
- [ ] Icône de l'application ajoutée
- [ ] Test de l'application via `phpdesktop-chrome.exe`

**Tests de production:**
- [ ] Application démarre sans erreur
- [ ] Login fonctionne
- [ ] Dashboard affiche les données
- [ ] Upload de fichiers fonctionne
- [ ] Toutes les routes sont accessibles
- [ ] Configuration accessible
- [ ] Recettes et Dépenses fonctionnelles

### 🔒 Sécurité en Production

**Important:**
1. **Ne jamais** laisser `APP_DEBUG=1` en production
2. **Changer** `APP_SECRET` avec une valeur unique
3. **Sauvegarder** régulièrement `var/data.db`
4. **Protéger** l'accès au fichier `phpdesktop-chrome.exe`
5. **Vérifier** les permissions sur les dossiers uploads

### 📦 Créer un Installateur

Pour distribuer l'application, utiliser **Inno Setup**:

```iss
[Setup]
AppName=SyndicDesktop
AppVersion=2.0
DefaultDirName={pf}\SyndicDesktop
DefaultGroupName=SyndicDesktop
OutputBaseFilename=SyndicDesktop-Setup
Compression=lzma2
SolidCompression=yes

[Files]
Source: "phpdesktop-chrome\*"; DestDir: "{app}"; Flags: recursesubdirs

[Icons]
Name: "{group}\SyndicDesktop"; Filename: "{app}\phpdesktop-chrome.exe"
Name: "{commondesktop}\SyndicDesktop"; Filename: "{app}\phpdesktop-chrome.exe"

[Run]
Filename: "{app}\phpdesktop-chrome.exe"; Description: "Lancer SyndicDesktop"; Flags: postinstall nowait
```

### 💾 Sauvegarde Automatique

Script PowerShell pour sauvegarder la base de données:

```powershell
# backup-database.ps1
$date = Get-Date -Format "yyyy-MM-dd_HHmmss"
$source = "phpdesktop-chrome\www\var\data.db"
$destination = "backups\data_$date.db"

New-Item -ItemType Directory -Force -Path "backups"
Copy-Item $source $destination
Write-Host "Sauvegarde créée: $destination"

# Garder seulement les 30 dernières sauvegardes
Get-ChildItem "backups\*.db" | 
    Sort-Object CreationTime -Descending | 
    Select-Object -Skip 30 | 
    Remove-Item
```

Ajouter au planificateur de tâches Windows pour exécution automatique quotidienne.

---

## �📄 Licence

Tous droits réservés © 2026
