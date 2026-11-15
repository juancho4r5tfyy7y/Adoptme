/* ADOPT ME — Interacciones del frontend de demostración. */
(function () {
    'use strict';

    const toastContainer = document.getElementById('toast-box');

    /** Muestra un mensaje temporal sin insertar texto HTML recibido. */
    function toast(message) {
        if (!toastContainer) {
            return;
        }

        const element = document.createElement('div');
        element.className = 'toast align-items-center show';
        element.setAttribute('role', 'status');
        element.innerHTML = '<div class="d-flex"><div class="toast-body"></div></div>';
        element.querySelector('.toast-body').textContent = message;
        toastContainer.appendChild(element);

        window.setTimeout(() => element.remove(), 3200);
    }

    window.adoptToast = toast;

    /* Confirmaciones reutilizables mediante data-confirm. */
    const modalElement = document.getElementById('confirm-modal');
    const confirmationModal = modalElement && window.bootstrap
        ? bootstrap.Modal.getOrCreateInstance(modalElement)
        : null;
    let pendingButton = null;

    document.addEventListener('click', function (event) {
        const target = event.target instanceof Element ? event.target : null;
        const button = target ? target.closest('[data-confirm]') : null;

        if (!button || !confirmationModal) {
            return;
        }

        pendingButton = button;
        modalElement.querySelector('.modal-title').textContent =
            button.dataset.confirmTitle || 'Confirmar acción';
        modalElement.querySelector('.modal-text').textContent = button.dataset.confirm;

        const input = modalElement.querySelector('textarea');
        input.value = '';
        input.classList.remove('is-invalid');
        input.placeholder = button.dataset.confirmInput || '';
        input.closest('.mb-3').classList.toggle('d-none', !button.dataset.confirmInput);

        confirmationModal.show();
    });

    if (modalElement && confirmationModal) {
        modalElement.addEventListener('hidden.bs.modal', function () {
            pendingButton = null;
        });

        modalElement.querySelector('.btn-ok').addEventListener('click', function () {
            const button = pendingButton;
            const input = modalElement.querySelector('textarea');

            if (!button) {
                return;
            }

            if (button.dataset.confirmInput && !input.value.trim()) {
                input.classList.add('is-invalid');
                return;
            }

            confirmationModal.hide();

            if (button.dataset.remove) {
                button.closest(button.dataset.remove)?.remove();
            }

            if (button.dataset.badge) {
                const row = button.closest('tr');
                const statusBadge = row?.querySelector('.badge-estado');

                if (statusBadge) {
                    statusBadge.className =
                        'badge badge-estado badge-' + button.dataset.badge;
                    statusBadge.textContent = button.dataset.badge;
                }

                row?.querySelectorAll('.acciones-pend').forEach((action) => action.remove());
            }

            if (button.dataset.toast) {
                toast(button.dataset.toast);
            }

            if (button.dataset.href) {
                window.setTimeout(() => {
                    window.location.href = button.dataset.href;
                }, 600);
            }
        });
    }

    /* Formularios demostrativos: validación nativa de Bootstrap y mensaje. */
    document.querySelectorAll('form[data-demo]').forEach((form) => {
        form.addEventListener('submit', function (event) {
            event.preventDefault();

            const passwordFields = form.querySelectorAll('[data-match]');

            if (passwordFields.length === 2) {
                passwordFields[1].setCustomValidity(
                    passwordFields[0].value === passwordFields[1].value ? '' : 'No coinciden'
                );
            }

            form.classList.add('was-validated');

            if (!form.checkValidity()) {
                toast('Revisa los campos marcados');
                return;
            }

            toast(form.dataset.toast || 'Cambios guardados');

            if (form.dataset.redirect) {
                window.setTimeout(() => {
                    window.location.href = form.dataset.redirect;
                }, 800);
            }
        });
    });

    /* Botones informativos. */
    document.addEventListener('click', function (event) {
        const target = event.target instanceof Element ? event.target : null;
        const button = target ? target.closest('[data-msg]') : null;

        if (button) {
            toast(button.dataset.msg);
        }
    });

    /* Mostrar u ocultar contraseñas. */
    document.querySelectorAll('[data-pw]').forEach((button) => {
        button.addEventListener('click', function () {
            const input = document.querySelector(button.dataset.pw);

            if (!input) {
                return;
            }

            input.type = input.type === 'password' ? 'text' : 'password';
            button.textContent = input.type === 'password' ? 'Ver' : 'Ocultar';
        });
    });

    /* Favoritos locales: se guardan en el navegador mientras no exista backend. */
    const FAVORITES_KEY = 'adoptme_fav';
    const DEFAULT_FAVORITES = [2, 5];

    function readFavorites() {
        try {
            const saved = localStorage.getItem(FAVORITES_KEY);

            if (saved === null) {
                return [...DEFAULT_FAVORITES];
            }

            const favorites = JSON.parse(saved);
            return Array.isArray(favorites)
                ? favorites.map(Number).filter(Number.isFinite)
                : [...DEFAULT_FAVORITES];
        } catch (error) {
            return [...DEFAULT_FAVORITES];
        }
    }

    function saveFavorites(favorites) {
        try {
            localStorage.setItem(FAVORITES_KEY, JSON.stringify(favorites));
            return true;
        } catch (error) {
            toast('No fue posible guardar los favoritos en este navegador');
            return false;
        }
    }

    function paintFavorites() {
        const favorites = readFavorites();

        document.querySelectorAll('.pet-fav').forEach((button) => {
            const isFavorite = favorites.includes(Number(button.dataset.id));
            const icon = button.querySelector('i');

            button.classList.toggle('active', isFavorite);

            if (icon) {
                icon.className = 'bi bi-heart' + (isFavorite ? '-fill' : '');
            }
        });

        const favoriteItems = document.querySelectorAll('[data-fav-item]');
        let visibleFavorites = 0;

        favoriteItems.forEach((item) => {
            const isFavorite = favorites.includes(Number(item.dataset.favItem));
            item.classList.toggle('d-none', !isFavorite);

            if (isFavorite) {
                visibleFavorites += 1;
            }
        });

        const emptyMessage = document.getElementById('fav-vacio');

        if (emptyMessage) {
            emptyMessage.classList.toggle('d-none', visibleFavorites > 0);
        }
    }

    document.addEventListener('click', function (event) {
        const target = event.target instanceof Element ? event.target : null;
        const button = target ? target.closest('.pet-fav') : null;

        if (!button) {
            return;
        }

        event.preventDefault();
        event.stopPropagation();

        if (button.dataset.login) {
            window.location.href = button.dataset.login;
            return;
        }

        const id = Number(button.dataset.id);
        const favorites = readFavorites();
        const updatedFavorites = favorites.includes(id)
            ? favorites.filter((favoriteId) => favoriteId !== id)
            : [...favorites, id];

        if (!saveFavorites(updatedFavorites)) {
            return;
        }

        toast(updatedFavorites.includes(id)
            ? 'Agregado a favoritos'
            : 'Eliminado de favoritos');
        paintFavorites();
    });

    paintFavorites();

    /* Filtros del catálogo. data-filtro-estado evita interferir con data-estado de cada mascota. */
    const catalogGrid = document.getElementById('catalogo-grid');

    if (catalogGrid) {
        const searchInput = document.getElementById('f-q');
        const speciesSelect = document.getElementById('f-especie');
        const sizeSelect = document.getElementById('f-tamano');
        const ageSelect = document.getElementById('f-edad');
        const countLabel = document.getElementById('f-count');
        const emptyMessage = document.getElementById('f-vacio');
        const stateButtons = document.querySelectorAll('[data-filtro-estado]');
        let selectedState = '';

        function applyCatalogFilters() {
            let visibleCount = 0;

            catalogGrid.querySelectorAll('[data-pet]').forEach((card) => {
                const data = card.dataset;
                const ageInMonths = Number(data.meses || 0);
                const ageInYears = ageInMonths / 12;
                const searchText = (data.nombre + ' ' + data.raza).toLowerCase();
                const searchTerm = searchInput.value.trim().toLowerCase();

                const matchesAge = !ageSelect.value
                    || (ageSelect.value === 'joven' && ageInYears <= 1)
                    || (ageSelect.value === 'adulto' && ageInYears > 1 && ageInYears < 5)
                    || (ageSelect.value === 'senior' && ageInYears >= 5);
                const matchesFilters = (!searchTerm || searchText.includes(searchTerm))
                    && (!speciesSelect.value || data.especie === speciesSelect.value)
                    && (!sizeSelect.value || data.tamano === sizeSelect.value)
                    && matchesAge
                    && (selectedState
                        ? data.estado === selectedState
                        : data.estado !== 'adoptado');

                card.classList.toggle('d-none', !matchesFilters);

                if (matchesFilters) {
                    visibleCount += 1;
                }
            });

            if (countLabel) {
                countLabel.textContent = visibleCount + ' mascotas encontradas';
            }

            if (emptyMessage) {
                emptyMessage.classList.toggle('d-none', visibleCount > 0);
            }
        }

        [searchInput, speciesSelect, sizeSelect, ageSelect].forEach((control) => {
            control.addEventListener('input', applyCatalogFilters);
            control.addEventListener('change', applyCatalogFilters);
        });

        stateButtons.forEach((button) => {
            button.addEventListener('click', function () {
                selectedState = button.dataset.filtroEstado;

                stateButtons.forEach((stateButton) => {
                    const isSelected = stateButton === button;
                    stateButton.classList.toggle('btn-primary', isSelected);
                    stateButton.classList.toggle('btn-outline-dark', !isSelected);
                });

                applyCatalogFilters();
            });
        });

        applyCatalogFilters();
    }

    /* Los cambios de estado en tablas son únicamente visuales en esta versión demo. */
    document.querySelectorAll('[data-cambia-estado]').forEach((select) => {
        select.addEventListener('change', () => {
            toast('Estado actualizado a "' + select.value + '"');
        });
    });

    /* Marcar notificaciones como leídas en la vista actual. */
    document.getElementById('leer-todas')?.addEventListener('click', function () {
        document.querySelectorAll('.notif.unread').forEach((notification) => {
            notification.classList.remove('unread');
        });

        toast('Notificaciones marcadas como leídas');
    });

    /* Exportación de tablas a CSV. */
    document.querySelectorAll('[data-csv]').forEach((button) => {
        button.addEventListener('click', function () {
            const table = document.querySelector(button.dataset.csv);

            if (!table) {
                toast('No se encontró la tabla para exportar');
                return;
            }

            const rows = [...table.querySelectorAll('tr')].map((row) => {
                return [...row.cells].map((cell) => {
                    const value = cell.innerText.trim().replace(/"/g, '""');
                    return '"' + value + '"';
                }).join(',');
            });

            // El BOM facilita que Excel reconozca correctamente los caracteres UTF-8.
            const csv = '\uFEFF' + rows.join('\r\n');
            const file = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
            const objectUrl = URL.createObjectURL(file);
            const link = document.createElement('a');

            link.href = objectUrl;
            link.download = button.dataset.file || 'reporte.csv';
            document.body.appendChild(link);
            link.click();
            link.remove();

            window.setTimeout(() => URL.revokeObjectURL(objectUrl), 1000);
            toast('Archivo CSV exportado');
        });
    });
})();
