<?php

/**
 * Controller de l'entité Médicament — BackOffice, réservé aux rôles
 * Responsable et Pharmacien. CRUD complet sur le stock de médicaments.
 */
class MedicamentController extends Controller
{
    private Medicament $medicamentModel;

    public function __construct()
    {
        $this->medicamentModel = new Medicament();
    }

    public function liste(): void
    {
        $this->exigerRole(['responsable', 'pharmacien']);

        $criteres = [
            'nom' => trim($_GET['nom'] ?? ''),
            'categorie' => trim($_GET['categorie'] ?? ''),
            'fabricant' => trim($_GET['fabricant'] ?? ''),
        ];

        $this->render('backoffice/medicaments/liste', [
            'medicaments' => $this->medicamentModel->rechercher($criteres),
            'criteres' => $criteres,
        ]);
    }

    public function creer(): void
    {
        $this->exigerRole(['responsable', 'pharmacien']);

        $erreurs = [];
        $donnees = $this->donneesVides();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $donnees = $this->lireDonneesFormulaire();
            $erreurs = $this->validerMedicament($donnees);

            if (empty($erreurs)) {
                $this->medicamentModel->creer($donnees);
                $this->flash('succes', 'Médicament ajouté.');
                $this->redirect('index.php?controller=Medicament&action=liste');
            }
        }

        $this->render('backoffice/medicaments/creer', [
            'erreurs' => $erreurs,
            'donnees' => $donnees,
        ]);
    }

    public function modifier(): void
    {
        $this->exigerRole(['responsable', 'pharmacien']);

        $id = (int) ($_GET['id'] ?? 0);
        $medicament = $this->medicamentModel->trouverParId($id);

        if (!$medicament) {
            http_response_code(404);
            echo 'Médicament introuvable.';
            return;
        }

        $erreurs = [];
        $donnees = [
            'nom'            => $medicament['nom'],
            'description'    => $medicament['description'] ?? '',
            'categorie'      => $medicament['categorie'] ?? '',
            'fabricant'      => $medicament['fabricant'] ?? '',
            'prix'           => $medicament['prix'],
            'quantite_stock' => $medicament['quantite_stock'],
            'seuil_critique' => $medicament['seuil_critique'],
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $donnees = $this->lireDonneesFormulaire();
            $erreurs = $this->validerMedicament($donnees);

            if (empty($erreurs)) {
                $this->medicamentModel->mettreAJour($id, $donnees);
                $this->flash('succes', 'Médicament modifié.');
                $this->redirect('index.php?controller=Medicament&action=liste');
            }
        }

        $this->render('backoffice/medicaments/modifier', [
            'id' => $id,
            'erreurs' => $erreurs,
            'donnees' => $donnees,
        ]);
    }

    public function supprimer(): void
    {
        $this->exigerRole(['responsable', 'pharmacien']);

        $id = (int) ($_GET['id'] ?? 0);
        $this->medicamentModel->supprimer($id);

        $this->flash('succes', 'Médicament supprimé.');
        $this->redirect('index.php?controller=Medicament&action=liste');
    }

    private function donneesVides(): array
    {
        return [
            'nom' => '',
            'description' => '',
            'categorie' => '',
            'fabricant' => '',
            'prix' => '',
            'quantite_stock' => '',
            'seuil_critique' => '10',
        ];
    }

    private function lireDonneesFormulaire(): array
    {
        return [
            'nom'            => trim($_POST['nom'] ?? ''),
            'description'    => trim($_POST['description'] ?? ''),
            'categorie'      => trim($_POST['categorie'] ?? ''),
            'fabricant'      => trim($_POST['fabricant'] ?? ''),
            'prix'           => trim($_POST['prix'] ?? ''),
            'quantite_stock' => trim($_POST['quantite_stock'] ?? ''),
            'seuil_critique' => trim($_POST['seuil_critique'] ?? ''),
        ];
    }

    private function validerMedicament(array $donnees): array
    {
        $erreurs = [];

        if ($donnees['nom'] === '') {
            $erreurs['nom'] = 'Le nom est obligatoire.';
        } elseif (mb_strlen($donnees['nom']) > 150) {
            $erreurs['nom'] = 'Le nom ne doit pas dépasser 150 caractères.';
        }

        if ($donnees['categorie'] !== '' && mb_strlen($donnees['categorie']) > 100) {
            $erreurs['categorie'] = 'La catégorie ne doit pas dépasser 100 caractères.';
        }

        if ($donnees['fabricant'] !== '' && mb_strlen($donnees['fabricant']) > 150) {
            $erreurs['fabricant'] = 'Le fabricant ne doit pas dépasser 150 caractères.';
        }

        if ($donnees['prix'] === '' || !is_numeric($donnees['prix']) || (float) $donnees['prix'] < 0) {
            $erreurs['prix'] = 'Le prix doit être un nombre positif.';
        }

        if ($donnees['quantite_stock'] === '' || !ctype_digit((string) $donnees['quantite_stock'])) {
            $erreurs['quantite_stock'] = 'La quantité en stock doit être un nombre entier positif.';
        }

        if ($donnees['seuil_critique'] === '' || !ctype_digit((string) $donnees['seuil_critique'])) {
            $erreurs['seuil_critique'] = 'Le seuil critique doit être un nombre entier positif.';
        }

        return $erreurs;
    }
}
