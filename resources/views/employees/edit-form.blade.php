
{{-- Employee Edit Form --}}
<div class="row g-2">

    <div class="col-12">
        <h2 class="form-section-heading">{{ __('messages.employee_details_section') }}</h2>
    </div>

    <div class="col-md-6">
        <x-form.input
            name="first_name"
            :label="__('messages.first_name')"
            data-validate="required"
            :value="old('first_name', $employee->first_name)"
        />
    </div>

    <div class="col-md-6">
        <x-form.input
            name="last_name"
            :label="__('messages.last_name')"
            data-validate="required"
            :value="old('last_name', $employee->last_name)"
        />
    </div>

    <div class="col-md-6">
        <x-form.input
            name="phone"
            :label="__('messages.phone')"
            :value="old('phone', $employee->phone)"
        />
    </div>

    <div class="col-md-6">
        <x-form.input
            name="date_of_birth"
            :label="__('messages.date_of_birth')"
            type="date"
            :value="old(
                'date_of_birth',
                $employee->date_of_birth?->format('Y-m-d')
            )"
        />
    </div>

    <div class="col-md-6">
        <x-form.input
            name="date_of_joining"
            :label="__('messages.date_of_joining')"
            type="date"
            :value="old(
                'date_of_joining',
                $employee->date_of_joining?->format('Y-m-d')
            )"
        />
    </div>

    <div class="col-md-6">
        <x-form.select
            name="gender"
            :label="__('messages.gender')"
            data-validate="required"
            :options="[
                'male' => __('messages.male'),
                'female' => __('messages.female'),
                'other' => __('messages.other')
            ]"
            :selected="old('gender', $employee->gender?->value)"
        />
    </div>

    <div class="col-md-6">
        <x-form.select
            name="department_id"
            :label="__('messages.department')"
            data-validate="required"
            :options="$departments"
            :selected="old(
                'department_id',
                $employee->department_id
            )"
        />
    </div>

    <div class="col-md-6">
        <x-form.input
            name="designation"
            :label="__('messages.designation')"
            data-validate="required"
            :value="old('designation', $employee->designation)"
        />
    </div>

    <div class="col-md-6">
        <x-form.input
            name="salary"
            :label="__('messages.salary')"
            type="number"
            data-validate="required"
            :value="old('salary', $employee->salary)"
        />
    </div>

    <div class="col-md-12">
        <x-form.input
            name="address"
            :label="__('messages.address')"
            :value="old('address', $employee->address)"
        />
    </div>


    {{-- Profile Photo --}}
    <div class="col-md-6">

        @if ($employee->profile_photo)
            <div class="mb-3">

                <img
                    src="{{ $employee->photo_url }}"
                    alt="{{ $employee->full_name }}"
                    class="rounded-circle border"
                    width="120"
                    height="120"
                    style="object-fit: cover;"
                >

            </div>
        @endif

        <x-form.input
            name="profile_photo"
            :label="__('messages.change_profile_photo')"
            type="file"
            accept=".jpg,.jpeg,.png"
            data-validate="file"
            data-types="jpg,jpeg,png"
            data-max-kb="2048"
        />

    </div>

</div>
