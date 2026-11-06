const registrationForm = document.querySelector('[data-registration-form]');

if (registrationForm) {
	const tableBody = document.querySelector('[data-registration-rows]');
	const feedback = registrationForm.querySelector('[data-form-feedback]');
	const submitLabel = registrationForm.querySelector('[data-submit-label]');
	const formHeading = registrationForm.querySelector('[data-form-heading]');
	const cancelButton = registrationForm.querySelector('[data-cancel-edit]');
	const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
	const editableFields = ['name', 'email', 'phone', 'address', 'program', 'status'];

	const createCell = (value, label) => {
		const cell = document.createElement('td');
		cell.dataset.label = label;
		cell.textContent = value || '-';

		return cell;
	};

	const createRegistrationRow = (registration) => {
		const row = document.createElement('tr');
		row.dataset.registrationRow = '';
		row.dataset.registrationId = registration.id;

		for (const field of editableFields) {
			row.dataset[field === 'status' ? 'status' : field] = registration[field] || '';
		}

		row.dataset.userName = registration.user?.name || '';

		if (registrationForm.dataset.isAdmin === 'true') {
			row.append(createCell(registration.user?.name, 'Pemohon'));
		}

		row.append(
			createCell(registration.name, 'Peserta'),
			createCell(registration.email, 'Email'),
			createCell(registration.program, 'Program'),
		);

		const statusCell = document.createElement('td');
		statusCell.dataset.label = 'Status';
		const statusPill = document.createElement('span');
		statusPill.className = `status-pill status-${registration.status}`;
		statusPill.textContent = registration.status.charAt(0).toUpperCase() + registration.status.slice(1);
		statusCell.append(statusPill);
		row.append(statusCell);

		const actionsCell = document.createElement('td');
		actionsCell.className = 'table-actions';
		actionsCell.dataset.label = 'Aksi';

		for (const [label, action, className] of [
			['Ubah', 'edit', 'text-action'],
			['Hapus', 'delete', 'text-action text-danger'],
		]) {
			const button = document.createElement('button');
			button.className = className;
			button.type = 'button';
			button.dataset[`${action}Registration`] = '';
			button.textContent = label;
			actionsCell.append(button);
		}

		row.append(actionsCell);

		return row;
	};

	const resetRegistrationForm = () => {
		registrationForm.reset();
		registrationForm.dataset.method = 'POST';
		registrationForm.dataset.registrationId = '';
		registrationForm.action = registrationForm.dataset.baseUrl;
		formHeading.textContent = 'Buat pendaftaran';
		submitLabel.textContent = 'Simpan pendaftaran';
		cancelButton.classList.add('is-hidden');
		feedback.textContent = '';
		feedback.className = 'ajax-feedback';
	};

	registrationForm.addEventListener('submit', async (event) => {
		event.preventDefault();
		feedback.textContent = '';

		const formData = new FormData(registrationForm);
		formData.delete('_token');
		const method = registrationForm.dataset.method || 'POST';

		try {
			const response = await fetch(registrationForm.action, {
				method,
				headers: {
					Accept: 'application/json',
					'Content-Type': 'application/json',
					'X-CSRF-TOKEN': csrfToken,
				},
				body: JSON.stringify(Object.fromEntries(formData.entries())),
			});
			const payload = await response.json();

			if (!response.ok) {
				const messages = Object.values(payload.errors || {}).flat();
				feedback.textContent = messages.join(' ')
					|| payload.message
					|| 'Pendaftaran gagal diproses.';
				feedback.classList.add('feedback-error');

				return;
			}

			const row = createRegistrationRow(payload.registration);
			const existingRow = tableBody.querySelector(`[data-registration-id="${payload.registration.id}"]`);

			if (existingRow) {
				existingRow.replaceWith(row);
			} else {
				tableBody.querySelector('[data-empty-row]')?.remove();
				tableBody.prepend(row);
			}

			feedback.textContent = payload.message;
			feedback.classList.add('feedback-success');
			resetRegistrationForm();
			feedback.textContent = payload.message;
			feedback.classList.add('feedback-success');
		} catch {
			feedback.textContent = 'Server tidak dapat dijangkau. Coba lagi.';
			feedback.classList.add('feedback-error');
		}
	});

	tableBody.addEventListener('click', async (event) => {
		const button = event.target.closest('button');
		const row = button?.closest('[data-registration-row]');

		if (!button || !row) {
			return;
		}

		if (button.hasAttribute('data-edit-registration')) {
			for (const field of editableFields) {
				const input = registrationForm.elements.namedItem(field);

				if (input) {
					input.value = row.dataset[field] || '';
				}
			}

			registrationForm.dataset.method = 'PATCH';
			registrationForm.dataset.registrationId = row.dataset.registrationId;
			registrationForm.action = `${registrationForm.dataset.baseUrl}/${row.dataset.registrationId}`;
			formHeading.textContent = 'Ubah pendaftaran';
			submitLabel.textContent = 'Simpan perubahan';
			cancelButton.classList.remove('is-hidden');
			feedback.textContent = '';
			registrationForm.scrollIntoView({ behavior: 'smooth', block: 'start' });
		}

		if (button.hasAttribute('data-delete-registration') && window.confirm('Hapus pendaftaran ini?')) {
			try {
				const response = await fetch(`${registrationForm.dataset.baseUrl}/${row.dataset.registrationId}`, {
					method: 'DELETE',
					headers: { Accept: 'application/json', 'X-CSRF-TOKEN': csrfToken },
				});
				const payload = await response.json();

				if (!response.ok) {
					feedback.textContent = payload.message || 'Pendaftaran gagal dihapus.';
					feedback.classList.add('feedback-error');

					return;
				}

				row.remove();
				feedback.textContent = payload.message;
				feedback.className = 'ajax-feedback feedback-success';
			} catch {
				feedback.textContent = 'Server tidak dapat dijangkau. Coba lagi.';
				feedback.className = 'ajax-feedback feedback-error';
			}
		}
	});

	cancelButton.addEventListener('click', resetRegistrationForm);
}
