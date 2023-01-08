{{-- Data Properties ['type', 'dismissible'] --}}

@props(['type' => 'success', 'dismissible' => true])

<div {{ $attributes->class(['alert', 'alert-success' => $type == 'success', 'alert-danger' => $type == 'danger', 'alert-warning' => $type == 'warning', 'alert-dismissible', 'fade', 'show']) }} role="alert">
    {{ $slot }}
    @if ($dismissible)
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    @endif
</div>