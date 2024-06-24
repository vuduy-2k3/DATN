<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        // Thêm nhiều quy tắc xác thực
        $request->validate([
            'email' => 'required|email|exists:admins,email',
            'password' => 'required',
        ], [
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không hợp lệ',
            'email.exists' => 'Email không tồn tại',
            'password.required' => 'Vui lòng nhập mật khẩu',
        ]);

        $admin = Admin::where('email', $request->email)->first();

        if (!$admin || !Hash::check($request->password, $admin->password)) {
            return redirect()->back()->withErrors(['password' => 'Mật khẩu không đúng']);
        }

        // Nếu thông tin đăng nhập đúng
        session(['admin' => $admin->id]);
        session()->flash('success', 'Đăng nhập thành công');
        return redirect()->route('dashboard');
    }

    public function logout()
    {
        session()->forget('admin');
        session()->flash('success', 'Đăng xuất thành công');
        return redirect()->route('login')->with('success','Đăng xuất thành công');
    }
}

