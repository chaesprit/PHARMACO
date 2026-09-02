<?php

/**
 * Controller de la table métier `conseil` (conseils santé).
 * Lecture : ouverte à tous (visiteurs et tous les rôles).
 * Rédaction (creer / modifier) : réservée au Pharmacien.
 * Suppression : Pharmacien ou Responsable (modération).
 */
class ConseilController extends Controller
{
    private Conseil $conseilModel;

    public function __construct()
    {
        $this->conseilModel = new Conseil();
    }

    public function liste(): void
    {
        $this->render('frontoffice/conseils/liste', [
            'conseils' => $this->conseilModel->tousLesConseils(),
        ]);
    }

    public function voir(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $conseil = $this->conseilModel->trouverParId($id);

        if (!$conseil) {
            http_response_code(404);
            echo 'Conseil introuvable.';
            return;
        }

        $this->render('frontoffice/conseils/voir', ['conseil' => $conseil]);
    }

    public function creer(): void
    {
        $this->exigerRole(['pharmacien']);

        $erreurs = [];
        $donnees = ['titre' => '', 'contenu' => ''];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $donnees['titre'] = trim($_POST['titre'] ?? '');
            $donnees['contenu'] = trim($_POST['contenu'] ?? '');

            $erreurs = $this->validerConseil($donnees);

            if (empty($erreurs)) {
                $this->conseilModel->creer([
                    'titre' => $donnees['titre'],
                    'contenu' => $donnees['contenu'],
                    'id_auteur' => (int) $_SESSION['user_id'],
                ]);

                $this->flash('succes', 'Conseil publié.');
                $this->redirect('index.php?controller=Conseil&action=liste');
            }
        }

        $this->render('backoffice/conseils/creer', [
            'erreurs' => $erreurs,
            'donnees' => $donnees,
        ]);
    }

    public function modifier(): void
    {
        $this->exigerRole(['pharmacien']);

        $id = (int) ($_GET['id'] ?? 0);
        $conseil = $this->conseilModel->trouverParId($id);

        if (!$conseil) {
            http_response_code(404);
            echo 'Conseil introuvable.';
            return;
        }

        $erreurs = [];
        $donnees = ['titre' => $conseil['titre'], 'contenu' => $conseil['contenu']];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $donnees['titre'] = trim($_POST['titre'] ?? '');
            $donnees['contenu'] = trim($_POST['contenu'] ?? '');

            $erreurs = $this->validerConseil($donnees);

            if (empty($erreurs)) {
                $this->conseilModel->mettreAJour($id, $donnees);

                $this->flash('succes', 'Conseil modifié.');
                $this->redirect('index.php?controller=Conseil&action=liste');
            }
        }

        $this->render('backoffice/conseils/modifier', [
            'id' => $id,
            'erreurs' => $erreurs,
            'donnees' => $donnees,
        ]);
    }

    public function supprimer(): void
    {
        $this->exigerRole(['pharmacien', 'responsable']);

        $id = (int) ($_GET['id'] ?? 0);
        $this->conseilModel->supprimer($id);

        $this->flash('succes', 'Conseil supprimé.');
        $this->redirect('index.php?controller=Conseil&action=liste');
    }

    private function validerConseil(array $donnees): array
    {
        $erreurs = [];

        if ($donnees['titre'] === '') {
            $erreurs['titre'] = 'Le titre est obligatoire.';
        } elseif (mb_strlen($donnees['titre']) > 150) {
            $erreurs['titre'] = 'Le titre ne doit pas dépasser 150 caractères.';
        }

        if ($donnees['contenu'] === '') {
            $erreurs['contenu'] = 'Le contenu est obligatoire.';
        } elseif (mb_strlen($donnees['contenu']) < 20) {
            $erreurs['contenu'] = 'Le contenu doit faire au moins 20 caractères.';
        }

        return $erreurs;
    }
}
