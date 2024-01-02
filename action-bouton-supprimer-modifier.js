document.addEventListener('DOMContentLoaded', function () {
    var actionButtons = document.querySelectorAll('.bouton_modifier, .bouton_supprimer');

    actionButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            var actionId = button.getAttribute('data-id');
            var actionFormulaire = button.classList.contains('bouton_modifier') ? 'modification' + button.name : 'edit';

            document.getElementById('actionId').value = actionId;
            document.getElementById('actionFormulaire').value = actionFormulaire;

            // Set the appropriate action based on the button clicked
            if (actionFormulaire === 'delete') {
                document.getElementById('actionForm').action = 'suppression.php';
            } else if (actionFormulaire === 'edit') {
                document.getElementById('actionForm').action = 'modificationMedecin.php';
            }

            document.getElementById('submitBtn').click();
        });
    });
});