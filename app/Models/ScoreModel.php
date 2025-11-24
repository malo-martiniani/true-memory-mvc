<?php
namespace App\Models;

use Core\Database;

/**
 * Classe ScoreModel
 * ------------------
 * Gère l'accès aux données pour l'entité "Score".
 * Permet l'enregistrement et la récupération des scores de jeu.
 */
class ScoreModel
{
    /**
     * Enregistre un nouveau score
     *
     * @param int $userId ID de l'utilisateur
     * @param int $difficulty Niveau de difficulté (nombre de paires)
     * @param int $moves Nombre de coups joués
     * @param int $timeSeconds Temps en secondes
     * @return bool Retourne true en cas de succès
     */
    public function create(int $userId, int $difficulty, int $moves, int $timeSeconds): bool
    {
        $stmt = Database::getPdo()->prepare(
            'INSERT INTO scores (user_id, difficulty, moves, time_seconds) 
             VALUES (:user_id, :difficulty, :moves, :time_seconds)'
        );
        return $stmt->execute([
            'user_id' => $userId,
            'difficulty' => $difficulty,
            'moves' => $moves,
            'time_seconds' => $timeSeconds
        ]);
    }

    /**
     * Récupère les meilleurs scores par difficulté (triés par coups puis par temps)
     *
     * @param int $difficulty Niveau de difficulté
     * @param int $limit Nombre maximum de résultats
     * @return array Liste des meilleurs scores
     */
    public function getTopScoresByDifficulty(int $difficulty, int $limit = 10): array
    {
        $stmt = Database::getPdo()->prepare(
            'SELECT s.*, u.username 
             FROM scores s
             INNER JOIN users u ON s.user_id = u.id
             WHERE s.difficulty = :difficulty
             ORDER BY s.moves ASC, s.time_seconds ASC
             LIMIT :limit'
        );
        $stmt->bindValue(':difficulty', $difficulty, \PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Récupère tous les meilleurs scores toutes difficultés confondues
     *
     * @param int $limit Nombre maximum de résultats
     * @return array Liste des meilleurs scores
     */
    public function getAllTopScores(int $limit = 20): array
    {
        $stmt = Database::getPdo()->prepare(
            'SELECT s.*, u.username 
             FROM scores s
             INNER JOIN users u ON s.user_id = u.id
             ORDER BY s.difficulty DESC, s.moves ASC, s.time_seconds ASC
             LIMIT :limit'
        );
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Récupère les scores d'un utilisateur spécifique
     *
     * @param int $userId ID de l'utilisateur
     * @param int $limit Nombre maximum de résultats
     * @return array Liste des scores de l'utilisateur
     */
    public function getUserScores(int $userId, int $limit = 10): array
    {
        $stmt = Database::getPdo()->prepare(
            'SELECT s.* 
             FROM scores s
             WHERE s.user_id = :user_id
             ORDER BY s.created_at DESC
             LIMIT :limit'
        );
        $stmt->bindValue(':user_id', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
