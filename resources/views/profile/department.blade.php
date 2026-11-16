 @extends('layouts.app')
@section('title', __('messages.my_department'))
@section('heading', __('messages.my_department'))

@section('content')
    <x-card :title="$department['name']">
        <dl class="row detail-list mb-0">
            <dt class="col-sm-3">{{ __('messages.description') }}</dt>
            <dd class="col-sm-9">{{ $department['description'] }}</dd>

            <dt class="col-sm-3">{{ __('messages.total_employees') }}</dt>
            <dd class="col-sm-9">{{ $department['employee_count'] }}</dd>

            <dt class="col-sm-3">{{ __('messages.status') }}</dt>
            <dd class="col-sm-9">
                <x-status-badge :status="$department['status']" />
            </dd>
        </dl>
    </x-card>

    <x-card :title="__('messages.department_employees_title')">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>{{ __('messages.employee_code') }}</th>
                        <th>{{ __('messages.name') }}</th>
                        <th>{{ __('messages.email') }}</th>
                        <th>{{ __('messages.status') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($employees as $employee)
                        <tr>
                            <td>{{ $employee['employee_code'] }}</td>
                            <td>{{ $employee['full_name'] }}</td>
                            <td>{{ $employee['email'] }}</td>
                            <td>
                                <x-status-badge :status="$employee['status']" />
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">
                                {{ __('messages.no_employees_in_department') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>
@endsection