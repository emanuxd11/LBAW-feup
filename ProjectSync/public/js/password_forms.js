let passwordFieldTouched = false;
let confirmationFieldTouched = false;

const validateForm = () => validatePassword();

const validatePassword = () => {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('password-confirm').value;

    const passwordError = document.getElementById('password-error');
    const confirmPasswordError = document.getElementById('password-confirm-error');

    passwordError.innerHTML = "";
    confirmPasswordError.innerHTML = "";

    if (passwordFieldTouched && password.length < 8) {
        passwordError.innerHTML = "Password must be at least 8 characters long.";
        disableSubmitButton();
        return false;
    }

    if (confirmationFieldTouched && password !== confirmPassword) {
        confirmPasswordError.innerHTML = "Passwords do not match.";
        disableSubmitButton();
        return false;
    }

    enableSubmitButton();
    return true;
};

const disableSubmitButton = () => {
    document.getElementById('submit-button').disabled = true;
};

const enableSubmitButton = () => {
    document.getElementById('submit-button').disabled = false;
};

document.getElementById('password').addEventListener('blur', () => {
    passwordFieldTouched = true;
    validatePassword();
});

document.getElementById('password-confirm').addEventListener('blur', () => {
    confirmationFieldTouched = true;
    validatePassword();
});
