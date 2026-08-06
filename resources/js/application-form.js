document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('driver-application-form');
    if (!form) return;

    const STORAGE_KEY = 'driver_application_draft';
    const TOTAL_STEPS = 6;
    let currentStep = 1;

    const steps = form.querySelectorAll('[data-step]');
    const stepPanels = form.querySelectorAll('[data-step-panel]');
    const progressBar = document.getElementById('progress-bar');
    const stepLabel = document.getElementById('step-label');
    const prevBtn = document.getElementById('prev-step');
    const nextBtn = document.getElementById('next-step');
    const submitBtn = document.getElementById('submit-application');
    const employerContainer = document.getElementById('employers-container');
    const addEmployerBtn = document.getElementById('add-employer');
    const addEmployerLabel = document.getElementById('add-employer-label');
    const noEmploymentCheckbox = document.getElementById('no_employment_history');
    const employmentFields = document.getElementById('employment-fields');
    const employmentEmptyNote = document.getElementById('employment-empty-note');
    const careerTextarea = document.getElementById('driving_career');
    const careerCounter = document.getElementById('career-counter');
    const loadingOverlay = document.getElementById('loading-overlay');

    const stepTitles = [
        { title: 'Personal Details', icon: 'fa-user' },
        { title: 'Driving Information', icon: 'fa-id-card' },
        { title: 'Employment History', icon: 'fa-building' },
        { title: 'Experience', icon: 'fa-road' },
        { title: 'Documents', icon: 'fa-cloud-arrow-up' },
        { title: 'Review & Submit', icon: 'fa-clipboard-check' },
    ];

    function updateUI() {
        stepPanels.forEach(panel => {
            panel.classList.toggle('hidden', parseInt(panel.dataset.stepPanel) !== currentStep);
        });

        steps.forEach(step => {
            const num = parseInt(step.dataset.step);
            step.classList.remove('bg-brand-600', 'text-white', 'bg-brand-100', 'text-brand-700', 'bg-slate-100', 'text-slate-500', 'ring-2', 'ring-brand-400', 'shadow-md');
            if (num < currentStep) {
                step.classList.add('bg-brand-100', 'text-brand-700');
            } else if (num === currentStep) {
                step.classList.add('bg-brand-600', 'text-white', 'ring-2', 'ring-brand-400', 'shadow-md');
            } else {
                step.classList.add('bg-slate-100', 'text-slate-500');
            }
        });

        if (progressBar) {
            progressBar.style.width = `${(currentStep / TOTAL_STEPS) * 100}%`;
        }
        if (stepLabel) {
            const current = stepTitles[currentStep - 1];
            stepLabel.innerHTML = `<i class="fa-solid ${current.icon} text-brand-500"></i> Step ${currentStep} of ${TOTAL_STEPS} — ${current.title}`;
        }

        prevBtn.classList.toggle('hidden', currentStep === 1);
        nextBtn.classList.toggle('hidden', currentStep === TOTAL_STEPS);
        submitBtn.classList.toggle('hidden', currentStep !== TOTAL_STEPS);

        if (currentStep === TOTAL_STEPS) {
            populateReview();
        }

        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function getFieldValue(name) {
        const field = form.querySelector(`[name="${name}"]`);
        if (!field) return '';
        if (field.type === 'checkbox') return field.checked;
        return field.value;
    }

    function validateStep(step) {
        clearStepErrors(step);
        let valid = true;
        const panel = form.querySelector(`[data-step-panel="${step}"]`);
        const requiredFields = panel.querySelectorAll('[required]');

        requiredFields.forEach(field => {
            if (field.type === 'checkbox' && field.name === 'vehicle_types[]') return;
            if (field.type === 'file') return;

            if (field.type === 'checkbox') {
                if (!field.checked) {
                    showFieldError(field, 'This field is required.');
                    valid = false;
                }
                return;
            }

            if (!field.value.trim()) {
                showFieldError(field, 'This field is required.');
                valid = false;
            }
        });

        if (step === 2) {
            const checked = panel.querySelectorAll('input[name="vehicle_types[]"]:checked');
            if (checked.length === 0) {
                const container = document.getElementById('vehicle-types-error');
                if (container) {
                    container.textContent = 'Please select at least one vehicle type.';
                    container.classList.remove('hidden');
                }
                valid = false;
            }
        }

        if (step === 3) {
            if (noEmploymentCheckbox?.checked) {
                return valid;
            }

            const employers = employerContainer.querySelectorAll('.employer-entry');
            employers.forEach(entry => {
                const companyName = entry.querySelector('[name*="company_name"]');
                const hasCompany = companyName?.value.trim();

                if (! hasCompany) {
                    return;
                }

                entry.querySelectorAll('.employer-field').forEach(field => {
                    if (! field.value.trim()) {
                        showFieldError(field, 'This field is required when adding an employer.');
                        valid = false;
                    }
                });
            });
        }

        if (step === 4 && careerTextarea) {
            if (careerTextarea.value.trim().length < 50) {
                showFieldError(careerTextarea, 'Please provide at least 50 characters.');
                valid = false;
            }
        }

        if (step === 5) {
            ['id_front', 'id_back', 'selfie', 'licence_document'].forEach(name => {
                const input = form.querySelector(`input[name="${name}"]`);
                if (input && !input.files.length && !input.dataset.hasServerFile) {
                    showFieldError(input, 'This document is required.');
                    valid = false;
                }
            });
        }

        if (step === 6) {
            const declaration = form.querySelector('#declaration');
            const signature = form.querySelector('#digital_signature');
            const fullName = form.querySelector('#full_name');

            if (declaration && !declaration.checked) {
                showFieldError(declaration, 'You must accept the declaration.');
                valid = false;
            }
            if (signature && fullName && signature.value.trim().toLowerCase() !== fullName.value.trim().toLowerCase()) {
                showFieldError(signature, 'Must match your full name exactly.');
                valid = false;
            }
        }

        return valid;
    }

    function showFieldError(field, message) {
        const fileWrapper = field.type === 'file' ? field.closest('[data-file-upload]') : null;
        const target = fileWrapper?.querySelector('[data-dropzone]') ?? field;
        const errorHost = fileWrapper ?? field.parentElement;

        target.classList.add('border-red-500', 'ring-2', 'ring-red-500/20');
        let errorEl = errorHost.querySelector('.js-field-error');
        if (!errorEl) {
            errorEl = document.createElement('p');
            errorEl.className = 'form-error js-field-error';
            errorHost.appendChild(errorEl);
        }
        errorEl.textContent = message;
    }

    function clearStepErrors(step) {
        const panel = form.querySelector(`[data-step-panel="${step}"]`);
        panel.querySelectorAll('.js-field-error').forEach(el => el.remove());
        panel.querySelectorAll('.border-red-500').forEach(el => {
            el.classList.remove('border-red-500', 'ring-red-500/20', 'ring-2');
        });
        const vehicleError = document.getElementById('vehicle-types-error');
        if (vehicleError) vehicleError.classList.add('hidden');
    }

    function saveDraft() {
        const data = {};
        const formData = new FormData(form);
        for (const [key, value] of formData.entries()) {
            if (value instanceof File) continue;
            if (key.endsWith('[]')) {
                const k = key.slice(0, -2);
                data[k] = data[k] || [];
                data[k].push(value);
            } else {
                data[key] = value;
            }
        }
        try {
            localStorage.setItem(STORAGE_KEY, JSON.stringify(data));
        } catch (_) {}
    }

    function loadDraft() {
        try {
            const raw = localStorage.getItem(STORAGE_KEY);
            if (!raw) return;
            const data = JSON.parse(raw);

            Object.entries(data).forEach(([key, value]) => {
                if (Array.isArray(value)) {
                    value.forEach(v => {
                        const inputs = form.querySelectorAll(`[name="${key}[]"]`);
                        inputs.forEach(input => {
                            if (input.type === 'checkbox' && input.value === v) {
                                input.checked = true;
                            }
                        });
                    });
                    return;
                }

                const field = form.querySelector(`[name="${key}"]`);
                if (field && field.type !== 'file') {
                    if (field.type === 'checkbox') {
                        field.checked = value === 'on' || value === true;
                    } else {
                        field.value = value;
                    }
                }
            });

            if (data.employment_history && Array.isArray(data.employment_history)) {
                employerContainer.innerHTML = '';
                data.employment_history.forEach((_, index) => addEmployerEntry(index));
            }

            toggleNoEmploymentHistory();
            updateEmploymentUi();
        } catch (_) {}
    }

    function addEmployerEntry(index = null) {
        const idx = index ?? employerContainer.querySelectorAll('.employer-entry').length;
        const template = document.getElementById('employer-template');
        const clone = template.content.cloneNode(true);
        const entry = clone.querySelector('.employer-entry');

        entry.querySelectorAll('[name]').forEach(field => {
            field.name = field.name.replace('__INDEX__', idx);
            field.id = field.id.replace('__INDEX__', idx);
        });

        entry.querySelector('.employer-number').textContent = idx + 1;

        entry.querySelector('.remove-employer').addEventListener('click', () => {
            entry.remove();
            reindexEmployers();
            updateEmploymentUi();
            saveDraft();
        });

        employerContainer.appendChild(entry);
        updateEmploymentUi();
    }

    function updateEmploymentUi() {
        const count = employerContainer.querySelectorAll('.employer-entry').length;

        if (addEmployerLabel) {
            addEmployerLabel.textContent = count === 0 ? 'Add Employer' : 'Add Another Employer';
        }

        if (employmentEmptyNote) {
            employmentEmptyNote.classList.toggle('hidden', count > 0 || noEmploymentCheckbox?.checked);
        }
    }

    function setEmploymentFieldsDisabled(disabled) {
        employmentFields?.querySelectorAll('.employer-field').forEach(field => {
            field.disabled = disabled;
        });

        if (addEmployerBtn) {
            addEmployerBtn.disabled = disabled;
            addEmployerBtn.classList.toggle('opacity-50', disabled);
            addEmployerBtn.classList.toggle('pointer-events-none', disabled);
        }
    }

    function toggleNoEmploymentHistory() {
        const none = noEmploymentCheckbox?.checked;

        employmentFields?.classList.toggle('hidden', !!none);
        setEmploymentFieldsDisabled(!!none);
        updateEmploymentUi();
        saveDraft();
    }

    function reindexEmployers() {
        employerContainer.querySelectorAll('.employer-entry').forEach((entry, idx) => {
            entry.querySelector('.employer-number').textContent = idx + 1;
            entry.querySelectorAll('[name]').forEach(field => {
                field.name = field.name.replace(/employment_history\[\d+\]/, `employment_history[${idx}]`);
                field.id = field.id.replace(/employment_history_\d+/, `employment_history_${idx}`);
            });
        });
    }

    function setupFileUploads() {
        form.querySelectorAll('[data-file-upload]').forEach(wrapper => {
            const input = wrapper.querySelector('input[type="file"]');
            const dropzone = wrapper.querySelector('[data-dropzone]');
            const preview = wrapper.querySelector('[data-preview]');
            const progress = wrapper.querySelector('[data-progress]');
            const progressBarEl = wrapper.querySelector('[data-progress-bar]');
            const fileName = wrapper.querySelector('[data-filename]');

            if (!input || !dropzone) return;

            ['dragenter', 'dragover'].forEach(evt => {
                dropzone.addEventListener(evt, e => {
                    e.preventDefault();
                    dropzone.classList.add('border-brand-500', 'bg-brand-50');
                });
            });

            ['dragleave', 'drop'].forEach(evt => {
                dropzone.addEventListener(evt, e => {
                    e.preventDefault();
                    dropzone.classList.remove('border-brand-500', 'bg-brand-50');
                });
            });

            dropzone.addEventListener('drop', e => {
                const files = e.dataTransfer.files;
                if (files.length) {
                    input.files = files;
                    handleFile(input, preview, progress, progressBarEl, fileName);
                }
            });

            input.addEventListener('change', () => {
                handleFile(input, preview, progress, progressBarEl, fileName);
            });
        });
    }

    function handleFile(input, preview, progress, progressBarEl, fileName) {
        const file = input.files[0];
        if (!file) return;

        const maxSize = 5 * 1024 * 1024;
        const allowed = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];

        if (file.size > maxSize) {
            showFieldError(input, 'File must not exceed 5 MB.');
            input.value = '';
            return;
        }

        if (!allowed.includes(file.type)) {
            showFieldError(input, 'Only PDF, JPG, JPEG, and PNG files are allowed.');
            input.value = '';
            return;
        }

        if (fileName) fileName.textContent = file.name;

        if (progress && progressBarEl) {
            progress.classList.remove('hidden');
            let pct = 0;
            const interval = setInterval(() => {
                pct += 20;
                progressBarEl.style.width = `${pct}%`;
                if (pct >= 100) clearInterval(interval);
            }, 50);
        }

        if (preview && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = e => {
                preview.innerHTML = `<img src="${e.target.result}" alt="Preview" class="mt-3 max-h-32 rounded-lg border border-slate-200">`;
                preview.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        } else if (preview) {
            preview.innerHTML = `<p class="mt-3 text-sm text-slate-500">📄 ${file.name}</p>`;
            preview.classList.remove('hidden');
        }

        saveDraft();
    }

    function populateReview() {
        const review = document.getElementById('review-content');
        if (!review) return;

        const fullName = getFieldValue('full_name');
        const email = getFieldValue('email');
        const phone = getFieldValue('phone');
        const licence = getFieldValue('licence_number');
        const experience = getFieldValue('years_of_experience');
        const career = getFieldValue('driving_career');

        const vehicleTypes = [...form.querySelectorAll('input[name="vehicle_types[]"]:checked')]
            .map(cb => cb.parentElement.textContent.trim());

        const employers = [...employerContainer.querySelectorAll('.employer-entry')].map(entry => {
            return entry.querySelector('[name*="company_name"]')?.value || '';
        }).filter(Boolean);

        const employersLabel = noEmploymentCheckbox?.checked
            ? 'None provided'
            : (employers.length ? employers.join(', ') : 'None provided');

        review.innerHTML = `
            <div class="space-y-4 text-sm">
                <div><strong>Name:</strong> ${escapeHtml(fullName)}</div>
                <div><strong>Email:</strong> ${escapeHtml(email)}</div>
                <div><strong>Phone:</strong> ${escapeHtml(phone)}</div>
                <div><strong>Licence:</strong> ${escapeHtml(licence)}</div>
                <div><strong>Experience:</strong> ${escapeHtml(experience)} years</div>
                <div><strong>Vehicle Types:</strong> ${escapeHtml(vehicleTypes.join(', '))}</div>
                <div><strong>Employers:</strong> ${escapeHtml(employersLabel)}</div>
                <div><strong>Driving Career:</strong><br>${escapeHtml(career.substring(0, 300))}${career.length > 300 ? '...' : ''}</div>
            </div>
        `;
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    prevBtn?.addEventListener('click', () => {
        if (currentStep > 1) {
            currentStep--;
            updateUI();
        }
    });

    nextBtn?.addEventListener('click', () => {
        if (validateStep(currentStep) && currentStep < TOTAL_STEPS) {
            saveDraft();
            currentStep++;
            updateUI();
        }
    });

    addEmployerBtn?.addEventListener('click', () => {
        if (noEmploymentCheckbox?.checked) {
            noEmploymentCheckbox.checked = false;
            toggleNoEmploymentHistory();
        }
        addEmployerEntry();
        saveDraft();
    });

    noEmploymentCheckbox?.addEventListener('change', toggleNoEmploymentHistory);

    careerTextarea?.addEventListener('input', () => {
        if (careerCounter) {
            careerCounter.textContent = `${careerTextarea.value.length} / 5000 characters (minimum 50)`;
        }
        saveDraft();
    });

    form.addEventListener('input', e => {
        if (e.target.type !== 'file') saveDraft();
    });

    form.addEventListener('change', e => {
        if (e.target.type === 'checkbox' || e.target.type === 'select-one') saveDraft();
    });

    form.addEventListener('submit', e => {
        for (let step = 1; step <= TOTAL_STEPS; step++) {
            if (! validateStep(step)) {
                e.preventDefault();
                currentStep = step;
                updateUI();
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fa-solid fa-paper-plane mr-1.5"></i> Submit Application';
                if (loadingOverlay) loadingOverlay.classList.add('hidden');
                return;
            }
        }

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-1.5"></i> Submitting...';
        if (loadingOverlay) loadingOverlay.classList.remove('hidden');
        localStorage.removeItem(STORAGE_KEY);
    });

    employerContainer.querySelectorAll('.remove-employer').forEach(btn => {
        btn.addEventListener('click', function () {
            this.closest('.employer-entry')?.remove();
            reindexEmployers();
            updateEmploymentUi();
            saveDraft();
        });
    });

    setupFileUploads();
    loadDraft();
    toggleNoEmploymentHistory();
    updateEmploymentUi();
    updateUI();

    if (careerTextarea && careerCounter) {
        careerCounter.textContent = `${careerTextarea.value.length} / 5000 characters (minimum 50)`;
    }

    const serverErrors = form.querySelectorAll('.server-error-step');
    if (serverErrors.length > 0) {
        const firstErrorStep = Math.min(...[...serverErrors].map(el => parseInt(el.dataset.serverErrorStep)));
        currentStep = firstErrorStep;
        updateUI();
    }
});
