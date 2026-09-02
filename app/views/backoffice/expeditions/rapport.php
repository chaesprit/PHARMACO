<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <?php require VUES_DIR . 'partials/polices.php'; ?>
    <title>Rapport des expéditions — Système de Gestion de Pharmacie</title>
</head>
<body>
    <?php require VUES_DIR . 'partials/navigation.php'; ?>
    <h1>Rapport des expéditions</h1>

    <p><a href="index.php?controller=Expedition&action=liste">Retour à la liste</a></p>

    <h2>Par statut</h2>
    <ul id="rapport-par-statut" class="rangee-stats">
        <li>En cours : <?= (int) $parStatut['en_cours'] ?></li>
        <li>Livrées : <?= (int) $parStatut['livree'] ?></li>
        <li>Annulées : <?= (int) $parStatut['annulee'] ?></li>
    </ul>

    <h2>Quantités reçues par médicament (expéditions livrées)</h2>
    <?php if (empty($parMedicament)): ?>
        <p>Aucune expédition livrée pour le moment.</p>
    <?php else: ?>
        <table id="tableau-rapport-medicament">
            <thead>
                <tr>
                    <th>Médicament</th>
                    <th>Quantité totale reçue</th>
                    <th>Nombre d'expéditions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($parMedicament as $ligne): ?>
                    <tr>
                        <td><?= htmlspecialchars($ligne['nom']) ?></td>
                        <td><?= (int) $ligne['quantite_totale'] ?></td>
                        <td><?= (int) $ligne['nombre_expeditions'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>
