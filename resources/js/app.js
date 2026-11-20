import './calendario.js';

const menuToggle = document.querySelector('[data-navbar-toggle]');
const mobileMenu = document.querySelector('[data-navbar-menu]');

if (menuToggle && mobileMenu) {
    const menuIcon = menuToggle.querySelector('[data-menu-icon]');
    const closeIcon = menuToggle.querySelector('[data-close-icon]');

    const setMenuOpen = (isOpen) => {
        menuToggle.setAttribute('aria-expanded', String(isOpen));
        menuToggle.setAttribute(
            'aria-label',
            isOpen ? 'Cerrar menú de navegación' : 'Abrir menú de navegación',
        );
        mobileMenu.classList.toggle('hidden', !isOpen);
        menuIcon?.classList.toggle('hidden', isOpen);
        closeIcon?.classList.toggle('hidden', !isOpen);
    };

    menuToggle.addEventListener('click', () => {
        setMenuOpen(menuToggle.getAttribute('aria-expanded') !== 'true');
    });

    mobileMenu.addEventListener('click', (event) => {
        if (event.target instanceof HTMLAnchorElement) {
            setMenuOpen(false);
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && menuToggle.getAttribute('aria-expanded') === 'true') {
            setMenuOpen(false);
            menuToggle.focus();
        }
    });
}

const profileMenu = document.querySelector('[data-profile-menu]');

if (profileMenu) {
    const profileToggle = profileMenu.querySelector('[data-profile-toggle]');
    const profileDropdown = profileMenu.querySelector('[data-profile-dropdown]');

    if (profileToggle && profileDropdown) {
        const setProfileMenuOpen = (isOpen) => {
            profileToggle.setAttribute('aria-expanded', String(isOpen));
            profileToggle.setAttribute(
                'aria-label',
                isOpen ? 'Cerrar menú del perfil' : 'Abrir menú del perfil',
            );
            profileDropdown.classList.toggle('hidden', !isOpen);
        };

        profileToggle.addEventListener('click', () => {
            setProfileMenuOpen(profileToggle.getAttribute('aria-expanded') !== 'true');
        });

        document.addEventListener('click', (event) => {
            if (event.target instanceof Node && !profileMenu.contains(event.target)) {
                setProfileMenuOpen(false);
            }
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && profileToggle.getAttribute('aria-expanded') === 'true') {
                setProfileMenuOpen(false);
                profileToggle.focus();
            }
        });
    }
}

const notificationsMenu = document.querySelector('[data-notifications-menu]');

if (notificationsMenu) {
    const notificationsToggle = notificationsMenu.querySelector('[data-notifications-toggle]');
    const notificationsDropdown = notificationsMenu.querySelector('[data-notifications-dropdown]');

    if (notificationsToggle && notificationsDropdown) {
        const setNotificationsOpen = (isOpen) => {
            notificationsToggle.setAttribute('aria-expanded', String(isOpen));
            notificationsDropdown.classList.toggle('hidden', !isOpen);
        };

        notificationsToggle.addEventListener('click', () => {
            setNotificationsOpen(notificationsToggle.getAttribute('aria-expanded') !== 'true');
        });

        document.addEventListener('click', (event) => {
            if (event.target instanceof Node && !notificationsMenu.contains(event.target)) {
                setNotificationsOpen(false);
            }
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && notificationsToggle.getAttribute('aria-expanded') === 'true') {
                setNotificationsOpen(false);
                notificationsToggle.focus();
            }
        });
    }
}

const THEME_STORAGE_KEY = 'itm-color-mode';

const syncThemeControls = (theme) => {
    const normalized = theme === 'dark' ? 'dark' : 'light';
    const isDark = normalized === 'dark';

    document.documentElement.dataset.theme = normalized;

    document.querySelectorAll('[data-theme-toggle]').forEach((toggle) => {
        toggle.setAttribute('aria-pressed', String(isDark));

        const themeLabel = toggle.querySelector('[data-theme-label]');
        const themeBadge = toggle.querySelector('[data-theme-badge]');
        const sunIcon = toggle.querySelector('[data-theme-sun]');
        const moonIcon = toggle.querySelector('[data-theme-moon]');

        if (themeLabel) {
            themeLabel.textContent = isDark ? 'Modo claro' : 'Modo oscuro';
        }

        if (themeBadge) {
            themeBadge.textContent = isDark ? 'Activo' : 'Desactivado';
        }

        sunIcon?.classList.toggle('hidden', !isDark);
        moonIcon?.classList.toggle('hidden', isDark);
    });
};

const savedTheme = localStorage.getItem(THEME_STORAGE_KEY);
const initialTheme = savedTheme === 'dark' || savedTheme === 'light'
    ? savedTheme
    : (document.documentElement.dataset.theme === 'dark' ? 'dark' : 'light');

syncThemeControls(initialTheme);

document.addEventListener('click', (event) => {
    if (!(event.target instanceof Element)) {
        return;
    }

    const toggle = event.target.closest('[data-theme-toggle]');
    if (!toggle) {
        return;
    }

    event.preventDefault();
    event.stopPropagation();

    const nextTheme = document.documentElement.dataset.theme === 'dark' ? 'light' : 'dark';
    localStorage.setItem(THEME_STORAGE_KEY, nextTheme);
    syncThemeControls(nextTheme);
});

document.querySelectorAll('[data-password-toggle]').forEach((toggle) => {
    const inputId = toggle.getAttribute('aria-controls');
    const passwordInput = inputId ? document.getElementById(inputId) : null;
    const toggleLabel = toggle.querySelector('[data-password-toggle-label]');
    const passwordLabel = toggle.getAttribute('data-password-label');

    if (
        !(passwordInput instanceof HTMLInputElement)
        || !(toggleLabel instanceof HTMLElement)
        || !passwordLabel
    ) {
        return;
    }

    toggle.addEventListener('click', () => {
        const isVisible = passwordInput.type === 'text';
        passwordInput.type = isVisible ? 'password' : 'text';
        toggleLabel.textContent = isVisible ? 'Mostrar' : 'Ocultar';
        toggle.setAttribute('aria-label', `${isVisible ? 'Mostrar' : 'Ocultar'} ${passwordLabel}`);
        toggle.setAttribute('aria-pressed', String(!isVisible));
    });
});

document.querySelectorAll('[data-account-form]').forEach((accountForm) => {
    const roleSelect = accountForm.querySelector('[data-account-role]');
    const applicantFields = accountForm.querySelector('[data-applicant-fields]');
    const applicantType = accountForm.querySelector('[data-applicant-type]');
    const positionField = accountForm.querySelector('[data-teaching-position-field]');
    const positionSelect = accountForm.querySelector('[name="plaza_jornada"]');

    if (
        applicantType instanceof HTMLSelectElement
        && positionField instanceof HTMLElement
        && positionSelect instanceof HTMLSelectElement
    ) {
        const updateAccountFields = () => {
            const isApplicant = !(roleSelect instanceof HTMLSelectElement)
                || roleSelect.value === 'usuario';
            const isTeachingApplicant = isApplicant && applicantType.value === 'aspirante_docente';

            if (applicantFields instanceof HTMLElement) {
                applicantFields.hidden = !isApplicant;
                applicantFields.querySelectorAll('input, select').forEach((field) => {
                    if (field instanceof HTMLInputElement || field instanceof HTMLSelectElement) {
                        field.disabled = !isApplicant;

                        if (field.hasAttribute('data-required-for-applicant')) {
                            field.required = isApplicant;
                        }
                    }
                });
            }

            positionField.hidden = !isTeachingApplicant;
            positionSelect.disabled = !isTeachingApplicant;
            positionSelect.required = isTeachingApplicant;

            if (!isTeachingApplicant) {
                positionSelect.value = '';
            }
        };

        roleSelect?.addEventListener('change', updateAccountFields);
        applicantType.addEventListener('change', updateAccountFields);
        updateAccountFields();
    }
});

const convocatoriaForm = document.querySelector('[data-convocatoria-form]');

if (convocatoriaForm) {
    const typeControls = convocatoriaForm.querySelectorAll('[data-convocatoria-type]');
    const detailSections = convocatoriaForm.querySelectorAll('[data-convocatoria-details]');

    const updateConvocatoriaDetails = () => {
        const tipoPersonal = convocatoriaForm.querySelector('[name="tipo_personal"]')?.value;
        const tipoConcurso = convocatoriaForm.querySelector('[name="tipo_concurso"]')?.value;

        detailSections.forEach((section) => {
            const isSelected = section.dataset.tipoPersonal === tipoPersonal
                && (section.dataset.tipoConcurso === '*' || section.dataset.tipoConcurso === tipoConcurso);
            section.hidden = !isSelected;
            section.disabled = !isSelected;
        });
    };

    typeControls.forEach((control) => control.addEventListener('change', updateConvocatoriaDetails));
    updateConvocatoriaDetails();
}

const photoModal = document.querySelector('[data-photo-modal]');

if (photoModal) {
    const photoModalOpen = document.querySelector('[data-photo-modal-open]');
    const photoModalClose = photoModal.querySelector('[data-photo-modal-close]');
    const photoModalPanel = photoModal.querySelector('[data-photo-modal-panel]');
    const photoCurrent = photoModal.querySelector('[data-photo-current]');
    const photoEmpty = photoModal.querySelector('[data-photo-empty]');
    const photoEditor = photoModal.querySelector('[data-photo-editor]');
    const photoChooseButtons = photoModal.querySelectorAll('[data-photo-choose]');
    const photoInput = photoModal.querySelector('[data-photo-input]');
    const photoStage = photoModal.querySelector('[data-photo-crop-stage]');
    const photoPreviewCanvas = photoModal.querySelector('[data-photo-crop-preview]');
    const photoZoom = photoModal.querySelector('[data-photo-zoom]');
    const photoUploadForm = photoModal.querySelector('[data-photo-upload-form]');
    const photoSubmit = photoModal.querySelector('[data-photo-submit]');
    const photoCancelSelection = photoModal.querySelector('[data-photo-cancel-selection]');
    let photoObjectUrl = null;
    let photoSourceImage = null;
    let photoScale = 1;
    let photoOffsetX = 0;
    let photoOffsetY = 0;
    let photoBaseScale = 1;
    let photoImageWidth = 0;
    let photoImageHeight = 0;
    let photoDragging = false;
    let photoDragOrigin = null;
    let photoPreviousFocus = null;

    const setModalOpen = (isOpen) => {
        photoModal.classList.toggle('hidden', !isOpen);
        photoModal.classList.toggle('flex', isOpen);
        photoModal.setAttribute('aria-hidden', String(!isOpen));
        document.body.classList.toggle('overflow-hidden', isOpen);

        if (isOpen) {
            photoPreviousFocus = document.activeElement;
            photoModalClose?.focus();
        } else {
            photoPreviousFocus?.focus();
        }
    };

    const setCropImagePosition = () => {
        if (!photoPreviewCanvas || !photoStage || !photoSourceImage || !photoImageWidth || !photoImageHeight) {
            return;
        }

        photoScale = Number(photoZoom?.value ?? 1);
        const stageSize = photoStage.clientWidth;
        const scaledWidth = photoImageWidth * photoBaseScale * photoScale;
        const scaledHeight = photoImageHeight * photoBaseScale * photoScale;
        const maxOffsetX = Math.max(0, (scaledWidth - stageSize) / 2);
        const maxOffsetY = Math.max(0, (scaledHeight - stageSize) / 2);
        photoOffsetX = Math.max(-maxOffsetX, Math.min(maxOffsetX, photoOffsetX));
        photoOffsetY = Math.max(-maxOffsetY, Math.min(maxOffsetY, photoOffsetY));
        photoDrawCrop(photoPreviewCanvas, photoPreviewCanvas.width);
    };

    const photoGetCrop = () => {
        const sourceSize = Math.min(photoImageWidth, photoImageHeight) / photoScale;
        const sourceScale = photoBaseScale * photoScale;

        return {
            x: (photoImageWidth - sourceSize) / 2 - photoOffsetX / sourceScale,
            y: (photoImageHeight - sourceSize) / 2 - photoOffsetY / sourceScale,
            size: sourceSize,
        };
    };

    const photoDrawCrop = (canvas, outputSize) => {
        const context = canvas.getContext('2d');

        if (!context || !photoSourceImage) {
            throw new Error('No se pudo preparar la vista previa de la foto.');
        }

        const crop = photoGetCrop();
        canvas.width = outputSize;
        canvas.height = outputSize;
        context.fillStyle = '#ffffff';
        context.fillRect(0, 0, outputSize, outputSize);
        context.imageSmoothingEnabled = true;
        context.imageSmoothingQuality = 'high';
        context.drawImage(
            photoSourceImage,
            crop.x,
            crop.y,
            crop.size,
            crop.size,
            0,
            0,
            outputSize,
            outputSize,
        );
    };

    const showCurrentPhoto = () => {
        photoEditor?.classList.add('hidden');
        photoCurrent?.classList.remove('hidden');
        photoEmpty?.classList.remove('hidden');
        photoSubmit.disabled = true;
        photoSourceImage = null;
        photoImageWidth = 0;
        photoImageHeight = 0;
        if (photoObjectUrl) {
            URL.revokeObjectURL(photoObjectUrl);
            photoObjectUrl = null;
        }
        if (photoInput) {
            photoInput.value = '';
        }
    };

    const choosePhoto = () => {
        showCurrentPhoto();
        if (photoInput) {
            photoInput.value = '';
            photoInput.click();
        }
    };

    photoModalOpen?.addEventListener('click', () => setModalOpen(true));
    photoModalClose?.addEventListener('click', () => setModalOpen(false));
    photoModal.addEventListener('click', (event) => {
        if (event.target === photoModal) {
            setModalOpen(false);
        }
    });
    photoModalPanel?.addEventListener('click', (event) => event.stopPropagation());
    photoChooseButtons.forEach((button) => button.addEventListener('click', choosePhoto));
    photoCancelSelection?.addEventListener('click', showCurrentPhoto);
    photoZoom?.addEventListener('input', setCropImagePosition);

    photoInput?.addEventListener('change', () => {
        const file = photoInput.files?.[0];

        if (!file) {
            return;
        }

        if (photoObjectUrl) {
            URL.revokeObjectURL(photoObjectUrl);
        }

        photoObjectUrl = URL.createObjectURL(file);
        photoSourceImage = new Image();
        photoSourceImage.onload = () => {
            photoImageWidth = photoSourceImage.naturalWidth;
            photoImageHeight = photoSourceImage.naturalHeight;

            if (!photoImageWidth || !photoImageHeight || photoImageWidth > 12000 || photoImageHeight > 12000) {
                showCurrentPhoto();
                window.alert('La imagen no se puede procesar. Elige una imagen válida de hasta 12000 × 12000 px.');
                return;
            }

            photoOffsetX = 0;
            photoOffsetY = 0;
            photoZoom.value = '1';
            photoCurrent?.classList.add('hidden');
            photoEmpty?.classList.add('hidden');
            photoEditor?.classList.remove('hidden');
            const stageSize = photoStage.clientWidth;

            if (!stageSize) {
                showCurrentPhoto();
                window.alert('No se pudo preparar el área de recorte. Cierra la ventana e inténtalo de nuevo.');
                return;
            }

            photoBaseScale = stageSize / Math.min(photoImageWidth, photoImageHeight);
            photoSubmit.disabled = false;

            try {
                setCropImagePosition();
            } catch (error) {
                showCurrentPhoto();
                window.alert(error instanceof Error ? error.message : 'No se pudo mostrar la imagen seleccionada.');
            }
        };
        photoSourceImage.onerror = () => {
            showCurrentPhoto();
            window.alert('No se pudo abrir la imagen seleccionada. Prueba con otro archivo JPG, PNG o WebP.');
        };
        photoSourceImage.src = photoObjectUrl;
    });

    photoStage?.addEventListener('pointerdown', (event) => {
        if (photoEditor.classList.contains('hidden')) {
            return;
        }

        photoDragging = true;
        photoDragOrigin = {
            x: event.clientX,
            y: event.clientY,
            offsetX: photoOffsetX,
            offsetY: photoOffsetY,
        };
        photoStage.setPointerCapture(event.pointerId);
    });

    photoStage?.addEventListener('pointermove', (event) => {
        if (!photoDragging || !photoDragOrigin) {
            return;
        }

        photoOffsetX = photoDragOrigin.offsetX + event.clientX - photoDragOrigin.x;
        photoOffsetY = photoDragOrigin.offsetY + event.clientY - photoDragOrigin.y;
        setCropImagePosition();
    });

    photoStage?.addEventListener('pointerup', () => {
        photoDragging = false;
        photoDragOrigin = null;
    });
    photoStage?.addEventListener('pointercancel', () => {
        photoDragging = false;
        photoDragOrigin = null;
    });

    photoUploadForm?.addEventListener('submit', (event) => {
        if (!photoInput?.files?.[0] || !photoSourceImage || !photoImageWidth || !photoImageHeight) {
            event.preventDefault();
            return;
        }

        event.preventDefault();
        photoSubmit.disabled = true;
        try {
            const canvas = document.createElement('canvas');
            photoDrawCrop(canvas, 512);
            canvas.toBlob((blob) => {
                if (!blob || blob.size === 0) {
                    photoSubmit.disabled = false;
                    window.alert('No se pudo generar la foto recortada. Inténtalo de nuevo.');
                    return;
                }

                try {
                    const croppedPhoto = new File([blob], 'foto-perfil.jpg', { type: 'image/jpeg' });
                    const transfer = new DataTransfer();
                    transfer.items.add(croppedPhoto);
                    photoInput.files = transfer.files;
                    photoUploadForm.submit();
                } catch (error) {
                    photoSubmit.disabled = false;
                    window.alert(error instanceof Error ? error.message : 'No se pudo preparar la foto para subirla.');
                }
            }, 'image/jpeg', 0.9);
        } catch (error) {
            photoSubmit.disabled = false;
            window.alert(error instanceof Error ? error.message : 'No se pudo recortar la foto seleccionada.');
        }
    });

    document.addEventListener('keydown', (event) => {
        if (photoModal.getAttribute('aria-hidden') !== 'false') {
            return;
        }

        if (event.key === 'Escape') {
            setModalOpen(false);
            return;
        }

        if (event.key !== 'Tab') {
            return;
        }

        const focusableElements = photoModal.querySelectorAll(
            'button:not([disabled]), input:not([disabled]), [href], [tabindex]:not([tabindex="-1"])',
        );
        const firstElement = focusableElements[0];
        const lastElement = focusableElements[focusableElements.length - 1];

        if (event.shiftKey && document.activeElement === firstElement) {
            event.preventDefault();
            lastElement?.focus();
        } else if (!event.shiftKey && document.activeElement === lastElement) {
            event.preventDefault();
            firstElement?.focus();
        }
    });

    if (photoModal.hasAttribute('data-photo-modal-autopen')) {
        setModalOpen(true);
        photoEditor?.classList.remove('hidden');
        photoCurrent?.classList.add('hidden');
        photoEmpty?.classList.add('hidden');
    }
}

document.querySelectorAll('[data-pdf-viewer]').forEach((pdfViewer) => {
    const pdfFrame = pdfViewer.querySelector('[data-pdf-frame]');
    const previewPanel = pdfViewer.querySelector('[data-pdf-preview]');
    const placeholder = pdfViewer.querySelector('[data-pdf-placeholder]');
    const pdfTitle = pdfViewer.querySelector('[data-pdf-current-title]');
    const openPdfLink = pdfViewer.querySelector('[data-pdf-open-new-tab]');
    const pdfStatus = pdfViewer.querySelector('[data-pdf-viewer-status]');

    const setPdfStatus = (message) => {
        if (!(pdfStatus instanceof HTMLElement)) {
            return;
        }

        pdfStatus.textContent = message;
        pdfStatus.classList.toggle('hidden', message === '');
    };

    const setToggleButtonState = (button, isExpanded) => {
        button.setAttribute('aria-expanded', String(isExpanded));
        button.setAttribute(
            'aria-label',
            `${isExpanded ? 'Ocultar' : 'Mostrar'} PDF: ${button.dataset.pdfTitle ?? 'documento'}`,
        );
        button.classList.toggle('border-rose-300', isExpanded);
        button.classList.toggle('bg-rose-50', isExpanded);
        button.classList.toggle('text-rose-900', isExpanded);
        button.classList.toggle('border-gray-200', !isExpanded);
        button.classList.toggle('bg-white', !isExpanded);
        button.classList.toggle('text-gray-700', !isExpanded);

        const label = button.querySelector('[data-pdf-toggle-label]');
        const chevron = button.querySelector('[data-pdf-toggle-chevron]');

        if (label instanceof HTMLElement) {
            label.textContent = isExpanded ? 'Ocultar' : 'Ver PDF';
        }

        chevron?.classList.toggle('rotate-180', isExpanded);
    };

    const hidePdf = () => {
        if (pdfFrame instanceof HTMLIFrameElement) {
            pdfFrame.removeAttribute('src');
        }

        previewPanel?.classList.add('hidden');
        placeholder?.classList.remove('hidden');

        pdfViewer.querySelectorAll('[data-pdf-viewer-toggle]').forEach((button) => {
            if (button instanceof HTMLButtonElement) {
                setToggleButtonState(button, false);
            }
        });

        if (openPdfLink instanceof HTMLAnchorElement) {
            openPdfLink.href = '#';
        }

        if (pdfTitle instanceof HTMLElement) {
            pdfTitle.textContent = '';
        }

        setPdfStatus('');
    };

    pdfViewer.addEventListener('click', async (event) => {
        if (!(event.target instanceof Element)) {
            return;
        }

        const toggleButton = event.target.closest('[data-pdf-viewer-toggle]');

        if (toggleButton instanceof HTMLButtonElement) {
            const pdfUrl = toggleButton.dataset.pdfUrl;
            const title = toggleButton.dataset.pdfTitle;
            const controlledPanel = toggleButton.getAttribute('aria-controls');

            if (
                !(pdfFrame instanceof HTMLIFrameElement)
                || !(previewPanel instanceof HTMLElement)
                || !(placeholder instanceof HTMLElement)
                || !(openPdfLink instanceof HTMLAnchorElement)
                || !(pdfTitle instanceof HTMLElement)
                || !pdfUrl
                || !title
                || controlledPanel !== previewPanel.id
            ) {
                setPdfStatus('No se pudo cargar el documento seleccionado.');
                return;
            }

            if (toggleButton.getAttribute('aria-expanded') === 'true') {
                hidePdf();

                return;
            }

            pdfFrame.src = pdfUrl;
            pdfFrame.title = `PDF: ${title}`;
            openPdfLink.href = pdfUrl;
            pdfTitle.textContent = title;
            previewPanel.classList.remove('hidden');
            placeholder.classList.add('hidden');

            pdfViewer.querySelectorAll('[data-pdf-viewer-toggle]').forEach((button) => {
                if (!(button instanceof HTMLButtonElement)) {
                    return;
                }

                setToggleButtonState(button, button === toggleButton);
            });

            setPdfStatus('');

            return;
        }

        if (event.target.closest('[data-pdf-hide]')) {
            hidePdf();

            return;
        }

        const fullscreenButton = event.target.closest('[data-pdf-fullscreen]');

        if (!(fullscreenButton instanceof HTMLButtonElement)) {
            return;
        }

        const fullscreenFrameId = fullscreenButton.dataset.pdfFullscreen;
        const fullscreenFrame = fullscreenFrameId
            ? document.getElementById(fullscreenFrameId)
            : null;

        if (!(fullscreenFrame instanceof HTMLIFrameElement) || fullscreenFrame !== pdfFrame) {
            setPdfStatus('No se encontró el visor para abrirlo en pantalla completa.');

            return;
        }

        if (typeof fullscreenFrame.requestFullscreen !== 'function') {
            setPdfStatus('Este navegador no permite mostrar el documento en pantalla completa.');

            return;
        }

        try {
            await fullscreenFrame.requestFullscreen();
            setPdfStatus('');
        } catch {
            setPdfStatus('El navegador no permitió abrir el documento en pantalla completa.');
        }
    });
});