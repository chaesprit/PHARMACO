<?php

/**
 * Model de l'entité Ordonnance.
 * Une ordonnance regroupe un ou plusieurs médicaments (table de jointure
 * ordonnance_medicament) et suit un statut : en_attente -> validee/rejetee.
 * Un renouvellement est une nouvelle ordonnance qui référence l'originale
 * via id_ordonnance_originale et réutilise le même cycle de statut.
 */
class Ordonnance extends Model
{
    /**
     * @param array<int, array{id_medicament:int, quantite:int, posologie:string}> $medicaments
     */
    public function creer(int $idClient, ?string $commentaire, array $medicaments): int
    {
        $this->db->beginTransaction();

        try {
            $stmt = $this->db->prepare('INSERT INTO ordonnance (id_client, commentaire) VALUES (:id_client, :commentaire)');
            $stmt->execute([
                'id_client' => $idClient,
                'commentaire' => $commentaire,
            ]);

            $idOrdonnance = (int) $this->db->lastInsertId();

            $stmtLigne = $this->db->prepare(
                'INSERT INTO ordonnance_medicament (id_ordonnance, id_medicament, quantite_prescrite, posologie)
                 VALUES (:id_ordonnance, :id_medicament, :quantite, :posologie)'
            );

            foreach ($medicaments as $ligne) {
                $stmtLigne->execute([
                    'id_ordonnance' => $idOrdonnance,
                    'id_medicament' => $ligne['id_medicament'],
                    'quantite' => $ligne['quantite'],
                    'posologie' => $ligne['posologie'] !== '' ? $ligne['posologie'] : null,
                ]);
            }

            $this->db->commit();

            return $idOrdonnance;
        } catch (Throwable $exception) {
            $this->db->rollBack();
            throw $exception;
        }
    }

    public function trouverParId(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM ordonnance WHERE id_ordonnance = :id');
        $stmt->execute(['id' => $id]);

        $resultat = $stmt->fetch();

        return $resultat ?: null;
    }

    public function medicamentsDeOrdonnance(int $idOrdonnance): array
    {
        $stmt = $this->db->prepare(
            'SELECT om.quantite_prescrite, om.posologie, m.id_medicament, m.nom
             FROM ordonnance_medicament om
             JOIN medicament m ON m.id_medicament = om.id_medicament
             WHERE om.id_ordonnance = :id'
        );
        $stmt->execute(['id' => $idOrdonnance]);

        return $stmt->fetchAll();
    }

    public function toutesLesOrdonnances(): array
    {
        return $this->db->query(
            'SELECT o.*, u.nom AS client_nom, u.prenom AS client_prenom
             FROM ordonnance o
             JOIN utilisateur u ON u.id_utilisateur = o.id_client
             ORDER BY o.date_soumission DESC'
        )->fetchAll();
    }

    public function ordonnancesParClient(int $idClient): array
    {
        $stmt = $this->db->prepare('SELECT * FROM ordonnance WHERE id_client = :id ORDER BY date_soumission DESC');
        $stmt->execute(['id' => $idClient]);

        return $stmt->fetchAll();
    }

    public function valider(int $id, int $idPharmacien, string $statut): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE ordonnance
             SET statut = :statut, id_pharmacien_validateur = :id_pharmacien, date_validation = NOW()
             WHERE id_ordonnance = :id'
        );

        return $stmt->execute([
            'statut' => $statut,
            'id_pharmacien' => $idPharmacien,
            'id' => $id,
        ]);
    }

    public function supprimer(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM ordonnance WHERE id_ordonnance = :id');

        return $stmt->execute(['id' => $id]);
    }

    public function compterParStatut(string $statut): int
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM ordonnance WHERE statut = :statut');
        $stmt->execute(['statut' => $statut]);

        return (int) $stmt->fetchColumn();
    }

    /**
     * Renouvellement : nouvelle ordonnance en_attente qui reprend les
     * médicaments d'une ordonnance déjà validée, en la référençant via
     * id_ordonnance_originale (voir sql/schema.sql).
     */
    public function creerRenouvellement(int $idOrdonnanceOriginale, int $idClient): int
    {
        $medicaments = $this->medicamentsDeOrdonnance($idOrdonnanceOriginale);

        $this->db->beginTransaction();

        try {
            $stmt = $this->db->prepare(
                'INSERT INTO ordonnance (id_client, commentaire, est_renouvellement, id_ordonnance_originale)
                 VALUES (:id_client, :commentaire, TRUE, :id_originale)'
            );
            $stmt->execute([
                'id_client' => $idClient,
                'commentaire' => 'Renouvellement de l\'ordonnance #' . $idOrdonnanceOriginale,
                'id_originale' => $idOrdonnanceOriginale,
            ]);

            $idNouvelleOrdonnance = (int) $this->db->lastInsertId();

            $stmtLigne = $this->db->prepare(
                'INSERT INTO ordonnance_medicament (id_ordonnance, id_medicament, quantite_prescrite, posologie)
                 VALUES (:id_ordonnance, :id_medicament, :quantite, :posologie)'
            );

            foreach ($medicaments as $ligne) {
                $stmtLigne->execute([
                    'id_ordonnance' => $idNouvelleOrdonnance,
                    'id_medicament' => $ligne['id_medicament'],
                    'quantite' => $ligne['quantite_prescrite'],
                    'posologie' => $ligne['posologie'],
                ]);
            }

            $this->db->commit();

            return $idNouvelleOrdonnance;
        } catch (Throwable $exception) {
            $this->db->rollBack();
            throw $exception;
        }
    }
}
