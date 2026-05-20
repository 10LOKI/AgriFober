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
            'users'                  => User::count(),
            'parcels'                => Parcel::count(),
            'cultures'               => Culture::count(),
            'products'               => Product::count(),
            'ai_interactions_today'  => InteractionIA::whereDate('created_at', today())->count(),
            'pending_users_count'    => User::where('is_approved', false)->count(),
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

        return view('admin.dashboard', compact('stats'));
    }
}