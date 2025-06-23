export default class {
	constructor() {
		const codeContainerEl = document.querySelector('.code-container');
		const codeInputContainerEl = document.querySelector('.input-container');

		if (codeContainerEl === null || codeInputContainerEl === null) {
			return;
		}

		let enteredCode = '';

		codeInputContainerEl.addEventListener('click', (event) => {
			const target = event.target;

			if (target.classList.contains('js-input-number-backspace')) {
				enteredCode = enteredCode.slice(0, -1);

				const lastCodeEl = codeContainerEl.querySelector(`.js-code-number-${enteredCode.length + 1}`);
				if (lastCodeEl) lastCodeEl.textContent = '';
			} else if (target.classList.contains('js-input-number')) {
				const number = target.textContent.trim();

				if (enteredCode.length < 4) {
					enteredCode += number;

					const nextCodeEl = codeContainerEl.querySelector(`.js-code-number-${enteredCode.length}`);
					if (nextCodeEl) nextCodeEl.textContent = number;
				}

				if (enteredCode.length === 4) {

					this.verifyCode(enteredCode)
						.then((response) => {
							if (response.isValid) {
								/* Reload now that cookie is placed. */
								location.reload();
							} else {
								enteredCode = '';
								[...codeContainerEl.children].forEach((codeEl) => (codeEl.textContent = ''));
							}
						})
						.catch((error) => {
							console.log(error);
						});

				}
			}
		});
	}

	async verifyCode(code) {
		const response = await fetch(window.routes['app.widget.verifyCode'], {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json',
			},
			body: JSON.stringify({ code }),
		});

		if (!response.ok) {
			throw new Error(`HTTP error! Status: ${response.status}`);
		}

		return response.json();
	}
}
