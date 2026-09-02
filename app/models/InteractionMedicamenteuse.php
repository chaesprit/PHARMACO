<?php

/**
 * Model de la table métier `interaction_medicamenteuse` (section 18 du
 * cahier des charges). Enregistrée par le Pharmacien, consultée par le
 * Client — voir sql/schema.sql.
 */
class InteractionMedicamenteuse extends Model
{
    public function creer(array $donnees): int
    {
        $sql = 'INSERT INTO interaction_medicamenteuse
                    (id_medicament_1, id_medicament_2, description_interaction, niveau_gravite, id_pharmacien)
                VALUES (:id_medicament_1, :id_medicament_2, :description, :niveau_gravite, :id_pharmacien)';

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'id_medicament_1' => $donnees['id_medicament_1'],
            'id_medicament_2' => $donnees['id_medicament_2'],
            'description'     => $donnees['description'],
            'niveau_gravite'  => $donnees['niveau_gravite'],
            'id_pharmacien'   => $donnees['id_pharmacien'],
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function trouverParId(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM interaction_medicamenteuse WHERE id_interaction = :id');
        $stmt->execute(['id' => $id]);

        $resultat = $stmt->fetch();

        return $resultat ?: null;
    }

    public function toutesLesInteractions(): array
    {
        return $this->db->query(
            'SELECT i.*, m1.nom AS medicament_1_nom, m2.nom AS medicament_2_nom,
                    u.nom AS pharmacien_nom, u.prenom AS pharmacien_prenom
             FROM interaction_medicamenteuse i
             JOIN medicament m1 ON m1.id_medicament = i.id_medicament_1
             JOIN medicament m2 ON m2.id_medicament = i.id_medicament_2
             JOIN utilisateur u ON u.id_utilisateur = i.id_pharmacien
             ORDER BY i.date_enregistrement DESC'
        )->fetchAll();
    }

    public function supprimer(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM interaction_medicamenteuse WHERE id_interaction = :id');

        return $stmt->execute(['id' => $id]);
    }
}
