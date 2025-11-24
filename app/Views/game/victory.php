<?php
/**
 * Vue : Victoire
 * ---------------
 * Affiche les résultats après une victoire
 */
$result = $result ?? [];
$difficulty = $result['difficulty'] ?? 0;
$moves = $result['moves'] ?? 0;
$time = $result['time'] ?? 0;
?>

<div style="max-width: 600px; margin: 50px auto; padding: 20px; text-align: center;">
    <div style="background-color: #d4edda; border: 3px solid #28a745; border-radius: 8px; padding: 40px;">
        <h1 style="color: #155724; margin-top: 0; font-size: 36px;">🎉 Félicitations ! 🎉</h1>
        
        <p style="font-size: 20px; color: #155724; margin: 20px 0;">
            Vous avez gagné !
        </p>

        <div style="background-color: white; padding: 20px; border-radius: 4px; margin: 30px 0;">
            <h2 style="margin-top: 0; color: #155724;">Vos statistiques</h2>
            
            <div style="margin: 15px 0;">
                <p style="font-size: 18px; margin: 10px 0;">
                    <strong>Difficulté:</strong> <?= (int)$difficulty ?> paires
                </p>
                <p style="font-size: 18px; margin: 10px 0;">
                    <strong>Nombre de coups:</strong> <?= (int)$moves ?>
                </p>
                <p style="font-size: 18px; margin: 10px 0;">
                    <strong>Temps:</strong> <?= (int)$time ?> secondes
                </p>
            </div>
        </div>

        <div style="margin-top: 30px;">
            <p style="color: #155724; margin-bottom: 20px;">
                Votre score a été enregistré !
            </p>
            
            <div style="display: flex; gap: 10px; justify-content: center; flex-wrap: wrap;">
                <a href="/game/difficulty" style="
                    display: inline-block;
                    padding: 15px 25px;
                    background-color: #28a745;
                    color: white;
                    text-decoration: none;
                    border-radius: 4px;
                    font-weight: bold;
                ">
                    Rejouer
                </a>
                
                <a href="/game/leaderboard" style="
                    display: inline-block;
                    padding: 15px 25px;
                    background-color: #007bff;
                    color: white;
                    text-decoration: none;
                    border-radius: 4px;
                    font-weight: bold;
                ">
                    Voir les scores
                </a>
            </div>
        </div>
    </div>
</div>
