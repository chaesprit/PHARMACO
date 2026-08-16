<?php

/**
 * Model de l'entité Utilisateur.
 * Gère l'accès aux données et les opérations CRUD sur la table `utilisateur`.
 * Le mot de passe n'est jamais manipulé en clair : haché à la création,
 * vérifié via password_verify() à la connexion.
 */
class Utilisateur extends Model
{
    public function creer(array $donnees): int
    {
        $sql = 'INSERT INTO utilisateur (nom, prenom, email, mot_de_passe, role, telephone)
                VALUES (:nom, :prenom, :email, :mot_de_passe, :role, :telephone)';

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'nom'          => $donnees['nom'],
            'prenom'       => $donnees['prenom'],
            'email'        => $donnees['email'],
            'mot_de_passe' => password_hash($donnees['mot_de_passe'], PASSWORD_DEFAULT),
            'role'         => $donnees['role'],
            'telephone'    => $donnees['telephone'] ?? null,
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function trouverParId(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM utilisateur WHERE id_utilisateur = :id');
        $stmt->execute(['id' => $id]);

        $resultat = $stmt->fetch();

        return $resultat ?: null;
    }

    public function trouverParEmail(string $email): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM utilisateur WHERE email = :email');
        $stmt->execute(['email' => $email]);

        $resultat = $stmt->fetch();

        return $resultat ?: null;
    }

    public function emailExiste(string $email): bool
    {
        return $this->trouverParEmail($email) !== null;
    }

    public function tousLesUtilisateurs(): array
    {
        return $this->db->query('SELECT * FROM utilisateur ORDER BY date_creation DESC')->fetchAll();
    }

    public function mettreAJour(int $id, array $donnees): bool
    {
        $sql = 'UPDATE utilisateur
                SET nom = :nom, prenom = :prenom, email = :email,
                    role = :role, telephone = :telephone
                WHERE id_utilisateur = :id';

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'nom'       => $donnees['nom'],
            'prenom'    => $donnees['prenom'],
            'email'     => $donnees['email'],
            'role'      => $donnees['role'],
            'telephone' => $donnees['telephone'] ?? null,
            'id'        => $id,
        ]);
    }

    public function changerMotDePasse(int $id, string $nouveauMotDePasse): bool
    {
        $stmt = $this->db->prepare('UPDATE utilisateur SET mot_de_passe = :mot_de_passe WHERE id_utilisateur = :id');

        return $stmt->execute([
            'mot_de_passe' => password_hash($nouveauMotDePasse, PASSWORD_DEFAULT),
            'id'           => $id,
        ]);
    }

    public function supprimer(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM utilisateur WHERE id_utilisateur = :id');

        return $stmt->execute(['id' => $id]);
    }

    public function verifierMotDePasse(string $motDePasseSaisi, string $hashStocke): bool
    {
        return password_verify($motDePasseSaisi, $hashStocke);
    }
}
