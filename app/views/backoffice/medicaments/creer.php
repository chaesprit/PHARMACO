<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <?php require VUES_DIR . 'partials/polices.php'; ?>
    <title>Ajouter un médicament — Système de Gestion de Pharmacie</title>
</head>
<body>
    <?php require VUES_DIR . 'partials/navigation.php'; ?>
    <h1>Ajouter un médicament</h1>

    <p><a href="index.php?controller=Medicament&action=liste">Retour à la liste</a></p>

    <?php if (!empty($erreurs)): ?>
        <ul id="liste-erreurs">
            <?php foreach ($erreurs as $erreur): ?>
                <li><?= htmlspecialchars($erreur) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="post" action="index.php?controller=Medicament&action=creer" id="formulaire-medicament" novalidate>
        <div>
            <label for="nom">Nom</label>
            <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($donnees['nom']) ?>">
        </div>

        <div>
            <label for="description">Description</label>
            <textarea id="description" name="description"><?= htmlspecialchars($donnees['description']) ?></textarea>
        </div>

        <div>
            <label for="categorie">Catégorie</label>
            <input type="text" id="categorie" name="categorie" value="<?= htmlspecialchars($donnees['categorie']) ?>">
        </div>

        <div>
            <label for="fabricant">Fabricant</label>
            <input type="text" id="fabricant" name="fabricant" value="<?= htmlspecialchars($donnees['fabricant']) ?>">
        </div>

        <div>
            <label for="prix">Prix</label>
            <input type="text" id="prix" name="prix" value="<?= htmlspecialchars((string) $donnees['prix']) ?>">
        </div>

        <div>
            <label for="quantite_stock">Quantité en stock</label>
            <input type="text" id="quantite_stock" name="quantite_stock" value="<?= htmlspecialchars((string) $donnees['quantite_stock']) ?>">
        </div>

        <div>
            <label for="seuil_critique">Seuil critique</label>
            <input type="text" id="seuil_critique" name="seuil_critique" value="<?= htmlspecialchars((string) $donnees['seuil_critique']) ?>">
        </div>

        <p id="message-js" role="alert"></p>

        <button type="submit">Créer le médicament</button>
    </form>

    <script src="js/gestion-medicament.js"></script>
</body>
</html>
