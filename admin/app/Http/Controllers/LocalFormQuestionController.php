<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;
use App\Models\LocalFormQuestion;

class LocalFormQuestionController extends Controller
{
    public function index()
    {

        $questions = LocalFormQuestion::where('status', 'active')->orderBy('order')->get();


        return view('local-form-questions.index', compact('questions'));
    }

    public function create()
    {
        return view('local-form-questions.create');
    }

    public function store(Request $request)
    {
        Log::info('Incoming request data', $request->all());
    
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
            'allow_other' => $request->has('allow_other') ? true : false,
            'order' => LocalFormQuestion::max('order') + 1,
        ];
    
        if ($request->type === 'choice') {
            $choicesArray = array_filter(array_map('trim', explode("\n", $request->choices)));
            $data['choices'] = json_encode($choicesArray);
            Log::info('📝 Parsed choices:', $choicesArray);
        } else {
            $data['choices'] = null;
        }
    
        Log::info('✅ Final data to save:', $data);
    
        try {
            \App\Models\LocalFormQuestion::create($data);
            Log::info('Question saved successfully!');
        } catch (\Exception $e) {
            Log::error('Save error: ' . $e->getMessage());
            return back()->with('error', 'Something went wrong. Check logs.');
        }
    
        return redirect()->route('local-form-questions.index')->with('success', 'Question added.');
    }
    
    
    
    public function edit($id)
    {
        $question = LocalFormQuestion::findOrFail($id);
        return view('local-form-questions.edit', compact('question'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'question' => 'required|string',
            'type' => 'required|in:text,choice',
            'choices' => 'nullable|string',
            'allow_other' => 'sometimes|accepted'
        ]);
    
        $question = LocalFormQuestion::findOrFail($id);
    
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
    
        return redirect()->route('local-form-questions.index')->with('success', 'Question updated.');
    }


    public function destroy($id)
    {
        $question = LocalFormQuestion::findOrFail($id);
        $question->update(['status' => 'disabled']);

        return redirect()->route('local-form-questions.index')->with('success', 'Question disabled.');
    }

    public function reorder($id, $direction)
    {
        Log::info("🔃 Reorder triggered", ['id' => $id, 'direction' => $direction]);
    
        $question = LocalFormQuestion::find($id);
    
        if (!$question) {
            Log::warning("⚠️ Question not found", ['id' => $id]);
            return redirect()->route('local-form-questions.index')->with('error', 'Question not found.');
        }
    
        Log::info("📌 Current question order", ['id' => $question->id, 'order' => $question->order]);
    
        $swapWith = LocalFormQuestion::where('status', 'active')
            ->where('order', $direction === 'up' ? '<' : '>', $question->order)
            ->orderBy('order', $direction === 'up' ? 'desc' : 'asc')
            ->first();
    
        if ($swapWith) {
            Log::info("🔁 Swapping with", ['id' => $swapWith->id, 'order' => $swapWith->order]);
    
            [$question->order, $swapWith->order] = [$swapWith->order, $question->order];
            $question->save();
            $swapWith->save();
    
            Log::info("✅ Swap successful", [
                'updated_question' => $question->id,
                'new_order' => $question->order,
                'swapped_with' => $swapWith->id,
                'swapped_order' => $swapWith->order,
            ]);
        } else {
            Log::info("🚫 No question to swap with", ['direction' => $direction]);
        }
    
        return redirect()->route('local-form-questions.index');
    }
    
    
}
