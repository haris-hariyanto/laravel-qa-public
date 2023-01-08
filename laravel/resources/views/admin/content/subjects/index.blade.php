<x-admin.layouts.app :breadcrumb="$breadcrumb">
    <x-slot:pageTitle>{{ __('Subjects') }}</x-slot>

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
        <a href="{{ route('admin.subjects.create') }}" class="btn btn-primary">{{ __('Create Subject') }}</a>
    </div>

    <div x-data="modalDelete">
        <div class="card">
            <div class="card-body">
                <table
                    data-toggle="table"
                    data-url="{{ route('admin.subjects.index.data') }}"
                    data-pagination="true"
                    data-side-pagination="server"
                    data-search="true"
                    data-show-columns="true"
                    data-show-columns-toggle-all="true"
                >
                    <thead>
                        <tr>
                            <th data-field="id" data-sortable="true" data-width="1" data-visible="false">{{ __('ID') }}</th>
                            <th data-field="name" data-sortable="true">{{ __('Subject name') }}</th>
                            <th data-field="slug" data-sortable="true" data-visible="false">{{ __('Slug') }}</th>
                            <th data-field="menu" data-align="center" data-switchable="false" data-width="1">{{ __('Menu') }}</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

        <x-admin.components.modal name="delete">
            <x-slot:modalTitle>{{ __('Delete Subject?') }}</x-slot>
            <p class="mb-0">{{ __('Delete subject:') }} <span class="font-weight-bold" x-text="subjectName"></span></p>
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
                    subjectName: false,
                    deleteItem(e) {
                        this.linkDelete = e.target.dataset.linkDelete;
                        this.subjectName = e.target.dataset.name;
                    },
                }));
            });
        </script>
    @endpush
</x-admin.layouts.app>