<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Controller;
use App\Models\Keyword;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KeywordController extends Controller
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
            __('Keywords') => '',
        ];

        return view('admin.content.keywords.index', compact('breadcrumb'));
    }

    public function indexData(Request $request)
    {
        $queryLimit = $request->query('limit', 10);
        $queryOffset = $request->query('offset', 0);
        $querySort = $request->query('sort', 'id');
        $queryOrder = $request->query('order', 'desc');
        $querySearch = $request->query('search');

        $keywordsCount = Keyword::count();

        $keywords = Keyword::when($querySearch, function ($query) use ($querySearch) {
            $query->where('keyword', 'like', '%' . $querySearch . '%');
        });
        $keywordsCountFiltered = $keywords->count();

        $keywords = $keywords->orderBy($querySort, $queryOrder)
            ->skip($queryOffset)
            ->take($queryLimit)
            ->get();
        
        return [
            'total' => $keywordsCountFiltered,
            'totalNotFiltered' => $keywordsCount,
            'rows' => $keywords->map(function ($keyword) {
                return [
                    'id' => $keyword->id,
                    'keyword' => $keyword->keyword,
                    'menu' => view('admin.content.keywords._menu', ['keyword' => $keyword])->render(),
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
        $breadcrumb = [
            __('Dashboard') => route('admin.index'),
            __('Keywords') => route('admin.keywords.index'),
            __('Add Keyword') => '',
        ];

        return view('admin.content.keywords.create', compact('breadcrumb'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'keyword' => ['required', 'string', 'min:2', 'max:255', 'unique:keywords,keyword'],
        ]);

        Keyword::create($validated);

        return redirect()->route('admin.keywords.index')->with('success', __('Keyword has been added!'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Keyword  $keyword
     * @return \Illuminate\Http\Response
     */
    public function show(Keyword $keyword)
    {
        return redirect()->route('admin.keywords.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Keyword  $keyword
     * @return \Illuminate\Http\Response
     */
    public function edit(Keyword $keyword)
    {
        $breadcrumb = [
            __('Dashboard') => route('admin.index'),
            __('Keywords') => route('admin.keywords.index'),
            __('Edit Keyword') => '',
        ];

        return view('admin.content.keywords.edit', compact('keyword', 'breadcrumb'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Keyword  $keyword
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Keyword $keyword)
    {
        $validated = $request->validate([
            'keyword' => ['required', 'string', 'min:2', 'max:255', Rule::unique('keywords')->ignore($keyword->id)],
        ]);

        $keyword->update($validated);

        return redirect()->back()->with('success', __('Keyword has been updated!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Keyword  $keyword
     * @return \Illuminate\Http\Response
     */
    public function destroy(Keyword $keyword)
    {
        $keyword->delete();

        return redirect()->route('admin.keywords.index')->with('success', __('Keyword has been deleted!'));
    }
}
