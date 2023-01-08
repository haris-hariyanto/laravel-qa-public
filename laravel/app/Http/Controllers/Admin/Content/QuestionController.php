<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Controller;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $breadcrumb = [
            __('Dashboard') => route('admin.index'),
            __('Questions') => '',
        ];

        return view('admin.content.questions.index', compact('breadcrumb'));
    }

    public function indexData(Request $request)
    {
        $queryLimit = $request->query('limit', 10);
        $queryOffset = $request->query('offset', 0);
        $querySort = $request->query('sort', 'id');
        $queryOrder = $request->query('order', 'desc');
        $querySearch = $request->query('search');

        $questionsCount = Question::count();

        $questions = Question::with(['answers'])->when($querySearch, function ($query) use ($querySearch) {
            $query->where('question', 'like', '%' . $querySearch . '%');
        });
        $questionsCountFiltered = $questions->count();

        $questions = $questions->orderBy($querySort, $queryOrder)
            ->skip($queryOffset)
            ->take($queryLimit)
            ->get();
        
        return [
            'total' => $questionsCountFiltered,
            'totalNotFiltered' => $questionsCount,
            'rows' => $questions->map(function ($question) {
                return [
                    'id' => $question->id,
                    'question' => $question->question,
                    'answers' => $question->answers->count(),
                    'slug' => $question->slug,
                    'username' => $question->user->username,
                    'grade' => $question->grade->name,
                    'subject' => $question->subject->name,
                    'menu' => view('admin.content.questions._menu', ['question' => $question])->render(),
                ];
            }),
        ];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function show(Question $question)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function edit(Question $question)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Question $question)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function destroy(Question $question)
    {
        //
    }
}
