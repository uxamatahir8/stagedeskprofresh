const inputs = document.querySelectorAll('.number, .required, .valid_url, .match');
const validateRequiredInput = (element) => {
    let required = false;
    if (!element.classList.contains('d-none')) {

        let validationMessage = element.parentNode.querySelector('.validation-message');
        if (!validationMessage) {
            const requiredElement = formatLabel(element.getAttribute('name')); // Format label
            validationMessage = document.createElement('span');
            validationMessage.classList.add('validation-message', 'text-danger', 'd-none');
            validationMessage.textContent = `${requiredElement} is required`; // Default validation message
            element.parentNode.appendChild(validationMessage);
        }

        if (element.classList.contains('required') && element.value.trim() === '') {
            element.classList.add('is-invalid');
            element.classList.remove('is-valid');
            validationMessage.textContent = `${formatLabel(element.getAttribute('name'))} is required!`;
            validationMessage.classList.remove('d-none');
            required = false;
        } else {
            element.classList.remove('is-invalid');
            element.classList.add('is-valid');
            validationMessage.classList.add('d-none');
            required = true;
        }


        if (element.classList.contains('number')) {
            if (element.value.trim() !== '') {
                const number_regex = /^[0-9]+$/;
                if (!number_regex.test(element.value.trim())) {
                    element.classList.add('is-invalid');
                    element.classList.remove('is-valid');
                    validationMessage.textContent = `${formatLabel(element.getAttribute('name'))} must be a number!`;
                    validationMessage.classList.remove('d-none');
                } else {
                    element.classList.remove('is-invalid');
                    element.classList.add('is-valid');
                    validationMessage.classList.add('d-none');
                }
            }
        }

        if (element.classList.contains('valid_url')) {
            if (element.value.trim() !== '') {
                const url_regex = /^(?!https?:\/\/)([a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)+)(:[0-9]{1,5})?(\/.*)?$/;
                if (!url_regex.test(element.value.trim())) {
                    element.classList.add('is-invalid');
                    element.classList.remove('is-valid');
                    validationMessage.textContent = `${formatLabel(element.getAttribute('name'))} must be a valid URL without 'http' or 'https' (e.g., example.com)!`;
                    validationMessage.classList.remove('d-none');
                } else {
                    element.classList.remove('is-invalid');
                    element.classList.add('is-valid');
                    validationMessage.classList.add('d-none');
                }
            } else {
                element.classList.remove('is-invalid');
                element.classList.add('is-valid');
                validationMessage.classList.add('d-none');
            }
        }
        if (element.classList.contains('match')) {
            const match_with = element.getAttribute('data-match');
            const match_with_element = document.querySelector('#' + match_with);
            if (match_with_element.value.trim() !== '') {
                if (element.value.trim() !== match_with_element.value.trim()) {
                    element.classList.add('is-invalid');
                    element.classList.remove('is-valid');
                    match_with_element.classList.remove('is-invalid');
                    match_with_element.classList.add('is-valid');
                    validationMessage.textContent = `${formatLabel(element.getAttribute('name'))} does not match with ${formatLabel(match_with_element.getAttribute('name'))}!`;
                    validationMessage.classList.remove('d-none');
                } else {
                    element.classList.remove('is-invalid');
                    element.classList.add('is-valid');
                    validationMessage.classList.add('d-none');
                }
            } else {
                if (match_with_element.classList.contains('required')) {
                    element.value = '';
                    element.classList.add('is-invalid');
                    element.classList.remove('is-valid');
                    match_with_element.classList.add('is-invalid');
                    match_with_element.classList.remove('is-valid');
                    validationMessage.textContent = `Provide ${formatLabel(match_with_element.getAttribute('name'))} First!`;
                    validationMessage.classList.remove('d-none');
                    match_with_element.focus();
                }
            }
        }

        if (required == true) {
            let validEmail;
            // Specific validation for email inputs
            if (element.getAttribute('type') === 'email' && element.value.trim() !== '') {
                const email = element.value.trim();
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(email)) {
                    element.classList.add('is-invalid');
                    element.classList.remove('is-valid');
                    validationMessage.textContent = `Please write a valid email!`;
                    validationMessage.classList.remove('d-none');
                    validEmail = false;
                } else {
                    element.classList.remove('is-invalid');
                    element.classList.add('is-valid');
                    validationMessage.classList.add('d-none');
                    validEmail = true;
                }
            }
            if (element.classList.contains('unique_email')) {
                const email = element.value.trim();
                if (validEmail == true) {
                    axios.get(base_url + 'manage/validate_email?email=' + email).then(response => {
                        console.log(response?.data?.status);
                        if (response.data.status == 1) {
                            element.classList.add('is-invalid');
                            element.classList.remove('is-valid');
                            validationMessage.textContent = `Email already exists!`;
                            validationMessage.classList.remove('d-none');
                        } else {
                            element.classList.remove('is-invalid');
                            element.classList.add('is-valid');
                            validationMessage.classList.add('d-none');
                        }
                    }).catch((error) => {
                        console.log(error);
                    });
                }
            }




            if (element.classList.contains('phone')) {
                const phone_regex = /^\+\d{1,3}\d{9,}$/;
                if (!phone_regex.test(element.value.trim())) {
                    element.classList.add('is-invalid');
                    element.classList.remove('is-valid');
                    validationMessage.textContent = 'Please Enter Valid Phone Number with Country Code!';
                    validationMessage.classList.remove('d-none');
                } else {
                    element.classList.remove('is-invalid');
                    element.classList.add('is-valid');
                    validationMessage.classList.add('d-none');
                }
            }
        }



    }
};


const formatLabel = (text) => {
    if (!text) return '';
    return text
        .split(/[_-]/)
        .map(word => word.charAt(0).toUpperCase() + word.slice(1))
        .join(' ');
};

inputs.forEach((element) => {
    element.addEventListener('input', () => {
        validateRequiredInput(element);
    });
});
const form = document.querySelector('.validate_form');

form?.addEventListener('submit', (e) => {
    e.preventDefault();
    inputs.forEach((element) => {
        validateRequiredInput(element);
    });
    const invalidInputs = document.querySelectorAll('.is-invalid');
    if (invalidInputs.length === 0) {
        form.submit();
    } else {
        invalidInputs[0].focus();
    }
});
