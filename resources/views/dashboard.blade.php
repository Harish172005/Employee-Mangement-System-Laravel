@extends('layouts.app')
@section('title', 'Dashboard')
@section('heading', 'Dashboard')

@section('content')
    <div class="row">
        @foreach ($stats as $label => $value)
            <div class="col-md-3">
                <x-card>
                    <div class="text-muted">{{ $label }}</div>
                    <div class="fs-3 fw-bold">{{ $value }}</div>
                </x-card>
            </div>
        @endforeach
    </div>

    <x-card title="Employees by Department">
        <table class="table mb-0">
            @foreach ($byDepartment as $name => $count)
                <tr><td>{{ $name }}</td><td>{{ $count }}</td></tr>
            @endforeach
        </table>
    </x-card>

    {{-- @while example (remove later) --}}
    @php($n = 1)
    @while ($n <= 3)
        <span class="badge bg-light text-dark border">Step {{ $n }}</span>
        @php($n++)
    @endwhile
@endsection
