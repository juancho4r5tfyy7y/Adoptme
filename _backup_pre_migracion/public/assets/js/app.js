/* ADOPT ME — comportamiento visual del frontend (sin backend). Todo funciona con datos de demostración. */
(function () {
  'use strict';

  /* ---------- Toast reutilizable ---------- */
  function toast(msg) {
    const box = document.getElementById('toast-box');
    const el = document.createElement('div');
    el.className = 'toast align-items-center show'; el.setAttribute('role', 'status');
    el.innerHTML = '<div class="d-flex"><div class="toast-body"></div></div>';
    el.querySelector('.toast-body').textContent = msg;   // textContent evita XSS
    box.appendChild(el);
    setTimeout(() => el.remove(), 3200);
  }
  window.adoptToast = toast;

  /* ---------- Modal de confirmación genérico ----------
     Botón con data-confirm="texto". Opcionales: data-confirm-title, data-confirm-input="Motivo" (campo obligatorio),
     data-toast="mensaje", data-remove="selector ancestro", data-badge="estado", data-href="url". */
  const modalEl = document.getElementById('confirm-modal');
  const modal = modalEl ? new bootstrap.Modal(modalEl) : null;
  let pendiente = null;
  document.addEventListener('click', function (ev) {
    const b = ev.target.closest('[data-confirm]');
    if (!b || !modal) return;
    pendiente = b;
    modalEl.querySelector('.modal-title').textContent = b.dataset.confirmTitle || 'Confirmar acción';
    modalEl.querySelector('.modal-text').textContent = b.dataset.confirm;
    const inp = modalEl.querySelector('textarea');
    inp.value = ''; inp.classList.remove('is-invalid');
    inp.placeholder = b.dataset.confirmInput || '';
    inp.closest('.mb-3').classList.toggle('d-none', !b.dataset.confirmInput);
    modal.show();
  });
  modalEl && modalEl.querySelector('.btn-ok').addEventListener('click', function () {
    const b = pendiente, inp = modalEl.querySelector('textarea');
    if (b.dataset.confirmInput && !inp.value.trim()) { inp.classList.add('is-invalid'); return; }
    modal.hide();
    if (b.dataset.remove) b.closest(b.dataset.remove)?.remove();
    if (b.dataset.badge) {                       // Cambia el estado visual de la fila
      const fila = b.closest('tr');
      const bd = fila.querySelector('.badge-estado');
      bd.className = 'badge badge-estado badge-' + b.dataset.badge; bd.textContent = b.dataset.badge;
      fila.querySelectorAll('.acciones-pend').forEach(x => x.remove());
    }
    if (b.dataset.toast) toast(b.dataset.toast);
    if (b.dataset.href) setTimeout(() => location.href = b.dataset.href, 600);
  });

  /* ---------- Formularios de demostración: validación Bootstrap + mensaje + redirección ---------- */
  document.querySelectorAll('form[data-demo]').forEach(f => f.addEventListener('submit', function (ev) {
    ev.preventDefault();
    const pass = f.querySelectorAll('[data-match]');            // confirmación de contraseña
    if (pass.length === 2) pass[1].setCustomValidity(pass[0].value === pass[1].value ? '' : 'No coinciden');
    f.classList.add('was-validated');
    if (!f.checkValidity()) { toast('Revisa los campos marcados'); return; }
    toast(f.dataset.toast || 'Cambios guardados');
    if (f.dataset.redirect) setTimeout(() => location.href = f.dataset.redirect, 800);
  }));

  /* Botones que solo muestran un mensaje */
  document.addEventListener('click', e => { const b = e.target.closest('[data-msg]'); if (b) toast(b.dataset.msg); });

  /* ---------- Mostrar/ocultar contraseña ---------- */
  document.querySelectorAll('[data-pw]').forEach(b => b.addEventListener('click', () => {
    const i = document.querySelector(b.dataset.pw); i.type = i.type === 'password' ? 'text' : 'password';
    b.textContent = i.type === 'password' ? 'Ver' : 'Ocultar';
  }));

  /* ---------- Favoritos (guardados en el navegador solo como demostración) ---------- */
  const FAV = 'adoptme_fav';
  const leerFav = () => { try { return JSON.parse(localStorage.getItem(FAV)) || [2, 5]; } catch (e) { return [2, 5]; } };
  const pintarFav = () => {
    const f = leerFav();
    document.querySelectorAll('.pet-fav').forEach(b => {
      const on = f.includes(+b.dataset.id); b.classList.toggle('active', on);
      b.querySelector('i').className = 'bi bi-heart' + (on ? '-fill' : '');
    });
    const items = document.querySelectorAll('[data-fav-item]');
    items.forEach(c => c.classList.toggle('d-none', !f.includes(+c.dataset.favItem)));
    const vacio = document.getElementById('fav-vacio');
    if (vacio) vacio.classList.toggle('d-none', f.length > 0);
  };
  document.addEventListener('click', e => {
    const b = e.target.closest('.pet-fav'); if (!b) return;
    e.preventDefault(); e.stopPropagation();
    if (b.dataset.login) { location.href = b.dataset.login; return; }
    let f = leerFav(), id = +b.dataset.id;
    f = f.includes(id) ? f.filter(x => x !== id) : f.concat(id);
    localStorage.setItem(FAV, JSON.stringify(f)); toast(f.includes(id) ? 'Agregado a favoritos' : 'Eliminado de favoritos'); pintarFav();
  });
  pintarFav();

  /* ---------- Filtros del catálogo (en el navegador) ---------- */
  const cat = document.getElementById('catalogo-grid');
  if (cat) {
    const q = document.getElementById('f-q'), esp = document.getElementById('f-especie'), tam = document.getElementById('f-tamano'), eda = document.getElementById('f-edad');
    let estado = '';
    const aplicar = () => {
      let n = 0;
      cat.querySelectorAll('[data-pet]').forEach(c => {
        const d = c.dataset, anios = d.meses / 12;
        const okEdad = !eda.value || (eda.value === 'joven' && anios <= 1) || (eda.value === 'adulto' && anios > 1 && anios < 5) || (eda.value === 'senior' && anios >= 5);
        const ok = (!q.value || (d.nombre + ' ' + d.raza).toLowerCase().includes(q.value.toLowerCase())) && (!esp.value || d.especie === esp.value)
          && (!tam.value || d.tamano === tam.value) && okEdad && (estado ? d.estado === estado : d.estado !== 'adoptado');
        c.classList.toggle('d-none', !ok); if (ok) n++;
      });
      document.getElementById('f-count').textContent = n + ' mascotas encontradas';
      document.getElementById('f-vacio').classList.toggle('d-none', n > 0);
    };
    [q, esp, tam, eda].forEach(x => x.addEventListener('input', aplicar));
    document.querySelectorAll('[data-estado]').forEach(p => p.addEventListener('click', () => {
      estado = p.dataset.estado; document.querySelectorAll('[data-estado]').forEach(x => x.classList.toggle('btn-primary', x === p)); 
      document.querySelectorAll('[data-estado]').forEach(x => x.classList.toggle('btn-outline-dark', x !== p)); aplicar();
    }));
    aplicar();
  }

  /* ---------- Cambio de estado de mascota (select en tabla) ---------- */
  document.querySelectorAll('[data-cambia-estado]').forEach(s => s.addEventListener('change', () => toast('Estado actualizado a "' + s.value + '"')));

  /* ---------- Marcar notificaciones como leídas ---------- */
  document.getElementById('leer-todas')?.addEventListener('click', () => { document.querySelectorAll('.notif.unread').forEach(n => n.classList.remove('unread')); toast('Notificaciones marcadas como leídas'); });

  /* ---------- Exportar tabla a CSV (reportes) ---------- */
  document.querySelectorAll('[data-csv]').forEach(b => b.addEventListener('click', () => {
    const filas = [...document.querySelectorAll(b.dataset.csv + ' tr')].map(tr => [...tr.children].map(td => '"' + td.innerText.trim().replace(/"/g, '""') + '"').join(','));
    const a = document.createElement('a'); a.href = URL.createObjectURL(new Blob([filas.join('\n')], { type: 'text/csv;charset=utf-8;' }));
    a.download = b.dataset.file || 'reporte.csv'; a.click(); toast('Archivo CSV exportado');
  }));
})();
