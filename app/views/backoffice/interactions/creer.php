<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <?php require VUES_DIR . 'partials/polices.php'; ?>
    <title>Enregistrer une interaction — Système de Gestion de Pharmacie</title>
</head>
<body>
    <?php require VUES_DIR . 'partials/navigation.php'; ?>
    <h1>Enregistrer une interaction médicamenteuse</h1>

    <p><a href="index.php?controller=Interaction&action=liste">Retour à la liste</a></p>

    <?php if (empty($medicaments) || count($medicaments) < 2): ?>
        <p>Il faut au moins deux médicaments enregistrés pour déclarer une interaction.</p>
    <?php else: ?>
        <?php if (!empty($erreurs)): ?>
            <ul id="liste-erreurs">
                <?php foreach ($erreurs as $erreur): ?>
                    <li><?= htmlspecialchars($erreur) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <form method="post" action="index.php?controller=Interaction&action=creer" id="formulaire-interaction" novalidate>
            <div>
                <label for="id_medicament_1">Premier médicament</label>
                <select id="id_medicament_1" name="id_medicament_1">
                    <option value="">— Choisir —</option>
                    <?php foreach ($medicaments as $medicament): ?>
                        <option value="<?= (int) $medicament['id_medicament'] ?>" <?= (string) $donnees['id_medicament_1'] === (string) $medicament['id_medicament'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($medicament['nom']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label for="id_medicament_2">Second médicament</label>
                <select id="id_medicament_2" name="id_medicament_2">
                    <option value="">— Choisir —</option>
                    <?php foreach ($medicaments as $medicament): ?>
                        <option value="<?= (int) $medicament['id_medicament'] ?>" <?= (string) $donnees['id_medicament_2'] === (string) $medicament['id_medicament'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($medicament['nom']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label for="description">Description de l'interaction</label>
                <textarea id="description" name="description"><?= htmlspecialchars($donnees['description']) ?></textarea>
            </div>

            <div>
                <label for="niveau_gravite">Niveau de gravité</label>
                <select id="niveau_gravite" name="niveau_gravite">
                    <?php foreach (['faible', 'moderee', 'elevee'] as $niveau): ?>
                        <option value="<?= $niveau ?>" <?= $donnees['niveau_gravite'] === $niveau ? 'selected' : '' ?>><?= $niveau ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <p id="message-js" role="alert"></p>

            <button type="submit">Enregistrer</button>
        </form>
    <?php endif; ?>

    <script src="js/gestion-interaction.js"></script>
</body>
</html>
