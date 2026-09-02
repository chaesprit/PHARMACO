<?php

/**
 * Classe mère de tous les Controllers.
 * Fournit les opérations communes : affichage d'une vue et redirection.
 */
class Controller
{
    protected function render(string $view, array $data = []): void
    {
        if (!defined('VUES_DIR')) {
            define('VUES_DIR', __DIR__ . '/../views/');
        }

        extract($data);

        $viewFile = VUES_DIR . $view . '.php';

        if (!file_exists($viewFile)) {
            http_response_code(500);
            echo "Vue introuvable : {$view}";
            return;
        }

        require $viewFile;
    }

    protected function redirect(string $url): void
    {
        header('Location: ' . $url);
        exit;
    }

    /**
     * Garde d'accès BackOffice : impose une connexion, puis un rôle
     * parmi ceux autorisés. À appeler en première ligne de chaque
     * action réservée (ex: exigerRole(['responsable'])).
     */
    protected function exigerRole(array $rolesAutorises): void
    {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('index.php?controller=Utilisateur&action=connexion');
        }

        if (!in_array($_SESSION['user_role'], $rolesAutorises, true)) {
            http_response_code(403);
            echo 'Accès refusé : cette page est réservée à un autre rôle.';
            exit;
        }
    }
}
