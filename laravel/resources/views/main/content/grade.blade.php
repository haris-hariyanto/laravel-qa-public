<x-main.layouts.app>
    @push('metaData')
        {!! $metaData->render() !!}
        {!! $openGraph->render() !!}
    @endpush
    
    <x-slot:pageTitle>{{ $pageTitle }}</x-slot>

    <div class="container">
        <h1 class="h2 my-3">{{ __('main.grade_heading', ['grade' => $grade->name]) }}</h1>

        <div class="row g-2">
            @foreach ($questions as $question)
                <div class="col-12 col-md-6 col-lg-4">
                    <x-main.components.content.question :question="$question" />
                </div>
            @endforeach
        </div>

        <div>
            {{ $questions->links('components.main.components.simple-pagination') }}
        </div>
    </div>
</x-main.layouts.app>