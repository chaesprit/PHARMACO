<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <?php require VUES_DIR . 'partials/polices.php'; ?>
    <title>Mes ordonnances — Système de Gestion de Pharmacie</title>
</head>
<body>
    <?php require VUES_DIR . 'partials/navigation.php'; ?>
    <h1>Mes ordonnances</h1>

    <p><a href="index.php?controller=Ordonnance&action=soumettre">Soumettre une nouvelle ordonnance</a></p>

    <?php if (empty($ordonnances)): ?>
        <p>Vous n'avez soumis aucune ordonnance.</p>
    <?php else: ?>
        <?php foreach ($ordonnances as $ordonnance): ?>
            <article class="carte-ordonnance">
                <h2>Ordonnance #<?= (int) $ordonnance['id_ordonnance'] ?></h2>

                <?php if ($ordonnance['est_renouvellement']): ?>
                    <p class="badge-renouvellement">Renouvellement de l'ordonnance #<?= (int) $ordonnance['id_ordonnance_originale'] ?></p>
                <?php endif; ?>

                <p>Soumise le <?= htmlspecialchars($ordonnance['date_soumission']) ?></p>
                <p class="ligne-statut">
                    <span class="timbre timbre-<?= htmlspecialchars($ordonnance['statut']) ?>"><?= htmlspecialchars(str_replace('_', ' ', $ordonnance['statut'])) ?></span>
                </p>

                <?php if ($ordonnance['date_validation']): ?>
                    <p>Traitée le <?= htmlspecialchars($ordonnance['date_validation']) ?></p>
                <?php endif; ?>

                <?php if (!empty($ordonnance['commentaire'])): ?>
                    <p>Commentaire : <?= htmlspecialchars($ordonnance['commentaire']) ?></p>
                <?php endif; ?>

                <ul>
                    <?php foreach ($ordonnance['medicaments'] as $ligne): ?>
                        <li>
                            <?= htmlspecialchars($ligne['nom']) ?>
                            — quantité <?= (int) $ligne['quantite_prescrite'] ?>
                            <?php if (!empty($ligne['posologie'])): ?>
                                (<?= htmlspecialchars($ligne['posologie']) ?>)
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <?php if ($ordonnance['statut'] === 'en_attente'): ?>
                    <form method="post" action="index.php?controller=Ordonnance&action=annuler&id=<?= (int) $ordonnance['id_ordonnance'] ?>" class="formulaire-annulation">
                        <button type="submit">Annuler cette ordonnance</button>
                    </form>
                <?php elseif ($ordonnance['statut'] === 'validee'): ?>
                    <form method="post" action="index.php?controller=Ordonnance&action=renouveler&id=<?= (int) $ordonnance['id_ordonnance'] ?>" class="formulaire-renouvellement">
                        <button type="submit">Renouveler cette ordonnance</button>
                    </form>
                <?php endif; ?>
            </article>
        <?php endforeach; ?>
    <?php endif; ?>

    <script src="js/mes-ordonnances.js"></script>
</body>
</html>
