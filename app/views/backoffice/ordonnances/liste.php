<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <?php require VUES_DIR . 'partials/polices.php'; ?>
    <title>Gestion des ordonnances — Système de Gestion de Pharmacie</title>
</head>
<body>
    <?php require VUES_DIR . 'partials/navigation.php'; ?>
    <h1>Gestion des ordonnances</h1>

    <?php if (empty($ordonnances)): ?>
        <p>Aucune ordonnance n'a été soumise.</p>
    <?php else: ?>
        <?php foreach ($ordonnances as $ordonnance): ?>
            <article class="carte-ordonnance">
                <h2>Ordonnance #<?= (int) $ordonnance['id_ordonnance'] ?></h2>

                <?php if ($ordonnance['est_renouvellement']): ?>
                    <p class="badge-renouvellement">Renouvellement de l'ordonnance #<?= (int) $ordonnance['id_ordonnance_originale'] ?></p>
                <?php endif; ?>

                <p>
                    Client : <?= htmlspecialchars($ordonnance['client_prenom'] . ' ' . $ordonnance['client_nom']) ?>
                    — soumise le <?= htmlspecialchars($ordonnance['date_soumission']) ?>
                </p>
                <p class="ligne-statut">
                    <span class="timbre timbre-<?= htmlspecialchars($ordonnance['statut']) ?>"><?= htmlspecialchars(str_replace('_', ' ', $ordonnance['statut'])) ?></span>
                </p>

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
                    <form method="post" action="index.php?controller=Ordonnance&action=traiter&id=<?= (int) $ordonnance['id_ordonnance'] ?>" class="formulaire-traitement">
                        <button type="submit" name="statut" value="validee">Valider</button>
                        <button type="submit" name="statut" value="rejetee">Rejeter</button>
                    </form>
                <?php endif; ?>

                <form method="post" action="index.php?controller=Ordonnance&action=supprimer&id=<?= (int) $ordonnance['id_ordonnance'] ?>" class="formulaire-suppression">
                    <button type="submit">Supprimer</button>
                </form>
            </article>
        <?php endforeach; ?>
    <?php endif; ?>

    <script src="js/gestion-ordonnance.js"></script>
</body>
</html>
