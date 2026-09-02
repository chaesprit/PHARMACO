/**
 * Validation côté client du formulaire de connexion.
 * Retour immédiat uniquement — la validation qui compte reste PHP,
 * exécutée côté serveur dans UtilisateurController::connexion().
 */
document.addEventListener('DOMContentLoaded', function () {
    var formulaire = document.getElementById('formulaire-connexion');

    if (!formulaire) {
        return;
    }

    var messageJs = document.getElementById('message-js');

    formulaire.addEventListener('submit', function (evenement) {
        var email = document.getElementById('email').value.trim();
        var motDePasse = document.getElementById('mot_de_passe').value;

        var erreurs = [];

        if (email === '') {
            erreurs.push("L'email est obligatoire.");
        }

        if (motDePasse === '') {
            erreurs.push('Le mot de passe est obligatoire.');
        }

        if (erreurs.length > 0) {
            evenement.preventDefault();
            messageJs.textContent = erreurs.join(' ');
        } else {
            messageJs.textContent = '';
        }
    });
});
