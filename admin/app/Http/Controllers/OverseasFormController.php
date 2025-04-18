<?php

namespace App\Http\Controllers;

use App\Notifications\TravelFormApproved;
use App\Notifications\TravelFormRejected;
use Illuminate\Http\Request;
use App\Models\OverseasTravelForm;

class OverseasFormController extends Controller
{
    public function index()
    {
        $forms = OverseasTravelForm::with('request.user')->orderByDesc('created_at')->get();
        return view('Overseas-forms.index', compact('forms'));
    }

    public function show($id)
    {
        $form = OverseasTravelForm::with(['answers.question', 'request.user'])->findOrFail($id);
        return view('Overseas-forms.show', compact('form'));
    }

    public function approve(Request $request, $id)
    {
        $form = OverseasTravelForm::findOrFail($id);
        $form->status = 'approved';
        $form->approved_at = now();
        $form->admin_comment = $request->admin_comment;
        $form->local_supervisor = auth()->id(); 
        $form->save();

        $form->request->user->notify(new TravelFormApproved());
        
        return redirect()->route('Overseas-forms.index')->with('success', 'Form approved.');
    }

    public function reject(Request $request, $id)
    {
        $form = OverseasTravelForm::findOrFail($id);
        $form->status = 'rejected';
        $form->admin_comment = $request->admin_comment;
        $form->rejected_at = now();
        $form->save();

        $form->request->user->notify(new TravelFormRejected());

        return redirect()->route('Overseas-forms.index')->with('success', 'Form rejected.');
    }

    public function edit($id)
    {
        $form = \App\Models\OverseasTravelForm::with(['answers', 'request.user'])->findOrFail($id);
    
        $answeredIds = $form->answers->pluck('question_id')->toArray();
    
        $answeredQuestions = \App\Models\OverseasFormQuestion::whereIn('id', $answeredIds)->get();
        $activeUnansweredQuestions = \App\Models\OverseasFormQuestion::where('status', 'active')
            ->whereNotIn('id', $answeredIds)
            ->get();
    
        $questions = $answeredQuestions->merge($activeUnansweredQuestions);
    
        return view('Overseas-forms.edit', compact('form', 'questions'));
    }

    public function update(Request $request, $id)
    {
        $form = \App\Models\OverseasTravelForm::with('answers')->findOrFail($id);
    
        $request->validate([
            'answers' => 'array'
        ]);
    
        foreach ($request->answers as $questionId => $value) {
            $question = \App\Models\OverseasFormQuestion::find($questionId);
    
            if ($question && $question->type === 'choice' && $value === '__other__') {
                $finalAnswer = $request->input("answers_other.$questionId");
            } else {
                $finalAnswer = $value;
            }
    
            $existing = $form->answers->where('question_id', $questionId)->first();
    
            if ($existing) {
                $existing->update(['answer' => $finalAnswer]);
            } else {
                \App\Models\OverseasFormAnswer::create([
                    'Overseas_travel_form_id' => $form->id,
                    'question_id' => $questionId,
                    'answer' => $finalAnswer,
                ]);
            }
        }
    
        return redirect()->route('Overseas-forms.show', $form->id)->with('success', 'Form updated successfully.');
    }

    public function resetStatus($id)
    {
        $form = \App\Models\OverseasTravelForm::findOrFail($id);
        $form->status = 'pending';
        $form->save();

        return redirect()->route('Overseas-forms.show', $form->id)->with('success', 'Form status reset to pending.');
    }

    
    
}

