<?php

/**
 * Controller de la table métier `expedition`.
 * BackOffice (rôles Pharmacien/Responsable) : enregistrement, suivi et
 * réception des expéditions. Le rapport agrégé est réservé au Responsable.
 */
class ExpeditionController extends Controller
{
    private Expedition $expeditionModel;
    private Medicament $medicamentModel;

    public function __construct()
    {
        $this->expeditionModel = new Expedition();
        $this->medicamentModel = new Medicament();
    }

    public function liste(): void
    {
        $this->exigerRole(['responsable', 'pharmacien']);

        $this->render('backoffice/expeditions/liste', [
            'expeditions' => $this->expeditionModel->toutesLesExpeditions(),
        ]);
    }

    public function creer(): void
    {
        $this->exigerRole(['responsable', 'pharmacien']);

        $erreurs = [];
        $donnees = [
            'id_medicament' => '',
            'quantite' => '',
            'fournisseur' => '',
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $donnees['id_medicament'] = trim($_POST['id_medicament'] ?? '');
            $donnees['quantite'] = trim($_POST['quantite'] ?? '');
            $donnees['fournisseur'] = trim($_POST['fournisseur'] ?? '');

            $erreurs = $this->validerExpedition($donnees);

            if (empty($erreurs)) {
                $this->expeditionModel->creer([
                    'id_medicament' => (int) $donnees['id_medicament'],
                    'quantite' => (int) $donnees['quantite'],
                    'fournisseur' => $donnees['fournisseur'] !== '' ? $donnees['fournisseur'] : null,
                ]);

                $this->redirect('index.php?controller=Expedition&action=liste');
            }
        }

        $this->render('backoffice/expeditions/creer', [
            'erreurs' => $erreurs,
            'donnees' => $donnees,
            'medicaments' => $this->medicamentModel->tousLesMedicaments(),
        ]);
    }

    public function changerStatut(): void
    {
        $this->exigerRole(['responsable', 'pharmacien']);

        $id = (int) ($_GET['id'] ?? 0);
        $statut = $_POST['statut'] ?? '';

        if (!in_array($statut, ['livree', 'annulee'], true)) {
            http_response_code(400);
            echo 'Statut invalide.';
            return;
        }

        $this->expeditionModel->changerStatut($id, $statut);

        $this->redirect('index.php?controller=Expedition&action=liste');
    }

    public function supprimer(): void
    {
        $this->exigerRole(['responsable', 'pharmacien']);

        $id = (int) ($_GET['id'] ?? 0);
        $this->expeditionModel->supprimer($id);

        $this->redirect('index.php?controller=Expedition&action=liste');
    }

    public function rapport(): void
    {
        $this->exigerRole(['responsable']);

        $this->render('backoffice/expeditions/rapport', [
            'parStatut' => $this->expeditionModel->rapportParStatut(),
            'parMedicament' => $this->expeditionModel->rapportParMedicament(),
        ]);
    }

    private function validerExpedition(array $donnees): array
    {
        $erreurs = [];

        if ($donnees['id_medicament'] === '' || !ctype_digit($donnees['id_medicament'])
            || !$this->medicamentModel->trouverParId((int) $donnees['id_medicament'])) {
            $erreurs['id_medicament'] = 'Sélectionnez un médicament valide.';
        }

        if ($donnees['quantite'] === '' || !ctype_digit($donnees['quantite']) || (int) $donnees['quantite'] < 1) {
            $erreurs['quantite'] = 'La quantité doit être un nombre entier positif.';
        }

        if ($donnees['fournisseur'] !== '' && mb_strlen($donnees['fournisseur']) > 150) {
            $erreurs['fournisseur'] = 'Le nom du fournisseur ne doit pas dépasser 150 caractères.';
        }

        return $erreurs;
    }
}
