// Live employee search: sends the text to the JSON endpoint with fetch()
// and rebuilds the table rows without reloading the page.

const input = document.getElementById('employee-search');

if (input) {
    const searchUrl = input.dataset.searchUrl;
    const tbody = document.getElementById('employee-rows');
    const pager = document.getElementById('employee-pagination');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    const originalRows = tbody.innerHTML;
    const startedWithQuery = input.value.trim() !== '';
    let timer;

    input.addEventListener('input', () => {
        clearTimeout(timer);
        timer = setTimeout(() => search(input.value.trim()), 300); // debounce
    });

    async function search(query) {
        if (query === '') {
            if (startedWithQuery) {
                window.location.href = input.form.action; // back to the plain list
                return;
            }
            tbody.innerHTML = originalRows;
            pager?.classList.remove('d-none');
            return;
        }

        try {
            const response = await fetch(`${searchUrl}?q=${encodeURIComponent(query)}`, {
                headers: {
                    Accept: 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken,
                },
            });
            if (!response.ok) throw new Error(`HTTP ${response.status}`);

            const json = await response.json();
            renderRows(json.data);
            pager?.classList.add('d-none');
        } catch (error) {
            showMessage('Search failed. Please try again.', 'text-danger');
        }
    }

    function showMessage(text, extraClass = '') {
        tbody.replaceChildren();
        const tr = document.createElement('tr');
        const td = document.createElement('td');
        td.colSpan = 6;
        td.className = `text-center ${extraClass}`;
        td.textContent = text;
        tr.append(td);
        tbody.append(tr);
    }

    function cell(text) {
        const td = document.createElement('td');
        td.textContent = String(text);
        return td;
    }

    function hiddenInput(name, value) {
        const field = document.createElement('input');
        field.type = 'hidden';
        field.name = name;
        field.value = value;
        return field;
    }

    function actionLink(label, href, style) {
        const a = document.createElement('a');
        a.href = href;
        a.className = `btn btn-sm btn-${style} me-1`;
        a.textContent = label;
        return a;
    }

    function renderRows(rows) {
        if (rows.length === 0) {
            showMessage('No employees found.');
            return;
        }

        tbody.replaceChildren();
        rows.forEach((employee, index) => {
            const tr = document.createElement('tr');
            tr.append(cell(index + 1), cell(employee.code), cell(employee.name), cell(employee.department));

            // Status badge (textContent is used everywhere so data can never inject HTML)
            const statusCell = document.createElement('td');
            const badge = document.createElement('span');
            badge.className = `badge bg-${employee.status === 'active' ? 'success' : 'secondary'}`;
            badge.textContent = employee.status.charAt(0).toUpperCase() + employee.status.slice(1);
            statusCell.append(badge);
            tr.append(statusCell);

            // Action buttons
            const actions = document.createElement('td');
            actions.append(
                actionLink('View', employee.urls.show, 'info'),
                actionLink('Edit', employee.urls.edit, 'warning'),
            );

            if (employee.status === 'active') {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = employee.urls.destroy;
                form.className = 'd-inline';
                form.dataset.confirm = 'Deactivate this employee?'; // handled by delete-confirm.js
                form.append(hiddenInput('_token', csrfToken), hiddenInput('_method', 'DELETE'));

                const button = document.createElement('button');
                button.className = 'btn btn-sm btn-danger';
                button.textContent = 'Deactivate';
                form.append(button);
                actions.append(form);
            }

            tr.append(actions);
            tbody.append(tr);
        });
    }
}
