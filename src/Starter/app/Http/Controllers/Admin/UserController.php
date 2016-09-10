<?php

namespace {{App\}}Http\Controllers\Admin;

use Illuminate\Http\Request;
use {{App\}}Http\Controllers\Controller;
use {{App\}}Http\Requests;
use {{App\}}Http\Requests\UserRequest;
use {{App\}}Repositories\Role\RoleRepository;
use {{App\}}Services\UserService;

class UserController extends Controller
{
    public function __construct(UserService $userService)
    {
        $this->service = $userService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = $this->service->all();
        return view('admin.users.index')->with('users', $users);
    }

    /**
     * Display a listing of the resource searched.
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        if (! $request->search) {
            return redirect('admin/users');
        }

        $users = $this->service->search($request->search);
        return view('admin.users.index')->with('users', $users);
    }

    /**
     * Show the form for inviting a customer.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(RoleRepository $role)
    {
        $roles = $role->all();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Show the form for inviting a customer.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $result = $this->service->invite($request->except(['_token', '_method']));

        if ($result) {
            return redirect('admin/users')->with('message', 'Successfully invited');
        }

        return back()->with('error', 'Failed to invite');
    }

    /**
     * Switch to a different User profile
     *
     * @return \Illuminate\Http\Response
     */
    public function switchToUser($id)
    {
        if ($this->service->switchToUser($id)) {
            return redirect('dashboard')->with('message', 'You\'ve switched users.');
        }

        return redirect('dashboard')->with('message', 'Could not switch users');
    }

    /**
     * Switch back to your original user
     *
     * @return \Illuminate\Http\Response
     */
    public function switchUserBack()
    {
        if ($this->service->switchUserBack()) {
            return back()->with('message', 'You\'ve switched back.');
        }

        return back()->with('message', 'Could not switch back');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, RoleRepository $role)
    {
        $user = $this->service->find($id);
        $roles = $role->all();
        $selectedRoles = $user->roles()->lists('id')->toArray();
        return view('admin.users.edit', compact('user', 'roles', 'selectedRoles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, $id)
    {
        $result = $this->service->update($id, $request->except(['_token', '_method']));

        if ($result) {
            return back()->with('message', 'Successfully updated');
        }

        return back()->with('message', 'Failed to update');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result = $this->service->destroy($id);

        if ($result) {
            return redirect('admin/users')->with('message', 'Successfully deleted');
        }

        return redirect('admin/users')->with('message', 'Failed to delete');
    }
}
