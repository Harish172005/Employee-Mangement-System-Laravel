<div class="row g-2">
    <div class="col-md-8">
        <x-form.input name="name" label="Department Name" data-validate="required" :value="old('name', $department?->name)" />
    </div>
    <div class="col-md-4">
        <x-form.select name="status" label="Status" :placeholder="null"
                       :options="['active' => 'Active', 'inactive' => 'Inactive']"
                       :selected="old('status', $department?->status?->value ?? 'active')" />
    </div>
    <div class="col-12">
        <x-form.input name="description" label="Description" :value="old('description', $department?->description)" />
    </div>
</div>
