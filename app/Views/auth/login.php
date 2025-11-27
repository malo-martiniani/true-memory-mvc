<?php
/**
 * Vue : Page de connexion
 * ------------------------
 * Formulaire de connexion simple avec nom d'utilisateur uniquement
 */
?>
<div style="max-width: 500px; margin: 50px auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px;">
    <h1><?= htmlspecialchars($title ?? 'Connexion', ENT_QUOTES, 'UTF-8') ?></h1>

    <?php if (isset($_SESSION['error'])): ?>
        <div style="background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 4px; margin-bottom: 15px;">
            <?= htmlspecialchars($_SESSION['error'], ENT_QUOTES, 'UTF-8') ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <form method="POST" action="/login">
        <div style="margin-bottom: 15px;">
            <label for="username" style="display: block; margin-bottom: 5px; font-weight: bold;">
                Nom d'utilisateur:
            </label>
            <input 
                type="text" 
                id="username" 
                name="username" 
                required 
                pattern="[a-zA-Z0-9_-]{3,50}"
                title="Entre 3 et 50 caractères alphanumériques"
                style="width: 100%; padding: 8px; box-sizing: border-box;"
                placeholder="Entrez votre nom d'utilisateur"
            >
            <small style="display: block; margin-top: 5px; color: #666;">
                Entre 3 et 50 caractères (lettres, chiffres, - et _)
            </small>
        </div>

        <button type="submit" style="width: 100%; padding: 10px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px;">
            Se connecter / S'inscrire
        </button>
    </form>

    <div style="margin-top: 20px; text-align: center;">
        <p style="color: #666; font-size: 14px;">
            Si vous n'avez pas encore de compte, il sera créé automatiquement !
        </p>
    </div>
</div>
