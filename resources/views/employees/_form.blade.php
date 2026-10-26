@php($employee = $employee ?? [])
<div class="row">
    <div class="col-md-6"><x-form.input name="first_name" label="First Name" data-validate="required" :value="$employee['first_name'] ?? null" /></div>
    <div class="col-md-6"><x-form.input name="last_name" label="Last Name" data-validate="required" :value="$employee['last_name'] ?? null" /></div>
    <div class="col-md-6"><x-form.input name="email" label="Email" type="email" data-validate="required|email" :value="$employee['email'] ?? null" /></div>
    <div class="col-md-6"><x-form.input name="phone" label="Phone" data-validate="required|phone" :value="$employee['phone'] ?? null" /></div>
    <div class="col-md-4"><x-form.input name="date_of_birth" label="Date of Birth" type="date" data-validate="required" :value="$employee['date_of_birth'] ?? null" /></div>
    <div class="col-md-4"><x-form.input name="date_of_joining" label="Date of Joining" type="date" data-validate="required" :value="$employee['date_of_joining'] ?? null" /></div>
    <div class="col-md-4">
        <x-form.select name="gender" label="Gender" data-validate="required"
                       :options="['male' => 'Male', 'female' => 'Female', 'other' => 'Other']"
                       :selected="$employee['gender'] ?? null" />
    </div>
    <div class="col-md-4">
        <x-form.select name="department_id" label="Department" data-validate="required" :options="$departments"
                       :selected="$employee['department_id'] ?? null" />
    </div>
    <div class="col-md-4"><x-form.input name="designation" label="Designation" data-validate="required" :value="$employee['designation'] ?? null" /></div>
    <div class="col-md-4"><x-form.input name="salary" label="Salary" type="number" data-validate="required|number" :value="$employee['salary'] ?? null" /></div>
    <div class="col-md-8"><x-form.input name="address" label="Address" :value="$employee['address'] ?? null" /></div>
    <div class="col-md-4">
        <x-form.select name="status" label="Status" :placeholder="null"
                       :options="['active' => 'Active', 'inactive' => 'Inactive']"
                       :selected="$employee['status'] ?? 'active'" />
    </div>
    <div class="col-md-6">
        <x-form.input name="profile_photo" label="Profile Photo (jpg or png, max 2 MB)" type="file"
                      accept=".jpg,.jpeg,.png" data-validate="file" data-types="jpg,jpeg,png" data-max-kb="2048" />
    </div>
</div>
