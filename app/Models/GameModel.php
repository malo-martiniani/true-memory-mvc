<?php
namespace App\Models;

/**
 * Classe GameModel
 * -----------------
 * Gère la logique du jeu de mémoire.
 * Génère les cartes, gère les paires, et vérifie les conditions de victoire.
 */
class GameModel
{
    private int $difficulty;
    private array $cards = [];
    private array $flippedCards = [];
    private array $matchedPairs = [];
    private int $moves = 0;
    private int $startTime;

    /**
     * Constructeur
     *
     * @param int $difficulty Nombre de paires (entre 3 et 12)
     */
    public function __construct(int $difficulty = 6)
    {
        $this->difficulty = max(3, min(12, $difficulty));
        $this->startTime = time();
    }

    /**
     * Initialise les cartes pour le jeu
     *
     * @return array Tableau de cartes mélangées
     */
    public function initializeCards(): array
    {
        $this->cards = [];
        
        // Génère les paires de cartes avec des IDs uniques
        for ($i = 1; $i <= $this->difficulty; $i++) {
            // Chaque paire a le même image_id mais des positions différentes
            $this->cards[] = [
                'id' => $i * 2 - 1,
                'image_id' => $i,
                'image_path' => "/assets/img/card-{$i}.jpg",
                'is_flipped' => false,
                'is_matched' => false
            ];
            $this->cards[] = [
                'id' => $i * 2,
                'image_id' => $i,
                'image_path' => "/assets/img/card-{$i}.jpg",
                'is_flipped' => false,
                'is_matched' => false
            ];
        }
        
        // Mélange les cartes
        shuffle($this->cards);
        
        return $this->cards;
    }

    /**
     * Retourne les cartes du jeu
     *
     * @return array
     */
    public function getCards(): array
    {
        return $this->cards;
    }

    /**
     * Définit les cartes du jeu
     *
     * @param array $cards
     */
    public function setCards(array $cards): void
    {
        $this->cards = $cards;
    }

    /**
     * Retourne les cartes retournées
     *
     * @return array
     */
    public function getFlippedCards(): array
    {
        return $this->flippedCards;
    }

    /**
     * Définit les cartes retournées
     *
     * @param array $flippedCards
     */
    public function setFlippedCards(array $flippedCards): void
    {
        $this->flippedCards = $flippedCards;
    }

    /**
     * Retourne les paires trouvées
     *
     * @return array
     */
    public function getMatchedPairs(): array
    {
        return $this->matchedPairs;
    }

    /**
     * Définit les paires trouvées
     *
     * @param array $matchedPairs
     */
    public function setMatchedPairs(array $matchedPairs): void
    {
        $this->matchedPairs = $matchedPairs;
    }

    /**
     * Retourne le nombre de coups
     *
     * @return int
     */
    public function getMoves(): int
    {
        return $this->moves;
    }

    /**
     * Définit le nombre de coups
     *
     * @param int $moves
     */
    public function setMoves(int $moves): void
    {
        $this->moves = $moves;
    }

    /**
     * Retourne le temps de départ
     *
     * @return int
     */
    public function getStartTime(): int
    {
        return $this->startTime;
    }

    /**
     * Définit le temps de départ
     *
     * @param int $startTime
     */
    public function setStartTime(int $startTime): void
    {
        $this->startTime = $startTime;
    }

    /**
     * Retourne la difficulté
     *
     * @return int
     */
    public function getDifficulty(): int
    {
        return $this->difficulty;
    }

    /**
     * Traite le clic sur une carte
     *
     * @param int $cardId ID de la carte cliquée
     * @return array Résultat du clic avec statut et informations
     */
    public function flipCard(int $cardId): array
    {
        // Trouve la carte dans le tableau
        $cardIndex = null;
        foreach ($this->cards as $index => $card) {
            if ($card['id'] === $cardId) {
                $cardIndex = $index;
                break;
            }
        }

        if ($cardIndex === null) {
            return ['status' => 'error', 'message' => 'Carte non trouvée'];
        }

        // Vérifie si la carte est déjà retournée ou matchée
        if ($this->cards[$cardIndex]['is_flipped'] || $this->cards[$cardIndex]['is_matched']) {
            return ['status' => 'error', 'message' => 'Carte déjà retournée'];
        }

        // Vérifie si on a déjà 2 cartes retournées
        if (count($this->flippedCards) >= 2) {
            return ['status' => 'error', 'message' => 'Deux cartes déjà retournées'];
        }

        // Retourne la carte
        $this->cards[$cardIndex]['is_flipped'] = true;
        $this->flippedCards[] = $cardId;

        // Si c'est la deuxième carte retournée
        if (count($this->flippedCards) === 2) {
            $this->moves++;
            return $this->checkMatch();
        }

        return ['status' => 'flipped', 'card_id' => $cardId];
    }

    /**
     * Vérifie si les deux cartes retournées forment une paire
     *
     * @return array Résultat de la vérification
     */
    private function checkMatch(): array
    {
        $card1Id = $this->flippedCards[0];
        $card2Id = $this->flippedCards[1];

        // Utilise un mapping pour une recherche O(1) au lieu de O(n)
        $cardMap = [];
        foreach ($this->cards as $key => &$card) {
            $cardMap[$card['id']] = &$card;
        }
        
        $card1 = &$cardMap[$card1Id];
        $card2 = &$cardMap[$card2Id];

        if ($card1['image_id'] === $card2['image_id']) {
            // C'est une paire !
            $card1['is_matched'] = true;
            $card2['is_matched'] = true;
            $this->matchedPairs[] = $card1['image_id'];
            
            // Réinitialise les cartes retournées après un match réussi
            $this->flippedCards = [];
            
            return [
                'status' => 'match',
                'card1_id' => $card1Id,
                'card2_id' => $card2Id,
                'is_game_won' => $this->isGameWon()
            ];
        } else {
            // Pas de paire
            return [
                'status' => 'no_match',
                'card1_id' => $card1Id,
                'card2_id' => $card2Id
            ];
        }
    }

    /**
     * Réinitialise les cartes retournées (après un échec de match)
     */
    public function resetFlippedCards(): void
    {
        foreach ($this->cards as &$card) {
            if ($card['is_flipped'] && !$card['is_matched']) {
                $card['is_flipped'] = false;
            }
        }
        $this->flippedCards = [];
    }

    /**
     * Vérifie si le jeu est gagné
     *
     * @return bool
     */
    public function isGameWon(): bool
    {
        return count($this->matchedPairs) === $this->difficulty;
    }

    /**
     * Calcule le temps écoulé depuis le début du jeu
     *
     * @return int Temps en secondes
     */
    public function getElapsedTime(): int
    {
        return time() - $this->startTime;
    }
}
