<?php

namespace App\Http\Controllers\Member;

use App\Models\User;
use App\Notifications\TravelFormSubmitted;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LocalTravelForm;
use App\Models\LocalFormQuestion;
use App\Models\LocalFormAnswer;

class LocalFormController extends Controller
{
    public function edit($id)
    {
        $form = LocalTravelForm::with(['answers', 'request'])->findOrFail($id);

        if ($form->request->user_id !== auth()->id()) {
            abort(403);
        }

        $questions = LocalFormQuestion::where('status', 'active')
            ->orWhereIn('id', $form->answers->pluck('question_id'))
            ->get();

        return view('local-forms.edit', compact('form', 'questions'));
    }

    public function update(Request $request, $id)
    {
        $form = LocalTravelForm::with('request')->findOrFail($id);

        if ($form->request->user_id !== auth()->id()) {
            abort(403);
        }

        foreach ($request->answers as $questionId => $answerText) {
            $answer = LocalFormAnswer::updateOrCreate(
                [
                    'local_travel_form_id' => $form->id,
                    'question_id' => $questionId,
                ],
                [
                    'answer' => $request->answers_other[$questionId] ?? $answerText,
                ]
            );
        }

        $form->status = 'submitted';
        $form->submitted_at = now();
        $form->save();

        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new TravelFormSubmitted($form, $form->request));
        }

        return redirect()->back()->with('success', 'Form submitted successfully!');
    }

    public function cancel($id)
    {
        $form = LocalTravelForm::findOrFail($id);

        if ($form->status !== 'submitted') {
            return redirect()->back()->with('error', 'Only submitted forms can be cancelled.');
        }

        $form->status = 'rejected';
        $form->admin_comment = 'Cancelled by user.';
        $form->rejected_at = now();
        $form->save();

        return redirect()->route('dashboard')->with('success', 'Travel form cancelled successfully.');
    }

    public function show($id)
    {
        $form = LocalTravelForm::with([
            'answers',
            'request.questionAnswers.question'
        ])->findOrFail($id);

        if ($form->request->user_id !== auth()->id()) {
            abort(403);
        }

        $questions = \App\Models\LocalFormQuestion::where('status', 'active')
            ->orWhereIn('id', $form->answers->pluck('question_id'))
            ->get();

        return view('local-forms.show', compact('form', 'questions'));
    }

    public function all()
    {
        $forms = \App\Models\LocalTravelForm::with([
            'request.user',
            'request.answers.question'
        ])
        ->whereHas('request', fn($q) => $q->where('user_id', auth()->id()))
        ->latest()
        ->get();
    
        return view('local-forms.index', compact('forms'));
    }
}
