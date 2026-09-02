<?php

/**
 * Barre de navigation commune à toutes les vues, adaptée au rôle de
 * l'utilisateur connecté (ou non connecté). Incluse en haut de chaque
 * page via VUES_DIR . 'partials/navigation.php' (voir Controller::render()).
 * Les liens propres à chaque module seront ajoutés au fur et à mesure
 * que les modules correspondants sont implémentés.
 */
$estConnecte = isset($_SESSION['user_id']);
$roleConnecte = $_SESSION['user_role'] ?? null;
?>
<nav id="navigation-principale">
    <a href="index.php">Accueil</a>

    <?php if (!$estConnecte): ?>
        <a href="index.php?controller=Utilisateur&action=connexion">Connexion</a>
        <a href="index.php?controller=Utilisateur&action=inscription">Inscription</a>
    <?php else: ?>
        <span id="utilisateur-connecte">Connecté : <?= htmlspecialchars($_SESSION['user_nom']) ?> (<?= htmlspecialchars($roleConnecte) ?>)</span>
        <a href="index.php?controller=Utilisateur&action=deconnexion">Se déconnecter</a>
    <?php endif; ?>
</nav>
