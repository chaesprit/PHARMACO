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
    <a href="index.php" id="lien-marque" aria-label="Accueil PHARMACO"><?php require VUES_DIR . 'partials/logo.php'; ?></a>

    <a href="index.php">Accueil</a>

    <?php if (!$estConnecte): ?>
        <a href="index.php?controller=Utilisateur&action=connexion">Connexion</a>
        <a href="index.php?controller=Utilisateur&action=inscription">Inscription</a>
    <?php else: ?>
        <?php if ($roleConnecte === 'client'): ?>
            <a href="index.php?controller=Ordonnance&action=soumettre">Soumettre une ordonnance</a>
            <a href="index.php?controller=Ordonnance&action=mesOrdonnances">Mes ordonnances</a>
            <a href="index.php?controller=Interaction&action=consulter">Interactions médicamenteuses</a>
        <?php elseif ($roleConnecte === 'pharmacien'): ?>
            <a href="index.php?controller=Medicament&action=liste">Médicaments</a>
            <a href="index.php?controller=Ordonnance&action=liste">Ordonnances</a>
            <a href="index.php?controller=Interaction&action=liste">Interactions médicamenteuses</a>
            <a href="index.php?controller=Expedition&action=liste">Expéditions</a>
        <?php elseif ($roleConnecte === 'responsable'): ?>
            <a href="index.php?controller=Utilisateur&action=liste">Utilisateurs</a>
            <a href="index.php?controller=Medicament&action=liste">Médicaments</a>
            <a href="index.php?controller=Ordonnance&action=liste">Ordonnances</a>
            <a href="index.php?controller=Interaction&action=liste">Interactions médicamenteuses</a>
            <a href="index.php?controller=Expedition&action=liste">Expéditions</a>
            <a href="index.php?controller=Expedition&action=rapport">Rapport</a>
        <?php endif; ?>

        <span id="utilisateur-connecte">Connecté : <?= htmlspecialchars($_SESSION['user_nom']) ?> (<?= htmlspecialchars($roleConnecte) ?>)</span>
        <a href="index.php?controller=Utilisateur&action=deconnexion">Se déconnecter</a>
    <?php endif; ?>
</nav>
