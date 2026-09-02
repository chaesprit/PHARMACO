<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <?php require VUES_DIR . 'partials/polices.php'; ?>
    <title>Publier un conseil — Système de Gestion de Pharmacie</title>
</head>
<body>
    <?php require VUES_DIR . 'partials/navigation.php'; ?>
    <h1>Publier un conseil santé</h1>

    <p><a href="index.php?controller=Conseil&action=liste">Retour aux conseils</a></p>

    <?php if (!empty($erreurs)): ?>
        <ul id="liste-erreurs">
            <?php foreach ($erreurs as $erreur): ?>
                <li><?= htmlspecialchars($erreur) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="post" action="index.php?controller=Conseil&action=creer" id="formulaire-conseil" novalidate>
        <div>
            <label for="titre">Titre</label>
            <input type="text" id="titre" name="titre" value="<?= htmlspecialchars($donnees['titre']) ?>">
        </div>

        <div>
            <label for="contenu">Contenu</label>
            <textarea id="contenu" name="contenu" rows="10"><?= htmlspecialchars($donnees['contenu']) ?></textarea>
        </div>

        <p id="message-js" role="alert"></p>

        <button type="submit">Publier</button>
    </form>

    <script src="js/gestion-conseil.js"></script>
</body>
</html>
