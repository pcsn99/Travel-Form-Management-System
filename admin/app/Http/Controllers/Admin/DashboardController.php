<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TravelRequest;
use App\Models\LocalTravelForm;
use App\Models\OverseasTravelForm;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        
        $pendingRequests = TravelRequest::with('user')
            ->where('status', 'pending')
            ->latest()
            ->take(10)
            ->get();

       
        $threeWeeksFromNow = Carbon::now()->addWeeks(3);
        $pendingLocalForms = LocalTravelForm::with('request.user')
            ->whereIn('status', ['submitted', 'approved'])
            ->whereNotNull('submitted_at')
            ->whereHas('request', function ($query) use ($threeWeeksFromNow) {
                $query->whereDate('intended_departure_date', '<=', $threeWeeksFromNow);
            })
            ->latest()
            ->take(10)
            ->get();

 
        $threeMonthsFromNow = Carbon::now()->addMonths(3);
        $pendingOverseasForms = OverseasTravelForm::with('request.user')
            ->whereIn('status', ['submitted', 'approved'])
            ->whereNotNull('submitted_at')
            ->whereHas('request', function ($query) use ($threeMonthsFromNow) {
                $query->whereDate('intended_departure_date', '<=', $threeMonthsFromNow);
            })
            ->latest()
            ->take(10)
            ->get();

            $calendarEvents = [];

            $localForms = LocalTravelForm::with('request.user')
                ->where('status', 'approved')
                ->whereHas('request', function ($query) {
                    $query->where('status', 'approved');
                })->get();
            
            foreach ($localForms as $form) {
                $calendarEvents[] = [
                    'title' => 'Local Travel',
                    'start' => $form->request->intended_departure_date,
                    'end' => Carbon::parse($form->request->intended_return_date)->addDay()->toDateString(),
                    'color' => 'red',
                    'display' => 'background'
                ];
            }
            
            $OverseasForms = OverseasTravelForm::with('request.user')
                ->where('status', 'approved')
                ->whereHas('request', function ($query) {
                    $query->where('status', 'approved');
                })->get();
            
            foreach ($OverseasForms as $form) {
                $calendarEvents[] = [
                    'title' => 'Overseas Travel',
                    'start' => $form->request->intended_departure_date,
                    'end' => Carbon::parse($form->request->intended_return_date)->addDay()->toDateString(),
                    'color' => 'red',
                    'display' => 'background'
                ];
            }

        return view('admin.dashboard', compact(
            'pendingRequests',
            'pendingLocalForms',
            'pendingOverseasForms',
            'calendarEvents'
        ));
    }

    public function calendarDetails($date)
    {
        $traveling = [];
    
        // Approved Local Forms (whose requests are also approved)
        $localTravelers = \App\Models\LocalTravelForm::with('request.user')
            ->where('status', 'approved')
            ->whereHas('request', function ($q) use ($date) {
                $q->where('status', 'approved')
                  ->whereDate('intended_departure_date', '<=', $date)
                  ->whereDate('intended_return_date', '>=', $date);
            })
            ->get()
            ->pluck('request.user.name')
            ->toArray();
    
        // Approved Overseas Forms (whose requests are also approved)
        $OverseasTravelers = \App\Models\OverseasTravelForm::with('request.user')
            ->where('status', 'approved')
            ->whereHas('request', function ($q) use ($date) {
                $q->where('status', 'approved')
                  ->whereDate('intended_departure_date', '<=', $date)
                  ->whereDate('intended_return_date', '>=', $date);
            })
            ->get()
            ->pluck('request.user.name')
            ->toArray();
    
        $traveling = array_merge($localTravelers, $OverseasTravelers);
    
        // All members
        $allMembers = \App\Models\User::where('role', 'member')->pluck('name')->toArray();
    
        // Available = not traveling
        $available = array_diff($allMembers, $traveling);
    
        return response()->json([
            'traveling' => $traveling,
            'available' => array_values($available),
        ]);
    }

}
