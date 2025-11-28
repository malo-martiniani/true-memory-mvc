<?php
/**
 * Vue : Page d'accueil
 * ---------------------
 * Cette vue reçoit une variable $title optionnelle
 * transmise par le HomeController.
 */
?>
<h1>
  <!-- On sécurise le titre avec htmlspecialchars et on définit une valeur par défaut -->
  <?= htmlspecialchars($title ?? 'Accueil', ENT_QUOTES, 'UTF-8') ?>
</h1>

<p>Bienvenue dans le projet mini-MVC avec jeu de mémoire intégré !</p>

<!-- Section Memory Game -->
<div style="background-color: #e7f3ff; padding: 20px; border-radius: 8px; margin: 20px 0;">
  <h2 style="margin-top: 0;">🎮 Memory Game</h2>
  <p style=color:black>
    Testez votre mémoire avec notre jeu de cartes ! Trouvez toutes les paires en un minimum de coups.
  </p>
  <ul style="line-height: 1.8;color:black">
    <li>Choisissez votre difficulté (de 3 à 12 paires)</li>
    <li>Retournez les cartes pour trouver les paires</li>
    <li>Enregistrez vos scores et comparez-vous aux autres joueurs</li>
  </ul>
  <div style="margin-top: 15px;">
    <?php if (isset($_SESSION['user_id'])): ?>
      <a href="/game/difficulty" style="display: inline-block; padding: 10px 20px; background-color: #28a745; color: white; text-decoration: none; border-radius: 4px; font-weight: bold;">
        Jouer maintenant
      </a>
    <?php else: ?>
      <a href="/login" style="display: inline-block; padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 4px; font-weight: bold;">
        Se connecter pour jouer
      </a>
    <?php endif; ?>
    <a href="/game/leaderboard" style="display: inline-block; padding: 10px 20px; background-color: #00060aff; color: white; text-decoration: none; border-radius: 4px; margin-left: 10px;">
      Voir les scores
    </a>
  </div>
</div>