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
use App\Models\ContentCache;
use App\Models\Subject;
use App\Models\Grade;
use App\Models\Answer;

class QuestionController extends Controller
{
    public function question($question)
    {
        $content = ContentCache::where('slug', $question)->first();
        if ($content) {
            $isCached = true;

            $contentData = json_decode($content->cached_data, true);

            $question = new Question();
            $question->id = $contentData['id'];
            $question->question = $content->question;
            $question->slug = $content->slug;

            $subject = new Subject();
            $subject->id = $contentData['subject']['id'];
            $subject->name = $contentData['subject']['name'];
            $subject->slug = $contentData['subject']['slug'];

            $grade = new Grade();
            $grade->id = $contentData['grade']['id'];
            $grade->name = $contentData['grade']['name'];
            $grade->slug = $contentData['grade']['slug'];

            $answers = [];
            foreach ($contentData['answers'] as $answer) {
                $answerModel = new Answer();
                $answerModel->id = $answer['id'];
                $answerModel->answer = $answer['answer'];
                $answerModel->is_best = $answer['is_best'];
                $answerModel->vote = $answer['vote'];
                $answers[] = $answerModel;
            }

            // Get related questions
            $relatedQuestions = [];
            foreach ($contentData['relatedQuestions'] as $relatedQuestion) {
                $relatedQuestionModel = new Question();
                $relatedQuestionModel->question = $relatedQuestion['question'];
                
                $relatedQuestionAnswers = [];
                foreach ($relatedQuestion['answers'] as $answer) {
                    $relatedQuestionAnswerModel = new Answer();
                    $relatedQuestionAnswerModel->answer = $answer;
                    $relatedQuestionAnswers[] = $relatedQuestionAnswerModel;
                }

                $relatedQuestionModel->answers = $relatedQuestionAnswers;

                $relatedQuestions[] = $relatedQuestionModel;
            }
            // [END] Get related questions
        }
        else {
            $isCached = false;

            $question = Question::where('slug', $question)->first();
            dd('OK');
            if (!$question) {
                return redirect()->route('index');
            }

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
        }

        $pageTitle = Text::plain($question->question, 100, true);
        $pageTitle = ucwords($pageTitle);

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
        if ($isCached) {
            $metaData->canonical(route('content.question', ['question' => $content->question]));
        }
        else {
            $metaData->canonical(route('content.question', ['question' => $question]));
        }
        $metaData->desc(Text::plain($answers[0]->answer, 160, true));
        // [END] Generate meta data

        // Generate structured data
        $structuredData = new StructuredData();
        $structuredData->breadcrumb([
            $subject->name => route('content.subject', ['subject' => $subject]),
            $pageTitle => '',
        ]);

        $sdQuestionAnswers = [];
        foreach ($answers as $answer) {
            $sdQuestionAnswer = [];
            $sdQuestionAnswer['text'] = Text::plain($answer->answer);
            $sdQuestionAnswer['upvoteCount'] = $answer->vote;
            $sdQuestionAnswer['url'] = route('content.question', ['question' => $question]) . '#answer' . $answer->id;
            $sdQuestionAnswer['isTop'] = $answer->is_best == 'Y' ? true : false;
            $sdQuestionAnswers[] = $sdQuestionAnswer;
        }

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

        return view('main.content.question', compact('question', 'pageTitle', 'subject', 'grade', 'answers', 'relatedQuestions', 'metaData', 'openGraph', 'structuredData', 'internalLinks'));
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
