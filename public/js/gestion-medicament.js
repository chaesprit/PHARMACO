/**
 * Validation côté client des formulaires BackOffice de médicament
 * (création / modification) et confirmation avant suppression depuis la
 * liste. Retour immédiat uniquement — la validation qui fait foi reste
 * côté serveur (MedicamentController::validerMedicament()).
 */
document.addEventListener('DOMContentLoaded', function () {
    var formulaire = document.getElementById('formulaire-medicament');

    if (formulaire) {
        var messageJs = document.getElementById('message-js');

        formulaire.addEventListener('submit', function (evenement) {
            var nom = document.getElementById('nom').value.trim();
            var prix = document.getElementById('prix').value.trim();
            var quantiteStock = document.getElementById('quantite_stock').value.trim();
            var seuilCritique = document.getElementById('seuil_critique').value.trim();

            var erreurs = [];

            if (nom === '') {
                erreurs.push('Le nom est obligatoire.');
            }

            if (prix === '' || isNaN(prix) || Number(prix) < 0) {
                erreurs.push('Le prix doit être un nombre positif.');
            }

            if (quantiteStock === '' || !/^\d+$/.test(quantiteStock)) {
                erreurs.push('La quantité en stock doit être un nombre entier positif.');
            }

            if (seuilCritique === '' || !/^\d+$/.test(seuilCritique)) {
                erreurs.push('Le seuil critique doit être un nombre entier positif.');
            }

            if (erreurs.length > 0) {
                evenement.preventDefault();
                messageJs.textContent = erreurs.join(' ');
            } else {
                messageJs.textContent = '';
            }
        });
    }

    document.querySelectorAll('.formulaire-suppression').forEach(function (form) {
        form.addEventListener('submit', function (evenement) {
            if (!confirm('Confirmer la suppression de ce médicament ?')) {
                evenement.preventDefault();
            }
        });
    });
});
