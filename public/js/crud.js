$(document).ready(function () {
    // Validate form on submit
    $('#form_reclamation').on('submit', function (e) {
        if (!validateForm()) {
            e.preventDefault(); // Prevent form submission if validation fails
        }
    });

    // Real-time validation for all inputs, including message field
    $('#reclamation_name_rec, #reclamation_email_rec, #reclamation_categorie_rec, #reclamation_message_rec').on('keyup blur change', function () {
        validateField($(this));
    });

    function validateForm() {
        let isValid = true;
        // Validate all fields, including message field
        $('#reclamation_name_rec, #reclamation_email_rec, #reclamation_categorie_rec, #reclamation_message_rec').each(function () {
            if (!validateField($(this))) {
                isValid = false;
            }
        });
        return isValid;
    }

    function validateField(element) {
        let value = element.val().trim();
        let isValid = true;
        let errorMessage = '';
        let icon = '<i class="fas fa-exclamation-circle text-danger ms-2"></i>';

        // Remove existing error messages and reset styles
        element.closest('.form-group').find('.alert-custom, .validation-icon').remove();
        element.removeClass('is-invalid is-valid');

        // Validation logic
        if (element.is('#reclamation_name_rec')) {
            if (value.length < 4) {
                errorMessage = 'Le nom doit contenir au moins 4 caractères.';
                isValid = false;
            }
        } else if (element.is('#reclamation_email_rec')) {
            let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (value === '') {
                errorMessage = "L'email est obligatoire.";
                isValid = false;
            } else if (!emailRegex.test(value)) {
                errorMessage = "L'email n'est pas valide.";
                isValid = false;
            }
        } else if (element.is('#reclamation_categorie_rec')) {
            if (!value || value.length === 0) {
                errorMessage = 'Veuillez sélectionner au moins une catégorie.';
                isValid = false;
            }
        } else if (element.is('#reclamation_message_rec')) {
            if (value.length < 10) {
                errorMessage = 'Le message doit contenir au moins 10 caractères.';
                isValid = false;
            }
        }

        // Apply styles based on validation
        if (!isValid) {
            element.addClass('is-invalid');
            element.after(`<div class="alert-custom alert-danger">${errorMessage}</div>`);
            element.closest('.form-group').append(`<span class="validation-icon">${icon}</span>`);
        } else {
            element.addClass('is-valid');
        }

        return isValid;
    }
});

$(document).ready(function () {
    // Validate form on submit
    $('#form_reponse').on('submit', function (e) {
        if (!validateForm()) {
            e.preventDefault(); // Prevent form submission if validation fails
        }
    });

    // Real-time validation for the "contenu" field
    $('#reponse_contenu').on('keyup blur change', function () {
        validateField($(this)); // Call validateField when content changes
    });

    function validateForm() {
        let isValid = true;
        // Validate the "contenu" field
        if (!validateField($('#reponse_contenu'))) {
            isValid = false;
        }
        return isValid;
    }

    function validateField(element) {
        let value = element.val().trim();
        let isValid = true;
        let errorMessage = '';
        let icon = '<i class="fas fa-exclamation-circle text-danger ms-2"></i>';

        // Remove existing error messages and reset styles
        element.closest('.mb-3').find('.alert-custom, .validation-icon').remove();
        element.removeClass('is-invalid is-valid');

        // Validation logic for the "contenu" field
        if (element.is('#reponse_contenu')) {
            if (value.length < 10) {
                errorMessage = 'La réponse doit contenir au moins 10 caractères.';
                isValid = false;
            }
        }

        // Apply styles based on validation
        if (!isValid) {
            element.addClass('is-invalid');
            element.after(`<div class="alert-custom alert-danger">${errorMessage}</div>`);
            element.closest('.mb-3').append(`<span class="validation-icon">${icon}</span>`);
        } else {
            element.addClass('is-valid');
        }

        return isValid;
    }
});
