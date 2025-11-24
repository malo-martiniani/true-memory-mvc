<?php
namespace App\Controllers;

use App\Models\ArticleModel;
use Core\BaseController;

/**
 * Classe ArticleController
 * -------------------------
 * Contrôleur responsable de la gestion des articles.
 * Hérite de BaseController afin de bénéficier des méthodes utilitaires
 * comme render() pour afficher les vues.
 */

class ArticleController extends BaseController
{
    /**
     * Action principale (point d'entrée de la page des articles)
     *
     * @return void
     */
    public function index(): void
    {
        // Appelle la méthode render() de BaseController
        // - Charge la vue "app/Views/articles/index.php"
        // - Injecte le tableau de paramètres (ici, une variable $title utilisable dans la vue)
        // - Insère le contenu de la vue dans le layout global "base.php"
        $articles = new ArticleModel();
        $data = [
            "title" => "Liste des Articles",
            "articles" => $articles->all()
        ];
        $this->render('article/index', $data);
    }
}

