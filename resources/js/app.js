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

const themeToggle = document.querySelector('[data-theme-toggle]');

if (themeToggle) {
    const themeLabel = themeToggle.querySelector('[data-theme-label]');
    const sunIcon = themeToggle.querySelector('[data-theme-sun]');
    const moonIcon = themeToggle.querySelector('[data-theme-moon]');

    const setTheme = (theme) => {
        const isDark = theme === 'dark';
        document.documentElement.dataset.theme = theme;
        localStorage.setItem('itm-color-mode', theme);
        themeToggle.setAttribute('aria-pressed', String(isDark));
        themeLabel.textContent = isDark ? 'Modo claro' : 'Modo oscuro';
        sunIcon.classList.toggle('hidden', !isDark);
        moonIcon.classList.toggle('hidden', isDark);
    };

    setTheme(document.documentElement.dataset.theme === 'dark' ? 'dark' : 'light');

    themeToggle.addEventListener('click', () => {
        setTheme(document.documentElement.dataset.theme === 'dark' ? 'light' : 'dark');
    });
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
