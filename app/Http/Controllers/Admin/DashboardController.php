<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Parcel;
use App\Models\Culture;
use App\Models\Product;
use App\Models\InteractionIA;
use Illuminate\Support\Facades\DB;
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
            'users_by_region' => \App\Models\User::whereNotNull('region')
                ->select('region', DB::raw('count(*) as count'))
                ->groupBy('region')
                ->orderByDesc('count')
                ->get(),
            'cultures_by_region' => \App\Models\Culture::whereNotNull('region')
                ->select('region', DB::raw('count(*) as count'))
                ->groupBy('region')
                ->orderByDesc('count')
                ->get(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}