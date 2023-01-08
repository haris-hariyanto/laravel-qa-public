{{-- Data Properties ['type'] --}}

@props(['type' => 'success'])

<div {{ $attributes->class(['alert', 'alert-success' => $type == 'success', 'alert-danger' => $type == 'danger', 'alert-dismissable', 'fade', 'show']) }} role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    {{ $slot }}
</div>