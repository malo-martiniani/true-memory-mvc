<?php

// Démarrage de la session pour gérer l'authentification
session_start();

// Chargement manuel des fichiers nécessaires au fonctionnement de l'application

require_once __DIR__ . '/../vendor/autoload.php'; // Chargement automatique des classes via Composer
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->safeLoad();

// Importation des classes avec namespaces pour éviter les conflits de noms
use Core\Router;
use App\Controllers\HomeController;
use App\Controllers\ArticleController;
use App\Controllers\AuthController;
use App\Controllers\GameController;

// Initialisation du routeur
$router = new Router();

// Définition des routes de l'application
// La route "/" pointe vers la méthode "index" du contrôleur HomeController
$router->get('/', 'App\\Controllers\\HomeController@index');

$router->get('/about', 'App\\Controllers\\HomeController@about');

// La route "/articles" pointe vers la méthode "index" du contrôleur ArticleController
$router->get('/articles', 'App\\Controllers\\ArticleController@index');

// Routes d'authentification
$router->get('/login', 'App\\Controllers\\AuthController@login');

// Routes du jeu de mémoire
$router->get('/game/difficulty', 'App\\Controllers\\GameController@difficulty');
$router->get('/game/play', 'App\\Controllers\\GameController@play');
$router->get('/game/victory', 'App\\Controllers\\GameController@victory');
$router->get('/game/leaderboard', 'App\\Controllers\\GameController@leaderboard');
$router->get('/game/continue', 'App\\Controllers\\GameController@continueGame');
$router->get('/logout', 'App\\Controllers\\AuthController@logout');

// Routes POST
$router->post('/login', 'App\\Controllers\\AuthController@doLogin');
$router->post('/game/start', 'App\\Controllers\\GameController@start');
$router->post('/game/flip', 'App\\Controllers\\GameController@flip');
$router->post('/game/continue', 'App\\Controllers\\GameController@continueGame');

// Exécution du routeur :
// On analyse l'URI et la méthode HTTP pour appeler le contrôleur et la méthode correspondants
$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
