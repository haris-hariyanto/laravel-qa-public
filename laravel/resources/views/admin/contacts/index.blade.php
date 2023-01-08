<x-admin.layouts.app :breadcrumb="$breadcrumb">
    <x-slot:pageTitle>{{ __('Messages') }}</x-slot>

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

    <div x-data="modalDelete">
        <div class="card">
            <div class="card-body">
                <div id="toolbar">
                    <div class="dropdown" x-data="{ status: '0', statusName: '{{ __('All') }}' }" :data-status="status" id="filterMessages" x-init="$watch('status', refreshTable)">
                        <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                            {{ __('Status') }} : <span x-text="statusName"></span>
                        </button>
                        <div class="dropdown-menu">
                            <button class="dropdown-item" type="button" @click="status = '0'; statusName = '{{ __('All') }}';">{{ __('All') }}</button>
                            <button class="dropdown-item" type="button" @click="status = 'UNREAD'; statusName = '{{ __('Unread') }}';">{{ __('Unread') }}</button>
                            <button class="dropdown-item" type="button" @click="status = 'READ'; statusName = '{{ __('Read') }}';">{{ __('Read') }}</button>
                        </div>
                    </div>
                </div>
                <table
                    id="mainTable"
                    data-toggle="table"
                    data-url="{{ route('admin.contacts.index.data') }}"
                    data-pagination="true"
                    data-side-pagination="server"
                    data-search="true"
                    data-show-columns="true"
                    data-show-columns-toggle-all="true"
                    data-toolbar="#toolbar"
                    data-query-params="filterMessages"
                >
                    <thead>
                        <tr>
                            <th data-field="id" data-sortable="true" data-width="1" data-visible="false">{{ __('ID') }}</th>
                            <th data-field="name" data-sortable="true" data-visible="false">{{ __('Name') }}</th>
                            <th data-field="email" data-sortable="true" data-visible="false">{{ __('Email') }}</th>
                            <th data-field="subject" data-sortable="true">{{ __('Subject') }}</th>
                            <th data-field="status" data-sortable="true" data-width="1">{{ __('Status') }}</th>
                            <th data-field="created_at" data-sortable="true">{{ __('Created at') }}</th>
                            <th data-field="menu" data-align="center" data-switchable="false" data-width="1">{{ __('Menu') }}</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

        <x-admin.components.modal name="delete">
            <x-slot:modalTitle>{{ __('Delete Message?') }}</x-slot>
            <p class="mb-0">{{ __('Delete message:') }} <span class="font-weight-bold" x-text="messageSubject"></span></p>
            <x-slot:modalFooter>
                <form :action="linkDelete" method="POST" id="formDelete">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">{{ __('Delete') }}</button>
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
                    messageSubject: false,
                    deleteItem(e) {
                        this.linkDelete = e.target.dataset.linkDelete;
                        this.messageSubject = e.target.dataset.subject;
                    },
                }));
            });

            function filterMessages(params) {
                const status = document.getElementById('filterMessages').dataset.status;
                params.status = status;
                return params;
            }

            function refreshTable() {
                $(document).ready(function () {
                    const table = $('#mainTable');
                    table.bootstrapTable('refresh');
                });
            }
        </script>
    @endpush
</x-admin.layouts.app>