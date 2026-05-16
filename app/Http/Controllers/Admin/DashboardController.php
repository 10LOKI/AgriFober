<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Parcel;
use App\Models\Culture;
use App\Models\Product;
use App\Models\InteractionIA;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $stats = [
            'users' => \App\Models\User::count(),
            'parcels' => \App\Models\Parcel::count(),
            'cultures' => \App\Models\Culture::count(),
            'products' => \App\Models\Product::count(),
            'ai_interactions_today' => \App\Models\InteractionIA::whereDate('created_at', today())->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}