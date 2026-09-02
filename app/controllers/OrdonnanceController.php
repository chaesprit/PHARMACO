<?php

/**
 * Controller de l'entité Ordonnance.
 * FrontOffice (rôle Client) : soumission, consultation de ses propres
 * ordonnances, annulation tant qu'elles sont en attente, renouvellement
 * d'une ordonnance déjà validée.
 * BackOffice (rôles Pharmacien/Responsable) : consultation de toutes les
 * ordonnances, validation/rejet, suppression.
 */
class OrdonnanceController extends Controller
{
    private Ordonnance $ordonnanceModel;
    private Medicament $medicamentModel;

    public function __construct()
    {
        $this->ordonnanceModel = new Ordonnance();
        $this->medicamentModel = new Medicament();
    }

    public function soumettre(): void
    {
        $this->exigerRole(['client']);

        $erreurs = [];
        $commentaire = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $commentaire = trim($_POST['commentaire'] ?? '');
            $lignes = $this->lireLignesMedicaments();

            $erreurs = $this->validerSoumission($lignes);

            if (empty($erreurs)) {
                $this->ordonnanceModel->creer(
                    (int) $_SESSION['user_id'],
                    $commentaire !== '' ? $commentaire : null,
                    $lignes
                );

                $this->redirect('index.php?controller=Ordonnance&action=mesOrdonnances');
            }
        }

        $this->render('frontoffice/ordonnances/soumettre', [
            'erreurs' => $erreurs,
            'commentaire' => $commentaire,
            'medicaments' => $this->medicamentModel->tousLesMedicaments(),
        ]);
    }

    public function mesOrdonnances(): void
    {
        $this->exigerRole(['client']);

        $ordonnances = $this->ordonnanceModel->ordonnancesParClient((int) $_SESSION['user_id']);

        foreach ($ordonnances as &$ordonnance) {
            $ordonnance['medicaments'] = $this->ordonnanceModel->medicamentsDeOrdonnance((int) $ordonnance['id_ordonnance']);
        }
        unset($ordonnance);

        $this->render('frontoffice/ordonnances/mesOrdonnances', [
            'ordonnances' => $ordonnances,
        ]);
    }

    public function annuler(): void
    {
        $this->exigerRole(['client']);

        $id = (int) ($_GET['id'] ?? 0);
        $ordonnance = $this->ordonnanceModel->trouverParId($id);

        if (!$ordonnance || (int) $ordonnance['id_client'] !== (int) $_SESSION['user_id']) {
            http_response_code(404);
            echo 'Ordonnance introuvable.';
            return;
        }

        if ($ordonnance['statut'] !== 'en_attente') {
            http_response_code(400);
            echo 'Seule une ordonnance en attente peut être annulée.';
            return;
        }

        $this->ordonnanceModel->supprimer($id);

        $this->redirect('index.php?controller=Ordonnance&action=mesOrdonnances');
    }

    public function renouveler(): void
    {
        $this->exigerRole(['client']);

        $id = (int) ($_GET['id'] ?? 0);
        $ordonnance = $this->ordonnanceModel->trouverParId($id);

        if (!$ordonnance || (int) $ordonnance['id_client'] !== (int) $_SESSION['user_id']) {
            http_response_code(404);
            echo 'Ordonnance introuvable.';
            return;
        }

        if ($ordonnance['statut'] !== 'validee') {
            http_response_code(400);
            echo 'Seule une ordonnance déjà validée peut être renouvelée.';
            return;
        }

        $this->ordonnanceModel->creerRenouvellement($id, (int) $_SESSION['user_id']);

        $this->redirect('index.php?controller=Ordonnance&action=mesOrdonnances');
    }

    public function liste(): void
    {
        $this->exigerRole(['responsable', 'pharmacien']);

        $ordonnances = $this->ordonnanceModel->toutesLesOrdonnances();

        foreach ($ordonnances as &$ordonnance) {
            $ordonnance['medicaments'] = $this->ordonnanceModel->medicamentsDeOrdonnance((int) $ordonnance['id_ordonnance']);
        }
        unset($ordonnance);

        $this->render('backoffice/ordonnances/liste', [
            'ordonnances' => $ordonnances,
        ]);
    }

    public function traiter(): void
    {
        $this->exigerRole(['responsable', 'pharmacien']);

        $id = (int) ($_GET['id'] ?? 0);
        $statut = $_POST['statut'] ?? '';

        if (!in_array($statut, ['validee', 'rejetee'], true)) {
            http_response_code(400);
            echo 'Statut invalide.';
            return;
        }

        $ordonnance = $this->ordonnanceModel->trouverParId($id);

        if (!$ordonnance) {
            http_response_code(404);
            echo 'Ordonnance introuvable.';
            return;
        }

        $this->ordonnanceModel->valider($id, (int) $_SESSION['user_id'], $statut);

        $this->redirect('index.php?controller=Ordonnance&action=liste');
    }

    public function supprimer(): void
    {
        $this->exigerRole(['responsable', 'pharmacien']);

        $id = (int) ($_GET['id'] ?? 0);
        $this->ordonnanceModel->supprimer($id);

        $this->redirect('index.php?controller=Ordonnance&action=liste');
    }

    /**
     * Lit les lignes de médicaments cochées dans le formulaire de
     * soumission. Format attendu : medicaments[<id>][inclure]=1,
     * medicaments[<id>][quantite]=N, medicaments[<id>][posologie]=texte.
     */
    private function lireLignesMedicaments(): array
    {
        $lignes = [];
        $brut = $_POST['medicaments'] ?? [];

        foreach ($brut as $idMedicament => $champs) {
            if (empty($champs['inclure'])) {
                continue;
            }

            $lignes[] = [
                'id_medicament' => (int) $idMedicament,
                'quantite' => trim((string) ($champs['quantite'] ?? '')),
                'posologie' => trim((string) ($champs['posologie'] ?? '')),
            ];
        }

        return $lignes;
    }

    private function validerSoumission(array $lignes): array
    {
        $erreurs = [];

        if (empty($lignes)) {
            $erreurs['medicaments'] = 'Sélectionnez au moins un médicament.';
            return $erreurs;
        }

        foreach ($lignes as $ligne) {
            if ($ligne['quantite'] === '' || !ctype_digit($ligne['quantite']) || (int) $ligne['quantite'] < 1) {
                $erreurs['medicaments'] = 'La quantité prescrite doit être un nombre entier positif pour chaque médicament sélectionné.';
                break;
            }
        }

        return $erreurs;
    }
}
