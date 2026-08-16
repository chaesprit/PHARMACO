<?php

/**
 * Point d'entrée unique de l'application.
 * Route les requêtes au format ?controller=<nom>&action=<methode>
 * vers la classe <Nom>Controller et sa méthode <methode>.
 */

spl_autoload_register(function (string $class): void {
    $paths = [
        __DIR__ . '/../app/config/' . $class . '.php',
        __DIR__ . '/../app/controllers/' . $class . '.php',
        __DIR__ . '/../app/models/' . $class . '.php',
    ];

    foreach ($paths as $path) {
        if (file_exists($path)) {
            require $path;
            return;
        }
    }
});

$controllerName = $_GET['controller'] ?? 'Home';
$actionName = $_GET['action'] ?? 'index';

$controllerClass = ucfirst($controllerName) . 'Controller';

if (!class_exists($controllerClass)) {
    http_response_code(404);
    echo "Page introuvable (controller inconnu : {$controllerClass})";
    exit;
}

$controller = new $controllerClass();

if (!method_exists($controller, $actionName)) {
    http_response_code(404);
    echo "Page introuvable (action inconnue : {$actionName})";
    exit;
}

$controller->$actionName();
