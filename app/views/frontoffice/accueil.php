<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <?php require VUES_DIR . 'partials/polices.php'; ?>
    <title><?= htmlspecialchars($titre) ?></title>
</head>
<body>
    <?php require VUES_DIR . 'partials/navigation.php'; ?>

    <?php $role = $_SESSION['user_role'] ?? null; ?>

    <?php if ($role === null): ?>

        <section class="hero">
            <div class="hero-marque"><?php require VUES_DIR . 'partials/logo.php'; ?></div>
            <p class="hero-texte">
                Gérez les médicaments, les ordonnances, les interactions et les
                expéditions d'une pharmacie, du dépôt d'une ordonnance par le
                client jusqu'à sa validation et le suivi du stock.
            </p>
            <p class="hero-actions">
                <a class="bouton bouton-principal" href="index.php?controller=Utilisateur&action=connexion">Se connecter</a>
                <a class="bouton bouton-secondaire" href="index.php?controller=Utilisateur&action=inscription">Créer un compte</a>
            </p>
        </section>

        <ul class="cartes-accueil">
            <li class="carte-accueil">
                <h2>Client</h2>
                <p>Soumettez vos ordonnances, suivez leur statut, renouvelez celles qui sont validées et consultez les interactions médicamenteuses connues.</p>
            </li>
            <li class="carte-accueil">
                <h2>Pharmacien</h2>
                <p>Gérez le catalogue de médicaments, validez ou rejetez les ordonnances, enregistrez les interactions et réceptionnez les expéditions.</p>
            </li>
            <li class="carte-accueil">
                <h2>Responsable</h2>
                <p>Tout ce que fait le pharmacien, plus la gestion des comptes utilisateurs et le rapport d'expéditions.</p>
            </li>
        </ul>

    <?php elseif ($role === 'client'): ?>

        <h1>Bonjour, <?= htmlspecialchars($_SESSION['user_nom']) ?></h1>

        <ul class="cartes-accueil">
            <li class="carte-accueil">
                <h2><a href="index.php?controller=Ordonnance&action=soumettre">Soumettre une ordonnance</a></h2>
                <p>Choisissez les médicaments et indiquez la posologie.</p>
            </li>
            <li class="carte-accueil">
                <h2><a href="index.php?controller=Ordonnance&action=mesOrdonnances">Mes ordonnances</a></h2>
                <p>Suivez le statut de vos demandes, annulez ou renouvelez.</p>
            </li>
            <li class="carte-accueil">
                <h2><a href="index.php?controller=Interaction&action=consulter">Interactions médicamenteuses</a></h2>
                <p>Consultez les interactions connues entre médicaments.</p>
            </li>
        </ul>

    <?php elseif ($role === 'pharmacien' || $role === 'responsable'): ?>

        <h1>Bonjour, <?= htmlspecialchars($_SESSION['user_nom']) ?></h1>

        <ul id="tableau-de-bord" class="rangee-stats">
            <li>
                <a href="index.php?controller=Medicament&action=liste">
                    <span class="stat-chiffre"><?= (int) $stockCritique ?> / <?= (int) $totalMedicaments ?></span>
                    <span class="stat-libelle">Médicaments en stock critique</span>
                </a>
            </li>
            <li>
                <a href="index.php?controller=Ordonnance&action=liste">
                    <span class="stat-chiffre"><?= (int) $ordonnancesEnAttente ?></span>
                    <span class="stat-libelle">Ordonnances en attente de traitement</span>
                </a>
            </li>
        </ul>

    <?php endif; ?>
</body>
</html>
