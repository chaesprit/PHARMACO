<?php

/**
 * Model de la table métier `expedition` (section 20 du cahier des charges).
 * Une expédition livrée incrémente automatiquement le stock du médicament
 * concerné ; c'est la seule voie de réapprovisionnement du stock.
 */
class Expedition extends Model
{
    public function creer(array $donnees): int
    {
        $sql = 'INSERT INTO expedition (id_medicament, quantite, fournisseur, statut)
                VALUES (:id_medicament, :quantite, :fournisseur, :statut)';

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'id_medicament' => $donnees['id_medicament'],
            'quantite'      => $donnees['quantite'],
            'fournisseur'   => $donnees['fournisseur'] ?? null,
            'statut'        => $donnees['statut'] ?? 'en_cours',
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function trouverParId(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM expedition WHERE id_expedition = :id');
        $stmt->execute(['id' => $id]);

        $resultat = $stmt->fetch();

        return $resultat ?: null;
    }

    public function toutesLesExpeditions(): array
    {
        return $this->db->query(
            'SELECT e.*, m.nom AS medicament_nom
             FROM expedition e
             JOIN medicament m ON m.id_medicament = e.id_medicament
             ORDER BY e.date_expedition DESC'
        )->fetchAll();
    }

    /**
     * Transition de statut, uniquement possible depuis 'en_cours' (statut
     * terminal sinon). Le passage à 'livree' incrémente le stock du
     * médicament concerné, dans la même transaction — une seule fois,
     * puisqu'on ne peut plus repartir de 'en_cours' après coup.
     */
    public function changerStatut(int $id, string $nouveauStatut): bool
    {
        $expedition = $this->trouverParId($id);

        if (!$expedition || $expedition['statut'] !== 'en_cours') {
            return false;
        }

        $this->db->beginTransaction();

        try {
            $stmt = $this->db->prepare('UPDATE expedition SET statut = :statut WHERE id_expedition = :id');
            $stmt->execute(['statut' => $nouveauStatut, 'id' => $id]);

            if ($nouveauStatut === 'livree') {
                $stmtStock = $this->db->prepare(
                    'UPDATE medicament SET quantite_stock = quantite_stock + :quantite WHERE id_medicament = :id_medicament'
                );
                $stmtStock->execute([
                    'quantite' => $expedition['quantite'],
                    'id_medicament' => $expedition['id_medicament'],
                ]);
            }

            $this->db->commit();

            return true;
        } catch (Throwable $exception) {
            $this->db->rollBack();
            throw $exception;
        }
    }

    /**
     * Suppression réservée aux expéditions non livrées : une expédition
     * livrée a déjà modifié le stock, la supprimer sans compensation
     * fausserait l'inventaire.
     */
    public function supprimer(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM expedition WHERE id_expedition = :id AND statut != 'livree'");

        return $stmt->execute(['id' => $id]);
    }

}
