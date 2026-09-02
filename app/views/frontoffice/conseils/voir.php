<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <?php require VUES_DIR . 'partials/polices.php'; ?>
    <title><?= htmlspecialchars($conseil['titre']) ?> — Conseils santé</title>
</head>
<body>
    <?php require VUES_DIR . 'partials/navigation.php'; ?>

    <p><a href="index.php?controller=Conseil&action=liste">Retour aux conseils</a></p>

    <h1><?= htmlspecialchars($conseil['titre']) ?></h1>

    <p class="conseil-meta">
        Par <?= htmlspecialchars($conseil['auteur_prenom'] . ' ' . $conseil['auteur_nom']) ?>
        — publié le <?= htmlspecialchars($conseil['date_publication']) ?>
        <?php if (!empty($conseil['date_maj'])): ?>
            — mis à jour le <?= htmlspecialchars($conseil['date_maj']) ?>
        <?php endif; ?>
    </p>

    <div class="conseil-contenu">
        <?= nl2br(htmlspecialchars($conseil['contenu'])) ?>
    </div>
</body>
</html>
