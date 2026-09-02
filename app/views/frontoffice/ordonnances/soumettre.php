<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <?php require VUES_DIR . 'partials/polices.php'; ?>
    <title>Soumettre une ordonnance — Système de Gestion de Pharmacie</title>
</head>
<body>
    <?php require VUES_DIR . 'partials/navigation.php'; ?>
    <h1>Soumettre une ordonnance</h1>

    <p><a href="index.php?controller=Ordonnance&action=mesOrdonnances">Voir mes ordonnances</a></p>

    <?php if (!empty($erreurs)): ?>
        <ul id="liste-erreurs">
            <?php foreach ($erreurs as $erreur): ?>
                <li><?= htmlspecialchars($erreur) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <?php if (empty($medicaments)): ?>
        <p>Aucun médicament n'est disponible pour le moment.</p>
    <?php else: ?>
        <form method="post" action="index.php?controller=Ordonnance&action=soumettre" id="formulaire-ordonnance" novalidate>
            <table id="tableau-medicaments-ordonnance">
                <thead>
                    <tr>
                        <th>Inclure</th>
                        <th>Médicament</th>
                        <th>Quantité</th>
                        <th>Posologie</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($medicaments as $medicament): ?>
                        <?php $idM = (int) $medicament['id_medicament']; ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="medicaments[<?= $idM ?>][inclure]" value="1" class="case-inclure" id="inclure-<?= $idM ?>">
                            </td>
                            <td>
                                <label for="inclure-<?= $idM ?>"><?= htmlspecialchars($medicament['nom']) ?></label>
                            </td>
                            <td>
                                <input type="text" name="medicaments[<?= $idM ?>][quantite]" class="champ-quantite">
                            </td>
                            <td>
                                <input type="text" name="medicaments[<?= $idM ?>][posologie]" placeholder="ex. 1 comprimé matin et soir">
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div>
                <label for="commentaire">Commentaire (optionnel)</label>
                <textarea id="commentaire" name="commentaire"><?= htmlspecialchars($commentaire) ?></textarea>
            </div>

            <p id="message-js" role="alert"></p>

            <button type="submit">Soumettre l'ordonnance</button>
        </form>
    <?php endif; ?>

    <script src="js/soumettre-ordonnance.js"></script>
</body>
</html>
