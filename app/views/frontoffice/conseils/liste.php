<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <?php require VUES_DIR . 'partials/polices.php'; ?>
    <title>Conseils santé — Système de Gestion de Pharmacie</title>
</head>
<body>
    <?php require VUES_DIR . 'partials/navigation.php'; ?>
    <h1>Conseils santé</h1>

    <?php $role = $_SESSION['user_role'] ?? null; ?>

    <?php if ($role === 'pharmacien'): ?>
        <p><a href="index.php?controller=Conseil&action=creer">Publier un conseil</a></p>
    <?php endif; ?>

    <?php if (empty($conseils)): ?>
        <p>Aucun conseil santé n'a encore été publié.</p>
    <?php else: ?>
        <?php foreach ($conseils as $conseil): ?>
            <article class="carte-conseil">
                <h2>
                    <a href="index.php?controller=Conseil&action=voir&id=<?= (int) $conseil['id_conseil'] ?>">
                        <?= htmlspecialchars($conseil['titre']) ?>
                    </a>
                </h2>
                <p class="conseil-meta">
                    Par <?= htmlspecialchars($conseil['auteur_prenom'] . ' ' . $conseil['auteur_nom']) ?>
                    — <?= htmlspecialchars($conseil['date_publication']) ?>
                </p>
                <p><?= htmlspecialchars(mb_strimwidth($conseil['contenu'], 0, 180, '…')) ?></p>

                <?php if ($role === 'pharmacien' || $role === 'responsable'): ?>
                    <p class="conseil-actions">
                        <?php if ($role === 'pharmacien'): ?>
                            <a href="index.php?controller=Conseil&action=modifier&id=<?= (int) $conseil['id_conseil'] ?>">Modifier</a>
                        <?php endif; ?>
                        <form method="post" action="index.php?controller=Conseil&action=supprimer&id=<?= (int) $conseil['id_conseil'] ?>" class="formulaire-suppression">
                            <button type="submit">Supprimer</button>
                        </form>
                    </p>
                <?php endif; ?>
            </article>
        <?php endforeach; ?>
    <?php endif; ?>

    <script src="js/gestion-conseil.js"></script>
</body>
</html>
