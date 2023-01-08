<div class="dropdown">
    <button type="button" class="btn btn-dark btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">{{ __('Menu') }}</button>
    <div class="dropdown-menu">
        <a href="{{ route('admin.subjects.edit', ['subject' => $subject]) }}" class="dropdown-item">{{ __('Edit') }}</a>
        <button
            type="button"
            class="dropdown-item"
            data-toggle="modal"
            data-target="#modalDelete"
            data-link-delete="{{ route('admin.subjects.destroy', ['subject' => $subject]) }}"
            data-name="{{ $subject->name }}"
            @click="deleteItem"
        >{{ __('Delete') }}</button>
    </div>
</div>