<x-form.input name="name" label="Department Name" data-validate="required" :value="old('name', $department?->name)" />
<x-form.input name="description" label="Description" :value="old('description', $department?->description)" />
<x-form.select name="status" label="Status" :placeholder="null"
               :options="['active' => 'Active', 'inactive' => 'Inactive']"
               :selected="old('status', $department?->status?->value ?? 'active')" />
