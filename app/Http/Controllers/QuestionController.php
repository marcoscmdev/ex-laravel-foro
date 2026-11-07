<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQuestionRequest;
use App\Models\Category;
use App\Models\Question;
use App\Support\QuestionShowLoader;
use Illuminate\Http\Request;

class QuestionController extends Controller
{

    public function index()
    {
        $questions = Question::with([
            'user',
            'category',
        ])
        ->latest()
        ->paginate(24);

        return view('questions.index', ['questions' => $questions]);
    }

    public function create()
    {
        $categories = Category::all();
        return view('questions.create',[
            'categories' => $categories 
                   ] );             
    }

    public function store(StoreQuestionRequest $request)
    {
        

        $question = Question::create([
            'user_id' => auth()->id(),
            'category_id' => $request->category_id,
            'title' => $request->title,
            'description' => $request->description,
        ]);


    return redirect()->route('questions.show', $question);
    }

    public function edit(Question $question)
    {
        $categories = Category::all();
        return view('questions.edit', [
            'question' => $question,
            'categories' => $categories,
        ]);
    }

    public function update(UpdateQuestionRequest $request, Question $question)
    {
        

        $question->update([
            'category_id' => $request->category_id,
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return redirect()->route('questions.show', $question);
    }   

    public function show(Question $question, QuestionShowLoader $loader)
    {
        
        $loader ->load($question);

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
