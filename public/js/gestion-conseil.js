/**
 * Validation côté client du formulaire de conseil santé et confirmation
 * avant suppression depuis la liste. Retour immédiat uniquement — la
 * validation qui fait foi reste côté serveur (ConseilController::validerConseil()).
 */
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.formulaire-suppression').forEach(function (form) {
        form.addEventListener('submit', function (evenement) {
            if (!confirm('Confirmer la suppression de ce conseil ?')) {
                evenement.preventDefault();
            }
        });
    });

    var formulaire = document.getElementById('formulaire-conseil');

    if (!formulaire) {
        return;
    }

    var messageJs = document.getElementById('message-js');

    formulaire.addEventListener('submit', function (evenement) {
        var titre = document.getElementById('titre').value.trim();
        var contenu = document.getElementById('contenu').value.trim();

        var erreurs = [];

        if (titre === '') {
            erreurs.push('Le titre est obligatoire.');
        } else if (titre.length > 150) {
            erreurs.push('Le titre ne doit pas dépasser 150 caractères.');
        }

        if (contenu === '') {
            erreurs.push('Le contenu est obligatoire.');
        } else if (contenu.length < 20) {
            erreurs.push('Le contenu doit faire au moins 20 caractères.');
        }

        if (erreurs.length > 0) {
            evenement.preventDefault();
            messageJs.textContent = erreurs.join(' ');
        } else {
            messageJs.textContent = '';
        }
    });
});
