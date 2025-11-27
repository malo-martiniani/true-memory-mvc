# Memory Game - PHP MVC

Un jeu de mémoire complet implémenté en PHP suivant le pattern MVC.

## Caractéristiques

### Jeu de Mémoire
- 🎮 Jeu de cartes avec recherche de paires
- 🎯 Difficulté ajustable de 3 à 12 paires
- 📊 Système de scores avec classement
- 👤 Authentification simple par nom d'utilisateur
- ⏱️ Suivi du temps et des coups

### Technique
- ✅ **100% PHP** - Aucun JavaScript
- ✅ **Architecture MVC** - Séparation claire des responsabilités
- ✅ **POO** - Programmation orientée objet
- ✅ **PDO** - Connexion sécurisée à la base de données
- ✅ **Sessions** - Gestion de l'authentification

## Structure du Projet

```
├── app/
│   ├── Controllers/
│   │   ├── AuthController.php      # Gestion authentification
│   │   ├── GameController.php      # Logique du jeu
│   │   ├── HomeController.php      # Page d'accueil
│   │   └── ArticleController.php   # Exemple articles
│   ├── Models/
│   │   ├── UserModel.php           # Gestion utilisateurs
│   │   ├── ScoreModel.php          # Gestion scores
│   │   ├── GameModel.php           # Logique du jeu
│   │   └── ArticleModel.php        # Exemple articles
│   └── Views/
│       ├── auth/
│       │   └── login.php           # Page de connexion
│       ├── game/
│       │   ├── difficulty.php      # Sélection difficulté
│       │   ├── play.php            # Plateau de jeu
│       │   ├── victory.php         # Page de victoire
│       │   └── leaderboard.php     # Tableau des scores
│       ├── home/
│       └── layouts/
│           └── base.php            # Layout principal
├── core/
│   ├── Database.php                # Singleton PDO
│   ├── Router.php                  # Gestionnaire de routes
│   └── BaseController.php          # Contrôleur de base
├── public/
│   ├── index.php                   # Point d'entrée
│   ├── .htaccess                   # Réécriture d'URL
│   └── assets/
│       └── img/                    # Images des cartes
└── mini-mvc.sql                    # Schéma de base de données
```

## Installation

### 1. Cloner le projet

```bash
git clone [url-du-repo]
cd true-memory-mvc
```

### 2. Installer les dépendances

```bash
composer install
```

### 3. Configurer la base de données

```bash
# Créer la base de données
mysql -u root -p < mini-mvc.sql

# Ou avec sudo si nécessaire
sudo mysql < mini-mvc.sql
```

### 4. Configuration de l'environnement

Copier `.env.example` vers `.env` et configurer :

```env
DB_HOST=localhost
DB_USER=root
DB_PASSWORD=
DB_NAME=memory_db
DB_PORT=3306
```

### 5. Ajouter les images

Placer vos images de cartes dans `public/assets/img/` :
- `card-1.jpg` à `card-12.jpg`
- Format recommandé : Carrées, 200x200px minimum
- Si les images manquent, des numéros s'afficheront

## Utilisation

### Démarrage du serveur

```bash
cd public
php -S localhost:8000
```

Accédez à : http://localhost:8000

### Workflow du jeu

1. **Connexion** (`/login`)
   - Entrez un nom d'utilisateur (3-50 caractères)
   - Le compte est créé automatiquement si inexistant

2. **Sélection difficulté** (`/game/difficulty`)
   - Choisissez entre 3 et 12 paires
   - Plus de paires = plus difficile

3. **Jeu** (`/game/play`)
   - Cliquez sur une carte pour la retourner
   - Trouvez la paire correspondante
   - Continuez jusqu'à trouver toutes les paires

4. **Victoire** (`/game/victory`)
   - Affichage de vos statistiques
   - Score automatiquement enregistré

5. **Classement** (`/game/leaderboard`)
   - Consultez les meilleurs scores
   - Filtrez par difficulté

## Schéma de Base de Données

### Table `users`
- `id` : INT AUTO_INCREMENT PRIMARY KEY
- `username` : VARCHAR(50) UNIQUE
- `created_at` : TIMESTAMP

### Table `scores`
- `id` : INT AUTO_INCREMENT PRIMARY KEY
- `user_id` : INT (FK vers users)
- `difficulty` : INT (nombre de paires)
- `moves` : INT (nombre de coups)
- `time_seconds` : INT (temps en secondes)
- `created_at` : TIMESTAMP

### Table `articles` (exemple)
- `id` : INT AUTO_INCREMENT PRIMARY KEY
- `title` : VARCHAR(150)
- `body` : TEXT

## Architecture MVC

### Modèles (Models)
Gèrent l'accès aux données via PDO :
- Requêtes préparées pour la sécurité
- Méthodes CRUD clairement définies
- Pas de logique métier complexe

### Vues (Views)
Affichent les données :
- PHP pur, pas de JavaScript
- Échappement HTML systématique
- Formulaires pour toutes les interactions

### Contrôleurs (Controllers)
Coordonnent modèles et vues :
- Gestion des requêtes HTTP
- Validation des données
- Gestion des sessions
- Redirections appropriées

## Fonctionnalités Techniques

### Authentification
- Session PHP pour maintenir la connexion
- Vérification `requireAuth()` dans GameController
- Création automatique de compte

### Jeu de Mémoire
- État du jeu stocké en session
- Logique dans GameModel (OOP)
- Cartes mélangées aléatoirement
- Suivi des paires trouvées

### Scores
- Enregistrement automatique à la victoire
- Classement par coups puis par temps
- Filtrage par difficulté

## Routes Disponibles

### Routes GET
- `/` - Page d'accueil
- `/login` - Page de connexion
- `/logout` - Déconnexion
- `/game/difficulty` - Sélection difficulté
- `/game/play` - Plateau de jeu
- `/game/victory` - Page de victoire
- `/game/leaderboard` - Classement
- `/articles` - Liste articles (exemple)
- `/about` - À propos

### Routes POST
- `/login` - Traitement connexion
- `/game/start` - Démarrer partie
- `/game/flip` - Retourner carte
- `/game/continue` - Continuer après échec

## Tests

### Test manuel

1. Démarrer le serveur : `php -S localhost:8000 -t public`
2. Ouvrir http://localhost:8000
3. Se connecter avec un nom d'utilisateur
4. Jouer une partie
5. Vérifier le classement

### Test de la base de données

```bash
php test_database.php
```

## Sécurité

- ✅ Requêtes préparées PDO (protection injection SQL)
- ✅ Échappement HTML (`htmlspecialchars`)
- ✅ Validation des entrées utilisateur
- ✅ Sessions sécurisées
- ✅ Protection CSRF via méthodes HTTP appropriées

## Développement

### Ajouter une nouvelle fonctionnalité

1. Créer le modèle dans `app/Models/`
2. Créer le contrôleur dans `app/Controllers/`
3. Créer les vues dans `app/Views/`
4. Ajouter les routes dans `public/index.php`

### Style du code

- PSR-4 pour l'autoloading
- Commentaires en français
- Nommage explicite des variables
- Documentation PHPDoc

## Améliorations Possibles

- [ ] Ajouter des animations CSS
- [ ] Implémenter un système de niveaux
- [ ] Ajouter des thèmes de cartes
- [ ] Mode multijoueur
- [ ] Statistiques avancées par joueur
- [ ] Export des scores en CSV
- [ ] API REST pour les scores

## Auteur

Malo Martiniani

## Licence

Projet éducatif - MVC PHP
