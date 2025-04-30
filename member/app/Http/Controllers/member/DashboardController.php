<?php

namespace App\Http\Controllers\member;

use Carbon\Carbon;
use App\Models\TravelRequest;
use App\Models\OverseasTravelForm;
use App\Models\LocalTravelForm;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $today = Carbon::today();
    
        // Pending travel requests
        $pendingRequests = TravelRequest::where('user_id', $userId)
            ->where('status', 'pending')
            ->latest()
            ->get();
    
        // Pending travel forms (not yet submitted)
        $pendingForms = collect()
            ->merge(LocalTravelForm::with('request')
                ->where('status', 'pending')
                ->whereHas('request', fn($q) => $q->where('user_id', $userId))
                ->get())
            ->merge(OverseasTravelForm::with('request')
                ->where('status', 'pending')
                ->whereHas('request', fn($q) => $q->where('user_id', $userId))
                ->get());
    
        // Submitted or approved travel forms that are upcoming
        $submittedForms = collect()
            ->merge(LocalTravelForm::with('request')
                ->whereIn('status', ['submitted', 'approved'])
                ->whereHas('request', fn($q) =>
                    $q->where('user_id', $userId)
                      ->whereDate('intended_return_date', '>=', $today)
                )
                ->get())
            ->merge(OverseasTravelForm::with('request')
                ->whereIn('status', ['submitted', 'approved'])
                ->whereHas('request', fn($q) =>
                    $q->where('user_id', $userId)
                      ->whereDate('intended_return_date', '>=', $today)
                )
                ->get());
    
        return view('dashboard', compact(
            'pendingRequests',
            'pendingForms',
            'submittedForms'
        ));
    }
}