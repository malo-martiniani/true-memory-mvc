<?php
/**
 * Vue : Tableau des scores
 * -------------------------
 * Affiche les meilleurs scores
 */
$scores = $scores ?? [];
$selectedDifficulty = $selected_difficulty ?? null;
?>

<div class="container" style="max-width: 1000px; margin: 20px auto; padding: 30px;">
    <h1 style="text-align: center; margin-bottom: 30px; color: var(--neon-blue); text-shadow: 0 0 10px var(--neon-blue);"><?= htmlspecialchars($title ?? 'Tableau des scores', ENT_QUOTES, 'UTF-8') ?></h1>

    <!-- Filtres de difficulté -->
    <div style="background: rgba(0,0,0,0.4); padding: 20px; border-radius: 15px; margin-bottom: 30px; border: 1px solid var(--neon-purple);">
        <p style="margin: 0 0 15px 0; font-weight: bold; color: var(--neon-pink); text-transform: uppercase; letter-spacing: 1px;">Filtrer par difficulté:</p>
        <div style="display: flex; gap: 10px; flex-wrap: wrap; justify-content: center;">
            <a href="/game/leaderboard" class="btn" style="
                background: <?= $selectedDifficulty === null ? 'var(--neon-pink)' : 'rgba(255,255,255,0.1)' ?>;
                border: 1px solid <?= $selectedDifficulty === null ? 'var(--neon-pink)' : 'rgba(255,255,255,0.3)' ?>;
                color: white;
                text-decoration: none;
                border-radius: 20px;
                font-size: 14px;
                padding: 8px 20px;
                transition: all 0.3s;
            ">
                Toutes
            </a>
            <?php for ($i = 3; $i <= 12; $i++): ?>
                <a href="/game/leaderboard?difficulty=<?= $i ?>" class="btn" style="
                    background: <?= $selectedDifficulty === $i ? 'var(--neon-blue)' : 'rgba(255,255,255,0.1)' ?>;
                    border: 1px solid <?= $selectedDifficulty === $i ? 'var(--neon-blue)' : 'rgba(255,255,255,0.3)' ?>;
                    color: white;
                    text-decoration: none;
                    border-radius: 20px;
                    font-size: 14px;
                    padding: 8px 15px;
                    transition: all 0.3s;
                ">
                    <?= $i ?>
                </a>
            <?php endfor; ?>
        </div>
    </div>

    <?php if (empty($scores)): ?>
        <div style="background: rgba(0,0,0,0.6); padding: 40px; border-radius: 15px; text-align: center; border: 2px dashed var(--neon-yellow);">
            <p style="margin: 0; color: var(--neon-yellow); font-size: 1.2rem;">
                Aucun score enregistré pour le moment. Soyez le premier à jouer !
            </p>
        </div>
    <?php else: ?>
        <div style="overflow-x: auto; border-radius: 15px; box-shadow: 0 0 20px rgba(0,0,0,0.5);">
            <table style="width: 100%; border-collapse: collapse; background: rgba(0,0,0,0.8);">
                <thead>
                    <tr style="background: linear-gradient(90deg, var(--neon-purple), var(--neon-blue));">
                        <th style="padding: 15px; text-align: center; color: white; text-transform: uppercase;">#</th>
                        <th style="padding: 15px; text-align: left; color: white; text-transform: uppercase;">Joueur</th>
                        <th style="padding: 15px; text-align: center; color: white; text-transform: uppercase;">Difficulté</th>
                        <th style="padding: 15px; text-align: center; color: white; text-transform: uppercase;">Coups</th>
                        <th style="padding: 15px; text-align: center; color: white; text-transform: uppercase;">Temps</th>
                        <th style="padding: 15px; text-align: right; color: white; text-transform: uppercase;">Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($scores as $index => $score): ?>
                        <tr style="border-bottom: 1px solid rgba(255,255,255,0.1); transition: background 0.3s;">
                            <td style="padding: 15px; text-align: center; font-weight: bold; color: var(--neon-yellow);">
                                <?php 
                                    if ($index === 0) echo '👑';
                                    elseif ($index === 1) echo '🥈';
                                    elseif ($index === 2) echo '🥉';
                                    else echo $index + 1;
                                ?>
                            </td>
                            <td style="padding: 15px; font-weight: bold; color: white;">
                                <?= htmlspecialchars($score['username'] ?? 'Anonyme', ENT_QUOTES, 'UTF-8') ?>
                            </td>
                            <td style="padding: 15px; text-align: center; color: #ccc;">
                                <?= (int)($score['difficulty'] ?? 0) ?> paires
                            </td>
                            <td style="padding: 15px; text-align: center; color: var(--neon-green); font-weight: bold;">
                                <?= (int)($score['moves'] ?? 0) ?>
                            </td>
                            <td style="padding: 15px; text-align: center; color: var(--neon-blue);">
                                <?= (int)($score['time_seconds'] ?? 0) ?>s
                            </td>
                            <td style="padding: 15px; text-align: right; color: #aaa; font-size: 0.9em;">
                                <?php
                                $date = $score['created_at'] ?? '';
                                if ($date) {
                                    $timestamp = strtotime($date);
                                    echo htmlspecialchars(date('d/m/Y H:i', $timestamp), ENT_QUOTES, 'UTF-8');
                                } else {
                                    echo '-';
                                }
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <div style="margin-top: 40px; text-align: center;">
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="/game/difficulty" class="btn" style="font-size: 1.2rem; padding: 15px 40px; background: linear-gradient(45deg, var(--neon-green), var(--neon-blue));">
                🎮 Jouer une partie
            </a>
        <?php else: ?>
            <a href="/login" class="btn" style="font-size: 1.2rem; padding: 15px 40px;">
                🔑 Se connecter pour jouer
            </a>
        <?php endif; ?>
    </div>
</div>
