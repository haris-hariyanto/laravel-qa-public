<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Blade;

class SubjectController extends Controller
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
            __('Subjects') => '',
        ];

        return view('admin.content.subjects.index', compact('breadcrumb'));
    }

    public function indexData(Request $request)
    {
        /*
        $counter = 0;
        $queries = [];
        DB::listen(function ($sql) use (&$counter, &$queries) {
            $counter++;
            $queries[] = $sql;
        });
        */

        $queryLimit = $request->query('limit', 10);
        $queryOffset = $request->query('offset', 0);
        $querySort = $request->query('sort', 'id');
        $queryOrder = $request->query('order', 'desc');
        $querySearch = $request->query('search');

        $subjectsCount = Subject::count();

        $subjects = Subject::when($querySearch, function ($query) use ($querySearch) {
            $query->where('name', 'like', '%' . $querySearch . '%')->orWhere('slug', 'like', '%' . $querySearch . '%');
        });
        $subjectsCountFiltered = $subjects->count();

        $subjects = $subjects->orderBy($querySort, $queryOrder)
            ->skip($queryOffset)
            ->take($queryLimit)
            ->get();
        
        return [
        // $respond = [
            'total' => $subjectsCountFiltered,
            'totalNotFiltered' => $subjectsCount,
            'rows' => $subjects->map(function ($subject) {
                return [
                    'id' => $subject->id,
                    'name' => $subject->name,
                    'slug' => $subject->slug,
                    'menu' => view('admin.content.subjects._menu', ['subject' => $subject])->render(),
                ];
            }),
        ];

        // dd($queries);
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
            __('Subjects') => route('admin.subjects.index'),
            __('Create Subject') => '',
        ];

        return view('admin.content.subjects.create', compact('breadcrumb'));
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
            'name' => ['required', 'string', 'min:2', 'max:64', 'regex:/^[a-zA-Z0-9\s]+$/'],
        ]);

        $slug = Str::slug($request->name);

        $loop = true;
        $slugToCheck = $slug;
        $counter = 1;
        while ($loop) {
            if (Subject::where('slug', $slugToCheck)->exists()) {
                $slugToCheck = $slug . '-' . $counter;
                $counter++;
            }
            else {
                $slug = $slugToCheck;
                $loop = false;
            }
        }

        $validated['slug'] = $slug;

        Subject::create($validated);

        return redirect()->route('admin.subjects.index')->with('success', __('Subject has been created!'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function show(Subject $subject)
    {
        return redirect()->route('admin.subjects.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function edit(Subject $subject)
    {
        $breadcrumb = [
            __('Dashboard') => route('admin.index'),
            __('Subjects') => route('admin.subjects.index'),
            __('Edit Subject') => '',
        ];

        return view('admin.content.subjects.edit', compact('subject', 'breadcrumb'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Subject $subject)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:64', 'regex:/^[a-zA-Z0-9\s]+$/'],
            'slug' => ['required', 'string', 'min:2', 'max:128', 'regex:/^[a-zA-Z0-9\-]+$/', Rule::unique('subjects')->ignore($subject->id)],
        ]);

        $validated['slug'] = strtolower($validated['slug']);

        $subject->update($validated);

        return redirect()->route('admin.subjects.edit', ['subject' => $subject])->with('success', __('Subject has been updated!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subject $subject)
    {
        $subjectQuestionsCount = $subject->questions()->count();

        if ($subjectQuestionsCount > 0) {
            return redirect()->route('admin.subjects.index')->with('error', __('Cannot delete a subject that still has contents!'));
        }

        $subject->delete();

        return redirect()->route('admin.subjects.index')->with('success', __('Subject has been deleted!'));
    }
}
