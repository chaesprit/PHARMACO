<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <?php require VUES_DIR . 'partials/polices.php'; ?>
    <title>Interactions médicamenteuses — Système de Gestion de Pharmacie</title>
</head>
<body>
    <?php require VUES_DIR . 'partials/navigation.php'; ?>
    <h1>Interactions médicamenteuses connues</h1>

    <?php if (empty($interactions)): ?>
        <p>Aucune interaction médicamenteuse n'est enregistrée pour le moment.</p>
    <?php else: ?>
        <table id="tableau-interactions-client">
            <thead>
                <tr>
                    <th>Médicament 1</th>
                    <th>Médicament 2</th>
                    <th>Description</th>
                    <th>Gravité</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($interactions as $interaction): ?>
                    <tr>
                        <td><?= htmlspecialchars($interaction['medicament_1_nom']) ?></td>
                        <td><?= htmlspecialchars($interaction['medicament_2_nom']) ?></td>
                        <td><?= htmlspecialchars($interaction['description_interaction']) ?></td>
                        <td><span class="etat etat-gravite-<?= htmlspecialchars($interaction['niveau_gravite']) ?>"><?= htmlspecialchars($interaction['niveau_gravite']) ?></span></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>
