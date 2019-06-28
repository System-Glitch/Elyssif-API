function registerAnimationOnScroll(selector, cssClass) {
	const options = {
	  threshold: 1.0
	}


	document.querySelectorAll(selector).forEach(element => {
		const observer = new IntersectionObserver((entries, observer) => {
			for(let key in entries) {
				const entry = entries[key];
				if (entry.intersectionRatio > 0) {
					entry.target.classList.add(cssClass);
				}
			}
		}, options);
		observer.observe(element);		
	});
}

document.addEventListener("DOMContentLoaded", function() {
	registerAnimationOnScroll('.animate-slide-in', 'slide-in');
	registerAnimationOnScroll('.animate-slide-in-left', 'slide-in-left');
	registerAnimationOnScroll('.animate-slide-in-right', 'slide-in-right');
	registerAnimationOnScroll('.animate-slide-in-down', 'slide-in-down');
	registerAnimationOnScroll('.scroll-indicator', 'scroll-indicator-animation');
});