<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class GradeController extends Controller
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
            __('Grades') => '',
        ];

        return view('admin.content.grades.index', compact('breadcrumb'));
    }

    public function indexData(Request $request)
    {
        $queryLimit = $request->query('limit', 10);
        $queryOffset = $request->query('offset', 0);
        $querySort = $request->query('sort', 'id');
        $queryOrder = $request->query('order', 'desc');
        $querySearch = $request->query('search');

        $gradesCount = Grade::count();

        $grades = Grade::when($querySearch, function ($query) use ($querySearch) {
            $query->where('name', 'like', '%' . $querySearch . '%')->orWhere('slug', 'like', '%' . $querySearch . '%');
        });
        $gradesCountFiltered = $grades->count();

        $grades = $grades->orderBy($querySort, $queryOrder)
            ->skip($queryOffset)
            ->take($queryLimit)
            ->get();
        
        return [
            'total' => $gradesCountFiltered,
            'totalNotFiltered' => $gradesCount,
            'rows' => $grades->map(function ($grade) {
                return [
                    'id' => $grade->id,
                    'name' => $grade->name,
                    'slug' => $grade->slug,
                    'menu' => view('admin.content.grades._menu', ['grade' => $grade])->render(),
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
            __('Grades') => route('admin.grades.index'),
            __('Create Grade') => '',
        ];

        return view('admin.content.grades.create', compact('breadcrumb'));
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
            if (Grade::where('slug', $slugToCheck)->exists()) {
                $slugToCheck = $slug . '-' . $counter;
                $counter++;
            }
            else {
                $slug = $slugToCheck;
                $loop = false;
            }
        }

        $validated['slug'] = $slug;

        Grade::create($validated);

        return redirect()->route('admin.grades.index')->with('success', __('Grade has been created!'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Grade  $grade
     * @return \Illuminate\Http\Response
     */
    public function show(Grade $grade)
    {
        return redirect()->route('admin.grades.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Grade  $grade
     * @return \Illuminate\Http\Response
     */
    public function edit(Grade $grade)
    {
        $breadcrumb = [
            __('Dashboard') => route('admin.index'),
            __('Grades') => route('admin.grades.index'),
            __('Edit Grade') => '',
        ];

        return view('admin.content.grades.edit', compact('grade', 'breadcrumb'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Grade  $grade
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Grade $grade)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:64', 'regex:/^[a-zA-Z0-9\s]+$/'],
            'slug' => ['required', 'string', 'min:2', 'max:128', 'regex:/^[a-zA-Z0-9\-]+$/', Rule::unique('grades')->ignore($grade->id)],
        ]);

        $validated['slug'] = strtolower($validated['slug']);

        $grade->update($validated);

        return redirect()->route('admin.grades.edit', ['grade' => $grade])->with('success', __('Grade has been updated!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Grade  $grade
     * @return \Illuminate\Http\Response
     */
    public function destroy(Grade $grade)
    {
        $gradeQuestionsCount = $grade->questions()->count();

        if ($gradeQuestionsCount > 0) {
            return redirect()->route('admin.grades.index')->with('error', __('Cannot delete a grade that still has contents!'));
        }

        $grade->delete();

        return redirect()->route('admin.grades.index')->with('success', __('Grade has been deleted!'));
    }
}
