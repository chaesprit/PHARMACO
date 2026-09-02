<?php

/**
 * Model de l'entité Médicament.
 * Gère l'accès aux données et les opérations CRUD sur la table `medicament`.
 * seuil_critique est paramétrable par médicament (voir sql/schema.sql).
 */
class Medicament extends Model
{
    public function creer(array $donnees): int
    {
        $sql = 'INSERT INTO medicament (nom, description, categorie, fabricant, prix, quantite_stock, seuil_critique)
                VALUES (:nom, :description, :categorie, :fabricant, :prix, :quantite_stock, :seuil_critique)';

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'nom'            => $donnees['nom'],
            'description'    => $donnees['description'] ?? null,
            'categorie'      => $donnees['categorie'] ?? null,
            'fabricant'      => $donnees['fabricant'] ?? null,
            'prix'           => $donnees['prix'],
            'quantite_stock' => $donnees['quantite_stock'],
            'seuil_critique' => $donnees['seuil_critique'],
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function trouverParId(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM medicament WHERE id_medicament = :id');
        $stmt->execute(['id' => $id]);

        $resultat = $stmt->fetch();

        return $resultat ?: null;
    }

    public function tousLesMedicaments(): array
    {
        return $this->db->query('SELECT * FROM medicament ORDER BY nom ASC')->fetchAll();
    }

    public function mettreAJour(int $id, array $donnees): bool
    {
        $sql = 'UPDATE medicament
                SET nom = :nom, description = :description, categorie = :categorie,
                    fabricant = :fabricant, prix = :prix, quantite_stock = :quantite_stock,
                    seuil_critique = :seuil_critique
                WHERE id_medicament = :id';

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'nom'            => $donnees['nom'],
            'description'    => $donnees['description'] ?? null,
            'categorie'      => $donnees['categorie'] ?? null,
            'fabricant'      => $donnees['fabricant'] ?? null,
            'prix'           => $donnees['prix'],
            'quantite_stock' => $donnees['quantite_stock'],
            'seuil_critique' => $donnees['seuil_critique'],
            'id'             => $id,
        ]);
    }

    public function supprimer(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM medicament WHERE id_medicament = :id');

        return $stmt->execute(['id' => $id]);
    }

    public function compterStockCritique(): int
    {
        return (int) $this->db->query(
            'SELECT COUNT(*) FROM medicament WHERE quantite_stock <= seuil_critique'
        )->fetchColumn();
    }

    public function medicamentsEnStockCritique(): array
    {
        return $this->db->query(
            'SELECT * FROM medicament WHERE quantite_stock <= seuil_critique ORDER BY nom ASC'
        )->fetchAll();
    }

    public function compterTous(): int
    {
        return (int) $this->db->query('SELECT COUNT(*) FROM medicament')->fetchColumn();
    }

    /**
     * Recherche multicritère (section 15 du cahier des charges) : les
     * critères non vides sont combinés avec AND. Chaque critère est
     * comparé par préfixe/contenu (LIKE) pour rester tolérant à la saisie.
     *
     * @param array{nom?:string, categorie?:string, fabricant?:string} $criteres
     */
    public function rechercher(array $criteres): array
    {
        $conditions = [];
        $parametres = [];

        if (($criteres['nom'] ?? '') !== '') {
            $conditions[] = 'nom LIKE :nom';
            $parametres['nom'] = '%' . $criteres['nom'] . '%';
        }

        if (($criteres['categorie'] ?? '') !== '') {
            $conditions[] = 'categorie LIKE :categorie';
            $parametres['categorie'] = '%' . $criteres['categorie'] . '%';
        }

        if (($criteres['fabricant'] ?? '') !== '') {
            $conditions[] = 'fabricant LIKE :fabricant';
            $parametres['fabricant'] = '%' . $criteres['fabricant'] . '%';
        }

        if (empty($conditions)) {
            return $this->tousLesMedicaments();
        }

        $sql = 'SELECT * FROM medicament WHERE ' . implode(' AND ', $conditions) . ' ORDER BY nom ASC';

        $stmt = $this->db->prepare($sql);
        $stmt->execute($parametres);

        return $stmt->fetchAll();
    }
}
