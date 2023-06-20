const textRegex = /^(\p{L}){2,30}$/u;
const emailRegex = /^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})$/u;
const loginRegex = /^[a-zA-Z0-9]{5,20}$/u;
const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*])([\w!@#$%^&*]){8,20}$/u;
const twoFactorRegex = /^[0-9]{6}$/u;
const dateRegex = /^[0-9]{4}-[0-9]{2}-[0-9]{2}$/u;
const placingRegex = /^[0-9]{1,3}$/u;
const disciplineRegex = /^(\P{Cn}){3,50}$/u;

function validateName() {
    const name = document.querySelector('#InputName');


    if (textRegex.test(name.value)) {
        name.classList.remove('is-invalid');
        name.classList.add('is-valid');
        return true;
    } else {
        name.classList.add('is-invalid');
        name.classList.remove('is-valid');
        return false;
    }
}

function validateSurname() {
    const surname = document.querySelector('#InputSurname');

    if (textRegex.test(surname.value)) {
        surname.classList.remove('is-invalid');
        surname.classList.add('is-valid');
        return true;
    } else {
        surname.classList.add('is-invalid');
        surname.classList.remove('is-valid');
        return false;
    }
}

function validateEmail() {
    const email = document.querySelector('#InputEmail');

    if (emailRegex.test(email.value)) {
        email.classList.remove('is-invalid');
        email.classList.add('is-valid');
        return true;
    } else {
        email.classList.add('is-invalid');
        email.classList.remove('is-valid');
        return false;
    }
}

function validateLogin() {
    const login = document.querySelector('#InputLogin');

    if (loginRegex.test(login.value)) {
        login.classList.remove('is-invalid');
        login.classList.add('is-valid');
        return true;
    } else {
        login.classList.add('is-invalid');
        login.classList.remove('is-valid');
        return false;
    }
}

function validatePassword() {
    const password = document.querySelector('#InputPassword');

    if (passwordRegex.test(password.value)) {
        password.classList.remove('is-invalid');
        password.classList.add('is-valid');
        return true;
    } else {
        password.classList.add('is-invalid');
        password.classList.remove('is-valid');
        return false;
    }
}

function validate2FA() {
    const twoFactor = document.querySelector('#Input2FA');

    if (twoFactorRegex.test(twoFactor.value)) {
        twoFactor.classList.remove('is-invalid');
        twoFactor.classList.add('is-valid');
        return true;
    } else {
        twoFactor.classList.add('is-invalid');
        twoFactor.classList.remove('is-valid');
        return false;
    }
}

function validateBrDay() {
    const brDay = document.querySelector('#InputBrDay');

    let birthDate = new Date(brDay.value);
    let years = new Date(new Date() - new Date(birthDate)).getFullYear() - 1970;

    if (years < 10 || years > 100) {
        brDay.classList.add('is-invalid');
        brDay.classList.remove('is-valid');
        return false;
    }

    if (dateRegex.test(brDay.value)) {
        brDay.classList.remove('is-invalid');
        brDay.classList.add('is-valid');
        return true;
    } else {
        brDay.classList.add('is-invalid');
        brDay.classList.remove('is-valid');
        return false;
    }
}

function validateBrPlace() {
    const brPlace = document.querySelector('#InputBrPlace');

    if (textRegex.test(brPlace.value)) {
        brPlace.classList.remove('is-invalid');
        brPlace.classList.add('is-valid');
        return true;
    } else {
        brPlace.classList.add('is-invalid');
        brPlace.classList.remove('is-valid');
        return false;
    }
}

function validateBrCountry() {
    const brCountry = document.querySelector('#InputBrCountry');

    if (textRegex.test(brCountry.value)) {
        brCountry.classList.remove('is-invalid');
        brCountry.classList.add('is-valid');
        return true;
    } else {
        brCountry.classList.add('is-invalid');
        brCountry.classList.remove('is-valid');
        return false;
    }
}

function validateDeathDay() {
    const deathDay = document.querySelector('#InputDeathDay');

    if (dateRegex.test(deathDay.value)) {
        deathDay.classList.remove('is-invalid');
        deathDay.classList.add('is-valid');
        return true;
    } else {
        deathDay.classList.add('is-invalid');
        deathDay.classList.remove('is-valid');
        return false;
    }
}

function validateDeathPlace() {
    const deathPlace = document.querySelector('#InputDeathPlace');

    if (textRegex.test(deathPlace.value)) {
        deathPlace.classList.remove('is-invalid');
        deathPlace.classList.add('is-valid');
        return true;
    } else {
        deathPlace.classList.add('is-invalid');
        deathPlace.classList.remove('is-valid');
        return false;
    }
}

function validateDeathCountry() {
    const deathCountry = document.querySelector('#InputDeathCountry');

    if (textRegex.test(deathCountry.value)) {
        deathCountry.classList.remove('is-invalid');
        deathCountry.classList.add('is-valid');
        return true;
    } else {
        deathCountry.classList.add('is-invalid');
        deathCountry.classList.remove('is-valid');
        return false;
    }
}

function validatePlacing() {
    const placing = document.querySelector('#InputPlacing');

    if (placingRegex.test(placing.value)) {
        placing.classList.remove('is-invalid');
        placing.classList.add('is-valid');
        return true;
    } else {
        placing.classList.add('is-invalid');
        placing.classList.remove('is-valid');
        return false;
    }
}

function validateDiscipline() {
    const discipline = document.querySelector('#InputDiscipline');

    if (disciplineRegex.test(discipline.value)) {
        discipline.classList.remove('is-invalid');
        discipline.classList.add('is-valid');
        return true;
    } else {
        discipline.classList.add('is-invalid');
        discipline.classList.remove('is-valid');
        return false;
    }
}
