(function () {
  const el = document.getElementById('sidebar');
  if (!el || !window.coreui) return;

  const inst = coreui.Sidebar.getOrCreateInstance(el);

  // Desktop: daralt/ genişlet (ikonlar kalsın)
  // Mobile (<992px): overlay aç/kapa
  function isMobile() { return window.innerWidth < 992; }

  window.App = window.App || {};
  window.App.toggleSidebar = function () {
    if (isMobile()) {
      inst.toggle();             // mobil overlay
    } else {
      el.classList.toggle('sidebar-narrow-unfoldable');   // daralt / genişlet
      // tercihi hatırla (opsiyonel)
      try { localStorage.setItem('gp-sidebar-narrow', el.classList.contains('sidebar-narrow-unfoldable') ? '1' : '0'); } catch(e){}
    }
  };

  // Sayfa açılışında tercihi uygula (ops.)
  try {
    if (!isMobile() && localStorage.getItem('gp-sidebar-narrow') === '1') {
      el.classList.add('sidebar-narrow-unfoldable');
    }
  } catch(e){}
})();


(function(){
  document.querySelectorAll('[data-digits]').forEach(function(el){
    el.addEventListener('input', function(e){
      var m = el.getAttribute('maxlength') || 99;
      el.value = el.value.replace(/\D+/g,'').slice(0, m);
    });
  });
})();