<?php

/**
 * Controller de la page d'accueil. Le contenu affiché dépend du rôle de
 * l'utilisateur connecté (ou de l'absence de connexion) ; voir accueil.php.
 * Pharmacien et Responsable voient un tableau de bord avec des chiffres
 * réels (stock critique, ordonnances en attente).
 */
class HomeController extends Controller
{
    public function index(): void
    {
        $role = $_SESSION['user_role'] ?? null;
        $donnees = [
            'titre' => 'Système de Gestion de Pharmacie',
        ];

        if ($role === 'pharmacien' || $role === 'responsable') {
            $medicamentModel = new Medicament();
            $ordonnanceModel = new Ordonnance();

            $donnees['stockCritique'] = $medicamentModel->compterStockCritique();
            $donnees['totalMedicaments'] = $medicamentModel->compterTous();
            $donnees['ordonnancesEnAttente'] = $ordonnanceModel->compterParStatut('en_attente');
        }

        $this->render('frontoffice/accueil', $donnees);
    }
}
