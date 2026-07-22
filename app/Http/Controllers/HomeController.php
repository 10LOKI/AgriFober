<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

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

        return Inertia::render('Landing');
    }
}
