<?php

namespace App\Http\Controllers\Main\Misc;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Question;

class SitemapController extends Controller
{
    private $sitemapLimit = 2000;

    public function index()
    {
        $questions = Question::count();
        $sitemapLimit = $this->sitemapLimit;
        $sitemapsTotal = ceil($questions / $sitemapLimit);
        
        $sitemaps = [];
        for ($i = 1; $i <= $sitemapsTotal; $i++) {
            $sitemaps[] = route('sitemap.contents', ['index' => $i]);
        }

        return response()->view('main.misc.sitemap.sitemaps-index', compact('sitemaps'), 200)->header('Content-Type', 'application/xml');
    }

    public function sitemapQuestions($index)
    {
        if (!is_numeric($index)) {
            abort(404);
        }

        $sitemapLimit = $this->sitemapLimit;

        $firstID = ($index - 1) * $sitemapLimit;
        $lastID = $index * $sitemapLimit;

        $questions = Question::withOnly([])->select('slug')->where('id', '>', $firstID)->where('id', '<=', $lastID)->get();
        $urls = $questions
            ->map(function ($question) {
                return [
                    'loc' => route('content.question', ['question' => $question]),
                ];
            })
            ->toArray();

        return response()->view('main.misc.sitemap.sitemap', compact('urls'), 200)->header('Content-Type', 'application/xml');
    }
}
