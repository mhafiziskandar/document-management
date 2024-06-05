<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function accountSettings()
    {
        return view('member.account-settings');
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
        return redirect()->route('member.account-settings');
    }
}
