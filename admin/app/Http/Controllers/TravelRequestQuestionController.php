<?php

namespace App\Http\Controllers;

use App\Models\TravelRequestQuestion;
use Illuminate\Http\Request;

class TravelRequestQuestionController extends Controller
{
    public function index()
    {
        $questions = TravelRequestQuestion::all();
        return view('travel-requests.questions.index', compact('questions'));
    }

    public function create()
    {
        return view('travel-requests.questions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required|string'
        ]);

        TravelRequestQuestion::create([
            'question' => $request->question,
            'status' => 'active'
        ]);

        return redirect()->route('travel-request-questions.index')->with('success', 'Question added.');
    }

    public function edit($id)
    {
        $question = TravelRequestQuestion::findOrFail($id);
        return view('travel-requests.questions.edit', compact('question'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'question' => 'required|string'
        ]);

        $question = TravelRequestQuestion::findOrFail($id);
        $question->update(['question' => $request->question]);

        return redirect()->route('travel-request-questions.index')->with('success', 'Question updated.');
    }

    public function destroy($id)
    {
        $question = TravelRequestQuestion::findOrFail($id);
        $question->update(['status' => 'disabled']);

        return redirect()->route('travel-request-questions.index')->with('success', 'Question disabled.');
    }
}
