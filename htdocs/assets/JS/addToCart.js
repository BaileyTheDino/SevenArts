export default class {
	constructor() {
		this.initializeCart();
		this.bindEvents();
		this.initializeToast();

		window.addEventListener('cartCleared', () => {
			this.clearCart();
		});
	}

	initializeCart() {
		this.cart = this.getCartFromCookies() || {
			items: [],
			total: 0
		};
	}

	bindEvents() {
		const productBoxes = document.querySelectorAll('.product-box');

		productBoxes.forEach(product => {
			product.addEventListener('click', (e) => {
				this.handleAddToCart(e.currentTarget);
			});
		});
	}

	initializeToast() {
		if (!document.querySelector('.cart-toast-container')) {
			const toastContainer = document.createElement('div');
			toastContainer.className = 'cart-toast-container';
			toastContainer.style.cssText = `
				position: fixed;
				top: 20px;
				left: 50%;
				transform: translateX(-50%);
				z-index: 10000;
				pointer-events: none;
			`;
			document.body.appendChild(toastContainer);
		}
	}

	showToast() {
		const toastContainer = document.querySelector('.cart-toast-container');

		const toast = document.createElement('div');
		toast.className = 'cart-toast';
		toast.style.cssText = `
			background: #28a745;
			color: white;
			padding: 12px 20px;
			border-radius: 6px;
			box-shadow: 0 4px 12px rgba(0,0,0,0.15);
			font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
			font-size: 14px;
			font-weight: 500;
			opacity: 0;
			transition: opacity 0.2s ease-in-out;
		`;
		toast.textContent = 'Product added';

		toastContainer.appendChild(toast);

		setTimeout(() => {
			toast.style.opacity = '1';
		}, 10);

		setTimeout(() => {
			toast.style.opacity = '0';
			setTimeout(() => {
				if (toast.parentElement) {
					toast.remove();
				}
			}, 200);
		}, 800);
	}

	handleAddToCart(productElement) {
		const productData = {
			name: productElement.dataset.name,
			amount: productElement.dataset.amount,
			value: this.parsePrice(productElement.dataset.value),
			quantity: 1
		};

		this.addToCart(productData);
	}

	parsePrice(priceString) {
		if (typeof priceString !== 'string') {
			return 0;
		}

		const numericValue = priceString.replace(/€/g, '').trim();
		const parsed = parseFloat(numericValue);

		return isNaN(parsed) ? 0 : parsed;
	}

	formatPrice(value) {
		return `€${value.toFixed(2)}`;
	}

	addToCart(product) {
		const existingItemIndex = this.cart.items.findIndex(item => item.name === product.name);

		if (existingItemIndex > -1) {
			this.cart.items[existingItemIndex].quantity += product.quantity;
		} else {
			this.cart.items.push({
				name: product.name,
				amount: product.amount,
				value: product.value,
				quantity: product.quantity
			});
		}

		this.calculateTotal();
		this.saveCartToCookies();
		this.dispatchCartUpdateEvent();
		this.showToast();
	}

	calculateTotal() {
		const rawTotal = this.cart.items.reduce((total, item) => {
			return total + (item.value * item.quantity);
		}, 0);

		this.cart.total = Math.round(rawTotal * 100) / 100;
	}

	saveCartToCookies() {
		const cartJson = JSON.stringify(this.cart);
		const expires = new Date();
		expires.setTime(expires.getTime() + (7 * 24 * 60 * 60 * 1000));

		document.cookie = `cart=${encodeURIComponent(cartJson)}; expires=${expires.toUTCString()}; path=/; SameSite=Lax`;
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

	removeFromCart(productName) {
		this.cart.items = this.cart.items.filter(item => item.name !== productName);
		this.calculateTotal();
		this.saveCartToCookies();
		this.dispatchCartUpdateEvent();
	}

	updateQuantity(productName, newQuantity) {
		const item = this.cart.items.find(item => item.name === productName);

		if (item) {
			if (newQuantity <= 0) {
				this.removeFromCart(productName);
			} else {
				item.quantity = newQuantity;
				this.calculateTotal();
				this.saveCartToCookies();
				this.dispatchCartUpdateEvent();
			}
		}
	}

	clearCart() {
		this.cart = {
			items: [],
			total: 0
		};
		this.saveCartToCookies();
		this.dispatchCartUpdateEvent();
	}

	dispatchCartUpdateEvent() {
		const event = new CustomEvent('cartUpdated', {
			detail: {
				cart: this.cart,
				itemCount: this.cart.items.reduce((count, item) => count + item.quantity, 0),
				formattedTotal: this.formatPrice(this.cart.total) // Add formatted total for display
			}
		});

		document.dispatchEvent(event);
	}

	getCart() {
		return this.cart;
	}

	getItemCount() {
		return this.cart.items.reduce((count, item) => count + item.quantity, 0);
	}

	getTotal() {
		return this.cart.total;
	}

	getFormattedTotal() {
		return this.formatPrice(this.cart.total);
	}

	getFormattedCart() {
		return {
			...this.cart,
			items: this.cart.items.map(item => ({
				...item,
				formattedValue: this.formatPrice(item.value),
				formattedSubtotal: this.formatPrice(item.value * item.quantity)
			})),
			formattedTotal: this.formatPrice(this.cart.total)
		};
	}
}
