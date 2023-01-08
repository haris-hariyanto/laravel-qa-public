<x-main.layouts.app>
    <x-slot:pageTitle>{{ __('main.search_result_page_title', ['q' => $searchQuery]) }}</x-slot>

    <div class="container">
        <div class="my-3">
            <h1 class="fs-2">{{ __('main.search_result_heading', ['q' => $searchQuery]) }}</h1>
            <p>{!! trans_choice('main.search_result_heading_p', $questionsCount, ['q' => htmlspecialchars($searchQuery)]) !!}</p>
        </div>

        <div class="row g-2">
            @forelse ($questions as $question)
                <div class="col-12 col-md-6 col-lg-4">
                    <x-main.components.content.question :question="$question" />
                </div>
            @empty
                <div class="col-12"></div>
            @endforelse
        </div>

        <div>
            {{ $questions->links('components.main.components.simple-pagination') }}
        </div>
    </div>
</x-main.layouts.app>