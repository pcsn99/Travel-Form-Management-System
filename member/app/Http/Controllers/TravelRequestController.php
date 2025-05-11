<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\TravelRequestSubmitted;
use App\Models\TravelRequest;
use App\Models\TravelRequestQuestion;
use App\Models\TravelRequestAnswer;
use Illuminate\Http\Request;

class TravelRequestController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();

        $pendingRequests = $user->travelRequests()->where('status', 'pending')->latest()->get();

        return view('dashboard', compact('pendingRequests'));
    }

    public function create()
    {
        $questions = TravelRequestQuestion::where('status', 'active')->get();
        return view('travel-requests.create', compact('questions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:local,Overseas',
            'intended_departure_date' => 'required|date',
            'intended_return_date' => 'required|date|after_or_equal:intended_departure_date',
            'answers' => 'array'
        ]);

        $travelRequest = TravelRequest::create([
            'user_id' => auth()->id(),
            'type' => $request->type,
            'status' => 'pending',
            'intended_departure_date' => $request->intended_departure_date,
            'intended_return_date' => $request->intended_return_date,
        ]);

        foreach ($request->answers as $questionId => $answer) {
            TravelRequestAnswer::create([
                'travel_request_id' => $travelRequest->id,
                'question_id' => $questionId,
                'answer' => $answer,
            ]);
        }

        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new TravelRequestSubmitted($travelRequest));
        }

        return redirect()->route('dashboard')->with('success', 'Travel request submitted!');
    }

    public function edit($id)
    {
        $request = TravelRequest::with(['answers'])->where('id', $id)
            ->where('user_id', auth()->id())
            ->where('status', 'pending')
            ->firstOrFail();

        $questions = TravelRequestQuestion::where('status', 'active')->get();

        return view('travel-requests.edit', compact('request', 'questions'));
    }

    public function update(Request $req, $id)
    {
        $travelRequest = TravelRequest::where('id', $id)
            ->where('user_id', auth()->id())
            ->where('status', 'pending')
            ->firstOrFail();

        $req->validate([
            'type' => 'required|in:local,Overseas',
            'intended_departure_date' => 'required|date',
            'intended_return_date' => 'required|date|after_or_equal:intended_departure_date',
            'answers' => 'array'
        ]);

        $travelRequest->update([
            'type' => $req->type,
            'intended_departure_date' => $req->intended_departure_date,
            'intended_return_date' => $req->intended_return_date,
        ]);

        foreach ($req->answers as $qid => $answer) {
            $travelRequest->answers()->updateOrCreate(
                ['question_id' => $qid],
                ['answer' => $answer]
            );
        }

        return redirect()->route('dashboard')->with('success', 'Travel request updated.');
    }

    public function destroy($id)
    {
        $request = TravelRequest::where('id', $id)
            ->where('user_id', auth()->id())
            ->where('status', 'pending')
            ->firstOrFail();
    
        $request->answers()->delete(); 
        $request->delete();
    
        return redirect()->route('dashboard')->with('success', 'Travel request deleted.');
    }

    public function index()
    {
        $requests = TravelRequest::where('user_id', auth()->id())->latest()->get();
        return view('travel-requests.index', compact('requests'));
    }

    public function show($id)
    {
        $request = TravelRequest::with(['questionAnswers.question'])
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return view('travel-requests.show', compact('request'));
    }
    

}
