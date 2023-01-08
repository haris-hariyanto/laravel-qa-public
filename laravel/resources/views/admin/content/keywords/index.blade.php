<x-admin.layouts.app :breadcrumb="$breadcrumb">
    <x-slot:pageTitle>{{ __('Keywords') }}</x-slot>

    @if (session('success'))
        <x-admin.components.alert>
            {{ session('success') }}
        </x-admin.components.alert>
    @endif

    @if (session('error'))
        <x-admin.components.alert type="danger">
            {{ session('error') }}
        </x-admin.components.alert>
    @endif

    <div class="d-flex justify-content-end mb-3">
        <div class="btn-group">
            <a href="{{ route('admin.keywords.create') }}" class="btn btn-primary">{{ __('Add Keyword') }}</a>
            <button class="btn btn-primary dropdown-toggle dropdown-toggle-split" type="button" data-toggle="dropdown" aria-expanded="false">
                <span class="sr-only">Toggle dropdown</span>
            </button>
            <div class="dropdown-menu">
                <a href="{{ route('admin.keywords.index.file') }}" class="dropdown-item">{{ __('Download Keywords') }}</a>
                <a href="{{ route('admin.keywords.import') }}" class="dropdown-item">{{ __('Import Keywords') }}</a>
            </div>
        </div>

    </div>

    <div x-data="modalDelete">
        <div class="card">
            <div class="card-body">
                <table
                    data-toggle="table"
                    data-url="{{ route('admin.keywords.index.data') }}"
                    data-pagination="true"
                    data-side-pagination="server"
                    data-search="true"
                    data-show-columns="true"
                    data-show-columns-toggle-all="true"
                >
                    <thead>
                        <tr>
                            <th data-field="id" data-sortable="true" data-width="1" data-visible="false">{{ __('ID') }}</th>
                            <th data-field="keyword" data-sortable="true">{{ __('Keyword') }}</th>
                            <th data-field="menu" data-align="center" data-switchable="false" data-width="1">{{ __('Menu') }}</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

        <x-admin.components.modal name="Delete">
            <x-slot:modalTitle>{{ __('Delete Keyword?') }}</x-slot>
            <p class="mb-0">{{ __('Delete keyword:') }} <span class="font-weight-bold" x-text="keyword"></span></p>
            <x-slot:modalFooter>
                <form :action="linkDelete" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger" type="submit">{{ __('Delete') }}</button>
                </form>
            </x-slot>
        </x-admin.components.modal>
    </div>

    @push('scripts')
        <link rel="stylesheet" href="{{ asset('assets/admin/plugins/bootstrap-table/bootstrap-table.min.css') }}">
    @endpush

    @push('scriptsBottom')
        <script src="{{ asset('assets/admin/plugins/bootstrap-table/bootstrap-table.min.js') }}"></script>
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('modalDelete', () => ({
                    linkDelete: false,
                    keyword: false,
                    deleteItem(e) {
                        this.linkDelete = e.target.dataset.linkDelete;
                        this.keyword = e.target.dataset.keyword;
                    },
                }));
            });
        </script>
    @endpush
</x-admin.layouts.app>