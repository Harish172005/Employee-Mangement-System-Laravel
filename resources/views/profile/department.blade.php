 @extends('layouts.app')
@section('title', 'My Department')
@section('heading', 'My Department')

@section('content')
    <x-card :title="$department['name']">
        <dl class="row mb-0">
            <dt class="col-sm-3">Description</dt>
            <dd class="col-sm-9">{{ $department['description'] }}</dd>

            <dt class="col-sm-3">Total Employees</dt>
            <dd class="col-sm-9">{{ $department['employee_count'] }}</dd>

            <dt class="col-sm-3">Status</dt>
            <dd class="col-sm-9">
                <x-status-badge :status="$department['status']" />
            </dd>
        </dl>
    </x-card>

    <x-card title="Department Employees">
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Employee Code</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Status</th>
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
                                No employees found in this department.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>
@endsection