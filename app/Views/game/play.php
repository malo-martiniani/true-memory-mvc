<?php
/**
 * Vue : Plateau de jeu
 * ---------------------
 * Affiche le plateau de jeu avec les cartes
 */
$game = $game ?? [];
$cards = $game['cards'] ?? [];
$moves = $game['moves'] ?? 0;
$startTime = $game['start_time'] ?? time();
$elapsedTime = time() - $startTime;
$difficulty = $game['difficulty'] ?? 6;

// Calcul de la grille en fonction de la difficulté
$totalCards = $difficulty * 2;
$cols = 4;
if ($totalCards <= 12) {
    $cols = 4;
} elseif ($totalCards <= 18) {
    $cols = 6;
} else {
    $cols = 8;
}
?>

<style>
.card-button:hover {
    transform: scale(1.05);
}
</style>

<div style="max-width: 900px; margin: 20px auto; padding: 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap;">
        <h1 style="margin: 0;"><?= htmlspecialchars($title ?? 'Memory Game', ENT_QUOTES, 'UTF-8') ?></h1>
        <div>
            <strong>Coups: <?= (int)$moves ?></strong> | 
            <strong>Temps: <?= (int)$elapsedTime ?>s</strong>
        </div>
    </div>

    <div style="background-color: #f8f9fa; padding: 15px; border-radius: 4px; margin-bottom: 20px;">
        <p style="margin: 0; font-size: 14px;">
            <strong>Instructions:</strong> Cliquez sur une carte pour la retourner. Trouvez toutes les paires !
        </p>
        <p style="margin: 10px 0 0 0; font-size: 14px;">
            Paires trouvées: <strong><?= count($game['matched_pairs'] ?? []) ?> / <?= (int)$difficulty ?></strong>
        </p>
    </div>

    <div style="display: grid; grid-template-columns: repeat(<?= $cols ?>, 1fr); gap: 10px; max-width: <?= $cols * 120 ?>px; margin: 0 auto;">
        <?php foreach ($cards as $card): ?>
            <?php
            $isFlipped = $card['is_flipped'] ?? false;
            $isMatched = $card['is_matched'] ?? false;
            $cardId = $card['id'] ?? 0;
            ?>
            
            <?php if ($isMatched): ?>
                <!-- Carte déjà trouvée -->
                <div style="
                    aspect-ratio: 1;
                    background-color: #d4edda;
                    border: 3px solid #28a745;
                    border-radius: 8px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-weight: bold;
                    color: #155724;
                ">
                    <img src="<?= htmlspecialchars($card['image_path'] ?? '', ENT_QUOTES, 'UTF-8') ?>" 
                         alt="Carte <?= (int)($card['image_id'] ?? 0) ?>"
                         style="max-width: 90%; max-height: 90%; object-fit: contain;">
                    <?php if (!file_exists(__DIR__ . '/../../../public' . ($card['image_path'] ?? ''))): ?>
                        <span>✓</span>
                    <?php endif; ?>
                </div>
            <?php elseif ($isFlipped): ?>
                <!-- Carte retournée -->
                <div style="
                    aspect-ratio: 1;
                    background-color: #fff3cd;
                    border: 3px solid #ffc107;
                    border-radius: 8px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-weight: bold;
                ">
                    <img src="<?= htmlspecialchars($card['image_path'] ?? '', ENT_QUOTES, 'UTF-8') ?>" 
                         alt="Carte <?= (int)($card['image_id'] ?? 0) ?>"
                         style="max-width: 90%; max-height: 90%; object-fit: contain;">
                    <?php if (!file_exists(__DIR__ . '/../../../public' . ($card['image_path'] ?? ''))): ?>
                        <span style="font-size:24px;"><?= (int)($card['image_id'] ?? 0) ?></span>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <!-- Carte face cachée - cliquable -->
                <form method="POST" action="/game/flip" style="margin: 0;">
                    <input type="hidden" name="card_id" value="<?= (int)$cardId ?>">
                    <button type="submit" class="card-button" style="
                        width: 100%;
                        aspect-ratio: 1;
                        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                        border: 2px solid #5a67d8;
                        border-radius: 8px;
                        cursor: pointer;
                        font-size: 24px;
                        color: white;
                    ">
                        ?
                    </button>
                </form>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>

    <div style="margin-top: 30px; text-align: center;">
        <a href="/game/difficulty" style="display: inline-block; padding: 10px 20px; background-color: #6c757d; color: white; text-decoration: none; border-radius: 4px;">
            Abandonner et recommencer
        </a>
    </div>
</div>
