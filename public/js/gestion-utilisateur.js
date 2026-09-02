/**
 * Validation côté client, partagée par les formulaires BackOffice de
 * création et de modification d'utilisateur. Retour immédiat uniquement
 * — la validation qui compte reste PHP, côté serveur (validerGestion()).
 */
document.addEventListener('DOMContentLoaded', function () {
    var formulaire = document.getElementById('formulaire-gestion-utilisateur');

    if (!formulaire) {
        return;
    }

    var messageJs = document.getElementById('message-js');

    formulaire.addEventListener('submit', function (evenement) {
        var nom = document.getElementById('nom').value.trim();
        var prenom = document.getElementById('prenom').value.trim();
        var email = document.getElementById('email').value.trim();
        var champMotDePasse = document.getElementById('mot_de_passe');

        var erreurs = [];

        if (nom === '') {
            erreurs.push('Le nom est obligatoire.');
        }

        if (prenom === '') {
            erreurs.push('Le prénom est obligatoire.');
        }

        if (email === '') {
            erreurs.push("L'email est obligatoire.");
        }

        if (champMotDePasse) {
            var motDePasse = champMotDePasse.value;
            var confirmation = document.getElementById('confirmation_mot_de_passe').value;

            if (motDePasse.length < 8) {
                erreurs.push('Le mot de passe doit contenir au moins 8 caractères.');
            }

            if (motDePasse !== confirmation) {
                erreurs.push('Les mots de passe ne correspondent pas.');
            }
        }

        if (erreurs.length > 0) {
            evenement.preventDefault();
            messageJs.textContent = erreurs.join(' ');
        } else {
            messageJs.textContent = '';
        }
    });
});
