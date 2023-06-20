document.querySelector('#InputPlacing').addEventListener('blur', validatePlacing);
document.querySelector('#InputDiscipline').addEventListener('blur', validateDiscipline);

const formsPlacement = document.querySelectorAll('.placementForm');

Array.prototype.slice.call(formsPlacement).forEach(function(form) {
    form.addEventListener('submit', function(event) {
        if (!form.checkValidity() || !validatePlacing() || !validateDiscipline()) {
            event.preventDefault();
            event.stopPropagation();
        }

        form.classList.add('was-validated');
    }, false);
});