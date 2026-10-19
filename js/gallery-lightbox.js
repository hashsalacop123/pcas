(function () {
	'use strict';

	var dialog;
	var image;
	var caption;
	var counter;
	var links = [];
	var activeIndex = 0;
	var returnFocus = null;

	function buildLightbox() {
		dialog = document.createElement('dialog');
		dialog.className = 'pcas-lightbox';
		dialog.setAttribute('aria-label', 'Photo viewer');
		dialog.innerHTML = '<div class="pcas-lightbox-panel"><button class="pcas-lightbox-close" type="button" aria-label="Close photo viewer">&times;</button><button class="pcas-lightbox-prev" type="button" aria-label="Previous photo">&#8592;</button><figure class="pcas-lightbox-figure"><img class="pcas-lightbox-image" alt=""><figcaption class="pcas-lightbox-caption"></figcaption></figure><button class="pcas-lightbox-next" type="button" aria-label="Next photo">&#8594;</button><div class="pcas-lightbox-counter" aria-live="polite"></div></div>';
		document.body.appendChild(dialog);

		image = dialog.querySelector('.pcas-lightbox-image');
		caption = dialog.querySelector('.pcas-lightbox-caption');
		counter = dialog.querySelector('.pcas-lightbox-counter');

		dialog.querySelector('.pcas-lightbox-close').addEventListener('click', function () {
			dialog.close();
		});
		dialog.querySelector('.pcas-lightbox-prev').addEventListener('click', function () {
			showPhoto((activeIndex - 1 + links.length) % links.length);
		});
		dialog.querySelector('.pcas-lightbox-next').addEventListener('click', function () {
			showPhoto((activeIndex + 1) % links.length);
		});
		dialog.addEventListener('click', function (event) {
			if (event.target === dialog) {
				dialog.close();
			}
		});
		dialog.addEventListener('close', function () {
			if (returnFocus) {
				returnFocus.focus();
			}
		});
		dialog.addEventListener('keydown', function (event) {
			if (event.key === 'ArrowLeft' && links.length > 1) {
				event.preventDefault();
				showPhoto((activeIndex - 1 + links.length) % links.length);
			} else if (event.key === 'ArrowRight' && links.length > 1) {
				event.preventDefault();
				showPhoto((activeIndex + 1) % links.length);
			}
		});
	}

	function showPhoto(index) {
		activeIndex = index;
		var link = links[index];
		var thumb = link.querySelector('img');
		image.src = link.href;
		image.alt = thumb ? thumb.alt : '';
		caption.textContent = link.querySelector('.pcas-gallery-caption') ? link.querySelector('.pcas-gallery-caption').textContent : (thumb ? thumb.alt : '');
		caption.hidden = !caption.textContent;
		counter.textContent = (index + 1) + ' / ' + links.length;
		dialog.querySelector('.pcas-lightbox-prev').hidden = links.length < 2;
		dialog.querySelector('.pcas-lightbox-next').hidden = links.length < 2;
	}

	document.addEventListener('click', function (event) {
		var trigger = event.target.closest('.pcas-gallery-item');
		if (!trigger) {
			return;
		}
		event.preventDefault();
		var group = trigger.getAttribute('data-gallery');
		links = Array.prototype.slice.call(document.querySelectorAll('.pcas-gallery-item')).filter(function (link) {
			return link.getAttribute('data-gallery') === group;
		});
		activeIndex = links.indexOf(trigger);
		returnFocus = trigger;
		if (!dialog) {
			buildLightbox();
		}
		showPhoto(activeIndex);
		dialog.showModal();
		dialog.querySelector('.pcas-lightbox-close').focus();
	});
}());
