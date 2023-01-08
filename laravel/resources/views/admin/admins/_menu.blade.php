<div class="dropdown">
    <button class="btn btn-dark btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">{{ __('Menu') }}</button>
    <div class="dropdown-menu">
        @can('auth-check', $userAuth->authorize('admin-admins-permissions'))
            <a href="{{ route('admin.groups.admin-permissions.edit', ['group' => $group]) }}" class="dropdown-item">{{ __('Edit Permissions') }}</a>
        @endcan

        @if ($group->id != 1)
            @can('auth-check', $userAuth->authorize('admin-admins-delete'))
                <button
                    type="button"
                    class="dropdown-item"
                    data-toggle="modal"
                    data-target="#modalRemove"
                    data-link-delete="{{ route('admin.admins.destroy', ['group' => $group]) }}"
                    data-name="{{ $group->name }}"
                    @click="removeItem"
                >{{ __('Remove Group') }}</button>
            @endcan
        @endif
    </div>
</div>