/**
 * Confirmation avant annulation d'une ordonnance par le client.
 * L'action réelle reste gérée côté serveur (POST).
 */
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.formulaire-annulation').forEach(function (formulaire) {
        formulaire.addEventListener('submit', function (evenement) {
            if (!confirm("Confirmer l'annulation de cette ordonnance ?")) {
                evenement.preventDefault();
            }
        });
    });
});
