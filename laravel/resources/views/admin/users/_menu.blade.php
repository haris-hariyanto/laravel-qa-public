<div class="dropdown">
    <button class="btn btn-dark btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">{{ __('Menu') }}</button>
    <div class="dropdown-menu">
        @can('auth-check', $userAuth->authorize('admin-users-edit'))
            <a href="{{ route('admin.users.edit', ['user' => $user]) }}" class="dropdown-item">{{ __('Edit') }}</a>
        @endcan

        @can('auth-check', $userAuth->authorize('admin-users-password'))
            <a href="{{ route('admin.users.password.edit', ['user' => $user]) }}" class="dropdown-item">{{ __('Edit Password') }}</a>
        @endcan

        @can('auth-check', $userAuth->authorize('admin-users-delete'))
            <button 
                type="button" 
                class="dropdown-item" 
                data-toggle="modal" 
                data-target="#modalDelete" 
                data-link-delete="{{ route('admin.users.destroy', ['user' => $user]) }}" 
                data-username="{{ $user->username }}"
                @click="deleteItem"
            >{{ __('Delete') }}</button>
        @endcan
    </div>
</div>