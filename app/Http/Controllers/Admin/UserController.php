<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Folder;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        return view('user.index');
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        $folders = Folder::all();

        return view('user.edit', compact('user', 'roles', 'folders'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required'
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email
        ]);

        $user->syncRoles($request->role);
        $user->checkEmailValidity();

        $user->folders()->sync($request->folders);

        Alert::success('Success', 'Update user success!');

        return back();
    }

    public function accountSettings()
    {
        return view('admin.account-settings');
    }

    public function updateAccount(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|unique:users,email,' . auth()->id(),
            'password' => 'required|confirmed',
        ]);

        $dataToUpdate = ['password' => Hash::make($request->password)];

        // Check if the email has changed
        if (auth()->user()->email !== $request->email) {
            $dataToUpdate['email'] = $request->email;
            $dataToUpdate['ic_no'] = $request->email;
        }

        auth()->user()->update($dataToUpdate);

        Alert::success('Success', 'Account details updated successfully!');
        return redirect()->route('admin.account-settings');
    }
}
