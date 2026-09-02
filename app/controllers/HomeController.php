<?php

/**
 * Controller de la page d'accueil du FrontOffice. Le contenu affiché
 * dépend du rôle de l'utilisateur connecté (ou de l'absence de
 * connexion) ; voir la vue frontoffice/accueil.php. Le tableau de bord
 * chiffré (stock critique, ordonnances en attente) sera branché avec
 * les modules Médicament et Ordonnance.
 */
class HomeController extends Controller
{
    public function index(): void
    {
        $this->render('frontoffice/accueil', [
            'titre' => 'Système de Gestion de Pharmacie',
        ]);
    }
}
