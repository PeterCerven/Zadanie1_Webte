document.querySelector('#InputName').addEventListener('blur', validateName);
document.querySelector('#InputSurname').addEventListener('blur', validateSurname);
document.querySelector('#InputEmail').addEventListener('blur', validateEmail);
document.querySelector('#InputLogin').addEventListener('blur', validateLogin);
document.querySelector('#InputPassword').addEventListener('blur', validatePassword);



const forms = document.querySelectorAll('.needs-validation');

Array.prototype.slice.call(forms).forEach(function(form) {
    form.addEventListener('submit', function(event) {
        if (!form.checkValidity() || !validateName() || !validateSurname() || !validateEmail() || !validateLogin() || !validatePassword()) {
            event.preventDefault();
            event.stopPropagation();
        }

        form.classList.add('was-validated');
    }, false);
});