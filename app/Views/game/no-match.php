<?php
/**
 * Vue : Pas de paire trouvée
 * ---------------------------
 * Page de transition après un échec de match
 */
$game = $game ?? [];
?>

<div style="max-width: 500px; margin: 50px auto; padding: 20px; text-align: center;">
    <div style="background-color: #fff3cd; border: 2px solid #ffc107; border-radius: 8px; padding: 30px;">
        <h1 style="color: #856404; margin-top: 0;">Pas de paire !</h1>
        
        <p style="font-size: 18px; color: #856404;">
            Les cartes ne correspondent pas. Réessayez !
        </p>

        <div style="margin: 30px 0;">
            <p style="font-size: 16px;">
                <strong>Coups joués:</strong> <?= (int)($game['moves'] ?? 0) ?>
            </p>
            <p style="font-size: 16px;">
                <strong>Paires trouvées:</strong> <?= count($game['matched_pairs'] ?? []) ?> / <?= (int)($game['difficulty'] ?? 6) ?>
            </p>
        </div>

        <form method="POST" action="/game/continue">
            <button type="submit" style="
                padding: 15px 30px;
                background-color: #007bff;
                color: white;
                border: none;
                border-radius: 4px;
                cursor: pointer;
                font-size: 18px;
                font-weight: bold;
            ">
                Continuer
            </button>
        </form>
    </div>
</div>
