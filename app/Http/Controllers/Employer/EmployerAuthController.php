<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employer;
use App\Traits\HasMathCaptcha;

class EmployerAuthController extends Controller
{
    use HasMathCaptcha;
    public function showRegisterForm(){

       $captcha = $this->generateCaptcha(); 
      
        return view('employer.register-form', [
            'captchaNum1' => $captcha['num1'],
            'captchaNum2' => $captcha['num2'],
        ]);

    }


    public function register(Request $request)
    {

    if (! $this->verifyCaptcha($request->captcha_answer)) {
            return back()
                ->withErrors(['captcha_answer' => 'ক্যাপচা উত্তর ভুল, আবার চেষ্টা করুন।  '])
                ->withInput($request->except('password', 'password_confirmation'));
    }

    //  return dd($request->all());
    $request->validate([
        'name' => 'required|string|max:255',
        'company_name' => 'required|string|max:255',
        'email' => 'required|email|unique:employers,email',
        'password' => 'required|confirmed|min:6',
    ]);

    $employer = Employer::create([
        'name' => $request->name,
        'company_name' => $request->company_name,
        'email' => $request->email,
        'password' => $request->password,
    ]);

       auth()->guard('employer')->login($employer);

        
       
       return redirect()->route('candidate.dashboard')
          ->with('message','You registered successfully as a Employer');
     
  
    }

    
     public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        
        if (auth('web')->check() || auth('admin')->check()) {
            auth()->logout(); 
        }

        $credentials = $request->only('email', 'password');

        if (auth()->guard('employer')->attempt($credentials)) {
            return redirect()->route('employer.dashboard');
        }

        return back()->withErrors(['email' => 'Invalid Credentials']);
    }

    public function dashboard()
    {
        return view('employer.dashboard');
    }


    public function logout(Request $request)
    {
        auth()->guard('employer')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
