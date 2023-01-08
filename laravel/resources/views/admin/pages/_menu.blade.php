<div class="dropdown">
    <button class="btn btn-dark btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">{{ __('Menu') }}</button>
    <div class="dropdown-menu">
        @if ($page->status == 'PUBLISHED')
            <a href="{{ route('page', ['page' => $page]) }}" class="dropdown-item" target="_blank">{{ __('Open Page') }}</a>
        @endif

        @can('auth-check', $userAuth->authorize('admin-pages-edit'))
            <a href="{{ route('admin.pages.edit', ['page' => $page]) }}" class="dropdown-item">{{ __('Edit') }}</a>            
        @endcan

        @can('auth-check', $userAuth->authorize('admin-pages-delete'))
            <button
                type="button"
                class="dropdown-item"
                data-toggle="modal"
                data-target="#modalDelete"
                data-link-delete="{{ route('admin.pages.destroy', ['page' => $page]) }}"
                data-title="{{ $page->title }}"
                @click="deleteItem"
            >{{ __('Delete') }}</button>
        @endcan
    </div>
</div>