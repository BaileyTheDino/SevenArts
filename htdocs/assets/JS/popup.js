export default class {
	constructor() {
		const popupEl = document.querySelector('.js-popup');
		const closeButtonEl = document.querySelector('.js-close-btn');
		const openButtonEl = document.querySelector('.js-open-btn');

		if (popupEl === null || closeButtonEl === null || openButtonEl === null) {
			return;
		}

		openButtonEl.addEventListener('click', (e) => {
			e.defaultPrevented;

			popupEl.classList.add('open');
		});

		closeButtonEl.addEventListener('click', (e) => {
			e.defaultPrevented;

			popupEl.classList.remove('open');
		});
	}
}
