<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use Illuminate\Http\Request;

class AnswerController extends Controller
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
            __('Answers') => '',
        ];

        return view('admin.content.answers.index', compact('breadcrumb'));
    }

    public function indexData(Request $request)
    {
        $queryLimit = $request->query('limit', 10);
        $queryOffset = $request->query('offset', 0);
        $querySort = $request->query('sort', 'id');
        $queryOrder = $request->query('order', 'desc');
        $querySearch = $request->query('search');

        $answersCount = Answer::count();

        $answers = Answer::with(['question'])->when($querySearch, function ($query) use ($querySearch) {
            $query->where('answer', 'like', '%' . $querySearch . '%');
        });
        $answersCountFiltered = $answers->count();

        $answers = $answers->orderBy($querySort, $queryOrder)
            ->skip($queryOffset)
            ->take($queryLimit)
            ->get();
        
        return [
            'total' => $answersCountFiltered,
            'totalNotFiltered' => $answersCount,
            'rows' => $answers->map(function ($answer) {
                return [
                    'id' => $answer->id,
                    'answer' => strip_tags($answer->answer),
                    'username' => $answer->user->username,
                    'menu' => view('admin.content.answers._menu', ['answer' => $answer])->render(),
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
     * @param  \App\Models\Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function show(Answer $answer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function edit(Answer $answer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Answer $answer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Answer $answer)
    {
        //
    }
}
