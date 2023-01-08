<?php

namespace App\Http\Controllers\Main\Content;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subject;
use App\Models\Question;
use App\Helpers\MetaData;
use App\Helpers\OpenGraph;

class SubjectController extends Controller
{
    public function index(Request $request, Subject $subject)
    {
        $questions = Question::with('answers')
            ->where('subject_id', $subject->id)
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
            $pageTitle = __('main.subject_page_title', ['subject' => $subject->name]);
            $pageDesc = __('main.subject_meta_desc', ['subject' => $subject->name]);

            $metaData->canonical(route('content.subject', ['subject' => $subject]));
            $metaData->desc($pageDesc);
        }
        else {
            $paginationTitle = __('main.page_position_simple', ['current' => $currentPage]);
            $pageTitle = $paginationTitle . ' - ' . __('main.subject_page_title', ['subject' => $subject->name]);
            $paginationMod = __('main.pagination_mod', ['page' => $currentPage, 'first' => $firstItemNum, 'last' => $lastItemNum]);
            $pageDesc = $paginationMod . ' - ' . __('main.subject_meta_desc', ['subject' => $subject->name]);

            $metaData->canonical(route('content.subject', ['subject' => $subject, 'page' => $currentPage]));
            $metaData->desc($pageDesc);
        }

        if ($questions->hasMorePages()) {
            $metaData->linkRelNext($questions->nextPageUrl());
        }

        if ($currentPage == 2) {
            $metaData->linkRelPrev(route('content.subject', ['subject' => $subject]));
        }

        if ($currentPage >= 3) {
            $metaData->linkRelPrev($questions->previousPageUrl());
        }
        // [END] Generate meta data

        // Generate open graph
        $openGraph = new OpenGraph();
        if ($currentPage == 1) {
            $openGraph->url(route('content.subject', ['subject' => $subject]));
        }
        else {
            $openGraph->url(route('content.subject', ['subject' => $subject, 'page' => $currentPage]));
        }
        $openGraph->title($pageTitle);
        $openGraph->desc($pageDesc);
        // [END] Generate open graph

        return view('main.content.subject', compact('subject', 'questions', 'pageTitle', 'metaData', 'openGraph'));
    }
}
