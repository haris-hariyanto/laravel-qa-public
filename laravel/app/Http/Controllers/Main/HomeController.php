<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Question;
use App\Helpers\MetaData;
use App\Helpers\OpenGraph;

class HomeController extends Controller
{
    public function index()
    {
        $questions = Question::with('answers')
            ->without('user')
            ->orderBy('id', 'desc')
            ->take(config('content.item_per_page'))
            ->get();

        // Generate meta data
        $metaData = new MetaData();
        $metaData->canonical(route('index'));
        $metaData->desc(__('main.main_meta_desc'));
        // [END] Generate meta data

        // Generate open graph
        $openGraph = new OpenGraph();
        $openGraph->url(route('index'));
        $openGraph->title(__('main.main_page_title'));
        $openGraph->desc(__('main.main_meta_desc'));
        // [END] Generate open graph

        return view('main.index', compact('questions', 'metaData', 'openGraph'));
    }
}
