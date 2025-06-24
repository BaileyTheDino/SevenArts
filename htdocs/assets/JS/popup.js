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

			this.handlePopupOpening(popupEl.getAttribute('data-popup-type'));

			popupEl.classList.add('open');
		});

		closeButtonEl.addEventListener('click', (e) => {
			e.defaultPrevented;

			popupEl.classList.remove('open');

			const loader = document.querySelector('.loader');
			loader.classList.remove('hide');

			const popUpContent = document.querySelector('.popup-content');
			popUpContent.innerHTML = '';
		});

		// Initialize tab selection popup
		this.initTabSelectionPopup();
	}

	handlePopupOpening(type) {

		if (type === 'cart') {
			this.cart = this.getCartFromCookies() || {
				items: [],
				total: 0
			};

			this.getPopupContent(
				'app.widget.cart.getContent',
				this.cart,
			)
				.then((response) => {
					const loader = document.querySelector('.loader');
					loader.classList.add('hide');

					const popUpContent = document.querySelector('.popup-content');
					popUpContent.innerHTML = response;

					this.initNewCartEventListeners();
				})
				.catch((error) => {
					console.log(error);
				});

			return;
		}

		if (type === 'tab') {
			this.getPopupContent(
				'app.widget.tab.getContent',
				{
					'test': 'test',
				},
			)
				.then((response) => {
					console.log(response);
				})
				.catch((error) => {
					console.log(error);
				});

			return;

		}

		console.error('invalid popup type provided');
	}

	async getPopupContent(route, data) {
		const response = await fetch(window.routes[route], {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json',
			},
			body: JSON.stringify(data),
		});

		if (!response.ok) {
			throw new Error(`HTTP error! Status: ${response.status}`);
		}

		return response.text();
	}

	getCartFromCookies() {
		const cookies = document.cookie.split(';');

		for (let cookie of cookies) {
			const [name, value] = cookie.trim().split('=');
			if (name === 'cart') {
				try {
					return JSON.parse(decodeURIComponent(value));
				} catch (e) {
					console.error('Error parsing cart from cookies:', e);
					return null;
				}
			}
		}

		return null;
	}

	initNewCartEventListeners() {
		const resetCartButton = document.querySelector('.js-reset-cart');

		if (resetCartButton !== null) {
			resetCartButton.addEventListener('click', () => {
				this.clearCart();
			});
		}

		const addToTabButton = document.querySelector('.js-add-to-tab');

		if (addToTabButton !== null) {
			addToTabButton.addEventListener('click', () => {

				fetch(window.routes['app.widget.tab.getTabs'], {
					method: 'POST',
				})
					.then(response => {
						if (!response.ok) {
							throw new Error('Network response was not ok');
						}
						return response.json();
					})
					.then(data => {
						this.createTabSelectionPopup(data);
						this.openTabSelectionPopup();
					})
					.catch(error => {
						console.error('Error fetching tabs:', error);
					});
			});
		}

		const payNowButton = document.querySelector('.js-pay-now');

		if (payNowButton !== null) {
			payNowButton.addEventListener('click', () => {

				fetch(window.routes['app.widget.cart.payNow'], {
					method: 'POST',
					headers: {
						'Content-Type': 'application/json'
					},
					body: JSON.stringify(this.cart),
				})
					.then(response => {
						if (!response.ok) {
							throw new Error('Network response was not ok');
						}
						return response.json();
					})
					.then(data => {
						if (data.success) {
							this.clearCart();
							alert('Data sent to printer.');
						} else {
							alert('Failed to send cart to POS. Contact baileylievens@hotmail.be');
						}
					});
			});
		}
	}

	initTabSelectionPopup() {
		// Add event listeners
		const closeButton = document.getElementById('closeTabSelection');
		const overlay = document.getElementById('tabSelectionPopup');

		if (closeButton) {
			closeButton.addEventListener('click', () => {
				this.closeTabSelectionPopup();
			});
		}

		if (overlay) {
			overlay.addEventListener('click', (e) => {
				if (e.target === overlay) {
					this.closeTabSelectionPopup();
				}
			});
		}

		// Close with Escape key
		document.addEventListener('keydown', (e) => {
			if (e.key === 'Escape' && overlay && overlay.style.display === 'block') {
				this.closeTabSelectionPopup();
			}
		});
	}

	createTabSelectionPopup(tabs) {
		const tabList = document.getElementById('tabSelectionList');

		if (!tabList) return;

		tabList.innerHTML = '';

		tabs.forEach((tab, index) => {
			const listItem = document.createElement('li');
			listItem.className = 'tab-selection-item';
			listItem.setAttribute('data-tab-index', index);

			const initials = tab.name.split(' ').map(n => n[0]).join('').toUpperCase();

			listItem.innerHTML = `
				<div class="tab-selection-avatar">${initials}</div>
				<div class="tab-selection-info">
					<p class="tab-selection-name">${tab.name}</p>
					<p class="tab-selection-total">${tab.total}</p>
				</div>
			`;

			listItem.addEventListener('click', () => {

				fetch(window.routes['app.widget.cart.addToTab'], {
					method: 'POST',
					headers: {
						'Content-Type': 'application/json'
					},
					body: JSON.stringify({cart: this.cart, tabName: tab.name}),
				})
					.then(response => {
						if (!response.ok) {
							throw new Error('Network response was not ok');
						}
						return response.json();
					})
					.then(data => {
						if (data.success) {
							alert('Added cart items to tab');

							this.closeTabSelectionPopup();
							this.clearCart();
						} else {
							alert('Failed to send cart to POS. Contact baileylievens@hotmail.be');
						}
					});

			});

			tabList.appendChild(listItem);
		});
	}

	openTabSelectionPopup() {
		const overlay = document.getElementById('tabSelectionPopup');
		if (overlay) {
			overlay.style.display = 'block';
			document.body.style.overflow = 'hidden';
		}
	}

	closeTabSelectionPopup() {
		const overlay = document.getElementById('tabSelectionPopup');
		if (overlay) {
			overlay.style.display = 'none';
			document.body.style.overflow = '';
		}
	}

	clearCart() {
		window.dispatchEvent(new CustomEvent('cartCleared'));

		this.cart = {
			items: [],
			total: 0
		};

		const expires = new Date();
		expires.setTime(expires.getTime() + (7 * 24 * 60 * 60 * 1000));

		document.cookie = `cart=${encodeURIComponent(JSON.stringify(this.cart))}; expires=${expires.toUTCString()}; path=/; SameSite=Lax`;

		const closeButtonEl = document.querySelector('.js-close-btn');
		closeButtonEl.click();
	}
}
