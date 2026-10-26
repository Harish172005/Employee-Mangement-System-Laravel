@php($department = $department ?? [])
<x-form.input name="name" label="Department Name" data-validate="required" :value="$department['name'] ?? null" />
<x-form.input name="description" label="Description" :value="$department['description'] ?? null" />
<x-form.select name="status" label="Status" :placeholder="null"
               :options="['active' => 'Active', 'inactive' => 'Inactive']"
               :selected="$department['status'] ?? 'active'" />
