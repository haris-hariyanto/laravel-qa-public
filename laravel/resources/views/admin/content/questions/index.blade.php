<x-admin.layouts.app :breadcrumb="$breadcrumb">
    <x-slot:pageTitle>{{ __('Questions') }}</x-slot>

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

    <div class="card">
        <div class="card-body">
            <div id="toolbar">
                <div class="input-group" x-data="{ pageNumber: '' }">
                    <input type="text" class="form-control" id="pageNumber" x-model="pageNumber">
                    <div class="input-group-append">
                        <button type="button" id="btnPageNumber" class="btn btn-secondary" @click="moveToPage(pageNumber); pageNumber = '';">{{ __('Select Page') }}</button>
                    </div>
                </div>
            </div>
            <table
                id="mainTable"
                data-toggle="table"
                data-url="{{ route('admin.questions.index.data') }}"
                data-pagination="true"
                data-side-pagination="server"
                data-search="true"
                data-show-columns="true"
                data-show-columns-toggle-all="true"
                data-toolbar="#toolbar"
            >
                <thead>
                    <tr>
                        <th data-field="id" data-sortable="true" data-width="1" data-width-unit="px" data-visible="false">{{ __('ID') }}</th>
                        <th data-field="question" data-sortable="true" data-switchable="false">{{ __('Question') }}</th>
                        <th data-field="answers" data-visible="false" data-align="center">{{ __('Answers') }}</th>
                        <th data-field="slug" data-sortable="true" data-visible="false">{{ __('Slug') }}</th>
                        <th data-field="username" data-visible="false">{{ __('Username') }}</th>
                        <th data-field="grade" data-visible="false">{{ __('Grade') }}</th>
                        <th data-field="subject" data-visible="false">{{ __('Subject') }}</th>
                        <th data-field="menu" data-align="center" data-switchable="false" data-width="1">{{ __('Menu') }}</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    @push('scripts')
        <link rel="stylesheet" href="{{ asset('assets/admin/plugins/bootstrap-table/bootstrap-table.min.css') }}">
    @endpush

    @push('scriptsBottom')
        <script src="{{ asset('assets/admin/plugins/bootstrap-table/bootstrap-table.min.js') }}"></script>
        <script>
            function moveToPage(page) {
                $(document).ready(function () {
                    const table = $('#mainTable');
                    table.bootstrapTable('selectPage', page);
                });
            }
        </script>
    @endpush
</x-admin.layouts.app>