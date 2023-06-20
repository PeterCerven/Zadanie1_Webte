document.querySelector('#InputName').addEventListener('blur', validateName);
document.querySelector('#InputSurname').addEventListener('blur', validateSurname);
document.querySelector('#InputBrDay').addEventListener('blur', validateBrDay);
document.querySelector('#InputBrPlace').addEventListener('blur', validateBrPlace);
document.querySelector('#InputBrCountry').addEventListener('blur', validateBrCountry);
document.querySelector('#InputDeathDay').addEventListener('blur', validateDeathDay);
document.querySelector('#InputDeathPlace').addEventListener('blur', validateDeathPlace);
document.querySelector('#InputDeathCountry').addEventListener('blur', validateDeathCountry);

const forms = document.querySelectorAll('.needs-validation');

Array.prototype.slice.call(forms).forEach(function(form) {
    form.addEventListener('submit', function(event) {
        if (!form.checkValidity() || !validateName() || !validateSurname() || !validateBrDay() || !validateBrPlace()
            || !validateBrCountry() || !validateDeathDay() || !validateDeathPlace() || !validateDeathCountry()) {
            event.preventDefault();
            event.stopPropagation();
        }

        form.classList.add('was-validated');
    }, false);
});