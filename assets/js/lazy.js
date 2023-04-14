(function() {
	var AppImages = {
		load: function() {
			if (typeof(window.IntersectionObserver) !== 'undefined') {
				AppImages.lazy();
			} else {
				AppImages.noLazy();
			}
		},
		lazy: function() {
			var imageObserver = new IntersectionObserver(function(entries, imgObserver) {
				entries.forEach(function(entry) {
					if (entry.isIntersecting) {
						var element = entry.target;
						if (element.nodeName === 'IMG') {
							element.src = element.dataset.lzysrc;
						} else if (element.nodeName === 'SOURCE') {
							element.srcset = element.dataset.lzysrc;
						}
						imgObserver.unobserve(element);
					}
				});
			}, {
				rootMargin: '200px'
			});
			var arrImages = document.querySelectorAll('[data-lzysrc]');
			arrImages.forEach(function(v) {
				imageObserver.observe(v);
			});
		},
		noLazy: function() {
			var arrImages = document.querySelectorAll('[data-lzysrc]');
			arrImages.forEach(function(element) {
				if (element.nodeName === 'IMG') {
					element.src = element.dataset.lzysrc;
				} else if (element.nodeName === 'SOURCE') {
					element.srcset = element.dataset.lzysrc;
				}
			});
		}
	};
	document.addEventListener("DOMContentLoaded", function() {
		AppImages.load();
		document.querySelectorAll('a[href=""],a[href="#"]').forEach(function(a) {
			a.addEventListener("click", function(event) {
				event.preventDefault();
			});
		});
	});
})();