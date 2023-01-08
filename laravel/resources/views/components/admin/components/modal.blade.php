{{-- Data Properties ['name'] --}}

@props(['name'])

<div class="modal fade" id="modal{{ Str::studly($name) }}" tabindex="-1" aria-labelledby="modal{{ Str::studly($name) }}Label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="h5 modal-title" id="modal{{ Str::studly($name) }}Label">{{ $modalTitle }}</div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {{ $slot }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Cancel') }}</button>
                {{ $modalFooter }}
            </div>
        </div>
    </div>
</div>