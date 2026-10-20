<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        return 'Employee list';
    }

    public function create()
    {
        return 'Create employee form';
    }

    public function store(Request $request)
    {
        return redirect()->route('admin.employees.index');
    }

    public function show($id)
    {
        return 'Showing employee ' . $id;
    }

    public function edit($id)
    {
        return 'Edit employee ' . $id;
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('admin.employees.index');
    }

    public function destroy($id)
    {
        return redirect()->route('admin.employees.index');
    }
}