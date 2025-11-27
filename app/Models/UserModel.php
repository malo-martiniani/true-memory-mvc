<?php
namespace App\Models;

use Core\Database;

/**
 * Classe UserModel
 * -----------------
 * Gère l'accès aux données pour l'entité "User".
 * Permet la création et la récupération d'utilisateurs.
 */
class UserModel
{
    /**
     * Récupère un utilisateur par son nom d'utilisateur
     *
     * @param string $username Nom d'utilisateur
     * @return array|null Retourne l'utilisateur trouvé ou null si aucun résultat
     */
    public function findByUsername(string $username): ?array
    {
        $stmt = Database::getPdo()->prepare(
            'SELECT id, username, created_at FROM users WHERE username = :username'
        );
        $stmt->execute(['username' => $username]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    /**
     * Crée un nouvel utilisateur avec un nom d'utilisateur unique
     *
     * @param string $username Nom d'utilisateur
     * @return int|null Retourne l'ID du nouvel utilisateur ou null en cas d'échec
     */
    public function create(string $username): ?int
    {
        try {
            $stmt = Database::getPdo()->prepare(
                'INSERT INTO users (username) VALUES (:username)'
            );
            $stmt->execute(['username' => $username]);
            return (int)Database::getPdo()->lastInsertId();
        } catch (\PDOException $e) {
            // En cas d'erreur (ex: username déjà existant)
            return null;
        }
    }

    /**
     * Récupère un utilisateur par son ID
     *
     * @param int $id Identifiant de l'utilisateur
     * @return array|null Retourne l'utilisateur trouvé ou null si aucun résultat
     */
    public function find(int $id): ?array
    {
        $stmt = Database::getPdo()->prepare(
            'SELECT id, username, created_at FROM users WHERE id = :id'
        );
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }
}
