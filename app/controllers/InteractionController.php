<?php

/**
 * Controller de la table métier `interaction_medicamenteuse`.
 * BackOffice (rôles Pharmacien/Responsable) : enregistrement et suppression.
 * FrontOffice (rôle Client) : consultation en lecture seule.
 */
class InteractionController extends Controller
{
    private InteractionMedicamenteuse $interactionModel;
    private Medicament $medicamentModel;

    public function __construct()
    {
        $this->interactionModel = new InteractionMedicamenteuse();
        $this->medicamentModel = new Medicament();
    }

    public function liste(): void
    {
        $this->exigerRole(['responsable', 'pharmacien']);

        $this->render('backoffice/interactions/liste', [
            'interactions' => $this->interactionModel->toutesLesInteractions(),
        ]);
    }

    public function creer(): void
    {
        $this->exigerRole(['responsable', 'pharmacien']);

        $erreurs = [];
        $donnees = [
            'id_medicament_1' => '',
            'id_medicament_2' => '',
            'description' => '',
            'niveau_gravite' => 'moderee',
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $donnees['id_medicament_1'] = trim($_POST['id_medicament_1'] ?? '');
            $donnees['id_medicament_2'] = trim($_POST['id_medicament_2'] ?? '');
            $donnees['description'] = trim($_POST['description'] ?? '');
            $donnees['niveau_gravite'] = $_POST['niveau_gravite'] ?? '';

            $erreurs = $this->validerInteraction($donnees);

            if (empty($erreurs)) {
                $this->interactionModel->creer([
                    'id_medicament_1' => (int) $donnees['id_medicament_1'],
                    'id_medicament_2' => (int) $donnees['id_medicament_2'],
                    'description' => $donnees['description'],
                    'niveau_gravite' => $donnees['niveau_gravite'],
                    'id_pharmacien' => (int) $_SESSION['user_id'],
                ]);

                $this->flash('succes', 'Interaction médicamenteuse enregistrée.');
                $this->redirect('index.php?controller=Interaction&action=liste');
            }
        }

        $this->render('backoffice/interactions/creer', [
            'erreurs' => $erreurs,
            'donnees' => $donnees,
            'medicaments' => $this->medicamentModel->tousLesMedicaments(),
        ]);
    }

    public function supprimer(): void
    {
        $this->exigerRole(['responsable', 'pharmacien']);

        $id = (int) ($_GET['id'] ?? 0);
        $this->interactionModel->supprimer($id);

        $this->flash('succes', 'Interaction supprimée.');
        $this->redirect('index.php?controller=Interaction&action=liste');
    }

    public function consulter(): void
    {
        $this->exigerRole(['client']);

        $this->render('frontoffice/interactions/consulter', [
            'interactions' => $this->interactionModel->toutesLesInteractions(),
        ]);
    }

    private function validerInteraction(array $donnees): array
    {
        $erreurs = [];

        if ($donnees['id_medicament_1'] === '' || !ctype_digit($donnees['id_medicament_1'])
            || !$this->medicamentModel->trouverParId((int) $donnees['id_medicament_1'])) {
            $erreurs['id_medicament_1'] = 'Sélectionnez un premier médicament valide.';
        }

        if ($donnees['id_medicament_2'] === '' || !ctype_digit($donnees['id_medicament_2'])
            || !$this->medicamentModel->trouverParId((int) $donnees['id_medicament_2'])) {
            $erreurs['id_medicament_2'] = 'Sélectionnez un second médicament valide.';
        }

        if (empty($erreurs['id_medicament_1']) && empty($erreurs['id_medicament_2'])
            && $donnees['id_medicament_1'] === $donnees['id_medicament_2']) {
            $erreurs['id_medicament_2'] = 'Les deux médicaments doivent être différents.';
        }

        if ($donnees['description'] === '') {
            $erreurs['description'] = 'La description de l\'interaction est obligatoire.';
        }

        if (!in_array($donnees['niveau_gravite'], ['faible', 'moderee', 'elevee'], true)) {
            $erreurs['niveau_gravite'] = 'Niveau de gravité invalide.';
        }

        return $erreurs;
    }
}
