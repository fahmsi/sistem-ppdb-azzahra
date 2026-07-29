const MAX_FILE_SIZE = 2 * 1024 * 1024;
const SIZE_ERROR_MESSAGE = 'Ukuran file melebihi batas maksimal 2 MB.';
const ALERT_OPTIONS = {
    icon: 'warning',
    title: 'Ukuran File Terlalu Besar',
    text: 'Ukuran file maksimal 2 MB. Silakan pilih file dengan ukuran yang lebih kecil.',
    confirmButtonText: 'Mengerti',
    confirmButtonColor: '#696cff',
};

function getField(input) {
    const target = input.dataset.fileFieldTarget;

    return (target ? document.querySelector(target) : null)
        || input.closest('[data-file-field]')
        || input;
}

function getErrorElement(input) {
    const errorId = input.dataset.fileError;

    return errorId ? document.getElementById(errorId) : null;
}

function showSizeAlert() {
    if (window.Swal) {
        window.Swal.fire(ALERT_OPTIONS);
    }
}

function setSubmitState(form) {
    const hasInvalidFile = Boolean(form.querySelector('[data-file-input][data-file-invalid="true"]'));

    form.querySelectorAll('button[type="submit"], input[type="submit"]').forEach((button) => {
        if (hasInvalidFile) {
            button.disabled = true;
            button.dataset.fileGuardDisabled = 'true';
            button.classList.add('cursor-not-allowed', 'opacity-60');
        } else if (button.dataset.fileGuardDisabled === 'true') {
            button.disabled = false;
            delete button.dataset.fileGuardDisabled;
            button.classList.remove('cursor-not-allowed', 'opacity-60');
        }
    });
}

function markInvalid(input) {
    const field = getField(input);
    const error = getErrorElement(input);

    input.value = '';
    input.dataset.fileInvalid = 'true';
    input.setAttribute('aria-invalid', 'true');

    if (error) {
        error.textContent = SIZE_ERROR_MESSAGE;
        error.classList.remove('hidden');
        input.setAttribute('aria-describedby', error.id);
    }

    field.classList.add('border-red-500', 'ring-1', 'ring-red-500/20', 'dark:border-red-400');
    field.classList.remove('border-gray-300', 'border-primary-500', 'bg-primary-50');
    setSubmitState(input.form);
}

function clearInvalid(input) {
    const field = getField(input);
    const error = getErrorElement(input);

    delete input.dataset.fileInvalid;
    input.removeAttribute('aria-invalid');

    if (error) {
        error.textContent = '';
        error.classList.add('hidden');

        if (input.getAttribute('aria-describedby') === error.id) {
            input.removeAttribute('aria-describedby');
        }
    }

    field.classList.remove('border-red-500', 'ring-1', 'ring-red-500/20', 'dark:border-red-400');
    setSubmitState(input.form);
}

function updateValidPreview(input, file) {
    const previewId = input.dataset.filePreview;

    if (previewId) {
        const preview = document.getElementById(previewId);
        const prefix = input.dataset.filePreviewPrefix || 'File: ';

        if (preview) {
            preview.textContent = `${prefix}${file.name}`;
        }
    }

    const imagePreviewId = input.dataset.fileImagePreview;

    if (imagePreviewId && file.type.startsWith('image/')) {
        const imagePreview = document.getElementById(imagePreviewId);

        if (imagePreview) {
            const reader = new FileReader();
            reader.addEventListener('load', () => {
                imagePreview.src = reader.result;
            }, { once: true });
            reader.readAsDataURL(file);
        }
    }

    const field = getField(input);
    field.classList.add('border-primary-500', 'bg-primary-50');
    field.classList.remove('border-gray-300');
}

function validateFileInput(input, notify = false) {
    const file = input.files?.[0];
    const maxSize = Number(input.dataset.fileMaxSize || MAX_FILE_SIZE);

    if (!file) {
        if (!input.required) {
            clearInvalid(input);
        }

        return input.dataset.fileInvalid !== 'true';
    }

    if (file.size > maxSize) {
        markInvalid(input);

        if (notify) {
            showSizeAlert();
        }

        return false;
    }

    clearInvalid(input);
    updateValidPreview(input, file);

    return true;
}

function focusFirstInvalidFile(form) {
    const invalidInput = form.querySelector('[data-file-input][data-file-invalid="true"]');

    if (!invalidInput) {
        return;
    }

    getField(invalidInput).scrollIntoView({ behavior: 'smooth', block: 'center' });
    window.setTimeout(() => invalidInput.focus({ preventScroll: true }), 350);
}

function validateUploadForm(form) {
    let valid = true;

    form.querySelectorAll('[data-file-input]').forEach((input) => {
        if (!validateFileInput(input)) {
            valid = false;
        }
    });

    if (!valid) {
        focusFirstInvalidFile(form);
        showSizeAlert();
    }

    return valid;
}

function isDraftable(element) {
    const type = (element.type || '').toLowerCase();

    return element.name
        && !element.disabled
        && !element.matches('[data-draft-ignore]')
        && !['_token', '_method'].includes(element.name)
        && !['file', 'password', 'submit', 'button'].includes(type);
}

function serializeDraft(form) {
    const values = {};

    Array.from(form.elements).filter(isDraftable).forEach((element) => {
        if (element.type === 'checkbox') {
            values[element.name] ??= [];

            if (element.checked) {
                values[element.name].push(element.value);
            }

            return;
        }

        if (element.type === 'radio') {
            if (element.checked) {
                values[element.name] = element.value;
            } else if (!(element.name in values)) {
                values[element.name] = null;
            }

            return;
        }

        values[element.name] = element.value;
    });

    return values;
}

function applyDraftValue(element, value) {
    if (element.type === 'checkbox') {
        element.checked = Array.isArray(value) && value.includes(element.value);
    } else if (element.type === 'radio') {
        element.checked = value === element.value;
    } else if (value !== null && value !== undefined) {
        element.value = value;
    }
}

function draftValuesMatch(currentValue, baselineValue) {
    return JSON.stringify(currentValue) === JSON.stringify(baselineValue);
}

function showDraftRestoredNotice() {
    if (!window.Swal) {
        return;
    }

    window.Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'info',
        title: 'Draft formulir dipulihkan.',
        showConfirmButton: false,
        timer: 2500,
        timerProgressBar: true,
    });
}

function initializeDraft(form) {
    const key = form.dataset.draftKey;

    if (!key) {
        return;
    }

    let savedDraft = null;

    try {
        savedDraft = JSON.parse(sessionStorage.getItem(key));
    } catch {
        sessionStorage.removeItem(key);
    }

    const currentValues = serializeDraft(form);
    let restored = false;

    if (savedDraft?.values && form.dataset.draftHasOld !== 'true') {
        Array.from(form.elements).filter(isDraftable).forEach((element) => {
            if (!(element.name in savedDraft.values)) {
                return;
            }

            const databaseValueChanged = form.dataset.draftMode === 'edit'
                && savedDraft.baseline
                && element.name in savedDraft.baseline
                && !draftValuesMatch(currentValues[element.name], savedDraft.baseline[element.name]);

            if (!databaseValueChanged) {
                applyDraftValue(element, savedDraft.values[element.name]);
                restored = true;
            }
        });
    }

    if (restored) {
        form.dispatchEvent(new Event('change', { bubbles: true }));
        showDraftRestoredNotice();
    }

    const baseline = savedDraft?.baseline || currentValues;
    let saveTimer;
    const saveDraft = () => {
        window.clearTimeout(saveTimer);
        saveTimer = window.setTimeout(() => {
            sessionStorage.setItem(key, JSON.stringify({
                version: 1,
                updatedAt: new Date().toISOString(),
                baseline,
                values: serializeDraft(form),
            }));
        }, 250);
    };

    form.addEventListener('input', saveDraft);
    form.addEventListener('change', saveDraft);
}

function clearSuccessfulDraft() {
    const meta = document.querySelector('meta[name="parent-draft-clear"]');

    if (meta?.content) {
        sessionStorage.removeItem(meta.content);
    }
}

export function initializeParentUploadGuard() {
    clearSuccessfulDraft();

    document.querySelectorAll('[data-parent-upload-form]').forEach((form) => {
        form.querySelectorAll('[data-file-input]').forEach((input) => {
            input.addEventListener('change', () => validateFileInput(input, true));
        });

        form.addEventListener('submit', (event) => {
            if (!validateUploadForm(form)) {
                event.preventDefault();
                event.stopImmediatePropagation();
            }
        });
    });

    document.querySelectorAll('[data-parent-draft-form]').forEach(initializeDraft);
}
