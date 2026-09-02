/**
 * Confirmations côté client sur la liste BackOffice des ordonnances :
 * avant un rejet et avant une suppression. Le traitement réel reste
 * côté serveur (OrdonnanceController::traiter() / supprimer()).
 */
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.formulaire-suppression').forEach(function (form) {
        form.addEventListener('submit', function (evenement) {
            if (!confirm('Confirmer la suppression de cette ordonnance ?')) {
                evenement.preventDefault();
            }
        });
    });

    document.querySelectorAll('.formulaire-traitement').forEach(function (form) {
        form.addEventListener('submit', function (evenement) {
            var bouton = evenement.submitter;

            if (bouton && bouton.value === 'rejetee' && !confirm('Confirmer le rejet de cette ordonnance ?')) {
                evenement.preventDefault();
            }
        });
    });
});
