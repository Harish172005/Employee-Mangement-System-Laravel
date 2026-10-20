<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return 'Employee list';
    }

    public function create()
    {
        return 'Create User form';
    }

    public function store(Request $request)
    {
        return redirect()->route('admin.employees.index');
    }


    public function edit($id)
    {
        return 'Edit User ' . $id;
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('admin.users.index');
    }

    public function destroy($id)
    {
        return redirect()->route('admin.users.index');
    }
}