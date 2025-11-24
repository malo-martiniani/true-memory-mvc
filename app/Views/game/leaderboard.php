<?php
/**
 * Vue : Tableau des scores
 * -------------------------
 * Affiche les meilleurs scores
 */
$scores = $scores ?? [];
$selectedDifficulty = $selected_difficulty ?? null;
?>

<div style="max-width: 900px; margin: 20px auto; padding: 20px;">
    <h1><?= htmlspecialchars($title ?? 'Tableau des scores', ENT_QUOTES, 'UTF-8') ?></h1>

    <!-- Filtres de difficulté -->
    <div style="background-color: #f8f9fa; padding: 15px; border-radius: 4px; margin-bottom: 20px;">
        <p style="margin: 0 0 10px 0; font-weight: bold;">Filtrer par difficulté:</p>
        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            <a href="/game/leaderboard" style="
                padding: 8px 15px;
                background-color: <?= $selectedDifficulty === null ? '#007bff' : '#6c757d' ?>;
                color: white;
                text-decoration: none;
                border-radius: 4px;
                font-size: 14px;
            ">
                Toutes
            </a>
            <?php for ($i = 3; $i <= 12; $i++): ?>
                <a href="/game/leaderboard?difficulty=<?= $i ?>" style="
                    padding: 8px 15px;
                    background-color: <?= $selectedDifficulty === $i ? '#007bff' : '#6c757d' ?>;
                    color: white;
                    text-decoration: none;
                    border-radius: 4px;
                    font-size: 14px;
                ">
                    <?= $i ?> paires
                </a>
            <?php endfor; ?>
        </div>
    </div>

    <?php if (empty($scores)): ?>
        <div style="background-color: #fff3cd; padding: 20px; border-radius: 4px; text-align: center;">
            <p style="margin: 0; color: #856404;">
                Aucun score enregistré pour le moment. Soyez le premier à jouer !
            </p>
        </div>
    <?php else: ?>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; background-color: white;">
                <thead>
                    <tr style="background-color: #007bff; color: white;">
                        <th style="padding: 12px; text-align: left; border: 1px solid #ddd;">#</th>
                        <th style="padding: 12px; text-align: left; border: 1px solid #ddd;">Joueur</th>
                        <th style="padding: 12px; text-align: center; border: 1px solid #ddd;">Difficulté</th>
                        <th style="padding: 12px; text-align: center; border: 1px solid #ddd;">Coups</th>
                        <th style="padding: 12px; text-align: center; border: 1px solid #ddd;">Temps</th>
                        <th style="padding: 12px; text-align: left; border: 1px solid #ddd;">Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($scores as $index => $score): ?>
                        <tr style="<?= $index % 2 === 0 ? 'background-color: #f8f9fa;' : '' ?>">
                            <td style="padding: 10px; border: 1px solid #ddd; font-weight: bold;">
                                <?= $index + 1 ?>
                            </td>
                            <td style="padding: 10px; border: 1px solid #ddd;">
                                <?= htmlspecialchars($score['username'] ?? 'Anonyme', ENT_QUOTES, 'UTF-8') ?>
                            </td>
                            <td style="padding: 10px; border: 1px solid #ddd; text-align: center;">
                                <?= (int)($score['difficulty'] ?? 0) ?> paires
                            </td>
                            <td style="padding: 10px; border: 1px solid #ddd; text-align: center;">
                                <?= (int)($score['moves'] ?? 0) ?>
                            </td>
                            <td style="padding: 10px; border: 1px solid #ddd; text-align: center;">
                                <?= (int)($score['time_seconds'] ?? 0) ?>s
                            </td>
                            <td style="padding: 10px; border: 1px solid #ddd;">
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

    <div style="margin-top: 30px; text-align: center;">
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="/game/difficulty" style="
                display: inline-block;
                padding: 12px 25px;
                background-color: #28a745;
                color: white;
                text-decoration: none;
                border-radius: 4px;
                font-weight: bold;
            ">
                Jouer une partie
            </a>
        <?php else: ?>
            <a href="/login" style="
                display: inline-block;
                padding: 12px 25px;
                background-color: #007bff;
                color: white;
                text-decoration: none;
                border-radius: 4px;
                font-weight: bold;
            ">
                Se connecter pour jouer
            </a>
        <?php endif; ?>
    </div>
</div>
