document.querySelector('#InputName').addEventListener('blur', validateName);
document.querySelector('#InputSurname').addEventListener('blur', validateSurname);
document.querySelector('#InputBrDay').addEventListener('blur', validateBrDay);
document.querySelector('#InputBrPlace').addEventListener('blur', validateBrPlace);
document.querySelector('#InputBrCountry').addEventListener('blur', validateBrCountry);

const personForm = document.querySelectorAll('.personForm');


Array.prototype.slice.call(personForm).forEach(function(form) {
    form.addEventListener('submit', function(event) {
        if (!form.checkValidity() || !validateName() || !validateSurname() || !validateBrDay() || !validateBrPlace() || !validateBrCountry()) {
            event.preventDefault();
            event.stopPropagation();
        }

        form.classList.add('was-validated');
    }, false);
});


