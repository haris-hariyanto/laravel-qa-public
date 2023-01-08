<?php

namespace App\Http\Controllers\Main\Content;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Grade;
use App\Models\Question;
use App\Helpers\MetaData;
use App\Helpers\OpenGraph;

class GradeController extends Controller
{
    public function index(Grade $grade)
    {
        $questions = Question::with('answers')
            ->where('grade_id', $grade->id)
            ->orderBy('id', 'desc')
            ->simplePaginate(config('content.item_per_page'));

        $pageItemsCount = $questions->count();        
        if ($pageItemsCount == 0) {
            abort(404);
        }

        // Generate meta data
        $currentPage = $questions->currentPage();
        $firstItemNum = $questions->firstItem();
        $lastItemNum = $questions->lastItem();

        $metaData = new MetaData();

        if ($currentPage == 1) {
            $pageTitle = __('main.grade_page_title', ['grade' => $grade->name]);
            $pageDesc = __('main.grade_meta_desc', ['grade' => $grade->name]);

            $metaData->canonical(route('content.grade', ['grade' => $grade]));
            $metaData->desc($pageDesc);
        }
        else {
            $paginationTitle = __('main.page_position_simple', ['current' => $currentPage]);
            $pageTitle = $paginationTitle . ' - ' . __('main.grade_page_title', ['grade' => $grade->name]);
            $paginationMod = __('main.pagination_mod', ['page' => $currentPage, 'first' => $firstItemNum, 'last' => $lastItemNum]);
            $pageDesc = $paginationMod . ' - ' . __('main.grade_meta_desc', ['grade' => $grade->name]);

            $metaData->canonical(route('content.grade', ['grade' => $grade, 'page' => $currentPage]));
            $metaData->desc($pageDesc);
        }

        if ($questions->hasMorePages()) {
            $metaData->linkRelNext($questions->nextPageUrl());
        }

        if ($currentPage == 2) {
            $metaData->linkRelPrev(route('content.grade', ['grade' => $grade]));
        }

        if ($currentPage >= 3) {
            $metaData->linkRelPrev($questions->previousPageUrl());
        }
        // [END] Generate meta data

        // Generate open graph
        $openGraph = new OpenGraph();
        if ($currentPage == 1) {
            $openGraph->url(route('content.grade', ['grade' => $grade]));
        }
        else {
            $openGraph->url(route('content.grade', ['grade' => $grade, 'page' => $currentPage]));
        }
        $openGraph->title($pageTitle);
        $openGraph->desc($pageDesc);
        // [END] Generate open graph

        return view('main.content.grade', compact('grade', 'questions', 'pageTitle', 'metaData', 'openGraph'));
    }
}
