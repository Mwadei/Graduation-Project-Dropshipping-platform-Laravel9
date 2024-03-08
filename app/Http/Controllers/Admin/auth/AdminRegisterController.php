<?php

namespace App\Http\Controllers\admin\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Validator;

class AdminRegisterController extends Controller
{

    protected $redirectTo = RouteServiceProvider::AdminHome;

    public function register()
    {
        return view('Admin.auth.register');
    }

    public function store(Request $request)
    {
        // التحقق والقيود للبريد الإلكتروني وكلمة المرور
        $adminkey = "adminkey1";
        if ($request->admin_key == $adminkey) {
            $request->validate([
                "name" => ["required", "string"],
                "email" => ["required", "regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/", "unique:admins"],
                "admin_key" => ["required", "string"],
                'password' => ['required', 'min:8', 'confirmed'],
                "password_confirmation" => ["required", "string"]
            ], [
                'name.required' => 'يرجى إدخال الاسم.',
                'email.required' => 'يرجى إدخال عنوان البريد الإلكتروني.',
                'email.regex' => 'يرجى إدخال عنوان بريد إلكتروني صحيح.',
                'email.unique' => 'هذا البريد الإلكتروني مستخدم بالفعل.',
                'password.required' => 'يرجى إدخال كلمة المرور.',
                'password.min' => 'يجب أن تتكون كلمة المرور من :min أحرف على الأقل.',
                'password.confirmed' => 'تأكيد كلمة المرور غير متطابق.',
                "password_confirmation.required" => "يرجى تأكيد كلمة المرور."
            ]);

            $data = $request->except(['password_confirmation','_token','admin_key']);
            $data['password'] = Hash::make($request->password);
            Admin::create($data);
            return redirect()->route('admin.dshboard.login');
        } else {
            return redirect()->back()->with('errorResponse', 'حدث خطأ ما');
        }
    }
}
