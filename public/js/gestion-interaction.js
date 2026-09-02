/**
 * Validation côté client du formulaire d'enregistrement d'une
 * interaction médicamenteuse et confirmation avant suppression depuis
 * la liste. Retour immédiat uniquement — la validation qui compte reste
 * PHP, côté serveur.
 */
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.formulaire-suppression').forEach(function (form) {
        form.addEventListener('submit', function (evenement) {
            if (!confirm('Confirmer la suppression de cette interaction ?')) {
                evenement.preventDefault();
            }
        });
    });

    var formulaire = document.getElementById('formulaire-interaction');

    if (!formulaire) {
        return;
    }

    var messageJs = document.getElementById('message-js');

    formulaire.addEventListener('submit', function (evenement) {
        var medicament1 = document.getElementById('id_medicament_1').value;
        var medicament2 = document.getElementById('id_medicament_2').value;
        var description = document.getElementById('description').value.trim();

        var erreurs = [];

        if (medicament1 === '') {
            erreurs.push('Sélectionnez un premier médicament.');
        }

        if (medicament2 === '') {
            erreurs.push('Sélectionnez un second médicament.');
        }

        if (medicament1 !== '' && medicament1 === medicament2) {
            erreurs.push('Les deux médicaments doivent être différents.');
        }

        if (description === '') {
            erreurs.push("La description de l'interaction est obligatoire.");
        }

        if (erreurs.length > 0) {
            evenement.preventDefault();
            messageJs.textContent = erreurs.join(' ');
        } else {
            messageJs.textContent = '';
        }
    });
});
