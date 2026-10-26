@php($user = $user ?? [])
<div class="row">
    <div class="col-md-6"><x-form.input name="name" label="Name" data-validate="required" :value="$user['name'] ?? null" /></div>
    <div class="col-md-6"><x-form.input name="email" label="Email" type="email" data-validate="required|email" :value="$user['email'] ?? null" /></div>
    <div class="col-md-6"><x-form.input name="username" label="Username" data-validate="required" :value="$user['username'] ?? null" /></div>
    <div class="col-md-6"><x-form.input name="password" label="Password" type="password" /></div>
    <div class="col-md-6">
        <x-form.select name="role" label="Role" data-validate="required"
                       :options="['admin' => 'Admin', 'employee' => 'Employee']"
                       :selected="$user['role'] ?? null" />
    </div>
    <div class="col-md-6">
        <x-form.select name="status" label="Status" :placeholder="null"
                       :options="['active' => 'Active', 'inactive' => 'Inactive']"
                       :selected="$user['status'] ?? 'active'" />
    </div>
</div>
