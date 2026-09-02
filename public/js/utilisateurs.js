/**
 * Confirmation avant suppression d'un utilisateur, depuis la liste
 * BackOffice. La suppression réelle reste gérée côté serveur (POST).
 */
document.addEventListener('DOMContentLoaded', function () {
    var formulaires = document.querySelectorAll('.formulaire-suppression');

    formulaires.forEach(function (formulaire) {
        formulaire.addEventListener('submit', function (evenement) {
            if (!confirm('Confirmer la suppression de cet utilisateur ?')) {
                evenement.preventDefault();
            }
        });
    });
});
