<div class="card h-100">
    <div class="card-body">
        <!-- Question data -->
        <div class="d-flex mb-3 justify-content-between">
            <div>
                <a href="{{ route('content.subject', ['subject' => $question->subject]) }}">
                    <span class="small">{{ ucwords($question->subject->name) }}</span>
                </a>
            </div>
            <div>
                <a href="{{ route('content.grade', ['grade' => $question->grade]) }}">
                    <span class="small">{{ ucwords($question->grade->name) }}</span>
                </a>
            </div>
        </div>
        <!-- [END] Question data -->

        <!-- Question -->
        <div class="fw-semibold">
            <a href="{{ route('content.question', ['question' => $question]) }}" class="text-dark">
                <span class="tw-line-clamp-3">{{ $question->question }}</span>
            </a>
        </div>
        <!-- [END] Question -->
    </div>
    <div class="card-footer">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <span class="text-muted small">{{ $question->answers->count() ? trans_choice('main.answers_count', $question->answers->count(), ['count' => $question->answers->count()]) : __('No answer yet') }}</span>
            </div>
            <div>
                <a href="{{ route('content.question', ['question' => $question]) }}" class="btn btn-primary">{{ __('Answers') }}</a>
            </div>
        </div>
    </div>
</div>