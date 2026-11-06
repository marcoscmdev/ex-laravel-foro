<?php

namespace App\Http\Controllers;

use App\Models\Question;

class PageController extends Controller
{
    public function index()
    {
        $questions = Question::with('category', 'user')->latest()->get();

        return view('pages.home', ['questions' => $questions]);
    }
}
