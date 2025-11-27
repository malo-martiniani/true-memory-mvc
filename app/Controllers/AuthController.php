<?php
namespace App\Controllers;

use App\Models\UserModel;
use Core\BaseController;

/**
 * Classe AuthController
 * ----------------------
 * Contrôleur responsable de l'authentification des utilisateurs.
 */
class AuthController extends BaseController
{
    /**
     * Affiche le formulaire de connexion
     *
     * @return void
     */
    public function login(): void
    {
        // Si l'utilisateur est déjà connecté, redirection vers la sélection de difficulté
        if (isset($_SESSION['user_id'])) {
            header('Location: /game/difficulty');
            exit;
        }

        $this->render('auth/login', [
            'title' => 'Connexion - Memory Game'
        ]);
    }

    /**
     * Traite la soumission du formulaire de connexion
     *
     * @return void
     */
    public function doLogin(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /login');
            exit;
        }

        $username = trim($_POST['username'] ?? '');

        if (empty($username)) {
            $_SESSION['error'] = 'Veuillez entrer un nom d\'utilisateur';
            header('Location: /login');
            exit;
        }

        // Validation du nom d'utilisateur (alphanumérique et tirets/underscores uniquement)
        if (!preg_match('/^[a-zA-Z0-9_-]{3,50}$/', $username)) {
            $_SESSION['error'] = 'Le nom d\'utilisateur doit contenir entre 3 et 50 caractères alphanumériques';
            header('Location: /login');
            exit;
        }

        $userModel = new UserModel();
        $user = $userModel->findByUsername($username);

        if (!$user) {
            // Créer un nouvel utilisateur
            $userId = $userModel->create($username);
            if (!$userId) {
                $_SESSION['error'] = 'Erreur lors de la création du compte';
                header('Location: /login');
                exit;
            }
            $_SESSION['user_id'] = $userId;
            $_SESSION['username'] = $username;
        } else {
            // Utilisateur existant
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
        }

        // Redirection vers la sélection de difficulté
        header('Location: /game/difficulty');
        exit;
    }

    /**
     * Déconnexion de l'utilisateur
     *
     * @return void
     */
    public function logout(): void
    {
        // Détruit toutes les variables de session
        $_SESSION = [];
        
        // Détruit la session
        session_destroy();
        
        // Démarre une nouvelle session pour les messages flash éventuels
        session_start();
        
        header('Location: /login');
        exit;
    }
}
