<div class="dropdown">
    <button type="button" class="btn btn-dark btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">{{ __('Menu') }}</button>
    <div class="dropdown-menu">
        <a href="{{ route('admin.keywords.edit', ['keyword' => $keyword]) }}" class="dropdown-item">{{ __('Edit') }}</a>
        <button
            type="button"
            class="dropdown-item"
            data-toggle="modal"
            data-target="#modalDelete"
            data-link-delete="{{ route('admin.keywords.destroy', ['keyword' => $keyword]) }}"
            data-keyword="{{ $keyword->keyword }}"
            @click="deleteItem"
        >{{ __('Delete') }}</button>
    </div>
</div>