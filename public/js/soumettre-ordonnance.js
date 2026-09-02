/**
 * Validation côté client du formulaire de soumission d'ordonnance.
 * Retour immédiat uniquement — la validation qui compte reste PHP,
 * côté serveur dans OrdonnanceController::validerSoumission().
 */
document.addEventListener('DOMContentLoaded', function () {
    var formulaire = document.getElementById('formulaire-ordonnance');

    if (!formulaire) {
        return;
    }

    var messageJs = document.getElementById('message-js');

    formulaire.addEventListener('submit', function (evenement) {
        var cases = formulaire.querySelectorAll('.case-inclure');
        var erreurs = [];
        var auMoinsUneCoche = false;

        cases.forEach(function (caseACocher) {
            if (!caseACocher.checked) {
                return;
            }

            auMoinsUneCoche = true;

            var ligne = caseACocher.closest('tr');
            var champQuantite = ligne.querySelector('.champ-quantite');
            var quantite = champQuantite.value.trim();

            if (quantite === '' || !/^\d+$/.test(quantite) || Number(quantite) < 1) {
                erreurs.push('Indiquez une quantité valide pour chaque médicament sélectionné.');
            }
        });

        if (!auMoinsUneCoche) {
            erreurs.push('Sélectionnez au moins un médicament.');
        }

        if (erreurs.length > 0) {
            evenement.preventDefault();
            messageJs.textContent = erreurs.filter(function (valeur, index, tableau) {
                return tableau.indexOf(valeur) === index;
            }).join(' ');
        } else {
            messageJs.textContent = '';
        }
    });
});
