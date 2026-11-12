import './bootstrap';

const confirmDialog = document.querySelector('#confirm-dialog');

if (confirmDialog) {
	const message = confirmDialog.querySelector('#confirm-dialog-message');
	const cancelButton = confirmDialog.querySelector('[data-confirm-cancel]');
	const submitButton = confirmDialog.querySelector('[data-confirm-submit]');
	let activeForm = null;

	document.addEventListener('submit', event => {
		const form = event.target;

		if (!(form instanceof HTMLFormElement) || !form.dataset.confirm) {
			return;
		}

		if (form.dataset.confirmed === 'true') {
			delete form.dataset.confirmed;
			return;
		}

		event.preventDefault();
		activeForm = form;
		message.textContent = form.dataset.confirm;
		confirmDialog.showModal();
	});

	cancelButton.addEventListener('click', () => confirmDialog.close('cancel'));
	submitButton.addEventListener('click', () => confirmDialog.close('confirm'));
	confirmDialog.addEventListener('click', event => {
		if (event.target === confirmDialog) {
			confirmDialog.close('cancel');
		}
	});
	confirmDialog.addEventListener('close', () => {
		if (confirmDialog.returnValue === 'confirm' && activeForm) {
			const form = activeForm;
			activeForm = null;
			form.dataset.confirmed = 'true';
			form.requestSubmit();
			return;
		}

		activeForm = null;
	});
}

document.querySelectorAll('[data-review-form]').forEach(form => {
	const decision = form.querySelector('[name="status"]');
	const note = form.querySelector('[name="catatan_sponsor"]');
	const syncNoteRequirement = () => {
		note.required = decision.value === 'ditolak';
	};

	decision.addEventListener('change', syncNoteRequirement);
	syncNoteRequirement();
});

document.querySelectorAll('[data-photo-preview]').forEach(preview => {
	const input = preview.closest('.form-group')?.querySelector('input[type="file"]');

	if (!input) {
		return;
	}

	let previewUrl = null;

	input.addEventListener('change', () => {
		if (previewUrl) {
			URL.revokeObjectURL(previewUrl);
			previewUrl = null;
		}

		const [file] = input.files ?? [];
		if (!file) {
			preview.removeAttribute('src');
			preview.hidden = true;
			return;
		}

		previewUrl = URL.createObjectURL(file);
		preview.src = previewUrl;
		preview.hidden = false;
	});
});
