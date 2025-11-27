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
$mismatch = $mismatch ?? false;

// Calcul de la disposition optimale pour la symétrie
$cols = 4;
switch ($difficulty) {
    case 3: $cols = 3; break; // 3x2
    case 4: $cols = 4; break; // 4x2
    case 5: $cols = 5; break; // 5x2
    case 6: $cols = 4; break; // 4x3
    case 7: $cols = 5; break; // 5,5,4
    case 8: $cols = 4; break; // 4x4
    case 9: $cols = 6; break; // 6x3
    case 10: $cols = 5; break; // 5x4
    case 11: $cols = 6; break; // 6,6,6,4
    case 12: $cols = 6; break; // 6x4
}
?>

<!-- Include custom assets for flip animation -->
<link rel="stylesheet" href="/assets/styles/card-flip.css">
<script src="/assets/js/game.js" defer></script>

<div style="max-width: 100%; margin: 10px auto; padding: 10px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; padding: 10px 20px; background-color: #f8f9fa; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
        <div style="display: flex; align-items: center; gap: 15px;">
            <h2 style="margin: 0; font-size: 1.5rem;"><?= htmlspecialchars($title ?? 'Memory', ENT_QUOTES, 'UTF-8') ?></h2>
        </div>
        <div style="font-size: 1.1rem;">
            <span style="margin-left: 20px;">Paires: <strong><?= count($game['matched_pairs'] ?? []) ?>/<?= (int)$difficulty ?></strong></span>
            <span style="margin-left: 20px;">Coups: <strong><?= (int)$moves ?></strong></span>
            <span style="margin-left: 20px;">Temps: <strong><?= (int)$elapsedTime ?>s</strong></span>
        </div>
    </div>

    <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 15px; max-width: <?= $cols * 160 ?>px; margin: 0 auto;">
        <?php foreach ($cards as $card): ?>
            <?php
            $isFlipped = $card['is_flipped'] ?? false;
            $isMatched = $card['is_matched'] ?? false;
            $cardId = $card['id'] ?? 0;
            
            $containerClass = 'card-container';
            if ($isFlipped) $containerClass .= ' flipped';
            if ($isMatched) $containerClass .= ' matched';
            ?>
            
            <div class="<?= $containerClass ?>" style="width: calc((100% - <?= ($cols - 1) * 15 ?>px) / <?= $cols ?>); aspect-ratio: 1;" data-card-id="<?= (int)$cardId ?>">
                <div class="card-inner">
                    <!-- Front Face (Image) -->
                    <div class="card-front <?= $isMatched ? 'matched' : '' ?>">
                        <img src="<?= htmlspecialchars($card['image_path'] ?? '', ENT_QUOTES, 'UTF-8') ?>" 
                             alt="Carte <?= (int)($card['image_id'] ?? 0) ?>"
                             class="card-img">
                    </div>
                    
                    <!-- Back Face (Question Mark) -->
                    <div class="card-back">
                        ?
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div style="margin-top: 30px; text-align: center;">
        <a href="/game/difficulty" style="display: inline-block; padding: 10px 20px; background-color: #6c757d; color: white; text-decoration: none; border-radius: 4px;">
            Abandonner et recommencer
        </a>
    </div>
</div>

<?php if ($mismatch): ?>
    <div id="mismatch-flag" style="display:none;"></div>
<?php endif; ?>
