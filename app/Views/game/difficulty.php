<?php
/**
 * Vue : Sélection de la difficulté
 * ---------------------------------
 * Permet de choisir le nombre de paires pour le jeu
 */
?>
<div style="max-width: 600px; margin: 50px auto; padding: 20px;">
    <h1><?= htmlspecialchars($title ?? 'Choisir la difficulté', ENT_QUOTES, 'UTF-8') ?></h1>

    <div style="background-color: #e7f3ff; padding: 15px; border-radius: 4px; margin-bottom: 20px;">
        <p style="margin: 0;">
            <strong>Bienvenue <?= htmlspecialchars($_SESSION['username'] ?? '', ENT_QUOTES, 'UTF-8') ?> !</strong>
        </p>
        <p style="margin: 10px 0 0 0; font-size: 14px;">
            Choisissez le nombre de paires de cartes pour commencer une nouvelle partie.
        </p>
    </div>

    <form method="POST" action="/game/start" style="margin-top: 30px;">
        <div style="margin-bottom: 20px;">
            <label for="difficulty" style="display: block; margin-bottom: 10px; font-weight: bold; font-size: 16px;">
                Nombre de paires:
            </label>
            <select 
                id="difficulty" 
                name="difficulty" 
                required
                style="width: 100%; padding: 10px; font-size: 16px; border: 1px solid #ddd; border-radius: 4px;"
            >
                <option value="3">3 paires (Facile)</option>
                <option value="4">4 paires (Facile)</option>
                <option value="5">5 paires (Facile)</option>
                <option value="6" selected>6 paires (Moyen)</option>
                <option value="7">7 paires (Moyen)</option>
                <option value="8">8 paires (Moyen)</option>
                <option value="9">9 paires (Difficile)</option>
                <option value="10">10 paires (Difficile)</option>
                <option value="11">11 paires (Très difficile)</option>
                <option value="12">12 paires (Très difficile)</option>
            </select>
        </div>

        <button type="submit" style="width: 100%; padding: 15px; background-color: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 18px; font-weight: bold;">
            Commencer la partie
        </button>
    </form>

    <div style="margin-top: 30px; text-align: center;">
        <a href="/game/leaderboard" style="color: #007bff; text-decoration: none;">
            Voir le tableau des scores
        </a>
    </div>
</div>
