<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Helpers\Permission;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $userAuth = new Permission($request->user()->group, 'admin');
        $this->authorize('auth-check', $userAuth->authorize('admin-pages-index'));
        
        $breadcrumb = [
            __('Dashboard') => route('admin.index'),
            __('Pages') => '',
        ];

        return view('admin.pages.index', compact('breadcrumb'));
    }

    public function indexData(Request $request)
    {
        $userAuth = new Permission($request->user()->group, 'admin');
        $this->authorize('auth-check', $userAuth->authorize('admin-pages-index'));

        $queryLimit = $request->query('limit', 10);
        $queryOffset = $request->query('offset', 0);
        $querySort = $request->query('sort', 'id');
        $queryOrder = $request->query('order', 'desc');
        $querySearch = $request->query('search');

        $pagesCount = Page::count();

        $pages = Page::when($querySearch, function ($query) use ($querySearch) {
            $query->where('title', 'like', '%' . $querySearch . '%');
        });
        $pagesCountFiltered = $pages->count();

        $pages = $pages->orderBy($querySort, $queryOrder)
            ->skip($queryOffset)
            ->take($queryLimit)
            ->get();
        
        return [
            'total' => $pagesCountFiltered,
            'totalNotFiltered' => $pagesCount,
            'rows' => $pages->map(function ($page) use ($userAuth) {
                return [
                    'id' => $page->id,
                    'slug' => $page->slug,
                    'title' => $page->title,
                    'status' => $page->status == 'PUBLISHED' ? '<span class="badge badge-info">' . __('Published') . '</span>' : '<span class="badge badge-secondary">' . __('Draft') . '</span>',
                    'menu' => view('admin.pages._menu', ['page' => $page, 'userAuth' => $userAuth])->render(),
                ];
            }),
        ];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $userAuth = new Permission($request->user()->group, 'admin');
        $this->authorize('auth-check', $userAuth->authorize('admin-pages-create'));

        $breadcrumb = [
            __('Dashboard') => route('admin.index'),
            __('Pages') => route('admin.pages.index'),
            __('Create Page') => '',
        ];

        return view('admin.pages.create', compact('breadcrumb'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $userAuth = new Permission($request->user()->group, 'admin');
        $this->authorize('auth-check', $userAuth->authorize('admin-pages-create'));

        if ($request->page_slug) {
            $pageSlugRule = ['between:1,200', 'unique:pages,slug'];

            $pageSlug = $request->page_slug;
        }
        else {
            $pageSlugRule = [];

            $pageSlug = Str::slug($request->page_title);
            
            $loop = true;
            $slugToCheck = $pageSlug;
            $counter = 1;

            while ($loop) {
                if (Page::where('slug', $slugToCheck)->exists()) {
                    $slugToCheck = $pageSlug . '-' . $counter;
                    $counter++;
                }
                else {
                    $pageSlug = $slugToCheck;
                    $loop = false;
                }
            }
        }

        $request->validate([
            'page_title' => ['required', 'between:1,200'],
            'page_content' => ['max:60000'],
            'page_description' => ['max:500'],
            'status' => ['in:PUBLISHED,DRAFT'],
            'page_slug' => $pageSlugRule,
        ]);

        Page::create([
            'slug' => $pageSlug,
            'user_id' => $request->user()->id,
            'title' => $request->page_title,
            'description' => $request->page_description,
            'content' => $request->page_content,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.pages.index')->with('success', __('Page has been created!'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function show(Page $page)
    {
        return redirect()->route('admin.pages.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Page $page)
    {
        $userAuth = new Permission($request->user()->group, 'admin');
        $this->authorize('auth-check', $userAuth->authorize('admin-pages-edit'));

        $breadcrumb = [
            __('Dashboard') => route('admin.index'),
            __('Pages') => route('admin.pages.index'),
            __('Edit Page') => '',
        ];

        return view('admin.pages.edit', compact('page', 'breadcrumb'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Page $page)
    {
        $userAuth = new Permission($request->user()->group, 'admin');
        $this->authorize('auth-check', $userAuth->authorize('admin-pages-edit'));

        $request->validate([
            'page_title' => ['required', 'between:1,200'],
            'page_content' => ['max:60000'],
            'page_description' => ['max:500'],
            'status' => ['in:PUBLISHED,DRAFT'],
            'page_slug' => ['between:1,200', Rule::unique('pages', 'slug')->ignore($page->id)],
        ]);

        $page->update([
            'slug' => $request->page_slug,
            'title' => $request->page_title,
            'content' => $request->page_content,
            'description' => $request->page_description,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', __('Page has been updated!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Page $page)
    {
        $userAuth = new Permission($request->user()->group, 'admin');
        $this->authorize('auth-check', $userAuth->authorize('admin-pages-delete'));

        $page->delete();

        return redirect()->back()->with('success', __('Page has been deleted!'));
    }
}
