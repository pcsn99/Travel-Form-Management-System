<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OverseasFormQuestion;
use Illuminate\Support\Facades\Log;

class OverseasFormQuestionController extends Controller
{
    public function index()
    {
        $questions = OverseasFormQuestion::where('status', 'active')->orderBy('order')->get();

        return view('Overseas-form-questions.index', compact('questions'));
    }

    public function create()
    {
        return view('Overseas-form-questions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required|string',
            'type' => 'required|in:text,choice',
            'choices' => 'nullable|string',
            'allow_other' => 'sometimes|accepted'
        ]);
    
        $data = [
            'question' => $request->question,
            'type' => $request->type,
            'status' => 'active',
            'allow_other' => $request->has('allow_other'),
        ];
    
        if ($request->type === 'choice') {
            $choicesArray = array_filter(array_map('trim', explode("\n", $request->choices)));
            $data['choices'] = json_encode($choicesArray);
        } else {
            $data['choices'] = null;
        }
    
        \App\Models\OverseasFormQuestion::create($data);
    
        return redirect()->route('Overseas-form-questions.index')->with('success', 'Question added.');
    }
    

    public function edit($id)
    {
        $question = OverseasFormQuestion::findOrFail($id);
        return view('Overseas-form-questions.edit', compact('question'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'question' => 'required|string',
            'type' => 'required|in:text,choice',
            'choices' => 'nullable|string',
            'allow_other' => 'sometimes|accepted'
        ]);
    
        $question = \App\Models\OverseasFormQuestion::findOrFail($id);
    
        $data = [
            'question' => $request->question,
            'type' => $request->type,
            'allow_other' => $request->has('allow_other'),
        ];
    
        if ($request->type === 'choice') {
            $choicesArray = array_filter(array_map('trim', explode("\n", $request->choices)));
            $data['choices'] = json_encode($choicesArray);
        } else {
            $data['choices'] = null;
        }
    
        $question->update($data);
    
        return redirect()->route('Overseas-form-questions.index')->with('success', 'Question updated.');
    }
    

    public function destroy($id)
    {
        $question = OverseasFormQuestion::findOrFail($id);
        $question->update(['status' => 'disabled']);

        return redirect()->route('Overseas-form-questions.index')->with('success', 'Question disabled.');
    }

    public function reorder($id, $direction)
    {
        Log::info("🔃 [Overseas] Reorder triggered", ['id' => $id, 'direction' => $direction]);

        $question = \App\Models\OverseasFormQuestion::find($id);

        if (!$question) {
            Log::warning("⚠️ [Overseas] Question not found", ['id' => $id]);
            return redirect()->route('Overseas-form-questions.index')->with('error', 'Question not found.');
        }

        Log::info("📌 [Overseas] Current question order", ['id' => $question->id, 'order' => $question->order]);

        $swapWith = \App\Models\OverseasFormQuestion::where('status', 'active')
            ->where('order', $direction === 'up' ? '<' : '>', $question->order)
            ->orderBy('order', $direction === 'up' ? 'desc' : 'asc')
            ->first();

        if ($swapWith) {
            Log::info("🔁 [Overseas] Swapping with", ['id' => $swapWith->id, 'order' => $swapWith->order]);

            [$question->order, $swapWith->order] = [$swapWith->order, $question->order];
            $question->save();
            $swapWith->save();

            Log::info("✅ [Overseas] Swap successful", [
                'updated_question' => $question->id,
                'new_order' => $question->order,
                'swapped_with' => $swapWith->id,
                'swapped_order' => $swapWith->order,
            ]);
        } else {
            Log::info("🚫 [Overseas] No question to swap with", ['direction' => $direction]);
        }

        return redirect()->route('Overseas-form-questions.index');
    }
}
