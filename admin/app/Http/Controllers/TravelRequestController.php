<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TravelRequest;
use App\Models\TravelRequestAnswer;
use App\Models\LocalTravelForm;
use App\Models\OverseasTravelForm;

class TravelRequestController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'pending');

        $requests = TravelRequest::with(['user', 'answers.question'])
            ->where('status', $status)
            ->orderByDesc('created_at')
            ->get();

        return view('travel-requests.index', compact('requests', 'status'));
    }
    public function show($id)
    {
        $request = TravelRequest::with([
            'user',
            'answers.question',
            'localForm',
            'OverseasForm',
        ])->findOrFail($id);
    
        return view('travel-requests.show', compact('request'));
    }

    public function approve(Request $request, $id)
    {
        $travelRequest = TravelRequest::findOrFail($id);
        $travelRequest->status = 'approved';
        $travelRequest->approved_at = now();
        $travelRequest->admin_comment = $request->admin_comment;
        $travelRequest->save();

        // Create corresponding travel form
        if ($travelRequest->type === 'local') {
            LocalTravelForm::create([
                'travel_request_id' => $travelRequest->id
            ]);
        } else {
            OverseasTravelForm::create([
                'travel_request_id' => $travelRequest->id
            ]);
        }

        $request->user->notify(new TravelRequestApproved($request));

        return redirect()->route('travel-requests.index')->with('success', 'Request approved.');
    }

    public function reject(Request $request, $id)
    {
        $travelRequest = TravelRequest::findOrFail($id);
        $travelRequest->status = 'rejected';
        $travelRequest->rejected_at = now();
        $travelRequest->admin_comment = $request->admin_comment;
        $travelRequest->save();

        return redirect()->route('travel-requests.index')->with('success', 'Request rejected.');
    }
}
