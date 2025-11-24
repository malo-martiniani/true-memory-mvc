<?php
namespace App\Controllers;

use App\Models\GameModel;
use App\Models\ScoreModel;
use Core\BaseController;

/**
 * Classe GameController
 * ----------------------
 * Contrôleur responsable de la gestion du jeu de mémoire.
 */
class GameController extends BaseController
{
    /**
     * Vérifie si l'utilisateur est connecté
     *
     * @return void
     */
    private function requireAuth(): void
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
    }

    /**
     * Affiche la page de sélection de difficulté
     *
     * @return void
     */
    public function difficulty(): void
    {
        $this->requireAuth();

        $this->render('game/difficulty', [
            'title' => 'Choisir la difficulté - Memory Game'
        ]);
    }

    /**
     * Démarre une nouvelle partie
     *
     * @return void
     */
    public function start(): void
    {
        $this->requireAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /game/difficulty');
            exit;
        }

        $difficulty = (int)($_POST['difficulty'] ?? 6);
        $difficulty = max(3, min(12, $difficulty));

        // Initialise le jeu en session
        $game = new GameModel($difficulty);
        $cards = $game->initializeCards();

        $_SESSION['game'] = [
            'difficulty' => $difficulty,
            'cards' => $cards,
            'flipped_cards' => [],
            'matched_pairs' => [],
            'moves' => 0,
            'start_time' => time()
        ];

        header('Location: /game/play');
        exit;
    }

    /**
     * Affiche le plateau de jeu
     *
     * @return void
     */
    public function play(): void
    {
        $this->requireAuth();

        if (!isset($_SESSION['game'])) {
            header('Location: /game/difficulty');
            exit;
        }

        $game = $_SESSION['game'];

        $this->render('game/play', [
            'title' => 'Memory Game - Difficulté: ' . $game['difficulty'] . ' paires',
            'game' => $game
        ]);
    }

    /**
     * Traite le clic sur une carte
     *
     * @return void
     */
    public function flip(): void
    {
        $this->requireAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /game/play');
            exit;
        }

        if (!isset($_SESSION['game'])) {
            header('Location: /game/difficulty');
            exit;
        }

        $cardId = (int)($_POST['card_id'] ?? 0);

        // Reconstruit le modèle de jeu depuis la session
        $game = new GameModel($_SESSION['game']['difficulty']);
        $game->setCards($_SESSION['game']['cards']);
        $game->setFlippedCards($_SESSION['game']['flipped_cards']);
        $game->setMatchedPairs($_SESSION['game']['matched_pairs']);
        $game->setMoves($_SESSION['game']['moves']);
        $game->setStartTime($_SESSION['game']['start_time']);

        // Traite le clic
        $result = $game->flipCard($cardId);

        // Sauvegarde l'état du jeu
        $_SESSION['game']['cards'] = $game->getCards();
        $_SESSION['game']['flipped_cards'] = $game->getFlippedCards();
        $_SESSION['game']['matched_pairs'] = $game->getMatchedPairs();
        $_SESSION['game']['moves'] = $game->getMoves();

        // Vérifie si c'est une victoire
        if (isset($result['is_game_won']) && $result['is_game_won']) {
            $_SESSION['game_result'] = [
                'difficulty' => $game->getDifficulty(),
                'moves' => $game->getMoves(),
                'time' => $game->getElapsedTime()
            ];
            header('Location: /game/victory');
            exit;
        }

        // Si pas de match, redirige vers une page de transition
        if ($result['status'] === 'no_match') {
            $_SESSION['no_match'] = true;
            header('Location: /game/no-match');
            exit;
        }

        header('Location: /game/play');
        exit;
    }

    /**
     * Affiche une page de transition après un échec de match
     *
     * @return void
     */
    public function noMatch(): void
    {
        $this->requireAuth();

        if (!isset($_SESSION['no_match']) || !isset($_SESSION['game'])) {
            header('Location: /game/play');
            exit;
        }

        unset($_SESSION['no_match']);

        // Reconstruit le modèle de jeu
        $game = new GameModel($_SESSION['game']['difficulty']);
        $game->setCards($_SESSION['game']['cards']);
        $game->setFlippedCards($_SESSION['game']['flipped_cards']);
        $game->setMatchedPairs($_SESSION['game']['matched_pairs']);
        $game->setMoves($_SESSION['game']['moves']);
        $game->setStartTime($_SESSION['game']['start_time']);

        $this->render('game/no-match', [
            'title' => 'Pas de paire',
            'game' => $_SESSION['game']
        ]);
    }

    /**
     * Continue le jeu après un échec de match
     *
     * @return void
     */
    public function continueGame(): void
    {
        $this->requireAuth();

        if (!isset($_SESSION['game'])) {
            header('Location: /game/difficulty');
            exit;
        }

        // Reconstruit le modèle de jeu
        $game = new GameModel($_SESSION['game']['difficulty']);
        $game->setCards($_SESSION['game']['cards']);
        $game->setFlippedCards($_SESSION['game']['flipped_cards']);
        $game->setMatchedPairs($_SESSION['game']['matched_pairs']);
        $game->setMoves($_SESSION['game']['moves']);
        $game->setStartTime($_SESSION['game']['start_time']);

        // Réinitialise les cartes retournées
        $game->resetFlippedCards();

        // Sauvegarde l'état
        $_SESSION['game']['cards'] = $game->getCards();
        $_SESSION['game']['flipped_cards'] = $game->getFlippedCards();

        header('Location: /game/play');
        exit;
    }

    /**
     * Affiche la page de victoire et enregistre le score
     *
     * @return void
     */
    public function victory(): void
    {
        $this->requireAuth();

        if (!isset($_SESSION['game_result'])) {
            header('Location: /game/difficulty');
            exit;
        }

        $result = $_SESSION['game_result'];

        // Enregistre le score
        $scoreModel = new ScoreModel();
        $scoreModel->create(
            $_SESSION['user_id'],
            $result['difficulty'],
            $result['moves'],
            $result['time']
        );

        $this->render('game/victory', [
            'title' => 'Victoire !',
            'result' => $result
        ]);

        // Nettoie la session
        unset($_SESSION['game']);
        unset($_SESSION['game_result']);
    }

    /**
     * Affiche le tableau des scores
     *
     * @return void
     */
    public function leaderboard(): void
    {
        $scoreModel = new ScoreModel();
        
        $difficulty = isset($_GET['difficulty']) ? (int)$_GET['difficulty'] : null;
        
        if ($difficulty && $difficulty >= 3 && $difficulty <= 12) {
            $scores = $scoreModel->getTopScoresByDifficulty($difficulty, 20);
            $title = "Meilleurs scores - Difficulté: {$difficulty} paires";
        } else {
            $scores = $scoreModel->getAllTopScores(30);
            $title = "Meilleurs scores - Toutes difficultés";
        }

        $this->render('game/leaderboard', [
            'title' => $title,
            'scores' => $scores,
            'selected_difficulty' => $difficulty
        ]);
    }
}
