export default class {
	constructor() {
		this.tabElements = document.querySelectorAll('.tab-item');

		if (this.tabElements.length <= 0) {
			return;
		}

		this.dialog = null;
		this.initEventListeners();
	}

	initEventListeners() {
		this.tabElements.forEach((tab) => {
			tab.addEventListener('click', async (event) => {
				const tabId = tab.dataset.id;

				if (!tabId) {
					console.error('Tab ID not found');
					return;
				}

				try {
					// Show loading state
					tab.classList.add('loading');

					// Create and open dialog
					this.openDialog();

					// Fetch tab details
					const html = await this.fetchTabDetails(tabId);

					// Put HTML inside the dialog
					this.populateDialog(html);

					// Add button event listeners after content is loaded
					this.initButtonEventListeners();

				} catch (error) {
					console.error('Error loading tab details:', error);
					this.showError('Failed to load tab details');
				} finally {
					// Remove loading state
					tab.classList.remove('loading');
				}
			});
		});
	}

	initButtonEventListeners() {
		if (!this.dialog) return;

		// Listen for button clicks using event delegation
		this.dialog.addEventListener('click', (event) => {
			const button = event.target.closest('[data-action]');
			if (!button) return;

			const action = button.dataset.action;
			const tabId = button.dataset.tabId;

			const payload = {id: tabId};
			console.log(payload);
			if (action === 'pay') {
				fetch(window.routes['app.widget.tab.payNow'], {
					method: 'POST',
					headers: {
						'Content-Type': 'application/json'
					},
					body: JSON.stringify(payload),
				})
					.then(response => {
						if (!response.ok) {
							throw new Error('Network response was not ok');
						}
						return response.json();
					})
					.then(data => {
						if (data.success) {
							alert('Data sent to printer.');
							location.reload();
						} else {
							alert('Failed to send tab to POS. Contact baileylievens@hotmail.be');
						}
					});
			} else if (action === 'delete') {
				fetch(window.routes['app.widget.tab.delete'], {
					method: 'POST',
					headers: {
						'Content-Type': 'application/json'
					},
					body: JSON.stringify(payload),
				})
					.then(response => {
						if (!response.ok) {
							throw new Error('Network response was not ok');
						}
						return response.json();
					})
					.then(data => {
						if (data.success) {
							alert('Tab was deleted.');
							location.reload();
						} else {
							alert('Failed to send tab to POS. Contact baileylievens@hotmail.be');
						}
					});
			}
		});
	}

	openDialog() {
		// Create dialog if it doesn't exist
		if (!this.dialog) {
			this.createDialog();
		}

		// Show the dialog
		this.dialog.style.display = 'block';
		document.body.classList.add('dialog-open');

		// Set initial loading content
		const content = this.dialog.querySelector('.tab-selection-content');
		content.innerHTML = '<div class="loading-spinner">Loading...</div>';
	}

	createDialog() {
		// Create dialog HTML
		this.dialog = document.createElement('div');
		this.dialog.className = 'tab-selection-overlay';
		this.dialog.innerHTML = `
			<div class="tab-selection-container">
				<div class="tab-selection-header">
					<h3 class="tab-selection-title">Tab Details</h3>
					<button class="tab-selection-close" type="button">&times;</button>
				</div>
				<div class="tab-selection-content">
					<!-- Content will be loaded here -->
				</div>
			</div>
		`;

		// Add to body
		document.body.appendChild(this.dialog);

		// Add event listeners
		this.dialog.querySelector('.tab-selection-close').addEventListener('click', () => {
			this.closeDialog();
		});

		// Close on overlay click
		this.dialog.addEventListener('click', (event) => {
			if (event.target === this.dialog) {
				this.closeDialog();
			}
		});

		// Close on Escape key
		document.addEventListener('keydown', (event) => {
			if (event.key === 'Escape' && this.dialog.style.display === 'block') {
				this.closeDialog();
			}
		});
	}

	async fetchTabDetails(tabId) {
		const url = new URL(window.routes['app.widget.tab.getTabDetails'], window.location.origin);
		url.searchParams.append('id', tabId);

		const response = await fetch(url, {
			method: 'GET',
			headers: {
				'Content-Type': 'application/json',
				'X-Requested-With': 'XMLHttpRequest'
			}
		});

		if (!response.ok) {
			throw new Error(`HTTP error! status: ${response.status}`);
		}

		return await response.text();
	}

	populateDialog(html) {
		const content = this.dialog.querySelector('.tab-selection-content');
		content.innerHTML = html;
	}

	showError(message) {
		if (this.dialog) {
			const content = this.dialog.querySelector('.tab-selection-content');
			content.innerHTML = `<div class="error-message">${message}</div>`;
		}
	}

	closeDialog() {
		if (this.dialog) {
			this.dialog.style.display = 'none';
			document.body.classList.remove('dialog-open');
		}
	}
}
