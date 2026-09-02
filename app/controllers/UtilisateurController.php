<?php

/**
 * Controller de l'entité Utilisateur.
 * FrontOffice : inscription (réservée au rôle Client), connexion,
 * déconnexion.
 * BackOffice (rôle Responsable) : CRUD complet des comptes, tous rôles
 * confondus.
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
     * BackOffice — réservé au rôle Responsable : lecture, création,
     * modification et suppression de n'importe quel compte, tous rôles
     * confondus. L'inscription publique (self-service) reste réservée
     * aux comptes Client et ne passe pas par ici.
     */
    public function liste(): void
    {
        $this->exigerRole(['responsable']);

        $this->render('backoffice/utilisateurs/liste', [
            'utilisateurs' => $this->utilisateurModel->tousLesUtilisateurs(),
        ]);
    }

    public function creer(): void
    {
        $this->exigerRole(['responsable']);

        $erreurs = [];
        $donnees = [
            'nom' => '',
            'prenom' => '',
            'email' => '',
            'role' => 'client',
            'telephone' => '',
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $donnees['nom'] = trim($_POST['nom'] ?? '');
            $donnees['prenom'] = trim($_POST['prenom'] ?? '');
            $donnees['email'] = trim($_POST['email'] ?? '');
            $donnees['role'] = $_POST['role'] ?? '';
            $donnees['telephone'] = trim($_POST['telephone'] ?? '');
            $motDePasse = $_POST['mot_de_passe'] ?? '';
            $confirmation = $_POST['confirmation_mot_de_passe'] ?? '';

            $erreurs = $this->validerGestion($donnees, null);

            if (strlen($motDePasse) < 8) {
                $erreurs['mot_de_passe'] = 'Le mot de passe doit contenir au moins 8 caractères.';
            }

            if ($motDePasse !== $confirmation) {
                $erreurs['confirmation_mot_de_passe'] = 'Les mots de passe ne correspondent pas.';
            }

            if (empty($erreurs)) {
                $this->utilisateurModel->creer([
                    'nom' => $donnees['nom'],
                    'prenom' => $donnees['prenom'],
                    'email' => $donnees['email'],
                    'mot_de_passe' => $motDePasse,
                    'role' => $donnees['role'],
                    'telephone' => $donnees['telephone'] !== '' ? $donnees['telephone'] : null,
                ]);

                $this->flash('succes', 'Compte créé.');
                $this->redirect('index.php?controller=Utilisateur&action=liste');
            }
        }

        $this->render('backoffice/utilisateurs/creer', [
            'erreurs' => $erreurs,
            'donnees' => $donnees,
        ]);
    }

    public function modifier(): void
    {
        $this->exigerRole(['responsable']);

        $id = (int) ($_GET['id'] ?? 0);
        $utilisateur = $this->utilisateurModel->trouverParId($id);

        if (!$utilisateur) {
            http_response_code(404);
            echo 'Utilisateur introuvable.';
            return;
        }

        $erreurs = [];
        $donnees = [
            'nom' => $utilisateur['nom'],
            'prenom' => $utilisateur['prenom'],
            'email' => $utilisateur['email'],
            'role' => $utilisateur['role'],
            'telephone' => $utilisateur['telephone'] ?? '',
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $donnees['nom'] = trim($_POST['nom'] ?? '');
            $donnees['prenom'] = trim($_POST['prenom'] ?? '');
            $donnees['email'] = trim($_POST['email'] ?? '');
            $donnees['role'] = $_POST['role'] ?? '';
            $donnees['telephone'] = trim($_POST['telephone'] ?? '');

            $erreurs = $this->validerGestion($donnees, $id);

            if (empty($erreurs)) {
                $this->utilisateurModel->mettreAJour($id, [
                    'nom' => $donnees['nom'],
                    'prenom' => $donnees['prenom'],
                    'email' => $donnees['email'],
                    'role' => $donnees['role'],
                    'telephone' => $donnees['telephone'] !== '' ? $donnees['telephone'] : null,
                ]);

                $this->flash('succes', 'Compte modifié.');
                $this->redirect('index.php?controller=Utilisateur&action=liste');
            }
        }

        $this->render('backoffice/utilisateurs/modifier', [
            'id' => $id,
            'erreurs' => $erreurs,
            'donnees' => $donnees,
        ]);
    }

    public function supprimer(): void
    {
        $this->exigerRole(['responsable']);

        $id = (int) ($_GET['id'] ?? 0);

        if ($id === (int) ($_SESSION['user_id'] ?? 0)) {
            http_response_code(400);
            echo 'Vous ne pouvez pas supprimer votre propre compte.';
            return;
        }

        $this->utilisateurModel->supprimer($id);
        $this->flash('succes', 'Compte supprimé.');
        $this->redirect('index.php?controller=Utilisateur&action=liste');
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

    /**
     * Validation partagée par creer() et modifier() (BackOffice).
     * $idAExclure vaut null en création (aucun compte à exclure de la
     * vérification d'unicité de l'email) et l'id du compte en modification.
     */
    private function validerGestion(array $donnees, ?int $idAExclure): array
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
        } else {
            $emailPris = $idAExclure === null
                ? $this->utilisateurModel->emailExiste($donnees['email'])
                : $this->utilisateurModel->emailExistePourAutre($donnees['email'], $idAExclure);

            if ($emailPris) {
                $erreurs['email'] = 'Cet email est déjà utilisé.';
            }
        }

        if (!in_array($donnees['role'], ['responsable', 'pharmacien', 'client'], true)) {
            $erreurs['role'] = 'Rôle invalide.';
        }

        if ($donnees['telephone'] !== '' && !preg_match('/^[0-9+ ]{8,20}$/', $donnees['telephone'])) {
            $erreurs['telephone'] = 'Le numéro de téléphone est invalide.';
        }

        return $erreurs;
    }
}
