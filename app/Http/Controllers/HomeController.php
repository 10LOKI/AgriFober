<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->role === 'admin') {
                return redirect('/admin');
            } elseif ($user->role === 'technicien') {
                return redirect('/technician'); // future
            } else {
                return redirect('/farmer'); // future mobile app
            }
        }
        
        return redirect('/login');
    }
}
