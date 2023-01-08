<?php

namespace App\Http\Controllers\Main\Content;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\Recommendation;
use App\Helpers\MetaData;
use App\Helpers\Text;
use App\Helpers\OpenGraph;
use App\Helpers\StructuredData;

class QuestionController extends Controller
{
    public function question(Question $question)
    {
        $pageTitle = Text::plain($question->question, 100, true);
        $pageTitle = ucwords($pageTitle);

        $asker = $question->user;
        $subject = $question->subject;
        $grade = $question->grade;
        $answers = $question->answers;

        // Get related questions
        $recommendations = Recommendation::where('parent_id', $question->id)->count();
        if ($recommendations >= config('content.related_questions')) {
            $recommendations = Recommendation::where('parent_id', $question->id)
                ->orderBy('id', 'desc')
                ->take(config('content.related_questions'))
                ->pluck('child_id')
                ->toArray();
            $relatedQuestions = Question::with('answers')->whereIn('id', $recommendations)->get();
        }
        else {
            $relatedQuestions = Question::with('answers')
                ->where('subject_id', $subject->id)
                ->where('grade_id', $grade->id)
                ->where('id', '<', $question->id)
                ->take(config('content.related_questions'))
                ->orderBy('id', 'desc')
                ->get();

            $relatedQuestionsCount = count($relatedQuestions);
            if ($relatedQuestionsCount < config('content.related_questions')) {
                $relatedQuestions = Question::with('answers')
                    ->where('subject_id', $subject->id)
                    ->where('grade_id', $grade->id)
                    ->where('id', '>', $question->id)
                    ->take(config('content.related_questions'))
                    ->orderBy('id', 'asc')
                    ->get();
            }
        }
        // [END] Get related questions

        // Get internal links
        $internalLinks = Question::where('id', '<', $question->id)
            ->take(config('content.related_questions'))
            ->orderBy('id', 'desc')
            ->get();

        $internalLinksCount = count($internalLinks);
        if ($internalLinksCount < config('content.related_questions')) {
            $internalLinks = Question::where('id', '>', $question->id)
                ->take(config('content.related_questions'))
                ->orderBy('id', 'asc')
                ->get();
        }
        // [END] Get internal links

        // Generate meta data
        $metaData = new MetaData();
        $metaData->canonical(route('content.question', ['question' => $question]));
        $metaData->desc(Text::plain($answers[0]->answer, 160, true));
        // [END] Generate meta data

        // Generate structured data
        $structuredData = new StructuredData();
        $structuredData->breadcrumb([
            $subject->name => route('content.subject', ['subject' => $subject]),
            $pageTitle => '',
        ]);

        $sdQuestionAnswers = $answers->map(function ($item, $key) use ($question) {
            $result = [];
            $result['text'] = Text::plain($item->answer);
            $result['upvoteCount'] = $item->vote;
            $result['url'] = route('content.question', ['question' => $question]) . '#answer' . $item->id;
            $result['isTop'] = $item->is_best == 'Y' ? true : false;

            return $result;
        })->toArray();

        $sdQuestion = [
            'questionShort' => ucwords(Text::plain($question->question)),
            'answerCount' => count($answers),
            'answers' => $sdQuestionAnswers,
        ];
        $structuredData->QA($sdQuestion);
        // [END] Generate structured data

        // Generate open graph
        $openGraph = new OpenGraph();
        $openGraph->url(route('content.question', ['question' => $question]));
        $openGraph->title($pageTitle);
        $openGraph->desc(Text::plain($answers[0]->answer, 160, true));
        // [END] Generate open graph

        return view('main.content.question', compact('question', 'pageTitle', 'asker', 'subject', 'grade', 'answers', 'relatedQuestions', 'metaData', 'openGraph', 'structuredData', 'internalLinks'));
    }

    public function search(Request $request)
    {
        $searchQuery = $request->query('q');
        if (!$searchQuery) {
            return redirect()->route('index');
        }

        $questions = Question::with('answers')->where('question', 'like', '%' . $searchQuery . '%')->orderBy('id', 'desc');
        $questionsCount = $questions->count();
        $questions = $questions->simplePaginate(config('content.item_per_page'))->withQueryString();
        
        return view('main.content.search', compact('searchQuery', 'questions', 'questionsCount'));
    }
}
