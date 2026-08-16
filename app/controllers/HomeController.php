<?php

/**
 * Controller temporaire servant à vérifier que le routeur et l'autoload
 * fonctionnent. Sera remplacé par le vrai accueil du FrontOffice.
 */
class HomeController extends Controller
{
    public function index(): void
    {
        $this->render('layouts/welcome', [
            'titre' => 'Système de Gestion de Pharmacie',
        ]);
    }
}
