<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <?php require VUES_DIR . 'partials/polices.php'; ?>
    <title><?= htmlspecialchars($titre) ?></title>
</head>
<body>
    <?php require VUES_DIR . 'partials/navigation.php'; ?>

    <h1><?= htmlspecialchars($titre) ?></h1>

    <?php $role = $_SESSION['user_role'] ?? null; ?>

    <?php if ($role === null): ?>
        <p>Bienvenue. Connectez-vous ou inscrivez-vous pour soumettre une ordonnance.</p>
    <?php elseif ($role === 'client'): ?>
        <p>Bienvenue, <?= htmlspecialchars($_SESSION['user_nom']) ?>. Depuis ce compte, vous pouvez soumettre une ordonnance et suivre son statut.</p>
    <?php elseif ($role === 'pharmacien' || $role === 'responsable'): ?>
        <p>Bienvenue, <?= htmlspecialchars($_SESSION['user_nom']) ?>.</p>

        <ul id="tableau-de-bord" class="rangee-stats">
            <li>
                <a href="index.php?controller=Medicament&action=liste">
                    Médicaments en stock critique : <?= (int) $stockCritique ?> / <?= (int) $totalMedicaments ?>
                </a>
            </li>
            <li>
                <a href="index.php?controller=Ordonnance&action=liste">
                    Ordonnances en attente de traitement : <?= (int) $ordonnancesEnAttente ?>
                </a>
            </li>
        </ul>
    <?php endif; ?>
</body>
</html>
