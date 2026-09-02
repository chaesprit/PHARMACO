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
    <?php else: ?>
        <p>Bienvenue, <?= htmlspecialchars($_SESSION['user_nom']) ?>.</p>
    <?php endif; ?>
</body>
</html>
