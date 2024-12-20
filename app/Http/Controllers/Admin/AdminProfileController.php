<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AdminProfileController extends Controller
{
    public function showProfile()
    {
        return view('admin.profile');
    }

    public function updateEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:admins,email,' . Auth::id(),
        ]);

        $user = Auth::guard('admin')->user();
        $user->email = $request->email;
        $user->save();

        return redirect()->back()->with('message', 'Email updated successfully');
    }

    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => [
                'required',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/',
            ],
        ]);

        if ($validator->fails()) {
            // تحقق من نوع الخطأ وتحديد الرسالة المناسبة
            if ($validator->errors()->has('password')) {
                return redirect()->back()->withErrors(['password' => 'Weak password. Please use a stronger password.']);
            }
        }

        $user = Auth::guard('admin')->user();
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->back()->with('message', 'Password updated successfully');
    }

    // تحديث رقم الهاتف
    public function updatePhoneNumber(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|string|min:9|max:9', // يجب أن يكون الرقم بين 10 و 15 حرفًا
        ]);

        Auth::user()->update(['phone_number' => $request->phone_number]);

        return redirect()->back()->with('message', 'Phone number updated successfully.');
    }
}
