<div class="row g-2">
    <div class="col-md-8">
        <x-form.input name="name" :label="__('messages.department_name')" data-validate="required" :value="old('name', $department?->name)" />
    </div>
    <div class="col-md-4">
        <x-form.select name="status" :label="__('messages.status')" :placeholder="null"
                       :options="['active' => __('messages.active'), 'inactive' => __('messages.inactive')]"
                       :selected="old('status', $department?->status?->value ?? 'active')" />
    </div>
    <div class="col-12">
        <x-form.input name="description" :label="__('messages.description')" :value="old('description', $department?->description)" />
    </div>
</div>
