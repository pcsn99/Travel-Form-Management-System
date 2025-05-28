<?php

namespace App\Http\Controllers\Member;

use App\Models\User;
use App\Notifications\TravelFormSubmitted;
use Illuminate\Http\Request;
use App\Models\OverseasFormAnswer;
use App\Models\OverseasTravelForm;
use App\Models\OverseasFormQuestion;
use App\Http\Controllers\Controller;

class OverseasFormController extends Controller
{
    public function edit($id)
    {
        $form = OverseasTravelForm::with(['answers', 'request'])->findOrFail($id);

        if ($form->request->user_id !== auth()->id()) {
            abort(403);
        }

        $questions = OverseasFormQuestion::where('status', 'active')
            ->orWhereIn('id', $form->answers->pluck('question_id'))
            ->get();

        return view('Overseas-forms.edit', compact('form', 'questions'));
    }

    public function update(Request $request, $id)
    {
        $form = OverseasTravelForm::with('request')->findOrFail($id);

        if ($form->request->user_id !== auth()->id()) {
            abort(403);
        }

        foreach ($request->answers as $questionId => $answerText) {
            OverseasFormAnswer::updateOrCreate(
                [
                    'overseas_travel_form_id' => $form->id,
                    'question_id' => $questionId,
                ],
                [
                    'overseas_travel_form_id' => $form->id,
                    'question_id' => $questionId,
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
        $form = OverseasTravelForm::findOrFail($id);

        if ($form->status !== 'submitted') {
            return redirect()->back()->with('error', 'Only submitted forms can be cancelled.');
        }

        $form->status = 'declined';
        $form->admin_comment = 'Cancelled by user.';
        $form->declined_at = now();
        $form->save();

        return redirect()->route('dashboard')->with('success', 'Travel form cancelled successfully.');
    }

    public function show($id)
    {
        $form = OverseasTravelForm::with([
            'answers',
            'request.questionAnswers.question'
        ])->findOrFail($id);

        if ($form->request->user_id !== auth()->id()) {
            abort(403);
        }

        $questions = OverseasFormQuestion::where('status', 'active')
            ->orWhereIn('id', $form->answers->pluck('question_id'))
            ->get();

        return view('Overseas-forms.show', compact('form', 'questions'));
    }

    public function all()
    {
        $forms = OverseasTravelForm::with('request')
            ->whereHas('request', fn($q) => $q->where('user_id', auth()->id()))
            ->latest()->get();

        return view('Overseas-forms.index', compact('forms'));
    }
}
