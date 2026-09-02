<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <?php require VUES_DIR . 'partials/polices.php'; ?>
    <title>Gestion des utilisateurs — Système de Gestion de Pharmacie</title>
</head>
<body>
    <?php require VUES_DIR . 'partials/navigation.php'; ?>
    <h1>Gestion des utilisateurs</h1>

    <p><a href="index.php?controller=Utilisateur&action=creer">Ajouter un utilisateur</a></p>

    <table id="tableau-utilisateurs">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Email</th>
                <th>Rôle</th>
                <th>Téléphone</th>
                <th>Inscrit le</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($utilisateurs as $utilisateur): ?>
                <tr>
                    <td><?= htmlspecialchars($utilisateur['nom']) ?></td>
                    <td><?= htmlspecialchars($utilisateur['prenom']) ?></td>
                    <td><?= htmlspecialchars($utilisateur['email']) ?></td>
                    <td><?= htmlspecialchars($utilisateur['role']) ?></td>
                    <td><?= htmlspecialchars($utilisateur['telephone'] ?? '') ?></td>
                    <td><?= htmlspecialchars($utilisateur['date_creation']) ?></td>
                    <td>
                        <a href="index.php?controller=Utilisateur&action=modifier&id=<?= (int) $utilisateur['id_utilisateur'] ?>">Modifier</a>
                        <form method="post" action="index.php?controller=Utilisateur&action=supprimer&id=<?= (int) $utilisateur['id_utilisateur'] ?>" class="formulaire-suppression">
                            <button type="submit">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <script src="js/utilisateurs.js"></script>
</body>
</html>
