<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <?php require VUES_DIR . 'partials/polices.php'; ?>
    <title>Modifier un utilisateur — Système de Gestion de Pharmacie</title>
</head>
<body>
    <?php require VUES_DIR . 'partials/navigation.php'; ?>
    <h1>Modifier l'utilisateur</h1>

    <p><a href="index.php?controller=Utilisateur&action=liste">Retour à la liste</a></p>

    <?php if (!empty($erreurs)): ?>
        <ul id="liste-erreurs">
            <?php foreach ($erreurs as $erreur): ?>
                <li><?= htmlspecialchars($erreur) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="post" action="index.php?controller=Utilisateur&action=modifier&id=<?= (int) $id ?>" id="formulaire-gestion-utilisateur" novalidate>
        <div>
            <label for="nom">Nom</label>
            <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($donnees['nom']) ?>">
        </div>

        <div>
            <label for="prenom">Prénom</label>
            <input type="text" id="prenom" name="prenom" value="<?= htmlspecialchars($donnees['prenom']) ?>">
        </div>

        <div>
            <label for="email">Email</label>
            <input type="text" id="email" name="email" value="<?= htmlspecialchars($donnees['email']) ?>">
        </div>

        <div>
            <label for="role">Rôle</label>
            <select id="role" name="role">
                <?php foreach (['responsable', 'pharmacien', 'client'] as $roleOption): ?>
                    <option value="<?= $roleOption ?>" <?= $donnees['role'] === $roleOption ? 'selected' : '' ?>><?= $roleOption ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label for="telephone">Téléphone (optionnel)</label>
            <input type="text" id="telephone" name="telephone" value="<?= htmlspecialchars($donnees['telephone']) ?>">
        </div>

        <p id="message-js" role="alert"></p>

        <button type="submit">Enregistrer</button>
    </form>

    <script src="js/gestion-utilisateur.js"></script>
</body>
</html>
