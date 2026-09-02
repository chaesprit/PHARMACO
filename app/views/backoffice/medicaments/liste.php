<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <?php require VUES_DIR . 'partials/polices.php'; ?>
    <title>Gestion des médicaments — Système de Gestion de Pharmacie</title>
</head>
<body>
    <?php require VUES_DIR . 'partials/navigation.php'; ?>
    <h1>Gestion des médicaments</h1>

    <p><a href="index.php?controller=Medicament&action=creer">Ajouter un médicament</a></p>

    <form method="get" action="index.php" id="formulaire-recherche-medicaments" class="formulaire-recherche">
        <input type="hidden" name="controller" value="Medicament">
        <input type="hidden" name="action" value="liste">

        <div>
            <label for="recherche-nom">Nom</label>
            <input type="text" id="recherche-nom" name="nom" value="<?= htmlspecialchars($criteres['nom']) ?>" placeholder="ex. Amoxicilline">
        </div>

        <div>
            <label for="recherche-categorie">Catégorie</label>
            <input type="text" id="recherche-categorie" name="categorie" value="<?= htmlspecialchars($criteres['categorie']) ?>" placeholder="ex. Antibiotique">
        </div>

        <div>
            <label for="recherche-fabricant">Fabricant</label>
            <input type="text" id="recherche-fabricant" name="fabricant" value="<?= htmlspecialchars($criteres['fabricant']) ?>" placeholder="ex. SIPHAT">
        </div>

        <div class="formulaire-recherche-actions">
            <button type="submit">Rechercher</button>
            <?php if ($criteres['nom'] !== '' || $criteres['categorie'] !== '' || $criteres['fabricant'] !== ''): ?>
                <a href="index.php?controller=Medicament&action=liste">Réinitialiser</a>
            <?php endif; ?>
        </div>
    </form>

    <?php if (empty($medicaments) && ($criteres['nom'] !== '' || $criteres['categorie'] !== '' || $criteres['fabricant'] !== '')): ?>
        <p>Aucun médicament ne correspond à ces critères.</p>
    <?php endif; ?>

    <table id="tableau-medicaments">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Catégorie</th>
                <th>Fabricant</th>
                <th>Prix</th>
                <th>Stock</th>
                <th>Seuil critique</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($medicaments as $medicament): ?>
                <tr<?= $medicament['quantite_stock'] <= $medicament['seuil_critique'] ? ' class="stock-critique"' : '' ?>>
                    <td><?= htmlspecialchars($medicament['nom']) ?></td>
                    <td><?= htmlspecialchars($medicament['categorie'] ?? '') ?></td>
                    <td><?= htmlspecialchars($medicament['fabricant'] ?? '') ?></td>
                    <td><?= htmlspecialchars(number_format((float) $medicament['prix'], 2)) ?></td>
                    <td><?= (int) $medicament['quantite_stock'] ?></td>
                    <td><?= (int) $medicament['seuil_critique'] ?></td>
                    <td>
                        <a href="index.php?controller=Medicament&action=modifier&id=<?= (int) $medicament['id_medicament'] ?>">Modifier</a>
                        <form method="post" action="index.php?controller=Medicament&action=supprimer&id=<?= (int) $medicament['id_medicament'] ?>" class="formulaire-suppression">
                            <button type="submit">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <script src="js/gestion-medicament.js"></script>
</body>
</html>
