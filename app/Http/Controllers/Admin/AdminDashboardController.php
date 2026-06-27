<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CreditLevel;
use App\Models\SiteSetting;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'totalCreditLevels' => CreditLevel::count(),
            'activeCreditLevels' => CreditLevel::where('is_active', true)->count(),
            'totalSettings' => SiteSetting::count(),
        ]);
    }
}
