<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        return 'Department list';
    }

    public function create()
    {
        return 'Create department form';
    }

    public function store(Request $request)
    {
        return redirect()->route('admin.departments.index');
    }

    public function show($id)
    {
        return 'Showing department ' . $id;
    }

    public function edit($id)
    {
        return 'Edit department ' . $id;
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('admin.departments.index');
    }

    public function destroy($id)
    {
        return redirect()->route('admin.departments.index');
    }
}