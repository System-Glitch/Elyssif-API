/*
 * Elyssif-API
 * Copyright (C) 2019 Jérémy LAMBERT (System-Glitch)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.

 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */
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