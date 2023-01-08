<div class="dropdown">
    <button class="btn btn-dark btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">{{ __('Menu') }}</button>
    <div class="dropdown-menu">
        <a href="{{ route('admin.contacts.show', ['contact' => $contact]) }}" class="dropdown-item">{{ __('Open') }}</a>

        <button
            type="button"
            class="dropdown-item"
            data-toggle="modal"
            data-target="#modalDelete"
            data-link-delete="{{ route('admin.contacts.destroy', ['contact' => $contact]) }}"
            data-subject="{{ $contact->subject }}"
            @click="deleteItem"
        >{{ __('Delete') }}</button>
    </div>
</div>