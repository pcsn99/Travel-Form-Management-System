<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LocalTravelForm;
use App\Notifications\TravelFormRejected;
use App\Notifications\TravelFormApproved;

class LocalFormController extends Controller
{
    public function index()
    {
        $forms = LocalTravelForm::with('request.user')->orderByDesc('created_at')->get();
        return view('local-forms.index', compact('forms'));
    }

    public function show($id)
    {
        $form = LocalTravelForm::with(['answers.question', 'request.user'])->findOrFail($id);
        return view('local-forms.show', compact('form'));
    }

    public function approve(Request $request, $id)
    {
        $form = LocalTravelForm::findOrFail($id);
        $form->status = 'approved';
        $form->approved_at = now();
        $form->admin_comment = $request->admin_comment;
        $form->local_supervisor = auth()->id(); 
        $form->save();
    
        
        $form->request->user->notify(new TravelFormApproved($form));
    
        return redirect()->route('local-forms.index')->with('success', 'Form approved.');
    }
    
    public function reject(Request $request, $id)
    {
        $form = LocalTravelForm::findOrFail($id);
        $form->status = 'rejected';
        $form->admin_comment = $request->admin_comment;
        $form->rejected_at = now();
        $form->save();
    
        
        $form->request->user->notify(new TravelFormRejected($form));
    
        return redirect()->route('local-forms.index')->with('success', 'Form rejected.');
    }
    public function edit($id)
    {
        $form = \App\Models\LocalTravelForm::with(['answers', 'request.user'])->findOrFail($id);
    
        // Get answered question IDs
        $answeredIds = $form->answers->pluck('question_id')->toArray();
    
        // Get answered questions (even if disabled)
        $answeredQuestions = \App\Models\LocalFormQuestion::whereIn('id', $answeredIds)->get();
    
        // Get currently active questions not yet answered
        $activeUnansweredQuestions = \App\Models\LocalFormQuestion::where('status', 'active')
            ->whereNotIn('id', $answeredIds)
            ->get();
    
        // Merge both
        $questions = $answeredQuestions->merge($activeUnansweredQuestions);
    
        return view('local-forms.edit', compact('form', 'questions'));
    }

    public function update(Request $request, $id)
    {
        $form = \App\Models\LocalTravelForm::with('answers')->findOrFail($id);
    
        $request->validate([
            'answers' => 'array'
        ]);
    
        foreach ($request->answers as $questionId => $value) {
            $question = \App\Models\LocalFormQuestion::find($questionId);
        
            if ($question && $question->type === 'choice' && $value === '__other__') {
                $finalAnswer = $request->input("answers_other.$questionId");
            } else {
                $finalAnswer = $value;
            }
        
            $existing = $form->answers->where('question_id', $questionId)->first();
        
            if ($existing) {
                $existing->update(['answer' => $finalAnswer]);
            } else {
                \App\Models\LocalFormAnswer::create([
                    'local_travel_form_id' => $form->id,
                    'question_id' => $questionId,
                    'answer' => $finalAnswer,
                ]);
            }
        }
        
    
        return redirect()->route('local-forms.show', $form->id)->with('success', 'Form updated successfully.');
    }

    public function resetStatus($id)
    {
        $form = \App\Models\LocalTravelForm::findOrFail($id);
        $form->status = 'pending';
        $form->save();
    
        return redirect()->route('local-forms.show', $form->id)->with('success', 'Form status reset to pending.');
    }
    
    
}

