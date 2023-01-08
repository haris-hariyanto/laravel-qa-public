<x-main.layouts.app>
    @push('metaData')
        {!! $metaData->render() !!}
        {!! $openGraph->render() !!}
    @endpush

    <x-slot:pageTitle>{{ __('main.main_page_title') }}</x-slot>

    <div class="container">

        <!-- Hero -->
        <div class="card py-4">
            <div class="card-body">

                <div class="row justify-content-center">
                    <div class="col-12 col-lg-9 col-xl-8">
                        <div>
                            <h1>{{ config('app.name') }}</h1>
                            <p>{{ __('main.main_subheading') }}</p>

                            <!-- Search bar -->
                            <form action="{{ route('content.search') }}" method="GET">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="q" placeholder="{{ __('main.search_placeholder') }}" aria-label="Search query" aria-describedby="heroSearchButton">
                                    <button class="btn btn-primary" type="submit" id="heroSearchButton">
                                        <span class="px-2">{{ __('Search') }}</span>
                                    </button>
                                </div>
                            </form>
                            <!-- [END] Search bar -->
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!-- [END] Hero -->

        <!-- Latest question -->
        <h2 class="h3 my-3">{{ __('main.latest_questions') }}</h2>
        <div class="row g-2">
            @foreach ($questions as $question)
                <div class="col-12 col-md-6 col-lg-4">
                    <x-main.components.content.question :question="$question" />
                </div>
            @endforeach
        </div>
        <!-- [END] Latest question -->

    </div>
</x-main.layouts.app>