document.querySelector('#Input2FA').addEventListener('blur', validate2FA);

const forms = document.querySelectorAll('.needs-validation');

Array.prototype.slice.call(forms).forEach(function(form) {
    form.addEventListener('submit', function(event) {
        if (!form.checkValidity() || !validate2FA()) {
            event.preventDefault();
            event.stopPropagation();
        }

        form.classList.add('was-validated');
    }, false);
});