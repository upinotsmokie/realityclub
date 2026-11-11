// ==========================================================================
// REALITY CLUB — main.js
// ==========================================================================
document.addEventListener('DOMContentLoaded', () => {

  /* ---------- loader ---------- */
  const loader = document.querySelector('.loader');
  if (loader) {
    window.addEventListener('load', () => {
      setTimeout(() => loader.classList.add('hide'), 350);
    });
    // fallback in case load event already fired
    setTimeout(() => loader.classList.add('hide'), 1600);
  }

  /* ---------- navbar scroll state ---------- */
  const navbar = document.querySelector('.navbar');
  const onScrollNav = () => {
    if (!navbar) return;
    if (window.scrollY > 40) navbar.classList.add('scrolled');
    else navbar.classList.remove('scrolled');
  };
  onScrollNav();
  window.addEventListener('scroll', onScrollNav, { passive: true });

  /* ---------- mobile nav toggle ---------- */
  const navToggle = document.querySelector('.nav-toggle');
  const navLinks = document.querySelector('.nav-links');
  if (navToggle && navLinks) {
    navToggle.addEventListener('click', () => {
      navLinks.classList.toggle('open');
      navToggle.setAttribute('aria-expanded', navLinks.classList.contains('open'));
    });
    navLinks.querySelectorAll('a').forEach(a => a.addEventListener('click', () => navLinks.classList.remove('open')));
  }

  /* ---------- back to top ---------- */
  const backTop = document.querySelector('.back-to-top');
  if (backTop) {
    window.addEventListener('scroll', () => {
      backTop.classList.toggle('show', window.scrollY > 600);
    }, { passive: true });
    backTop.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
  }

  /* ---------- scroll reveal ---------- */
  const revealEls = document.querySelectorAll('.reveal, .reveal-left, .reveal-right, .reveal-scale');
  const io = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('in');
        io.unobserve(entry.target);
      }
    });
  }, { threshold: 0.15, rootMargin: '0px 0px -40px 0px' });
  revealEls.forEach(el => io.observe(el));

  /* ---------- timeline scroll-driven progress ---------- */
  const timeline = document.querySelector('.timeline');
  const timelineItems = document.querySelectorAll('.timeline-item');
  if (timeline) {
    const itemIo = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) entry.target.classList.add('in');
      });
    }, { threshold: 0.4 });
    timelineItems.forEach(el => itemIo.observe(el));

    const updateProgress = () => {
      const rect = timeline.getBoundingClientRect();
      const vh = window.innerHeight;
      const total = rect.height;
      const visible = Math.min(Math.max(vh * 0.7 - rect.top, 0), total);
      const pct = total > 0 ? (visible / total) * 100 : 0;
      timeline.style.setProperty('--tl-progress', pct + '%');
    };
    window.addEventListener('scroll', updateProgress, { passive: true });
    updateProgress();
  }

  /* ---------- ripple buttons ---------- */
  document.querySelectorAll('.btn').forEach(btn => {
    btn.addEventListener('click', function (e) {
      const rect = this.getBoundingClientRect();
      const ripple = document.createElement('span');
      ripple.className = 'btn-ripple';
      ripple.style.left = (e.clientX - rect.left - 10) + 'px';
      ripple.style.top = (e.clientY - rect.top - 10) + 'px';
      ripple.style.width = ripple.style.height = '20px';
      this.appendChild(ripple);
      setTimeout(() => ripple.remove(), 650);
    });
  });

  /* ---------- generic modal open/close ---------- */
  document.querySelectorAll('[data-modal-open]').forEach(trigger => {
    trigger.addEventListener('click', () => {
      const target = document.querySelector(trigger.getAttribute('data-modal-open'));
      if (target) {
        target.classList.add('open');
        document.body.style.overflow = 'hidden';
        // carry data (tour name) if present
        if (trigger.dataset.tour) {
          const nameEl = target.querySelector('[data-fill="tour-name"]');
          if (nameEl) nameEl.textContent = trigger.dataset.tour;
          const hidden = target.querySelector('input[name="tour_id"]');
          if (hidden) hidden.value = trigger.dataset.tourId || '';
        }
      }
    });
  });
  
  /* ---------- album: klik cover -> daftar lagu terbuka di bawahnya ---------- */
  const albumCards = document.querySelectorAll('.album-clickable');
  function closeAlbumPanels(except) {
    document.querySelectorAll('.album-panel.open').forEach(p => {
      if (p !== except) { p.classList.remove('open'); p.setAttribute('aria-hidden', 'true'); }
    });
    albumCards.forEach(c => {
      const isActive = except && c.getAttribute('data-album-panel') === '#' + except.id;
      c.classList.toggle('active', !!isActive);
      c.setAttribute('aria-expanded', isActive ? 'true' : 'false');
    });
  }
  albumCards.forEach(card => {
    const toggle = () => {
      const panel = document.querySelector(card.getAttribute('data-album-panel'));
      if (!panel) return;
      if (panel.classList.contains('open')) { closeAlbumPanels(null); return; }
      closeAlbumPanels(panel);
      panel.classList.add('open');
      panel.setAttribute('aria-hidden', 'false');
      setTimeout(() => panel.scrollIntoView({ behavior: 'smooth', block: 'start' }), 250);
    };
    card.addEventListener('click', toggle);
    card.addEventListener('keydown', (e) => {
      if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); toggle(); }
    });
  });
  document.querySelectorAll('[data-panel-close]').forEach(btn => {
    btn.addEventListener('click', () => closeAlbumPanels(null));
  });

  document.querySelectorAll('[data-modal-close]').forEach(btn => {
    btn.addEventListener('click', () => {
      const overlay = btn.closest('.modal-overlay');
      if (overlay) { 
        overlay.classList.remove('open'); 
        document.body.style.overflow = ''; 
      }
    });
  });
  
  document.querySelectorAll('.modal-overlay').forEach(overlay => {
    overlay.addEventListener('click', (e) => {
      if (e.target === overlay) { 
        overlay.classList.remove('open'); 
        document.body.style.overflow = ''; 
      }
    });
  });
  
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      document.querySelectorAll('.modal-overlay.open').forEach(o => { 
        o.classList.remove('open'); 
        document.body.style.overflow = ''; 
      });
    }
  });

  /* ---------- form: validasi lalu kirim ke server (PHP + MySQL) ---------- */
  const EMAIL_RE = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

  function showFormError(form, msg) {
    let box = form.querySelector('.form-error');
    if (!box) {
      box = document.createElement('p');
      box.className = 'form-error';
      form.prepend(box);
    }
    box.textContent = msg || '';
    box.style.display = msg ? 'block' : 'none';
  }

  document.querySelectorAll('form[data-validate]').forEach(form => {
    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      let valid = true;
      form.querySelectorAll('[required]').forEach(input => {
        const field = input.closest('.field');
        const emailOk = input.type !== 'email' || EMAIL_RE.test(input.value);
        if (!input.value.trim() || !emailOk) {
          field && field.classList.add('error');
          valid = false;
        } else {
          field && field.classList.remove('error');
        }
      });
      if (!valid) return;

      const endpoint = form.getAttribute('data-endpoint');
      const btn = form.querySelector('button[type="submit"]');
      let result = { ok: true, message: null, details: [] };
      if (endpoint) {
        if (btn) btn.disabled = true;
        try {
          const res = await fetch(endpoint, { method: 'POST', body: new FormData(form) });
          result = await res.json();
        } catch (err) {
          result = { ok: false, message: 'Tidak dapat terhubung ke server. Pastikan Apache dan MySQL sudah berjalan.' };
        }
        if (btn) btn.disabled = false;
      }
      if (!result.ok) { showFormError(form, result.message); return; }
      showFormError(form, '');

      const successModal = document.querySelector(form.getAttribute('data-validate'));
      if (successModal) {
        const msgEl = successModal.querySelector('[data-fill="result-message"]');
        if (msgEl && result.message) msgEl.textContent = result.message;
        const extraEl = successModal.querySelector('[data-fill="result-extra"]');
        if (extraEl) {
          extraEl.innerHTML = '';
          (result.details || []).forEach(d => {
            const div = document.createElement('div');
            div.textContent = d;
            extraEl.appendChild(div);
          });
        }
      }
      const currentModal = form.closest('.modal-overlay');
      if (currentModal) { currentModal.classList.remove('open'); }
      if (successModal) {
        setTimeout(() => successModal.classList.add('open'), currentModal ? 350 : 0);
      }
      form.reset();
      document.body.style.overflow = currentModal ? 'hidden' : document.body.style.overflow;
      form.dispatchEvent(new CustomEvent('form:success', { bubbles: true, detail: result }));
    });
  });

  /* ---------- FAQ accordion ---------- */
  document.querySelectorAll('.faq-item').forEach(item => {
    const q = item.querySelector('.faq-q');
    const a = item.querySelector('.faq-a');
    if (q && a) {
      q.addEventListener('click', () => {
        const isOpen = item.classList.contains('open');
        document.querySelectorAll('.faq-item.open').forEach(other => {
          if (other !== item) {
            other.classList.remove('open');
            other.querySelector('.faq-a').style.maxHeight = null;
          }
        });
        item.classList.toggle('open', !isOpen);
        a.style.maxHeight = !isOpen ? a.scrollHeight + 'px' : null;
      });
    }
  });

  /* ---------- shop: keranjang + checkout ---------- */
  const cart = {};
  const cartFab = document.querySelector('.cart-fab');
  const cartCountEl = document.querySelector('.cart-fab .count');
  const checkoutModal = document.querySelector('#checkout-modal');
  const rupiah = n => 'Rp' + Number(n).toLocaleString('id-ID');
  const cartCount = () => Object.values(cart).reduce((sum, it) => sum + it.qty, 0);
  const refreshCount = () => { if (cartCountEl) cartCountEl.textContent = cartCount(); };

  function renderCart() {
    const list = document.querySelector('#cart-list');
    const form = document.querySelector('#checkout-form');
    if (!list || !form) return;
    const items = Object.values(cart);
    list.innerHTML = '';
    if (!items.length) {
      list.innerHTML = '<p style="color:var(--silver);margin-top:1rem;">Keranjang masih kosong.</p>';
      form.style.display = 'none';
      return;
    }
    form.style.display = '';
    let total = 0;
    items.forEach(it => {
      total += it.price * it.qty;
      const row = document.createElement('div');
      row.className = 'cart-row';
      row.innerHTML = '<div class="cart-name"></div>' +
        '<div class="cart-qty"><button type="button" data-act="dec" aria-label="Kurangi">−</button><span></span><button type="button" data-act="inc" aria-label="Tambah">+</button></div>' +
        '<div class="cart-sub"></div>';
      row.querySelector('.cart-name').textContent = it.name;
      row.querySelector('.cart-qty span').textContent = it.qty;
      row.querySelector('.cart-sub').textContent = rupiah(it.price * it.qty);
      row.querySelectorAll('button').forEach(b => b.addEventListener('click', () => {
        it.qty += b.dataset.act === 'inc' ? 1 : -1;
        if (it.qty > 10) it.qty = 10;
        if (it.qty <= 0) delete cart[it.id];
        refreshCount();
        renderCart();
      }));
      list.appendChild(row);
    });
    const tot = document.createElement('div');
    tot.className = 'cart-total';
    tot.innerHTML = '<span>Total</span><strong></strong>';
    tot.querySelector('strong').textContent = rupiah(total);
    list.appendChild(tot);

    document.querySelector('#cart-json').value = JSON.stringify(items.map(i => ({ id: i.id, qty: i.qty })));
    // alamat hanya wajib jika ada produk fisik
    const needAddr = items.some(i => !i.digital);
    const addr = document.querySelector('#shipping-address');
    if (addr) { if (needAddr) addr.setAttribute('required', ''); else addr.removeAttribute('required'); }
    const addrField = document.querySelector('#address-field');
    if (addrField) addrField.style.display = needAddr ? '' : 'none';
  }

  document.querySelectorAll('.add-btn[data-id]').forEach(btn => {
    btn.addEventListener('click', () => {
      if (btn.classList.contains('added')) return;
      const id = btn.dataset.id;
      if (!cart[id]) {
        cart[id] = { id, name: btn.dataset.name, price: Number(btn.dataset.price), digital: btn.dataset.digital === '1', qty: 0 };
      }
      if (cart[id].qty < 10) cart[id].qty++;
      btn.classList.add('added');
      refreshCount();
      if (cartFab) {
        cartFab.classList.add('bump');
        setTimeout(() => cartFab.classList.remove('bump'), 400);
      }
      setTimeout(() => btn.classList.remove('added'), 1800);
    });
  });

  if (cartFab && checkoutModal) {
    cartFab.addEventListener('click', () => {
      renderCart();
      checkoutModal.classList.add('open');
      document.body.style.overflow = 'hidden';
    });
  }
  document.addEventListener('form:success', (ev) => {
    if (ev.target && ev.target.id === 'checkout-form') {
      Object.keys(cart).forEach(k => delete cart[k]);
      refreshCount();
    }
  });

  /* ---------- hero waveform canvas ---------- */
  const canvas = document.querySelector('.hero-canvas');
  if (canvas) {
    const ctx = canvas.getContext('2d');
    let w, h, mouseX = 0.5, dpr = Math.min(window.devicePixelRatio || 1, 2);

    const resize = () => {
      w = canvas.offsetWidth; h = canvas.offsetHeight;
      canvas.width = w * dpr; canvas.height = h * dpr;
      ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
    };
    resize();
    window.addEventListener('resize', resize);
    window.addEventListener('mousemove', (e) => { mouseX = e.clientX / window.innerWidth; }, { passive: true });

    const bars = 64;
    let t = 0;
    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    function draw() {
      ctx.clearRect(0, 0, w, h);
      const baseY = h * 0.62;
      const spacing = w / bars;
      for (let i = 0; i < bars; i++) {
        const x = i * spacing + spacing / 2;
        const distFromMouse = Math.abs((i / bars) - mouseX);
        const proximityBoost = Math.max(0, 1 - distFromMouse * 2.4);
        const amp = (Math.sin(t * 0.04 + i * 0.35) * 0.5 + 0.5) * (h * 0.16) + proximityBoost * h * 0.22 + h * 0.02;
        const grad = ctx.createLinearGradient(0, baseY - amp, 0, baseY + amp * 0.3);
        grad.addColorStop(0, 'rgba(143,246,228,' + (0.15 + proximityBoost * 0.5) + ')');
        grad.addColorStop(1, 'rgba(43,79,217,0.05)');
        ctx.fillStyle = grad;
        const bw = Math.max(spacing * 0.35, 1.5);
        ctx.fillRect(x - bw / 2, baseY - amp, bw, amp * 1.3);
      }
      if (!reduceMotion) t++;
      requestAnimationFrame(draw);
    }
    draw();
  }

  /* ---------- ambient cursor glow on cards ---------- */
  document.querySelectorAll('.card, .tour-card, .product-card').forEach(card => {
    card.addEventListener('mousemove', (e) => {
      const rect = card.getBoundingClientRect();
      card.style.setProperty('--mx', ((e.clientX - rect.left) / rect.width * 100) + '%');
      card.style.setProperty('--my', ((e.clientY - rect.top) / rect.height * 100) + '%');
    });
  });

});
