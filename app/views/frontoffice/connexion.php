<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <?php require VUES_DIR . 'partials/polices.php'; ?>
    <title>Connexion — Système de Gestion de Pharmacie</title>
</head>
<body>
    <?php require VUES_DIR . 'partials/navigation.php'; ?>
    <h1>Connexion</h1>

    <?php if ($dejaConnecte): ?>
        <p id="message-connecte">
            Connecté en tant que <?= htmlspecialchars($_SESSION['user_nom']) ?>
            (<?= htmlspecialchars($_SESSION['user_role']) ?>).
        </p>
        <a href="index.php?controller=Utilisateur&action=deconnexion" id="lien-deconnexion">Se déconnecter</a>
    <?php else: ?>
        <?php if (!empty($erreurs)): ?>
            <ul id="liste-erreurs">
                <?php foreach ($erreurs as $erreur): ?>
                    <li><?= htmlspecialchars($erreur) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <form method="post" action="index.php?controller=Utilisateur&action=connexion" id="formulaire-connexion" novalidate>
            <div>
                <label for="email">Email</label>
                <input type="text" id="email" name="email" value="<?= htmlspecialchars($email) ?>">
            </div>

            <div>
                <label for="mot_de_passe">Mot de passe</label>
                <input type="password" id="mot_de_passe" name="mot_de_passe">
            </div>

            <p id="message-js" role="alert"></p>

            <button type="submit">Se connecter</button>
        </form>
    <?php endif; ?>

    <script src="js/connexion.js"></script>
</body>
</html>
