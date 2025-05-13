<?php

namespace App\Http\Controllers\Member;

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
    
        
        $questions = \App\Models\TravelRequestQuestion::where('status', 'active')->get();
    
        
        $pendingRequests = TravelRequest::with(['answers.question'])
            ->where('user_id', $userId)
            ->where('status', 'pending')
            ->latest()
            ->get();
    
        
        $pendingForms = collect()
            ->merge(LocalTravelForm::with(['request.answers.question', 'request.user'])
                ->where('status', 'pending')
                ->whereHas('request', fn($q) => $q->where('user_id', $userId))
                ->get())
            ->merge(OverseasTravelForm::with(['request.answers.question', 'request.user'])
                ->where('status', 'pending')
                ->whereHas('request', fn($q) => $q->where('user_id', $userId))
                ->get());
    
     
        $submittedForms = collect()
            ->merge(LocalTravelForm::with(['request.answers.question', 'request.user'])
                ->whereIn('status', ['submitted', 'approved'])
                ->whereHas('request', fn($q) =>
                    $q->where('user_id', $userId)
                      ->whereDate('intended_return_date', '>=', $today)
                )
                ->get())
            ->merge(OverseasTravelForm::with(['request.answers.question', 'request.user'])
                ->whereIn('status', ['submitted', 'approved'])
                ->whereHas('request', fn($q) =>
                    $q->where('user_id', $userId)
                      ->whereDate('intended_return_date', '>=', $today)
                )
                ->get());
    
        return view('dashboard', compact(
            'pendingRequests',
            'pendingForms',
            'submittedForms',
            'questions'
        ));
    }
    
}