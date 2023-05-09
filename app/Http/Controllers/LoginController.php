<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.login');
    }

    public function loginAdmin(Request $request)
    {
        if ($request->password != 'U7YwrfBe3jbRucx36C') {
            flash()->addFlash('error', 'Your password is wrong!!');
            return redirect('/admin');
        }
        session()->put('login', 'true');
        return redirect()->action([AdminController::class, 'index']);
    }

}
