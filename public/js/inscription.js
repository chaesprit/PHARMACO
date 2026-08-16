/**
 * Validation côté client du formulaire d'inscription.
 * Ne remplace pas la validation PHP (obligatoire pour les données
 * sensibles) : sert uniquement à donner un retour immédiat à l'utilisateur.
 * Aucun attribut HTML5 (required, pattern, type=email...) n'est utilisé
 * comme mécanisme de validation, conformément à la consigne du professeur.
 */
document.addEventListener('DOMContentLoaded', function () {
    var formulaire = document.getElementById('formulaire-inscription');

    if (!formulaire) {
        return;
    }

    var messageJs = document.getElementById('message-js');
    var regexEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    var regexTelephone = /^[0-9+ ]{8,20}$/;

    formulaire.addEventListener('submit', function (evenement) {
        var nom = document.getElementById('nom').value.trim();
        var prenom = document.getElementById('prenom').value.trim();
        var email = document.getElementById('email').value.trim();
        var telephone = document.getElementById('telephone').value.trim();
        var motDePasse = document.getElementById('mot_de_passe').value;
        var confirmation = document.getElementById('confirmation_mot_de_passe').value;

        var erreurs = [];

        if (nom === '') {
            erreurs.push('Le nom est obligatoire.');
        }

        if (prenom === '') {
            erreurs.push('Le prénom est obligatoire.');
        }

        if (email === '' || !regexEmail.test(email)) {
            erreurs.push("L'email est obligatoire et doit être valide.");
        }

        if (telephone !== '' && !regexTelephone.test(telephone)) {
            erreurs.push('Le numéro de téléphone est invalide.');
        }

        if (motDePasse.length < 8) {
            erreurs.push('Le mot de passe doit contenir au moins 8 caractères.');
        }

        if (motDePasse !== confirmation) {
            erreurs.push('Les mots de passe ne correspondent pas.');
        }

        if (erreurs.length > 0) {
            evenement.preventDefault();
            messageJs.textContent = erreurs.join(' ');
        } else {
            messageJs.textContent = '';
        }
    });
});
