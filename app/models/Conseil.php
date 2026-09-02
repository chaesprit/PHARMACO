<?php

/**
 * Model de la table métier `conseil` : conseils santé rédigés par un
 * pharmacien et consultés en lecture par les clients et les visiteurs.
 * La liste et le détail joignent l'auteur (table utilisateur).
 */
class Conseil extends Model
{
    public function creer(array $donnees): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO conseil (titre, contenu, id_auteur)
             VALUES (:titre, :contenu, :id_auteur)'
        );
        $stmt->execute([
            'titre' => $donnees['titre'],
            'contenu' => $donnees['contenu'],
            'id_auteur' => $donnees['id_auteur'],
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function mettreAJour(int $id, array $donnees): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE conseil
             SET titre = :titre, contenu = :contenu, date_maj = NOW()
             WHERE id_conseil = :id'
        );

        return $stmt->execute([
            'titre' => $donnees['titre'],
            'contenu' => $donnees['contenu'],
            'id' => $id,
        ]);
    }

    public function supprimer(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM conseil WHERE id_conseil = :id');

        return $stmt->execute(['id' => $id]);
    }

    public function trouverParId(int $id): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT c.*, u.nom AS auteur_nom, u.prenom AS auteur_prenom
             FROM conseil c
             JOIN utilisateur u ON u.id_utilisateur = c.id_auteur
             WHERE c.id_conseil = :id'
        );
        $stmt->execute(['id' => $id]);

        $resultat = $stmt->fetch();

        return $resultat ?: null;
    }

    public function tousLesConseils(): array
    {
        return $this->db->query(
            'SELECT c.*, u.nom AS auteur_nom, u.prenom AS auteur_prenom
             FROM conseil c
             JOIN utilisateur u ON u.id_utilisateur = c.id_auteur
             ORDER BY c.date_publication DESC'
        )->fetchAll();
    }
}
