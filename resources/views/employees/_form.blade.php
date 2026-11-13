
<div class="row g-2">

    {{-- USER DETAILS --}}
    <div class="col-12">
        <h2 class="form-section-heading">User Details</h2>
    </div>

    <div class="col-md-6">
        <x-form.input
            name="email"
            label="Email"
            type="email"
            data-validate="required|email"
        />
    </div>

    <div class="col-md-6">
        <x-form.input
            name="username"
            label="Username"
            data-validate="required"
        />
    </div>

    <div class="col-md-6">
        <x-form.input
            name="password"
            label="Password"
            type="password"
            data-validate="required"
        />
    </div>

    <div class="col-md-6">
        <x-form.select
            name="role"
            label="Role"
            data-validate="required"
            :options="[
                'admin' => 'Admin',
                'employee' => 'Employee'
            ]"
        />
    </div>

    <div class="col-md-6">
        <x-form.select
            name="status"
            label="Status"
            :placeholder="null"
            :options="[
                'active' => 'Active',
                'inactive' => 'Inactive'
            ]"
            selected="active"
        />
    </div>

</div>


{{-- =========================
    EMPLOYEE FIELDS
========================== --}}

<div id="employee-fields" class="mt-4">

    <h2 class="form-section-heading">Employee Details</h2>

    <div class="row g-2">

        <div class="col-md-6">
            <x-form.input
                name="first_name"
                label="First Name"
            />
        </div>

        <div class="col-md-6">
            <x-form.input
                name="last_name"
                label="Last Name"
            />
        </div>

        <div class="col-md-6">
            <x-form.input
                name="phone"
                label="Phone"
            />
        </div>

        <div class="col-md-4">
            <x-form.input
                name="date_of_birth"
                label="Date of Birth"
                type="date"
            />
        </div>

        <div class="col-md-4">
            <x-form.input
                name="date_of_joining"
                label="Date of Joining"
                type="date"
            />
        </div>

        <div class="col-md-4">
            <x-form.select
                name="gender"
                label="Gender"
                :options="[
                    'male' => 'Male',
                    'female' => 'Female',
                    'other' => 'Other'
                ]"
            />
        </div>

        <div class="col-md-4">
            <x-form.select
                name="department_id"
                label="Department"
                :options="$departments"
            />
        </div>

        <div class="col-md-4">
            <x-form.input
                name="designation"
                label="Designation"
            />
        </div>

        <div class="col-md-4">
            <x-form.input
                name="salary"
                label="Salary"
                type="number"
            />
        </div>

        <div class="col-md-8">
            <x-form.input
                name="address"
                label="Address"
            />
        </div>

        <div class="col-md-6">
            <x-form.input
                name="profile_photo"
                label="Profile Photo (jpg or png, max 2 MB)"
                type="file"
                accept=".jpg,.jpeg,.png"
                data-validate="file"
                data-types="jpg,jpeg,png"
                data-max-kb="2048"
            />
        </div>

    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', () => {

    const roleSelect = document.querySelector('[name="role"]');
    const employeeFields = document.getElementById('employee-fields');

    function toggleEmployeeFields() {
        const isEmployee = roleSelect.value === 'employee';

        employeeFields.classList.toggle('d-none', !isEmployee);
    }

    roleSelect.addEventListener('change', toggleEmployeeFields);

    toggleEmployeeFields();

});
</script>
