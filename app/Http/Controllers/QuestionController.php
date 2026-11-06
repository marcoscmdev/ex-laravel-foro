<?php

namespace App\Http\Controllers;

use App\Models\Question;

class QuestionController extends Controller
{
    public function show(Question $question)
    {
        $userId = 20;

        $question->load([
            'user',
            'category',

            'answers' => fn ($query) => $query->with([
                'user',
                'hearts' => fn ($query) => $query->where('user_id', $userId),
                'comments' => fn ($query) => $query->with([
                    'user',
                    'hearts' => fn ($query) => $query->where('user_id', $userId),
                ]),
            ]),

            'comments' => fn ($query) => $query->with([
                'user',
                'hearts' => fn ($query) => $query->where('user_id', $userId),
            ]),

            'hearts' => fn ($query) => $query->where('user_id', $userId),
        ]);

        return view('questions.show', [
            'question' => $question,

        ]);
    }

    public function destroy(Question $question)
    {
        $question->delete();
        return redirect()->route('home');
    }
}
