<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <?php require VUES_DIR . 'partials/polices.php'; ?>
    <title>Gestion des expéditions — Système de Gestion de Pharmacie</title>
</head>
<body>
    <?php require VUES_DIR . 'partials/navigation.php'; ?>
    <h1>Gestion des expéditions</h1>

    <p><a href="index.php?controller=Expedition&action=creer">Enregistrer une expédition</a></p>

    <?php if (empty($expeditions)): ?>
        <p>Aucune expédition enregistrée.</p>
    <?php else: ?>
        <table id="tableau-expeditions">
            <thead>
                <tr>
                    <th>Médicament</th>
                    <th>Quantité</th>
                    <th>Fournisseur</th>
                    <th>Statut</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($expeditions as $expedition): ?>
                    <tr>
                        <td><?= htmlspecialchars($expedition['medicament_nom']) ?></td>
                        <td><?= (int) $expedition['quantite'] ?></td>
                        <td><?= htmlspecialchars($expedition['fournisseur'] ?? '') ?></td>
                        <td><span class="etat etat-expedition-<?= htmlspecialchars($expedition['statut']) ?>"><?= htmlspecialchars(str_replace('_', ' ', $expedition['statut'])) ?></span></td>
                        <td><?= htmlspecialchars($expedition['date_expedition']) ?></td>
                        <td>
                            <?php if ($expedition['statut'] === 'en_cours'): ?>
                                <form method="post" action="index.php?controller=Expedition&action=changerStatut&id=<?= (int) $expedition['id_expedition'] ?>" class="formulaire-statut-expedition">
                                    <button type="submit" name="statut" value="livree">Marquer livrée</button>
                                    <button type="submit" name="statut" value="annulee">Annuler</button>
                                </form>
                            <?php else: ?>
                                <form method="post" action="index.php?controller=Expedition&action=supprimer&id=<?= (int) $expedition['id_expedition'] ?>" class="formulaire-suppression">
                                    <button type="submit">Supprimer</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <script src="js/gestion-expedition.js"></script>
</body>
</html>
