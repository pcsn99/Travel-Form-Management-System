<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\TravelRequest;
use App\Models\TravelRequestQuestion;
use App\Models\TravelRequestAnswer;
use Illuminate\Http\Request;

class AdminTravelRequestController extends Controller
{
    public function search()
    {
        return view('admin-travel-requests.search');
    }

    public function findUser(Request $request)
    {
        $users = User::where('role', 'member')
            ->where('name', 'like', '%' . $request->input('q') . '%')
            ->get();

        return view('admin-travel-requests.search', compact('users'));
    }

    public function createForm($userId)
    {
        $user = User::findOrFail($userId);
        $questions = TravelRequestQuestion::where('status', 'active')->get();

        return view('admin-travel-requests.create', compact('user', 'questions'));
    }

    public function store(Request $request, $userId)
    {
        $request->validate([
            'type' => 'required|in:local,Overseas',
            'intended_departure_date' => 'required|date',
            'intended_return_date' => 'required|date|after_or_equal:intended_departure_date',
            'answers' => 'array'
        ]);

        $travelRequest = TravelRequest::create([
            'user_id' => $userId,
            'type' => $request->type,
            'date_of_travel' => $request->intended_departure_date,
            'intended_departure_date' => $request->intended_departure_date,
            'intended_return_date' => $request->intended_return_date,
            'status' => 'pending'
        ]);

        foreach ($request->answers as $questionId => $answer) {
            TravelRequestAnswer::create([
                'travel_request_id' => $travelRequest->id,
                'question_id' => $questionId,
                'answer' => $answer,
            ]);
        }

        return redirect()->route('travel-requests.index')->with('success', 'Travel request created successfully.');
    }
}

