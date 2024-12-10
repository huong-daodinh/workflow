<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Session;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      $departments = Department::with('employees')->get();
      $employees = User::whereIn('role', ['member', 'manager'])->whereNull('department_id')->get();
      foreach( $departments as $department ) {
        $department->canDelete = $department->employees->count() == 0;
      }
      return Inertia::render('Department/IndexPage', [
        'departments' => $departments,
        'employees' => $employees
      ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
          'name' => 'required|string',
          'employees' => 'nullable|array',
          'employees.*' => 'required|exists:users,id'
        ]);
        Department::create($data);
        Session::flash('flash', [
          'type' => 'success',
          'message' => 'Tạo phòng ban thành công'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
      $data = $request->validate([
        'name' => 'required|string',
        'employees' => 'nullable|array',
        'employees.*' => 'required|exists:users,id'
      ]);
      $department = Department::findOrFail($id);
      $department->update($data);
      Session::flash('flash', [
        'type' => 'success',
        'message' => 'Cập nhật phòng ban thành công'
      ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
