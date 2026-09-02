<?php

/**
 * Controller de l'entité Utilisateur.
 * FrontOffice : inscription (réservée au rôle Client), connexion,
 * déconnexion. Le CRUD BackOffice (gestion des comptes par le
 * Responsable) sera ajouté avec le module correspondant.
 */
class UtilisateurController extends Controller
{
    private Utilisateur $utilisateurModel;

    public function __construct()
    {
        $this->utilisateurModel = new Utilisateur();
    }

    public function inscription(): void
    {
        $erreurs = [];
        $succes = false;
        $donnees = [
            'nom' => '',
            'prenom' => '',
            'email' => '',
            'telephone' => '',
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $donnees['nom'] = trim($_POST['nom'] ?? '');
            $donnees['prenom'] = trim($_POST['prenom'] ?? '');
            $donnees['email'] = trim($_POST['email'] ?? '');
            $donnees['telephone'] = trim($_POST['telephone'] ?? '');
            $motDePasse = $_POST['mot_de_passe'] ?? '';
            $confirmation = $_POST['confirmation_mot_de_passe'] ?? '';

            $erreurs = $this->validerInscription($donnees, $motDePasse, $confirmation);

            if (empty($erreurs)) {
                $id = $this->utilisateurModel->creer([
                    'nom' => $donnees['nom'],
                    'prenom' => $donnees['prenom'],
                    'email' => $donnees['email'],
                    'mot_de_passe' => $motDePasse,
                    'role' => 'client',
                    'telephone' => $donnees['telephone'] !== '' ? $donnees['telephone'] : null,
                ]);

                $_SESSION['user_id'] = $id;
                $_SESSION['user_role'] = 'client';
                $_SESSION['user_nom'] = $donnees['prenom'] . ' ' . $donnees['nom'];

                $succes = true;
            }
        }

        $this->render('frontoffice/inscription', [
            'erreurs' => $erreurs,
            'donnees' => $donnees,
            'succes' => $succes,
        ]);
    }

    public function connexion(): void
    {
        if (isset($_SESSION['user_id'])) {
            $this->render('frontoffice/connexion', [
                'erreurs' => [],
                'email' => '',
                'dejaConnecte' => true,
            ]);
            return;
        }

        $erreurs = [];
        $email = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $motDePasse = $_POST['mot_de_passe'] ?? '';

            if ($email === '') {
                $erreurs[] = "L'email est obligatoire.";
            }

            if ($motDePasse === '') {
                $erreurs[] = 'Le mot de passe est obligatoire.';
            }

            if (empty($erreurs)) {
                $utilisateur = $this->utilisateurModel->trouverParEmail($email);

                if (!$utilisateur || !$this->utilisateurModel->verifierMotDePasse($motDePasse, $utilisateur['mot_de_passe'])) {
                    $erreurs[] = 'Email ou mot de passe incorrect.';
                } else {
                    $_SESSION['user_id'] = $utilisateur['id_utilisateur'];
                    $_SESSION['user_role'] = $utilisateur['role'];
                    $_SESSION['user_nom'] = $utilisateur['prenom'] . ' ' . $utilisateur['nom'];

                    $this->render('frontoffice/connexion', [
                        'erreurs' => [],
                        'email' => '',
                        'dejaConnecte' => true,
                    ]);
                    return;
                }
            }
        }

        $this->render('frontoffice/connexion', [
            'erreurs' => $erreurs,
            'email' => $email,
            'dejaConnecte' => false,
        ]);
    }

    public function deconnexion(): void
    {
        $_SESSION = [];
        session_destroy();

        $this->redirect('index.php?controller=Utilisateur&action=connexion');
    }

    /**
     * Validation côté serveur — indispensable pour des données sensibles
     * (email, mot de passe). Les attributs HTML5 (required, pattern, type=email...)
     * ne comptent pas comme validation, conformément à la consigne du professeur.
     */
    private function validerInscription(array $donnees, string $motDePasse, string $confirmation): array
    {
        $erreurs = [];

        if ($donnees['nom'] === '') {
            $erreurs['nom'] = 'Le nom est obligatoire.';
        } elseif (mb_strlen($donnees['nom']) > 100) {
            $erreurs['nom'] = 'Le nom ne doit pas dépasser 100 caractères.';
        }

        if ($donnees['prenom'] === '') {
            $erreurs['prenom'] = 'Le prénom est obligatoire.';
        } elseif (mb_strlen($donnees['prenom']) > 100) {
            $erreurs['prenom'] = 'Le prénom ne doit pas dépasser 100 caractères.';
        }

        if ($donnees['email'] === '') {
            $erreurs['email'] = "L'email est obligatoire.";
        } elseif (!filter_var($donnees['email'], FILTER_VALIDATE_EMAIL)) {
            $erreurs['email'] = "Le format de l'email est invalide.";
        } elseif ($this->utilisateurModel->emailExiste($donnees['email'])) {
            $erreurs['email'] = 'Cet email est déjà utilisé.';
        }

        if (strlen($motDePasse) < 8) {
            $erreurs['mot_de_passe'] = 'Le mot de passe doit contenir au moins 8 caractères.';
        }

        if ($motDePasse !== $confirmation) {
            $erreurs['confirmation_mot_de_passe'] = 'Les mots de passe ne correspondent pas.';
        }

        if ($donnees['telephone'] !== '' && !preg_match('/^[0-9+ ]{8,20}$/', $donnees['telephone'])) {
            $erreurs['telephone'] = 'Le numéro de téléphone est invalide.';
        }

        return $erreurs;
    }
}
