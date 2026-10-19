(function () {
	var toggle = document.querySelector('.nav-toggle');
	var menu = document.getElementById('pcas-primary-menu');
	if (!toggle || !menu) return;

	document.documentElement.classList.add('pcas-menu-ready');

	function setMenuOpen(isOpen) {
		toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
		menu.classList.toggle('is-open', isOpen);
	}

	toggle.addEventListener('click', function () {
		setMenuOpen(toggle.getAttribute('aria-expanded') !== 'true');
	});

	menu.addEventListener('click', function (event) {
		if (event.target.closest('a')) setMenuOpen(false);
	});

	document.addEventListener('keydown', function (event) {
		if (event.key === 'Escape') {
			setMenuOpen(false);
			toggle.focus();
		}
	});

	window.addEventListener('resize', function () {
		if (window.innerWidth > 720) setMenuOpen(false);
	});
})();
