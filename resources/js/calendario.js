const calendarRoot = document.querySelector('[data-calendar-root]');

if (calendarRoot) {
    const calendarContent = calendarRoot.querySelector('[data-calendar-content]');
    const feedback = calendarRoot.querySelector('[data-calendar-feedback]');
    let requestPending = false;

    const feedbackClasses = [
        'border-red-200',
        'bg-red-50',
        'text-red-800',
        'border-emerald-200',
        'bg-emerald-50',
        'text-emerald-800',
    ];

    const showFeedback = (message, type = 'success') => {
        feedback.textContent = message;
        feedback.hidden = false;
        feedback.setAttribute('role', type === 'error' ? 'alert' : 'status');
        feedback.setAttribute('aria-live', type === 'error' ? 'assertive' : 'polite');
        feedback.classList.remove(...feedbackClasses);
        feedback.classList.add(
            ...(type === 'error'
                ? ['border-red-200', 'bg-red-50', 'text-red-800']
                : ['border-emerald-200', 'bg-emerald-50', 'text-emerald-800']),
        );
    };

    const clearFeedback = () => {
        feedback.textContent = '';
        feedback.hidden = true;
    };

    const getResponseMessage = (payload, status) => {
        if (payload?.errors && typeof payload.errors === 'object') {
            const validationMessages = Object.values(payload.errors)
                .flat()
                .filter((message) => typeof message === 'string');

            if (validationMessages.length > 0) {
                return validationMessages.join(' ');
            }
        }

        if (typeof payload?.message === 'string') {
            return payload.message;
        }

        return `No se pudo completar la solicitud (código ${status}).`;
    };

    const getJsonResponse = async (response) => {
        const contentType = response.headers.get('content-type') ?? '';

        if (!contentType.includes('application/json')) {
            throw new Error('El servidor devolvió una respuesta inesperada. Inténtalo de nuevo.');
        }

        let payload;

        try {
            payload = await response.json();
        } catch {
            throw new Error('El servidor devolvió datos que no se pudieron interpretar. Inténtalo de nuevo.');
        }

        if (!payload || typeof payload !== 'object' || Array.isArray(payload)) {
            throw new Error('El servidor devolvió una respuesta inesperada. Inténtalo de nuevo.');
        }

        return payload;
    };

    const requestCalendarContent = async (url) => {
        const response = await fetch(url, {
            headers: {
                Accept: 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
            credentials: 'same-origin',
        });
        const payload = await getJsonResponse(response);

        if (!response.ok || typeof payload.html !== 'string') {
            throw new Error(getResponseMessage(payload, response.status));
        }

        return payload.html;
    };

    const replaceCalendarContent = (html, scrollPosition) => {
        const openDialog = calendarContent.querySelector('dialog[open]');
        openDialog?.close();
        calendarContent.innerHTML = html;
        window.scrollTo(0, scrollPosition);
    };

    const setCalendarBusy = (isBusy) => {
        requestPending = isBusy;
        calendarRoot.setAttribute('aria-busy', String(isBusy));
    };

    const showDateEvents = (dateButton) => {
        const dialog = calendarContent.querySelector('[data-calendar-event-dialog]');
        const dateHeading = dialog?.querySelector('[data-calendar-dialog-date]');
        const eventContainer = dialog?.querySelector('[data-calendar-dialog-events]');
        const date = dateButton.dataset.calendarDate;
        const dateLabel = dateButton.dataset.dateLabel;
        const eventTemplate = [...calendarContent.querySelectorAll('template[data-calendar-events-for]')]
            .find((template) => template.dataset.calendarEventsFor === date);

        if (!(dialog instanceof HTMLDialogElement)
            || !(dateHeading instanceof HTMLElement)
            || !(eventContainer instanceof HTMLElement)
            || !date
            || !dateLabel) {
            showFeedback('No se pudieron mostrar los eventos de esta fecha.', 'error');
            return;
        }

        dateHeading.textContent = dateLabel;
        eventContainer.replaceChildren();

        if (eventTemplate) {
            eventContainer.append(eventTemplate.content.cloneNode(true));
        } else {
            const emptyMessage = document.createElement('p');
            emptyMessage.className = 'rounded-xl bg-gray-50 p-4 text-sm text-gray-600';
            emptyMessage.textContent = 'No hay eventos registrados para esta fecha.';
            eventContainer.append(emptyMessage);
        }

        dialog.showModal();
        dialog.querySelector('[data-calendar-dialog-close]')?.focus();
    };

    calendarRoot.addEventListener('click', async (event) => {
        if (!(event.target instanceof Element)) {
            return;
        }

        const monthLink = event.target.closest('[data-calendar-month-link]');

        if (monthLink instanceof HTMLAnchorElement) {
            event.preventDefault();

            if (requestPending) {
                return;
            }

            setCalendarBusy(true);
            const scrollPosition = window.scrollY;

            try {
                const html = await requestCalendarContent(monthLink.href);
                replaceCalendarContent(html, scrollPosition);
                window.history.pushState({}, '', monthLink.href);
                clearFeedback();
            } catch (error) {
                showFeedback(
                    error instanceof Error ? error.message : 'No se pudo cambiar de mes.',
                    'error',
                );
            } finally {
                setCalendarBusy(false);
            }

            return;
        }

        const dateButton = event.target.closest('[data-calendar-date]');

        if (dateButton instanceof HTMLButtonElement) {
            showDateEvents(dateButton);
            return;
        }

        const closeButton = event.target.closest('[data-calendar-dialog-close]');

        if (closeButton instanceof HTMLButtonElement) {
            calendarContent.querySelector('[data-calendar-event-dialog]')?.close();
            return;
        }

        const dialog = event.target.closest('[data-calendar-event-dialog]');

        if (dialog instanceof HTMLDialogElement && event.target === dialog) {
            dialog.close();
        }
    });

    calendarRoot.addEventListener('submit', async (event) => {
        if (!(event.target instanceof HTMLFormElement)
            || !event.target.matches('[data-calendar-form]')) {
            return;
        }

        event.preventDefault();

        if (requestPending) {
            return;
        }

        const form = event.target;
        const submitButton = form.querySelector('button[type="submit"]');
        const wasDisabled = submitButton instanceof HTMLButtonElement && submitButton.disabled;
        const scrollPosition = window.scrollY;
        let mutationMessage = null;
        setCalendarBusy(true);

        if (submitButton instanceof HTMLButtonElement) {
            submitButton.disabled = true;
        }

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: {
                    Accept: 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                credentials: 'same-origin',
            });
            const payload = await getJsonResponse(response);

            if (!response.ok || typeof payload.message !== 'string') {
                throw new Error(getResponseMessage(payload, response.status));
            }

            mutationMessage = payload.message;
            const html = await requestCalendarContent(window.location.href);
            replaceCalendarContent(html, scrollPosition);
            clearFeedback();
            showFeedback(mutationMessage);
        } catch (error) {
            const message = error instanceof Error
                ? error.message
                : 'No se pudo guardar el evento.';
            const feedbackMessage = mutationMessage
                ? `${mutationMessage} No se pudo actualizar la vista del calendario; vuelve a seleccionar el mes para cargar los cambios.`
                : message;

            showFeedback(feedbackMessage, 'error');
        } finally {
            if (submitButton instanceof HTMLButtonElement && submitButton.isConnected) {
                submitButton.disabled = wasDisabled;
            }

            setCalendarBusy(false);
        }
    });

    window.addEventListener('popstate', async () => {
        if (requestPending) {
            return;
        }

        setCalendarBusy(true);
        const scrollPosition = window.scrollY;

        try {
            const html = await requestCalendarContent(window.location.href);
            replaceCalendarContent(html, scrollPosition);
        } catch (error) {
            showFeedback(
                error instanceof Error ? error.message : 'No se pudo cargar el mes solicitado.',
                'error',
            );
        } finally {
            setCalendarBusy(false);
        }
    });
}
