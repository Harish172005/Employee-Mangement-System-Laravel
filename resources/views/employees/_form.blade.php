
<div class="row g-2">

    {{-- USER DETAILS --}}
    <div class="col-12">
        <h2 class="form-section-heading">{{ __('messages.user_details') }}</h2>
    </div>

    <div class="col-md-6">
        <x-form.input
            name="email"
            :label="__('messages.email')"
            type="email"
            data-validate="required|email"
        />
    </div>

    <div class="col-md-6">
        <x-form.input
            name="username"
            :label="__('messages.username')"
            data-validate="required"
        />
    </div>

    <div class="col-md-6">
        <x-form.input
            name="password"
            :label="__('messages.password')"
            type="password"
            data-validate="required"
        />
    </div>

    <div class="col-md-6">
        <x-form.select
            name="role"
            :label="__('messages.role')"
            data-validate="required"
            :options="[
                'admin' => __('messages.admin'),
                'employee' => __('messages.employee')
            ]"
        />
    </div>

    <div class="col-md-6">
        <x-form.select
            name="status"
            :label="__('messages.status')"
            :placeholder="null"
            :options="[
                'active' => __('messages.active'),
                'inactive' => __('messages.inactive')
            ]"
            selected="active"
        />
    </div>

</div>


{{-- =========================
    EMPLOYEE FIELDS
========================== --}}

<div id="employee-fields" class="mt-4">

    <h2 class="form-section-heading">{{ __('messages.employee_details_section') }}</h2>

    <div class="row g-2">

        <div class="col-md-6">
            <x-form.input
                name="first_name"
                :label="__('messages.first_name')"
            />
        </div>

        <div class="col-md-6">
            <x-form.input
                name="last_name"
                :label="__('messages.last_name')"
            />
        </div>

        <div class="col-md-6">
            <x-form.input
                name="phone"
                :label="__('messages.phone')"
            />
        </div>

        <div class="col-md-4">
            <x-form.input
                name="date_of_birth"
                :label="__('messages.date_of_birth')"
                type="date"
            />
        </div>

        <div class="col-md-4">
            <x-form.input
                name="date_of_joining"
                :label="__('messages.date_of_joining')"
                type="date"
            />
        </div>

        <div class="col-md-4">
            <x-form.select
                name="gender"
                :label="__('messages.gender')"
                :options="[
                    'male' => __('messages.male'),
                    'female' => __('messages.female'),
                    'other' => __('messages.other')
                ]"
            />
        </div>

        <div class="col-md-4">
            <x-form.select
                name="department_id"
                :label="__('messages.department')"
                :options="$departments"
            />
        </div>

        <div class="col-md-4">
            <x-form.input
                name="designation"
                :label="__('messages.designation')"
            />
        </div>

        <div class="col-md-4">
            <x-form.input
                name="salary"
                :label="__('messages.salary')"
                type="number"
            />
        </div>

        <div class="col-md-8">
            <x-form.input
                name="address"
                :label="__('messages.address')"
            />
        </div>

        <div class="col-md-6">
            <x-form.input
                name="profile_photo"
                :label="__('messages.profile_photo')"
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
