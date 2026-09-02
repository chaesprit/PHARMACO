/**
 * Confirmation avant annulation ou renouvellement d'une ordonnance par
 * le client. L'action réelle reste gérée côté serveur (POST).
 */
document.addEventListener('DOMContentLoaded', function () {
    var confirmations = {
        'formulaire-annulation': "Confirmer l'annulation de cette ordonnance ?",
        'formulaire-renouvellement': 'Confirmer le renouvellement de cette ordonnance ?',
    };

    Object.keys(confirmations).forEach(function (classe) {
        document.querySelectorAll('.' + classe).forEach(function (formulaire) {
            formulaire.addEventListener('submit', function (evenement) {
                if (!confirm(confirmations[classe])) {
                    evenement.preventDefault();
                }
            });
        });
    });
});
