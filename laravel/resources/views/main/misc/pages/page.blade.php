<x-main.layouts.app>
    <x-slot:pageTitle>{{ $page->title }}</x-slot>

    @push('metaData')
        {!! $metaData->render() !!}
    @endpush

    <div class="container">
        <div class="row g-2 justify-content-center">
            <div class="col-12 col-sm-10 col-md-8">

                <x-main.components.breadcrumb :links="$breadcrumb" class="mb-2" />

                <div class="card">
                    <div class="card-body">
                        <h1 class="fs-2 mb-3">{{ $page->title }}</h1>
                        <div>
                            {!! $page->content() !!}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-main.layouts.app>