/**
 * Validation côté client du formulaire d'enregistrement d'une
 * expédition et confirmations sur la liste (annulation, suppression).
 * Retour immédiat uniquement — la validation qui compte reste PHP,
 * côté serveur.
 */
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.formulaire-suppression').forEach(function (form) {
        form.addEventListener('submit', function (evenement) {
            if (!confirm('Confirmer la suppression de cette expédition ?')) {
                evenement.preventDefault();
            }
        });
    });

    document.querySelectorAll('.formulaire-statut-expedition').forEach(function (form) {
        form.addEventListener('submit', function (evenement) {
            var bouton = evenement.submitter;

            if (bouton && bouton.value === 'annulee' && !confirm("Confirmer l'annulation de cette expédition ?")) {
                evenement.preventDefault();
            }
        });
    });

    var formulaire = document.getElementById('formulaire-expedition');

    if (!formulaire) {
        return;
    }

    var messageJs = document.getElementById('message-js');

    formulaire.addEventListener('submit', function (evenement) {
        var medicament = document.getElementById('id_medicament').value;
        var quantite = document.getElementById('quantite').value.trim();

        var erreurs = [];

        if (medicament === '') {
            erreurs.push('Sélectionnez un médicament.');
        }

        if (quantite === '' || !/^\d+$/.test(quantite) || Number(quantite) < 1) {
            erreurs.push('La quantité doit être un nombre entier positif.');
        }

        if (erreurs.length > 0) {
            evenement.preventDefault();
            messageJs.textContent = erreurs.join(' ');
        } else {
            messageJs.textContent = '';
        }
    });
});
