<x-admin.layouts.app :breadcrumb="$breadcrumb">
    <x-slot:pageTitle>{{ __('Administrators') }}</x-slot>

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
        @can('auth-check', $userAuth->authorize('admin-admins-create'))
            <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#modalAdd">{{ __('Add Administrator') }}</button>
        @endcan
    </div>

    <form action="{{ route('admin.admins.store') }}" method="POST">
        @csrf
        <x-admin.components.modal name="add">
            <x-slot:modalTitle>{{ __('Add Administrator') }}</x-slot>

            <x-admin.forms.select :label="__('Group')" :options="$groupsNotAdmin" name="group_id" :default-option="__('Select group')" />

            <x-slot:modalFooter>
                <button type="submit" class="btn btn-primary">{{ __('Add Administrator') }}</button>
            </x-slot>
        </x-admin.components.modal>
    </form>

    <div x-data="modalRemove">
        <div class="card">
            <div class="card-body">
                <table
                    data-toggle="table"
                    data-url="{{ route('admin.admins.index.data') }}"
                    data-pagination="true"
                    data-side-pagination="server"
                    data-search="true"
                    data-show-columns="true"
                    data-show-columns-toggle-all="true"
                >
                    <thead>
                        <tr>
                            <th data-field="id" data-sortable="true" data-width="1" data-visible="false">{{ __('ID') }}</th>
                            <th data-field="name" data-sortable="true">{{ __('Group name') }}</th>
                            <th data-field="created_at" data-sortable="true" data-visible="false">{{ __('Created at') }}</th>
                            <th data-field="menu" data-align="center" data-switchable="false" data-width="1">{{ __('Menu') }}</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    
        <x-admin.components.modal name="remove">
            <x-slot:modalTitle>{{ __('Remove Group') }}</x-slot>
            <p class="mb-0">{{ __('Remove group from administrator:') }} <span class="font-weight-bold" x-text="groupName"></span></p>
            <x-slot:modalFooter>
                <form :action="linkDelete" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">{{ __('Remove Group') }}</button>
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
                Alpine.data('modalRemove', () => ({
                    linkDelete: false,
                    groupName: false,
                    removeItem(e) {
                        this.linkDelete = e.target.dataset.linkDelete;
                        this.groupName = e.target.dataset.name;
                    },
                }));
            });
        </script>
    @endpush
</x-admin.layouts.app>