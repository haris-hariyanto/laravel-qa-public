<x-admin.layouts.app :breadcrumb="$breadcrumb">
    <x-slot:pageTitle>{{ __('Users') }}</x-slot>

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
        @can('auth-check', $userAuth->authorize('admin-users-create'))
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">{{ __('Create User') }}</a>
        @endcan
    </div>

    <div x-data="modalDelete">
        <div class="card">
            <div class="card-body">
                <div id="toolbar">
                    <div class="dropdown" x-data="{ groupId: '0', groupName: '{{ __('All') }}' }" :data-group-id="groupId" id="filterGroup" x-init="$watch('groupId', refreshTable)">
                        <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                            {{ __('Group') }} : <span x-text="groupName"></span>
                        </button>
                        <div class="dropdown-menu">
                            <button class="dropdown-item" type="button" @click="groupId = 0; groupName = '{{ __('All') }}'">{{ __('All') }}</button>
                            @foreach ($groups as $group)
                                <button class="dropdown-item" type="button" @click="groupId = '{{ $group->id }}'; groupName = '{{ $group->name }}';">{{ $group->name }}</button>
                            @endforeach
                        </div>
                    </div>
                </div>
                <table
                    id="mainTable"
                    data-toggle="table"
                    data-url="{{ route('admin.users.index.data') }}"
                    data-pagination="true"
                    data-side-pagination="server"
                    data-search="true"
                    data-show-columns="true"
                    data-show-columns-toggle-all="true"
                    data-toolbar="#toolbar"
                    data-query-params="filterGroup"
                >
                    <thead>
                        <tr>
                            <th data-field="id" data-sortable="true" data-width="1">{{ __('ID') }}</th>
                            <th data-field="username" data-sortable="true">{{ __('Username') }}</th>
                            <th data-field="email" data-sortable="true" data-visible="false">{{ __('Email') }}</th>
                            <th data-field="created_at" data-sortable="true" data-visible="false">{{ __('Created at') }}</th>
                            <th data-field="email_verified_at" data-visible="false">{{ __('Status') }}</th>
                            <th data-field="group" data-visible="false">{{ __('Group') }}</th>
                            <th data-field="menu" data-align="center" data-switchable="false" data-width="1">{{ __('Menu') }}</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

        <x-admin.components.modal name="delete">
            <x-slot:modalTitle>{{ __('Delete User?') }}</x-slot>
            <p class="mb-0">{{ __('Delete user account:') }} <span id="usernameToDelete" class="font-weight-bold" x-text="username"></span></p>
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
                    username: false,
                    deleteItem(e) {
                        this.linkDelete = e.target.dataset.linkDelete;
                        this.username = e.target.dataset.username;
                    },
                }));
            });

            function filterGroup(params) {
                const groupId = document.getElementById('filterGroup').dataset.groupId;
                params.group_id = groupId;
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