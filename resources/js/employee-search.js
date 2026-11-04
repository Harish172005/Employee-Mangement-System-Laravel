// Live employee search and filters:
// Sends search + all filter values to the JSON endpoint
// and rebuilds only the table rows without reloading the page.

const input = document.getElementById('employee-search');

if (input) {
    const form = input.form;
    const searchUrl = input.dataset.searchUrl;
    const tbody = document.getElementById('employee-rows');
    const pager = document.getElementById('employee-pagination');

    const csrfToken =
        document.querySelector('meta[name="csrf-token"]')?.content;

    let timer;

    // Search input → debounce
    input.addEventListener('input', () => {
        clearTimeout(timer);

        timer = setTimeout(() => {
            search();
        }, 300);
    });

    // Filters → search immediately when changed
    form.querySelectorAll(
        'select, input[type="checkbox"]'
    ).forEach((filter) => {
        filter.addEventListener('change', () => {
            search();
        });
    });

    // Prevent normal form submission
    form.addEventListener('submit', (event) => {
        event.preventDefault();
        search();
    });


    async function search() {
        const formData = new FormData(form);
        const params = new URLSearchParams();

        // Collect all filter values
        for (const [key, value] of formData.entries()) {
            if (value !== '') {
                params.append(key, value);
            }
        }

        try {
            const response = await fetch(
                `${searchUrl}?${params.toString()}`,
                {
                    headers: {
                        Accept: 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                }
            );

            if (!response.ok) {
                throw new Error(`HTTP ${response.status}`);
            }

            const json = await response.json();

            // Expected response:
            // {
            //     status: true,
            //     message: "...",
            //     data: {
            //         current_page: 1,
            //         data: [ employees... ]
            //     }
            // }

            const employees = json.data?.data ?? [];

            renderRows(employees);

            // Hide normal Laravel pagination while using AJAX
            pager?.classList.add('d-none');

        } catch (error) {
            console.error('Employee search failed:', error);

            showMessage(
                'Search failed. Please try again.',
                'text-danger'
            );
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

        td.textContent = String(text ?? '');

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
        const link = document.createElement('a');

        link.href = href;
        link.className = `btn btn-sm btn-${style} me-1`;
        link.textContent = label;

        return link;
    }


    function createActionForm({
        action,
        method = 'POST',
        buttonText,
        buttonClass,
        confirmMessage = null,
    }) {
        const form = document.createElement('form');

        form.method = 'POST';
        form.action = action;
        form.className = 'd-inline';

        if (confirmMessage) {
            form.dataset.confirm = confirmMessage;
        }

        form.append(
            hiddenInput('_token', csrfToken)
        );

        if (method !== 'POST') {
            form.append(
                hiddenInput('_method', method)
            );
        }

        const button = document.createElement('button');

        button.type = 'submit';
        button.className = `btn btn-sm ${buttonClass}`;
        button.textContent = buttonText;

        form.append(button);

        return form;
    }


    function renderRows(rows) {
        if (!Array.isArray(rows) || rows.length === 0) {
            showMessage('No employees found.');
            return;
        }

        tbody.replaceChildren();

        rows.forEach((employee, index) => {
            const tr = document.createElement('tr');

            // Basic employee information
            tr.append(
                cell(index + 1),
                cell(employee.employee_code),
                cell(
                    `${employee.first_name ?? ''} ${employee.last_name ?? ''}`.trim()
                ),
                cell(employee.department?.name ?? '-')
            );


            const statusCell = document.createElement('td');

            const badge = document.createElement('span');

            const status =
                typeof employee.status === 'string'
                    ? employee.status
                    : employee.status?.value;

            badge.className =
                `badge bg-${
                    status === 'active'
                        ? 'success'
                        : 'secondary'
                }`;

            badge.textContent =
                status
                    ? status.charAt(0).toUpperCase() + status.slice(1)
                    : '-';

            statusCell.append(badge);
            tr.append(statusCell);


            const actions = document.createElement('td');

            const isTrashed =
                employee.deleted_at !== null &&
                employee.deleted_at !== undefined;


            if (isTrashed) {

                // Restore
                if (employee.urls?.restore) {
                    actions.append(
                        createActionForm({
                            action: employee.urls.restore,
                            buttonText: 'Restore',
                            buttonClass: 'btn-success',
                        })
                    );
                }


                // Force Delete
                if (employee.urls?.force_delete) {
                    actions.append(
                        createActionForm({
                            action: employee.urls.force_delete,
                            method: 'DELETE',
                            buttonText: 'Force delete',
                            buttonClass: 'btn-outline-danger',
                            confirmMessage:
                                'Permanently delete this employee? This cannot be undone.',
                        })
                    );
                }

            }

            else {

                // View
                if (employee.urls?.show) {
                    actions.append(
                        actionLink(
                            'View',
                            employee.urls.show,
                            'info'
                        )
                    );
                }


                // Edit
                if (employee.urls?.edit) {
                    actions.append(
                        actionLink(
                            'Edit',
                            employee.urls.edit,
                            'warning'
                        )
                    );
                }


                // Deactivate
                if (
                    status === 'active' &&
                    employee.urls?.destroy
                ) {
                    actions.append(
                        createActionForm({
                            action: employee.urls.destroy,
                            method: 'DELETE',
                            buttonText: 'Deactivate',
                            buttonClass: 'btn-danger',
                            confirmMessage:
                                'Deactivate this employee?',
                        })
                    );
                }
            }

            tr.append(actions);
            tbody.append(tr);
        });
    }
}