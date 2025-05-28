<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TravelRequest;
use App\Models\LocalTravelForm;
use App\Models\OverseasTravelForm;
use App\Models\TravelRequestAnswer;
use App\Notifications\TravelRequestApproved;
use App\Notifications\TravelRequestDeclined;

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

        $travelRequest->user->notify(new TravelRequestApproved($request));
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

        

        return redirect()->route('travel-requests.index')->with('success', 'Request approved.');
    }

    public function decline(Request $request, $id)
    {
        $travelRequest = TravelRequest::findOrFail($id);
        $travelRequest->status = 'declined';
        $travelRequest->admin_comment = $request->admin_comment;
        $travelRequest->save();

        $travelRequest->user->notify(new TravelRequestDeclined($travelRequest));
        return redirect()->route('travel-requests.index')->with('success', 'Request declined.');
    }

    public function resetStatus($id)
    {
        $request = TravelRequest::with(['localForm', 'OverseasForm'])->findOrFail($id);
    
        
        if ($request->type === 'local' && $request->localForm) {
            $request->localForm->delete();
        } elseif ($request->type === 'overseas' && $request->OverseasForm) {
            $request->OverseasForm->delete();
        }
    
       
        $request->status = 'pending';
        $request->approved_at = null;
        $request->admin_comment = null;
        $request->save();
    
        return redirect()->route('travel-requests.show', $request->id)->with('success', 'Travel request reset to pending.');
    }

}
