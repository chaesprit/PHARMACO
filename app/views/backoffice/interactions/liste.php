<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <?php require VUES_DIR . 'partials/polices.php'; ?>
    <title>Interactions médicamenteuses — Système de Gestion de Pharmacie</title>
</head>
<body>
    <?php require VUES_DIR . 'partials/navigation.php'; ?>
    <h1>Interactions médicamenteuses</h1>

    <p><a href="index.php?controller=Interaction&action=creer">Enregistrer une interaction</a></p>

    <?php if (empty($interactions)): ?>
        <p>Aucune interaction médicamenteuse enregistrée.</p>
    <?php else: ?>
        <table id="tableau-interactions">
            <thead>
                <tr>
                    <th>Médicament 1</th>
                    <th>Médicament 2</th>
                    <th>Description</th>
                    <th>Gravité</th>
                    <th>Enregistrée par</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($interactions as $interaction): ?>
                    <tr>
                        <td><?= htmlspecialchars($interaction['medicament_1_nom']) ?></td>
                        <td><?= htmlspecialchars($interaction['medicament_2_nom']) ?></td>
                        <td><?= htmlspecialchars($interaction['description_interaction']) ?></td>
                        <td><span class="etat etat-gravite-<?= htmlspecialchars($interaction['niveau_gravite']) ?>"><?= htmlspecialchars($interaction['niveau_gravite']) ?></span></td>
                        <td><?= htmlspecialchars($interaction['pharmacien_prenom'] . ' ' . $interaction['pharmacien_nom']) ?></td>
                        <td><?= htmlspecialchars($interaction['date_enregistrement']) ?></td>
                        <td>
                            <form method="post" action="index.php?controller=Interaction&action=supprimer&id=<?= (int) $interaction['id_interaction'] ?>" class="formulaire-suppression">
                                <button type="submit">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <script src="js/gestion-interaction.js"></script>
</body>
</html>
