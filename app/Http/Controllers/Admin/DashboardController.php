<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Parcel;
use App\Models\Culture;
use App\Models\Product;
use App\Models\InteractionIA;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $stats = [
            'total_users'            => User::count(),
            'total_parcels'          => Parcel::count(),
            'total_cultures'         => Culture::count(),
            'total_products'         => Product::count(),
            'ai_interactions_today'  => InteractionIA::whereDate('created_at', today())->count(),
            'pending_users_count'    => User::where('is_approved', false)->count(),
            'pending_users'          => User::where('is_approved', false)
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get(),
            'recent_users'           => User::orderBy('created_at', 'desc')->take(5)->get(),
            'recent_parcels'         => Parcel::with('user')->orderBy('created_at', 'desc')->take(5)->get(),
            'users_by_region'        => User::whereNotNull('region')
                ->select('region', DB::raw('count(*) as count'))
                ->groupBy('region')
                ->orderByDesc('count')
                ->get(),
            'cultures_by_region'     => Culture::whereNotNull('region')
                ->select('region', DB::raw('count(*) as count'))
                ->groupBy('region')
                ->orderByDesc('count')
                ->get(),
        ];

        return Inertia::render('Admin/Dashboard', compact('stats'));
    }
}