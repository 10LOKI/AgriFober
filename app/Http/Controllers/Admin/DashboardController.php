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
        $this->middleware(['auth', 'role:admin']);
    }

    public function index()
    {
        $stats = [
            'users' => User::count(),
            'parcels' => Parcel::count(),
            'cultures' => Culture::count(),
            'products' => Product::count(),
            'ai_interactions_today' => InteractionIA::whereDate('created_at', today())->count(),
        ];

        return inertia('Admin/Dashboard', [
            'stats' => $stats,
            'auth' => ['user' => auth()->user()]
        ]);
    }
}