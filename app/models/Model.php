<?php

/**
 * Classe mère de tous les Models.
 * Expose la connexion PDO partagée à toutes les classes filles
 * (Utilisateur, Medicament, Ordonnance, ...).
 */
abstract class Model
{
    protected PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }
}
