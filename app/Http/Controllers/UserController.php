<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UserRequest;
use App\Models\Department;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Session;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      $authUser = Auth::user();
      $departments = Department::all();
      $users = User::with('department')->where('id', '!=', $authUser->id)
        ->when($authUser->role === 'manager', function($query) use($authUser) {
            $query->where('department_id', $authUser->department_id);
        })->orderBy('created_at', 'desc')->get();
      foreach ($users as $user) {
          $user->can_action = $user->role === 'member' || $user->role === 'manager' && $authUser->role === 'admin';
      }
      return Inertia::render('User/IndexPage', [
        'users' => $users,
        'departments' => $departments
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
    public function store(UserRequest $request)
    {
        if (Gate::denies('create-user')) {
          Session::flash('flash', [
            'type' => 'error',
            'message' => 'Bạn không có quyền tạo'
          ]);
          return ;
        }
        $data = $request->validated();
        User::create($data);
        Session::flash('flash', [
          'type' => 'success',
          'message' => 'Tạo thành công'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, $id)
    {
      $user = User::findOrFail($id);
      if (Gate::denies('update-user', $user)) {
        Session::flash('flash', [
          'type' => 'error',
          'message' => 'Bạn không có quyền cập nhật'
        ]);
        return ;
      }
      $data = $request->validated();
      if ($data['avatar'] == null) {
        $data['avatar'] = $user->avatar;
      }
      $user->update($data);
      Session::flash('flash', [
        'type' => 'success',
        'message' => 'Cập nhật thành công'
      ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
      $user = User::findOrFail($id);
      if (Gate::denies('update-user', $user)) {
        Session::flash('flash', [
          'type' => 'error',
          'message' => 'Bạn không có quyền cập nhật'
        ]);
        return ;
      }
      $user->delete();
      Session::flash('flash', [
        'type' => 'success',
        'message' => 'Xoá thành công'
      ]);
    }
}
