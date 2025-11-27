-- Script SQL de base pour le mini-projet memory_db

CREATE DATABASE IF NOT EXISTS memory_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE memory_db;

CREATE TABLE articles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    body TEXT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table des utilisateurs pour le jeu de mémoire
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table des scores pour le jeu de mémoire
CREATE TABLE scores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    difficulty INT NOT NULL,
    moves INT NOT NULL,
    time_seconds INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_difficulty_moves (difficulty, moves),
    INDEX idx_difficulty_time (difficulty, time_seconds)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;