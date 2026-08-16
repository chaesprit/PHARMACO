<?php

/**
 * Classe mère de tous les Controllers.
 * Fournit les opérations communes : affichage d'une vue et redirection.
 */
class Controller
{
    protected function render(string $view, array $data = []): void
    {
        extract($data);

        $viewFile = __DIR__ . '/../views/' . $view . '.php';

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
}
