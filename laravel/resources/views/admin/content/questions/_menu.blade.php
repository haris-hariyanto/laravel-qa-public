<div class="dropdown">
    <button type="button" class="btn btn-dark btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">{{ __('Menu') }}</button>
    <div class="dropdown-menu">
        <a href="{{ route('content.question', ['question' => $question]) }}" class="dropdown-item" target="_blank">{{ __('Open Page') }}</a>
    </div>
</div>