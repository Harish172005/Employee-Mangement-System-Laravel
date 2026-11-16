@extends('layouts.app')

@section('title', __('messages.employees'))
@section('heading', __('messages.employees'))

@section('content')

    <div class="filter-panel d-flex flex-wrap align-items-start gap-3 justify-content-between mb-3">

        <form id="employee-filter-form"
              method="GET"
              action="{{ route('admin.employees.index') }}"
              class="employee-filters">

            <input type="text"
                   id="employee-search"
                   name="q"
                   value="{{ $filters['q'] ?? '' }}"
                   class="form-control employee-search-field"
                   placeholder="{{ __('messages.search_employee_name_email_code') }}"
                   autocomplete="off"
                   data-search-url="{{ route('admin.employees.search') }}">

            <select name="department" class="form-select">
                <option value="">{{ __('messages.all_departments') }}</option>

                @foreach ($departments as $id => $name)
                    <option value="{{ $id }}"
                        @selected(($filters['department'] ?? '') == $id)>
                        {{ $name }}
                    </option>
                @endforeach
            </select>

            {{-- Status --}}
            <select name="status" class="form-select">
                <option value="">{{ __('messages.all_statuses') }}</option>

                <option value="active"
                    @selected(($filters['status'] ?? '') === 'active')}>
                    {{ __('messages.active') }}
                </option>

                <option value="inactive"
                    @selected(($filters['status'] ?? '') === 'inactive')}>
                    {{ __('messages.inactive') }}
                </option>
            </select>

            {{-- Gender --}}
            <select name="gender" class="form-select">
                <option value="">{{ __('messages.all_genders') }}</option>

                <option value="male"
                    @selected(($filters['gender'] ?? '') === 'male')}>
                    {{ __('messages.male') }}
                </option>

                <option value="female"
                    @selected(($filters['gender'] ?? '') === 'female')}>
                    {{ __('messages.female') }}
                </option>

                <option value="other"
                    @selected(($filters['gender'] ?? '') === 'other')}>
                    {{ __('messages.other') }}
                </option>
            </select>

            {{-- Sorting --}}
            <select name="sort" class="form-select">

                <option value="last_name"
                    @selected(($filters['sort'] ?? '') === 'last_name')}>
                    {{ __('messages.sort_last_name') }}
                </option>

                <option value="employee_code"
                    @selected(($filters['sort'] ?? '') === 'employee_code')}>
                    {{ __('messages.sort_code') }}
                </option>

                <option value="first_name"
                    @selected(($filters['sort'] ?? '') === 'first_name')}>
                    {{ __('messages.sort_first_name') }}
                </option>

                <option value="salary"
                    @selected(($filters['sort'] ?? '') === 'salary')}>
                    {{ __('messages.sort_salary') }}
                </option>

                <option value="date_of_joining"
                    @selected(($filters['sort'] ?? '') === 'date_of_joining')}>
                    {{ __('messages.sort_joining_date') }}
                </option>

            </select>

            <select name="direction" class="form-select">

                <option value="asc"
                    @selected(($filters['direction'] ?? 'asc') === 'asc')}>
                    {{ __('messages.ascending') }}
                </option>

                <option value="desc"
                    @selected(($filters['direction'] ?? '') === 'desc')}>
                    {{ __('messages.descending') }}
                </option>

            </select>

            <label class="employee-filter-check">

                <input class="form-check-input"
                       type="checkbox"
                       name="with_trashed"
                       value="1"
                       @checked(request()->boolean('with_trashed'))>

                <span class="form-check-label">
                    {{ __('messages.include_deactivated_records') }}
                </span>

            </label>

        </form>

        <a href="{{ route('admin.employees.create') }}"
           class="btn btn-primary align-self-start text-nowrap">
            {{ __('messages.add_employee') }}
        </a>

    </div>


    <div class="mb-3">

        <a href="{{ route('admin.employees.export') }}"
           class="btn btn-sm btn-outline-primary">
            {{ __('messages.export_csv') }}
        </a>

    </div>


    <x-card>

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('messages.code') }}</th>
                        <th>{{ __('messages.name') }}</th>
                        <th>{{ __('messages.department') }}</th>
                        <th>{{ __('messages.status') }}</th>
                        <th>{{ __('messages.actions') }}</th>
                    </tr>
                </thead>


                {{-- JavaScript updates ONLY this tbody --}}
                <tbody id="employee-rows">

                    @forelse ($employees as $employee)

                        <tr>

                            <td>
                                {{ $employees->firstItem() + $loop->index }}
                            </td>

                            <td>
                                {{ $employee->employee_code }}
                            </td>

                            <td>
                                {{ $employee->full_name }}
                            </td>

                            <td>
                                {{ $employee->department?->name ?? '-' }}
                            </td>

                            <td>
                                <x-status-badge :status="$employee->status" />
                            </td>

                            <td class="table-actions">

                                @if (
                                    $employee->trashed()
                                    && $employee->status === \App\Enums\Status::Inactive
                                )
                                    <form
                                        action="{{ route('admin.employees.restore', $employee) }}"
                                        method="POST"
                                        class="d-inline">

                                        @csrf

                                        <button class="btn btn-sm btn-success">
                                            {{ __('messages.restore') }}
                                        </button>

                                    </form>

                                    <form
                                        action="{{ route('admin.employees.force-delete', $employee) }}"
                                        method="POST"
                                        class="d-inline"
                                        data-confirm="{{ __('messages.permanently_delete_employee_confirm') }}">

                                        @csrf
                                        @method('DELETE')

                                        <button class="btn btn-sm btn-outline-danger">
                                            {{ __('messages.force_delete') }}
                                        </button>

                                    </form>

                                @else

                                    <a
                                        href="{{ route('admin.employees.show', $employee) }}"
                                        class="btn btn-sm btn-info">
                                        {{ __('messages.view') }}
                                    </a>

                                    <a
                                        href="{{ route('admin.employees.edit', $employee) }}"
                                        class="btn btn-sm btn-warning">
                                        {{ __('messages.edit') }}
                                    </a>


                                    {{-- Deactivate --}}
                                    <form
                                        action="{{ route('admin.employees.destroy', $employee) }}"
                                        method="POST"
                                        class="d-inline"
                                        data-confirm="{{ __('messages.deactivate_employee_confirm') }}">

                                        @csrf
                                        @method('DELETE')

                                        <button class="btn btn-sm btn-danger">
                                            {{ __('messages.deactivate') }}
                                        </button>

                                    </form>

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="6" class="text-center">
                                {{ __('messages.no_employees_found') }}
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </x-card>


    {{-- Normal Laravel pagination --}}
    <div id="employee-pagination">
        {{ $employees->links() }}
    </div>

@endsection