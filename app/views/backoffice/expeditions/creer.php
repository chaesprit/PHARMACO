<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <?php require VUES_DIR . 'partials/polices.php'; ?>
    <title>Enregistrer une expédition — Système de Gestion de Pharmacie</title>
</head>
<body>
    <?php require VUES_DIR . 'partials/navigation.php'; ?>
    <h1>Enregistrer une expédition</h1>

    <p><a href="index.php?controller=Expedition&action=liste">Retour à la liste</a></p>

    <?php if (empty($medicaments)): ?>
        <p>Aucun médicament enregistré pour le moment.</p>
    <?php else: ?>
        <?php if (!empty($erreurs)): ?>
            <ul id="liste-erreurs">
                <?php foreach ($erreurs as $erreur): ?>
                    <li><?= htmlspecialchars($erreur) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <form method="post" action="index.php?controller=Expedition&action=creer" id="formulaire-expedition" novalidate>
            <div>
                <label for="id_medicament">Médicament</label>
                <select id="id_medicament" name="id_medicament">
                    <option value="">— Choisir —</option>
                    <?php foreach ($medicaments as $medicament): ?>
                        <option value="<?= (int) $medicament['id_medicament'] ?>" <?= (string) $donnees['id_medicament'] === (string) $medicament['id_medicament'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($medicament['nom']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label for="quantite">Quantité</label>
                <input type="text" id="quantite" name="quantite" value="<?= htmlspecialchars($donnees['quantite']) ?>">
            </div>

            <div>
                <label for="fournisseur">Fournisseur (optionnel)</label>
                <input type="text" id="fournisseur" name="fournisseur" value="<?= htmlspecialchars($donnees['fournisseur']) ?>">
            </div>

            <p id="message-js" role="alert"></p>

            <button type="submit">Enregistrer l'expédition</button>
        </form>
    <?php endif; ?>

    <script src="js/gestion-expedition.js"></script>
</body>
</html>
