<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [Controllers\Main\HomeController::class, 'index'])->name('index');

Route::get('/p/{page}', [Controllers\Main\Misc\PageController::class, 'page'])->name('page');
Route::get('/contact', [Controllers\Main\Misc\ContactController::class, 'contact'])->name('contact');
Route::post('/contact', [Controllers\Main\Misc\ContactController::class, 'send']);

Route::get('/sitemaps-index.xml', [Controllers\Main\Misc\SitemapController::class, 'index']);
Route::get('/sitemap-questions-{index}.xml', [Controllers\Main\Misc\SitemapController::class, 'sitemapQuestions'])->name('sitemap.questions');
Route::get('/sitemap-contents-{index}.xml', [Controllers\Main\Misc\SitemapController::class, 'sitemapQuestions'])->name('sitemap.contents');
Route::get('/indexer', [Controllers\Main\Misc\IndexerController::class, 'index']);

require __DIR__.'/auth.php';

Route::prefix('admin')->name('admin.')->middleware(['auth', 'can:is-admin'])->group(function () {
    Route::redirect('/', '/admin/questions')->name('index');

    Route::resource('pages', Controllers\Admin\PageController::class);
    Route::get('/pages-index.json', [Controllers\Admin\PageController::class, 'indexData'])->name('pages.index.data');

    Route::resource('contacts', Controllers\Admin\ContactController::class)->only(['index', 'show', 'destroy']);
    Route::get('/contacts-index.json', [Controllers\Admin\ContactController::class, 'indexData'])->name('contacts.index.data');
    Route::put('/contacts/{contact}/toggle-status', [Controllers\Admin\ContactController::class, 'toggleStatus'])->name('contacts.toggle-status');

    Route::get('/grades-index.json', [Controllers\Admin\Content\GradeController::class, 'indexData'])->name('grades.index.data');
    Route::resource('grades', Controllers\Admin\Content\GradeController::class);

    Route::get('/subjects-index.json', [Controllers\Admin\Content\SubjectController::class, 'indexData'])->name('subjects.index.data');
    Route::resource('subjects', Controllers\Admin\Content\SubjectController::class);

    Route::get('/questions-index.json', [Controllers\Admin\Content\QuestionController::class, 'indexData'])->name('questions.index.data');
    Route::resource('questions', Controllers\Admin\Content\QuestionController::class);

    Route::get('/answers-index.json', [Controllers\Admin\Content\AnswerController::class, 'indexData'])->name('answers.index.data');
    Route::resource('answers', Controllers\Admin\Content\AnswerController::class);

    Route::get('/keywords-index.json', [Controllers\Admin\Content\KeywordController::class, 'indexData'])->name('keywords.index.data');
    Route::get('/keywords.txt', [Controllers\Admin\Content\KeywordEximController::class, 'file'])->name('keywords.index.file');
    Route::get('/keywords/import', [Controllers\Admin\Content\KeywordEximController::class, 'import'])->name('keywords.import');
    Route::post('/keywords/import', [Controllers\Admin\Content\KeywordEximController::class, 'saveImport']);
    Route::resource('keywords', Controllers\Admin\Content\KeywordController::class);
});

Route::name('content.')->group(function () {
    Route::get('/answer/{question}', [Controllers\Main\Content\QuestionController::class, 'question'])->name('question');
    Route::get('/answer', function () {
        return redirect()->route('index');
    });

    Route::get('/subject/{subject}', [Controllers\Main\Content\SubjectController::class, 'index'])->name('subject');
    Route::get('/subject', function () {
        return redirect()->route('index');
    });

    Route::get('/grade/{grade}', [Controllers\Main\Content\GradeController::class, 'index'])->name('grade');
    Route::get('/grade', function () {
        return redirect()->route('index');
    });

    Route::get('/search', [Controllers\Main\Content\QuestionController::class, 'search'])->name('search');
});

Route::fallback(function () {
    return redirect()->route('index');
});